<?php

class EmployeePrize extends AppModel {
	public $useTable = 'employee_prizes';
	public $primaryKey = 'id';

	public $belongsTo = array(
		'Employee' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_id'
		),
		'Prize' => array(
			'className' => 'Prize',
			'foreignKey' => 'prize_id'
		)
	);

	private function l_c($l = null, $c = null) {
		$ms = '';

		if($l !== null) { $ms .= '&nbsp;<span style="text-decoration:underline;">'. __('line') .':&nbsp;'. $l .'</span>'; }
		if($c !== null) { $ms .= '&nbsp;<span style="text-decoration:underline;">'. __('column') .':&nbsp;'. $c .'</span>'; }

		return $ms;
	}

	public function update_employee_prizes($data) {
		$year = date('Y', strtotime($data['year-month']));
		$month = intval(date('m', strtotime($data['year-month'])));

		$datasource = $this->getDataSource();
		$isError = false;
		try{
			$datasource->begin();

			$_employees = ClassRegistry::init('Employee');
			$_employee_prizes = ClassRegistry::init('EmployeePrize');

			$employees = $_employees->find('all', array(
				'conditions' => array(
					'company_id' => $data['company'],
					'office_id' => $data['office'],
				),
				'contain' => array(
					'EmployeePrize' => array(
						'conditions' => array(
							'year' => $year,
							'month' => $month,
							'prize_id' => $data['prize']
						)
					)
				)
			));

			if(!empty($employees)) {
				foreach($employees as $employee) {
					$saved = false;
					if(
						!empty($employee['EmployeePrize'])
						&& !empty($data['employees'][$employee['Employee']['id']])
						&& !empty($data['employees'][$employee['Employee']['id']]['prize'])
						&& $data['employees'][$employee['Employee']['id']]['prize'] != $employee['EmployeePrize'][0]['value']
					) {
						$_employee_prizes->id = $employee['EmployeePrize'][0]['id'];
						$saved = $_employee_prizes->save(array(
							'EmployeePrize' => array(
								'value' => (double)$this->clean($data['employees'][$employee['Employee']['id']]['prize'])
							)
						));
					} else if(
						!empty($employee['EmployeePrize'])
						&& isset($data['employees'][$employee['Employee']['id']])
						&& empty($data['employees'][$employee['Employee']['id']]['prize'])
					) {
						$saved = $_employee_prizes->delete($employee['EmployeePrize'][0]['id']);
					} else if(
						empty($employee['EmployeePrize'])
						&& isset($data['employees'][$employee['Employee']['id']])
						&& !empty($data['employees'][$employee['Employee']['id']]['prize'])
					) {
						$_employee_prizes->create();
						$saved = $_employee_prizes->save(array(
							'employee_id' => $employee['Employee']['id'],
							'prize_id' => $data['prize'],
							'year' => $year,
							'month' => $month,
							'value' => (double)$this->clean($data['employees'][$employee['Employee']['id']]['prize'])
						));
					} else {
						$saved = true;
					}

					if(!$saved) {
						$isError = true;
						break;
					}
				}
			} else {
				$isError = true;
			}

			if($isError) {
				$datasource->rollback();
				$response = false;
			} else {
				$datasource->commit();
				$response = true;
			}
		} catch(Exception $e) {
			$isError = true;
			$datasource->rollback();
			$response = false;
		}

		return $response;
	}

	public function import_prize($chunk) {
		$response = array(
			'status' => false,
			'message' => array()
		);
		$today = date("Y-m-d H:i:s");

		$datasource = $this->getDataSource();
		$isError = false;

		try {
			$datasource->begin();

			$_prizes = ClassRegistry::init('Prize');
			$_employees = ClassRegistry::init('Employee');
		} catch(Exception $e) {
			$isError = true;
			$response['message'][] = __('an unknown error occurred');
		}

		if(!$isError) {
			foreach($chunk as $ix => $data) {
				$ix = $ix + 3;
				$skip = false;

				try {
					$v_prizes = $_prizes->find('count', array(
						'conditions' => array(
							'id' => $data['prize_id']['value']
						),
						'limit' => 1,
						'recursive' => -1
					));

					$v_employees = $_employees->find('count', array(
						'conditions' => array(
							'id' => $data['employee_id']['value']
						),
						'limit' => 1,
						'recursive' => -1
					));

					if(empty($v_prizes)) {
						$isError = $skip = true;
						$response['message'][] = (
							__('couldn\'t find prize with id: %s', $data['prize_id']['value'])
							. $this->l_c($data['prize_id']['position']['line'], $data['prize_id']['position']['col'])
						);
					}

					if(empty($v_employees)) {
						$isError = $skip = true;
						$response['message'][] = (
							__('couldn\'t find employee with id: %s', $data['employee_id']['value'])
							. $this->l_c($data['employee_id']['position']['line'], $data['employee_id']['position']['col'])
						);
					}

					if(!$skip) {
						$old_prize = $this->find('first', array(
							'conditions' => array(
								'prize_id' => $data['prize_id']['value'],
								'employee_id' => $data['employee_id']['value'],
								'year' => $data['year']['value'],
								'month' => $data['month']['value'],
							),
							'recursive' => -1
						));

						if(!empty($old_prize)) {
							if($data['prize_value']['value'] != $old_prize['EmployeePrize']['value']) {
								if(!empty($data['prize_value']['value'])) {
									$this->id = $old_prize['EmployeePrize']['id'];
									if(
										!$this->save(array(
											'EmployeePrize' => array(
												'value' => (double)$this->clean($data['prize_value']['value'])
											)
										))
									) {
										$isError = true;
										if(!empty($this->validationErrors)) {
											foreach($this->validationErrors as $msg) {
												$response['message'][] = current($msg) . $this->l_c($ix);
											}
										}
									}
								} else {
									$this->unbindModel(array('belongsTo' => array('Prize', 'Employee')));
									if(
										!$this->delete($old_prize['EmployeePrize']['id'])
									) {
										$isError = true;
										if(!empty($this->validationErrors)) {
											foreach($this->validationErrors as $msg) {
												$response['message'][] = current($msg) . $this->l_c($ix);
											}
										}
									}
								}
							}
						} else {
							if(!empty($data['prize_value']['value'])) {
								$this->create();
								if(
									!$this->save(array(
										'prize_id' => $data['prize_id']['value'],
										'employee_id' => $data['employee_id']['value'],
										'year' => $data['year']['value'],
										'month' => $data['month']['value'],
										'value' => (double)$this->clean($data['prize_value']['value']),
									))
								) {
									$isError = true;
									if(!empty($this->validationErrors)) {
										foreach($this->validationErrors as $msg) {
											$response['message'][] = current($msg) . $this->l_c($ix);
										}
									}
								}
							}
						}
					}
				} catch(Exception $e) {
					$isError = true;
					$response['message'][] = __('an unknown error occurred') . $this->l_c($ix);
				}
			}
		}

		try {
			if($isError) {
				$datasource->rollback();
				$response['status'] = false;
			} else {
				$datasource->commit();
				$response['status'] = true;
			}
		} catch(Exception $e) {
			$response['status'] = false;
			$response['message'][] = __('an unknown error occurred');
			$datasource->rollback();
		}

		return $response;
	}

    private function clean($string)
    {
        $string = str_replace('', ',', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }
}