<?php

App::uses('CsvContentChunk', 'Controller/Component');

class EmployeePrizesController extends AppController {
	public $uses = array('EmployeePrize', 'Office', 'Company', 'Prize', 'Employee');

	public $components = array('CsvContentChunk');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	private $csv_data_format = array(
		'year'        => ['Year',            '対象年'],
		'month'       => ['Month',           '対象月'],
		'prize_id'    => ['Prize ID',        '報奨（ID）'],
		'employee_id' => ['Employee ID',     '従業員（ID）'],
		'prize_value' => ['Amount of Money', '金額'],
	);

	private function csv_format_title() {
		$arr = array();
		foreach($this->csv_data_format as $alias => $title) { $arr[$title[0]] = $title[1]; }
		return $arr;
	}

	private function csv_format_alias() {
		$arr = array();
		foreach($this->csv_data_format as $alias => $title) { $arr[$alias] = $title[0]; }
		return $arr;
	}

	public function admin_enter_prize_money() {
		$this->set('title_for_layout', __('Nhập điểm thưởng'));

		$companies = $offices = $prizes = array();

		$data = array();
		if($this->request->is('post')) {
			$req = $this->request->data['EmployeePrize'];
			if(!empty($req)) {
				$year = date('Y', strtotime($req['year-month']));
				$month = intval(date('m', strtotime($req['year-month'])));

				$data = array(
					'company_id' => $req['company'],
					'office_id' => $req['office'],
					'year' => $year,
					'month' => $month,
					'prize' => $req['prize'],
					'employees' => array()
				);

				if($this->EmployeePrize->update_employee_prizes($req)) {
					$this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
					$data['employees'] = $this->admin_get_employee_prizes($req['company'], $req['office'], $year, $month, $req['prize'])['employees'];
				} else {
					$this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
					foreach($req['employees'] as $id => $employee) {
						$data['employees'][] = array(
							'id' => $id,
							'name' => $employee['name'],
							'prize' => $employee['prize']
						);
					}
				}
			}
		}

		// switch for super admin or company admin

        // get list company
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));


		if(empty($data['company_id'])) { $data['company_id'] = key($companies); }

		$offices = $this->Office->find('list', array(
			'fields' => array('id', 'name'),
			'conditions' => array(
				'company_id' => $data['company_id']
			),
			'order' => array(
				'created' => 'asc'
			)
		));

		$prizes = $this->Prize->find('list', array(
			'fields' => array('id', 'name'),
			'order' => array(
				'created' => 'asc'
			)
		));

		if(empty($data['office_id'])) { $data['office_id'] = key($offices); }
		if(empty($data['prize'])) { $data['prize'] = key($prizes); }
		if(empty($data['year'])) { $data['year'] = date('Y'); }
		if(empty($data['month'])) { $data['month'] = date('m'); }

		$this->set('data', $data);
		$this->set(compact('companies', 'offices', 'prizes'));
	}

	public function admin_generate_employee_prizes() {
		$this->autoRender = false;

		$response = array(
			'status' => 0,
			'message' => ''
		);

		if($this->request->is('ajax')) {
			$req = $this->request->data;
			if(
				!empty($req['company'])
				&& !empty($req['office'])
				&& !empty($req['year'])
				&& !empty($req['month'])
				&& !empty($req['prize'])
			) {
				$data = $this->admin_get_employee_prizes($req['company'], $req['office'], $req['year'], $req['month'], $req['prize']);
				$response['status'] = 1;
				$response['data'] = $data;
			}
		}

		return json_encode($response);
	}

	private function admin_get_employee_prizes($company, $office, $year, $month, $prize) {
		$employee_prizes = $this->Employee->find('all', array(
			'fields' => array('id', 'name'),
			'conditions' => array(
				'company_id' => $company,
				'office_id' => $office,
			),
			'contain' => array(
				'EmployeePrize' => array(
					'fields' => array('id', 'prize_id', 'value'),
					'conditions' => array(
						'year' => $year,
						'month' => $month,
						'prize_id' => $prize
					)
				)
			),
			'order' => array(
				'Employee.name' => 'asc'
			)
		));

		$data = array('employees' => array());

		foreach($employee_prizes as $employee) {
			$data['employees'][] = array(
				'id' => $employee['Employee']['id'],
				'name' => $employee['Employee']['name'],
				'prize' => !empty($employee['EmployeePrize']) ? $employee['EmployeePrize'][0]['value'] : 0
			);
		}

		return $data;
	}

	public function admin_csv_import() {
		$this->set('title_for_layout', '賞金入力CSV uploading');

		$companies = $offices = array();

		$this->set(compact('companies', 'offices'));
	}

	public function admin_export_sample() {
		$this->autoRender = false;

		$watting_time = 30;
		$last_time = $this->Session->read('Prize.Csv.Sample');
		$last_time = $last_time ? $last_time : 0;
		$current_time = time();

		if($current_time - $last_time < $watting_time) {
			$time_left = abs($watting_time - ($current_time - $last_time));
			$this->Session->setFlash(__('the next download will be available in %s seconds', $time_left), 'flashmessage', array('type' => 'warning'), 'warning');
			$this->redirect(array('action' => 'admin_csv_import', 'plugin' => null, 'admin' => true));
		} else {
			$this->Session->write('Prize.Csv.Sample', time());
		}

		$sample_format = $this->csv_format_title();

		$csv_data = array();
		$row = array();
		foreach($sample_format as $key => $column) {
			$row[$key] = '';
		}
		$csv_data[] = $row;

		$this->Export->exportCsv($csv_data, '賞金入力.sample.csv', null, ',', '"', true);
	}

	public function admin_import_from_csv() {
		$this->autoRender = false;

		$response = array(
			'status' => 0,
			'success' => 0,
			'failure' => 0,
			'message' => array('Oops! Something went wrong.')
		);

		if($this->request->is('ajax')) {
			$req = $this->request->data;
			if(
				!empty($req['CsvUpload'])
				&& !empty($req['CsvUpload']['file'])
			) {
				$files = $req['CsvUpload']['file'];
				$method = 'get_content_file_p';
				$valid = $this->csv_format_alias();
				$processer = $this->EmployeePrize;
				$procession = 'import_prize';

				$response = $this->CsvContentChunk->get_response($files, $method, $valid, $processer, $procession);
			}
		} else {
			$this->redirect('admin_index');
		}

		return json_encode($response);
	}

}