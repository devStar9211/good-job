<?php
/**
 * BonusQuarters Controller
 *
 */
App::uses('AppController', 'Controller');

class PointBonusController extends AppController
{
    public $uses = array('Employee', 'Company', 'Office', 'PointHeader', 'BonusQuarter', 'PointDetail','PointType', 'PointBonus');
    private $csv_data_format = array(
        'employee_id' => ['ID', '従業員（ID）'],
        'year' => ['Year', '年度'],
        'month' => ['Month', '月'],
        'bonus_yen' => ['Amount', '金額']

    );

    private $csv_quarter_data_format = array(
        'id' => ['ID', '従業員（ID）'],
        'month_year' => ['Year / month', '年月'],
        'office_name' => ['Office', '施設名'],
        'employee_number' => ['Employee number', '従業員番号'],
        'name' => ['Name', '氏名'],
        'total' => ['Total Income', '獲得ボーナス金額'],
        'total_point' => ['Total Point', '獲得ポイント数'],
    );

    public $components = array('CsvContentChunk');

    public function admin_quarterly_csv_download(){
        $this->set('title_for_layout', __('Quarterly Bonus CSV Download'));
        if ($this->request->is('post')) {
            $req = $this->request->data;
            $str_date = $req['data']['date'];
            $pos = strrpos($str_date,'〜');

            $year = substr($str_date, 0, 4);
            $begin_date = substr($str_date, $pos-2,2);
            $end_date = substr($str_date, $pos+3,2);
            $file_name = '四半期ボーナス_'.$year.'年'.$begin_date.'〜'.$end_date.'月.csv';

            $query_point_bonuses = $query_point_details = '';
            $i=0;
            foreach (range($begin_date, $end_date) as $month) { $i++;
                $or = $i == 1 ? '' : ' OR';
                $query_point_bonuses .= $or.'( point_bonuses.month = '.$month.' AND point_bonuses.year = '.$year.')';
                $query_point_details .= $or.'( point_details.month = '.$month.' AND point_details.year = '.$year.')';
            }

            $session_key = 'PointBonus.Csv.Download';
            $watting_time = 1;
            $last_time = $this->Session->read($session_key);
            $last_time = $last_time ? $last_time : 0;
            $current_time = time();

            if ($current_time - $last_time < $watting_time) {
                $time_left = abs($watting_time - ($current_time - $last_time));
                $this->Session->setFlash(__('the next download will be available in %s seconds', $time_left), 'flashmessage', array('type' => 'warning'), 'warning');
                $this->redirect(array('action' => 'admin_index', 'plugin' => null, 'admin' => true));
            } else {
                $this->Session->write($session_key, time());
            }

            $csv_alias = $this->csv_format_alias($this->csv_quarter_data_format);
            $csv_format = $this->csv_format_title($this->csv_quarter_data_format);

            $csv_data = array();
            $row = array();
            foreach ($csv_format as $key => $column) {
                $row[$key] = $column;
            }


            $this->Employee->virtualFields = array(
                'office_name' => 'SELECT name FROM offices WHERE offices.id = Employee.office_id',
                'total' => 'SELECT SUM(point_bonuses.bonus_point) as point_bonus_total
                                  FROM point_bonuses
                                  WHERE point_bonuses.employee_id = Employee.id AND ('.$query_point_bonuses.')',
                'total_point' => 'SELECT SUM(point_details.value) 
                                  FROM point_details 
                                  WHERE point_details.employee_id = Employee.id AND ('.$query_point_details.')',

            );
            $data = $this->Employee->find('all', array(
                'contain'=> array(
                    'Office'
                ),
                'recursive'=>-1,
                'order'=>array('Employee.id'=>'asc')
            ));

            $csv_data[] = $row;
            foreach ($data as $_point) {
                $_point['Employee']['month_year'] = $year.'年'.$begin_date.'〜'.$end_date.'月';
                $e = $_point['Employee'];
                $row = array();
                foreach ($csv_format as $key => $column) {
                    $ix = array_search($key, $csv_alias);
                    if ($ix !== false) {
                        $row[$csv_alias[$ix]] = isset($e[$ix]) ? $e[$ix] : '';
                    }
                }
                $csv_data[] = $row;
            }
            $this->Export->exportCsv($csv_data, $file_name);
        }
    }

    public function admin_csv_import()
    {
        $this->set('title_for_layout', 'import employee');

        $companies = $offices = array();

        // switch for super admin or company admin
        // get list company
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        $this->set(compact('companies', 'offices'));
    }

    public function admin_export()
    {
        $this->autoRender = false;

        $watting_time = 1;
        $last_time = $this->Session->read('PointBonus.Csv.Download');
        $last_time = $last_time ? $last_time : 0;
        $current_time = time();

        if ($current_time - $last_time < $watting_time) {
            $time_left = abs($watting_time - ($current_time - $last_time));
            $this->Session->setFlash(__('the next download will be available in %s seconds', $time_left), 'flashmessage', array('type' => 'warning'), 'warning');
            $this->redirect(array('action' => 'admin_index', 'plugin' => null, 'admin' => true));
        } else {
            $this->Session->write('PointBonus.Csv.Download', time());
        }

        $csv_alias = $this->csv_format_alias($this->csv_data_format);
        $csv_format = $this->csv_format_title($this->csv_data_format);
        $csv_data = array();
        $row = array();
        foreach ($csv_format as $key => $column) {
            $row[$key] = $column;
        }
        $data = $this->PointBonus->find('all', array(
            'recursive'=>-1
        ));
        $csv_data[] = $row;
        foreach ($data as $_point) {
            $e = $_point['PointBonus'];
            $row = array();
            foreach ($csv_format as $key => $column) {
                $ix = array_search($key, $csv_alias);

                if ($ix !== false) {
                    $row[$csv_alias[$ix]] = isset($e[$ix]) ? $e[$ix] : '';
                }
            }
            $csv_data[] = $row;
        }
        $this->Export->exportCsv($csv_data, '基本ボーナス.csv');
    }

    private function csv_format_alias($format)
    {
        $arr = array();
        foreach ($format as $alias => $title) {
            $arr[$alias] = $title[0];
        }
        return $arr;
    }

    private function csv_format_title($format)
    {
        $arr = array();
        foreach ($format as $alias => $title) {
            $arr[$title[0]] = $title[1];
        }
        return $arr;
    }


    public function admin_import_point_bonus_from_csv()
    {
        $this->autoRender = false;

        $response = array(
            'status' => 0,
            'success' => 0,
            'failure' => 0,
            'message' => array('Oops! Something went wrong.')
        );

        if ($this->request->is('ajax')) {
            $req = $this->request->data;
            if (
                !empty($req['CsvUpload'])
                && !empty($req['CsvUpload']['file'])
            ) {
                $files = $req['CsvUpload']['file'];
                $method = 'get_content_file_pb';
                $valid = array();
                $processer = $this->PointBonus;
                $procession = 'import_point';
                $response = $this->CsvContentChunk->get_response($files, $method, $valid, $processer, $procession);
            }
        } else {
            $this->redirect('admin_index');
        }

        return json_encode($response);
    }

    public function admin_export_sample($type = null)
    {
        $this->autoRender = false;

        if (in_array($type, ['point_bonus'])) {

            $watting_time = 30;
            $session_key = 'PoitBonus.Csv.Sample.' . $type;
            $last_time = $this->Session->read($session_key);
            $last_time = $last_time ? $last_time : 0;
            $current_time = time();

            if ($current_time - $last_time < $watting_time) {
                $time_left = abs($watting_time - ($current_time - $last_time));
                $this->Session->setFlash(__('the next download will be available in %s seconds', $time_left), 'flashmessage', array('type' => 'warning'), 'warning');

                $action = 'admin_' . $type . '_csv_import';
                $this->redirect(array('action' => $action, 'plugin' => null, 'admin' => true));
            } else {
                $this->Session->write($session_key, time());
            }

            $sample_format = $this->csv_format_title($this->csv_data_format);

            $csv_data = array();
            $row = array();
            foreach ($sample_format as $key => $column) {
                $row[$key] = '';
            }
            $csv_data[] = $row;

            $sample_name = 'sample.csv';
            switch ($type) {
                case 'point_bonus':
                    $sample_name = '基本ボーナスインポート' . '.' . $sample_name;
                    break;

            }

            $this->Export->exportCsv($csv_data, $sample_name, null, ',', '"', true);
        } else {
            $this->redirect(Controller::referer());
        }
    }

    public function admin_input(){
        $this->set('title_for_layout', __('Basic bonus'));
        $companies = $offices = $data = array();
        // get list company
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));
        $data['company_id'] = key($companies);
        $data['office_id'] = null;
        $data['date'] = date('Y'); 

        if (!empty($_GET['company'])) {
            $conditions['Employee.company_id'] = $data['company_id'] = $_GET['company'];
            $offices = $this->Office->find('list', array(
                'fields' => array('id', 'name'),
                'conditions' => array(
                    'company_id' => $_GET['company']
                ),
                'recursive' => -1
            ));
            if (
                !empty($_GET['office'])
                && in_array($_GET['office'], array_keys($offices))
            ) {
                $conditions['Employee.office_id'] = $data['office_id'] = $_GET['office'];
            } else {
                $data['office_id'] = key($offices);
            }
        } else {
            $offices = $this->Office->find('list', array(
                'fields' => array('id', 'name'),
                'conditions' => array(
                    'company_id' => $data['company_id']
                ),
                'order' => array(
                    'created' => 'asc'
                )
            ));
            $data['office_id'] = key($offices);
        } 
        $data['start_month'] = $this->Base->get_start_month($data['office_id']); 
        if (!empty($_GET['date'])) {
            $data['date'] = $_GET['date'];
        }
		 
        // Save data
        if ($this->request->is('post')) {
            $req = $this->request->data;
			 
            if ($this->PointBonus->saveData($req)) {
                $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
            } else {
                $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
            }
            $this->redirect(Controller::referer());
        } else {
            $data = array_merge($data, $this->admin_generate_input_point_data($data['company_id'], $data['office_id'] , $data['date']));
        }
        $this->set('data', $data);
        $this->set(compact('companies', 'offices', 'point_type'));
    }

    private function admin_generate_input_point_data($company_id, $office_id, $date)
    {
		 
		$data = array(
            'company' => $office_id,
            'date' => $date,
            'employees' => array(),
        );
		
        $companies = $offices = $data = $conditions = array();

        $conditionsEmployee = array();
        if (!empty($company_id)) {
            $conditionsEmployee['Employee.company_id'] = $company_id;
        }
        if (!empty($office_id)) {
            $conditionsEmployee['Employee.office_id'] = $office_id;
        }
		$start_month = $this->Base->get_start_month($office_id); 
		$end_month = $start_month-1;
		$year = $date;
		$dates = array(0=>array('year'=>$year),1=>array('year'=>$year+1));    
		for($i = $start_month; $i <= 12; $i++){
			$dates[0]['months'][$i] = $i;
		}
		
		for($i = 1; $i <= $end_month; $i++){
			$dates[1]['months'][$i] = $i;
		}
		
        $count_year = 0;
		
		 
        foreach ($dates as $_date) {
			 
            $count_year++;
			$employees = $this->Employee->find('all', array(
				'conditions' => $conditionsEmployee,
				'contain' => array(
					'Office',
					'PointBonus' => array(
						'conditions' => array(
							'PointBonus.year'=>$_date['year'],
							'PointBonus.month'=>$_date['months']
						),
					)
				),
				'fields' => array('Employee.id', 'Employee.name', 'Employee.company_id', 'Employee.office_id'),
				'recursive' => -1
			));
		  
			
			if (!empty($employees)) {
				foreach ($employees as $k => $_employee) {
					 
					$data['employees'][$_employee['Employee']['id']]['info'] = $_employee['Employee'];
					 
					if (!empty($_employee['PointBonus'])) {
						 
						foreach($_employee['PointBonus'] as $_bonus){
							if($_bonus['month'] < $start_month){
								$month = $_bonus['month'] + 12; 
							}else{
								$month = $_bonus['month'];
							}						
							$data['employees'][$_employee['Employee']['id']]['points'][$month] = array(
								'id' => isset($_bonus['id']) ? $_bonus['id'] : '',

								'bonus_yen' => $_bonus['bonus_yen']
							);
								
						}
						
					}
				}
			}
        }
		 
        return $data;
    } 


    public function admin_generate_list_employee_data($company_id, $office_id, $quarter, $date){
        if(!empty($office_id)){
            $conditions['Employee.company_id'] = $company_id;
        }
        if(!empty($office_id)){
            $conditions['Employee.office_id'] = $office_id;
        }
        if(!empty($quarter)){
            $conditions['BonusQuarter.quarter'] = $quarter;
        }
        if(!empty($date)){
            $conditions['BonusQuarter.year'] = $date;
        }
        $data = array(
            'company' => $office_id,
            'date' => $date,
            'employees' => array(),

        );

        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions'=>$conditions,
            'contain'=> array(
                'Employee'=>array(
                    'Office'=>array(
                        'Company'
                    ),
                )
            ),
            'paramType' => 'querystring',
            'recursive' => -1
        );
        $bonusQuarters = $this->Paginator->paginate('BonusQuarter');
        $data['employees'] = $bonusQuarters;
        return $data;

    }



}


