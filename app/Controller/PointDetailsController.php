<?php

/**
 * Home Controller
 *
 */
class PointDetailsController extends AppController
{
    public $uses = array('Company', 'Office', 'Employee', 'PointDetail', 'PointRank', 'BudgetSale', 'HonobonoResult', 'HonobonoSchedule', 'HonobonoRiyouResult' ,'HonobonoKaigoResult', 'BudgetSale', 'PointHeader', 'PointType');
    private $csv_data_format = array(
        'employee_id' => ['ID', '従業員（ID）'],
        'point_type_id' => ['Point type', 'ポイントの種類'],
        'year' => ['Year', '年度'],
        'month' => ['Month', '月'],
        'value' => ['Point', 'ポイント']
    );
    public $components = array('Base', 'CsvContentChunk');

    private $csv_history_data_format = array(
        'id' => ['ID', '従業員（ID）'],
        'name' => ['Name', '氏名'],
        'employee_number' => ['Employee number', '従業員番号'],
        'office_name' => ['Office', '施設名'],
        'point_total' => ['Total', 'トータル'],
    );

    public function admin_import_point_from_csv()
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
                $method = 'get_content_file_point_detail';
                $valid = array();
                $processer = $this->PointDetail;
                $procession = 'import_point';
                $response = $this->CsvContentChunk->get_response($files, $method, $valid, $processer, $procession);
            }
        } else {
            $this->redirect('admin_index');
        }
        return json_encode($response);
    }

    public function admin_csv_import()
    {
        $this->set('title_for_layout', 'import point for employee');

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
        $session_key = 'PointDetail.Csv.Download';
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

        $csv_alias = $this->csv_format_alias($this->csv_data_format);
        $csv_format = $this->csv_format_title($this->csv_data_format);
        $csv_data = array();
        $row = array();
        foreach ($csv_format as $key => $column) {
            $row[$key] = $column;
        }

        $this->Employee->virtualFields = array(
            'office_name' => 'SELECT name FROM offices WHERE offices.id = Employee.office_id',
        );
        $data = $this->PointDetail->find('all', array(
            'recursive'=>-1
        ));
        $csv_data[] = $row;
        foreach ($data as $_point) {
            $e = $_point['PointDetail'];
            $row = array();
            foreach ($csv_format as $key => $column) {
                $ix = array_search($key, $csv_alias);

                if ($ix !== false) {
                    $row[$csv_alias[$ix]] = isset($e[$ix]) ? $e[$ix] : '';
                }
            }
            $csv_data[] = $row;
        }
        $this->Export->exportCsv($csv_data, 'ボーナス.csv');
    }



    public function admin_history_csv_download()
    {
        $this->set('title_for_layout', __('Basic Bonus + Quarterly Point CSV Download'));
        if ($this->request->is('post')) {
            $req = $this->request->data;
            $date_from = $req['data']['date_from'];
            $date_to = $req['data']['date_to'];

            $begin = new DateTime(date('Y-m-01', strtotime($date_from)));
            $end = new DateTime(date('Y-m-01', strtotime('+1 month',strtotime($date_to))));



            $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

            $dates = array();
            foreach($daterange as $date){
                $dates[$date->format("Y")][$date->format("m")] = $date->format("m");
            }
            $query = '';
            $i=0;
            foreach ($dates as $_year=>$_date){ $i++;
                $or = $i == 1 ? '' : ' OR';
                $query .= $or.'( point_details.month IN ('.implode(',',$_date).') AND point_details.year="'.$_year.'")';
            }

            $session_key = 'PointDetail.Csv.Download';
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

            $csv_alias = $this->csv_format_alias($this->csv_history_data_format);
            $csv_format = $this->csv_format_title($this->csv_history_data_format);
            $csv_data = array();
            $row = array();
            foreach ($csv_format as $key => $column) {
                $row[$key] = $column;
            }

            $this->Employee->virtualFields = array(
                'office_name' => 'SELECT name FROM offices WHERE offices.id = Employee.office_id',
                'point_total' => 'SELECT SUM(point_details.value) 
                                  FROM point_details 
                                  WHERE point_details.employee_id = Employee.id AND ('.$query.')',
            );
            $data = $this->Employee->find('all', array(
                'contain'=> array(
                    'Office'
                ),
                'recursive'=>-1
            ));
            $csv_data[] = $row;
            foreach ($data as $_point) {
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
            $this->Export->exportCsv($csv_data, '報奨ポイント.'.$req['data']['date_from'].'〜'.$req['data']['date_to'].'.csv');
        }
    }

    public function admin_history_export()
    {
        $this->autoRender = false;
        $session_key = 'PointDetail.Csv.Download';
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

        $csv_alias = $this->csv_format_alias($this->csv_history_data_format);
        $csv_format = $this->csv_format_title($this->csv_history_data_format);
        $csv_data = array();
        $row = array();
        foreach ($csv_format as $key => $column) {
            $row[$key] = $column;
        }

        $this->Employee->virtualFields = array(
            'office_name' => 'SELECT name FROM offices WHERE offices.id = Employee.office_id',
        );
        $data = $this->Employee->find('all', array(
            'recursive'=>-1
        ));
        $csv_data[] = $row;
        foreach ($data as $_point) {
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
        $this->Export->exportCsv($csv_data, '報奨ポイント.csv');
    }


    public function admin_export_sample($type = null)
    {
        $this->autoRender = false;

        if (in_array($type, ['point_details'])) {

            $watting_time = 30;
            $session_key = 'PoitDetails.Csv.Sample.' . $type;
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
                case 'point_details':
                    $sample_name = __('Import point') . '.' . $sample_name;
                    break;

            }

            $this->Export->exportCsv($csv_data, $sample_name, null, ',', '"', true);
        } else {
            $this->redirect(Controller::referer());
        }
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

    public function admin_achievement_point()
    {
        $lastYear = date('Y');
        $lastMonth = date('m');
        $offices = $this->Office->find('all',
            array(
                'fields' => array(
                    '(BudgetSale.sales_revenues / BudgetSale.budget_revenues) * 100 as revenue',
                    '(BudgetSale.sales_revenues - BudgetSale.budget_revenues) as excess_profit',
                ),
                'conditions' => array(
                    'BudgetSale.year' => $lastYear,
                    'BudgetSale.month' => $lastMonth,
                ),
                'joins' => array(
                    array(
                        'table' => 'budget_sales',
                        'alias' => 'BudgetSale',
                        'type' => 'INNER',
                        'conditions' => array(
                            'BudgetSale.office_id = Office.id'
                        )
                    )
                ),
            )
        );
        $office_pass = array();
        foreach ($offices as $office) {
            if ($office[0]['excess_profit'] > 0) {
                $office_pass[$office['Office']['id']] = round($office[0]['excess_profit'], 2);
            }
        }

        $employees = $this->Employee->find('all', array(
            'fields' => array('Employee.id'),
            'conditions' => array(
                'Employee.office_id' => array_keys($office_pass)
            ),
            'recursive' => -1
        ));
        $data = array(
            'point_type' => 1,
            'point' => '',
            'date' => '2017-05'
        );
        foreach ($employees as $_employee) {
//            saveAchivementPoint
            $data['point'][$_employee['Employee']['id']] = array(
                'id' => '',
                'point_header_id' => '',
                'value' => '999',
            );
        }
        if ($this->PointDetail->savePointDetailData($data)) {
            $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
            die('Item saved');
        } else {
            $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
            die('The item could not be saved. Please try again.');
        }
    }

    public function admin_input_point()
    {
        $this->set('title_for_layout', __('Manual input point'));
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
        $data['date'] = date('Y-m');


        $point_types = $this->PointType->find('list', array(
            'fields' => array(
                'PointType.id',
                'PointType.name',
            ),
            'conditions' => array(

            ),
        ));
        $data['point_types'] = $point_types;

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
        if (!empty($_GET['point_type'])) {
            $data['point_type'] = $_GET['point_type'];
        }else{
            $data['point_type'] = key($data['point_types']);;
        }
        if (!empty($_GET['date'])) {
            $data['date'] = $_GET['date'];
        }
        // Save data
        if ($this->request->is('post')) {
            $req = $this->request->data['CampaignPoint'];
            if ($this->PointDetail->savePointInputData($req)) {
                $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
            } else {
                $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
            }
            $this->redirect(Controller::referer());
        } else {

            $data = array_merge($data, $this->admin_generate_input_point_data($data['company_id'], $data['office_id'], $data['point_type'], $data['date']));
        }
        $this->set('data', $data);
        $this->set(compact('companies', 'offices', 'point_type'));
    }

    public function admin_ajax_manual_input()
    {
        $this->autoRender = false;
        $response = array(
            'status' => 0,
            'message' => ''
        );
        if ($this->request->is('ajax')) {
            $req = $this->request->data;
            $data = array();
            if (!empty($req['office']) && !empty($req['date']) && !empty($req['point_type'])) {
                // switch for super admin or company admin
                $monthly = $this->admin_generate_input_point_data($req['company'], $req['office'], $req['point_type'], $req['date']);
                $view = new View($this, false);
                $table_data = $view->element('manual_input_point_table', array('data' => $monthly));
                $response['status'] = 1;
                $response['table_data'] = $table_data;
            }
        }
        return json_encode($response);
    }

    public function admin_generate_input_point_data($company_id, $office_id, $point_type_id, $date)
    {
        $companies = $offices = $data = $conditions = array();
        $conditionsPointDetail = array();

        $conditionsEmployee = array();
        if (!empty($company_id)) {
            $conditionsEmployee['Employee.company_id'] = $company_id;
        }
        if (!empty($office_id)) {
            $conditionsEmployee['Employee.office_id'] = $office_id;
        }
        if (!empty($point_type_id)) {
            $conditionsPointDetail['point_type_id'] = $point_type_id;
        }
        if (!empty($date)) {
            $conditionsPointDetail['year'] = date('Y', strtotime($date));
            $conditionsPointDetail['month'] = date('m', strtotime($date));
        }
        $employees = $this->Employee->find('all', array(
            'conditions' => $conditionsEmployee,
            'contain' => array(
                'Office',

                'PointDetail' => array(
                    'conditions' => $conditionsPointDetail,
                    'fields' => array('PointDetail.id', 'PointDetail.value', 'PointDetail.employee_id')
                ),

            ),
            'fields' => array('Employee.id', 'Employee.name', 'Employee.company_id', 'Employee.office_id'),
            'recursive' => -1
        ));
//        pr($employees);die;


        $data = array(
            'company' => $office_id,
            'date' => $date,
            'employees' => array(),
        );
        if (!empty($employees)) {
            foreach ($employees as $k => $item) {
                $data['employees'][$k]['info'] = $item['Employee'];
                $data['employees'][$k]['points'] = array();
                if (!empty($item['PointDetail'])) {
                    $data['employees'][$k]['points'] = array(
                        'id' => isset($item['PointDetail'][0]['id']) ? $item['PointDetail'][0]['id'] : '',
                        'employee_id' => isset($item['PointDetail'][0]['employee_id']) ? $item['PointDetail'][0]['employee_id'] : '',
                        'value' => isset($item['PointDetail'][0]['value']) ? $item['PointDetail'][0]['value'] : ''
                    );
                }
            }
        }
        return $data;
    }
    public function admin_history($id = null)
    {
        $this->set("title_for_layout", __('Point history'));
        $data = $conditions = array();
        $data['date'] = date('Y-m');

        // switch for super admin or company admin
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));
        $data['company_id'] = key($companies);
        $data['office_id'] = null;
        if (!empty($_GET['company'])) {
            $data['company_id'] = $_GET['company'];
            $data['company_id'] = $_GET['company'];
            if (
            !empty($_GET['office'])
            ) {
                $data['office_id'] = $_GET['office'];
            }
        }
        $offices = $this->Office->find('list', array(
            'fields' => array('id', 'name'),
            'conditions' => array(
                'company_id' => $data['company_id']
            ),
            'order' => array(
                'created' => 'asc'
            )
        ));
        $data['office_id'] = empty($data['office_id']) ? key($offices) : $data['office_id'];
        $data = array_merge($data, $this->admin_generate_list_employee_data($data['company_id'], $data['office_id'], $data['date']));
        $this->set(compact('companies', 'offices', 'employees', 'data'));
    }

    public function admin_generate_list_employee_data($company_id, $office_id, $date)
    {
        $conditionsEmployee = array();
        if (!empty($office_id)) {
            $conditionsEmployee['Employee.company_id'] = $company_id;
        }
        if (!empty($office_id)) {
            $conditionsEmployee['Employee.office_id'] = $office_id;
        }
        $data = array(
            'company' => $office_id,
            'date' => $date,
            'employees' => array(),

        );
        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions' => $conditionsEmployee,
            'contain' => array(
                'PointDetail',
            ),
            'paramType' => 'querystring',
            'recursive' => -1
        );
        $employees = $this->Paginator->paginate('Employee');
        $data['employees'] = $employees;
        return $data;

    }

    public function admin_ajax_point_detail_view($id = null)
    {
        $this->autoRender = false;
        $view = new View($this, false);
        if ($this->request->is('ajax') && $id != null) {

            $this->Paginator->settings = array(
                'limit' => Configure::read('Paging.size'),
                'conditions' => array(
                    'PointDetail.employee_id' => $id,
                    'PointDetail.value <>' => null,
                ),
                'contain' => 'PointType',
                'paramType' => 'querystring',

            );
            $data = $this->Paginator->paginate('PointDetail');
            $table_data = $view->element('ajax_point_detail_view_table', array('data' => $data));
        } else {
            $table_data = $view->element('ajax_point_detail_view_table', array('data' => ''));
        }
        return $table_data;
    }
}


