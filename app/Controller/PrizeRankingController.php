<?php

App::uses('FrontendController', 'Controller');

class PrizeRankingController extends FrontendController {
	public $uses = array('Office', 'Prize', 'EmployeePrize', 'Employee');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index() {
		$this->set('title_for_layout', __('Daily Settlement'));

		$year = date('Y');
		$month = date('m');

		$data = $this->generate_budget_ranking($year, $month);
		$paging = $data['paging'];
		unset($data['paging']);

		$this->set('data', $data);
		$this->set('paging', $paging);
		$this->set('year', $year);
		$this->set('month', $month);
	}

	public function get_prize_ranking_data() {
		$this->autoRender = false;

		$lastYear = date('Y', strtotime("-1 month"));
		$lastMonth = date('m', strtotime("-1 month"));

		$response = array(
			'status' => 0,
			'message' => ''
		);

		if($this->request->is('ajax')) {
			$req = $this->request->data;
			if(
				!empty($req['month']) && !empty($req['year'])
			) {
				$page = 1;
				if(isset($req['page'])) { $page = intval($req['page']); }
				if(!($page > 0)) { $page = 1; }

				$data = $this->generate_budget_ranking($req['year'], $req['month'], $page);
				$paging = $data['paging'];
				unset($data['paging']);

				$view = new View($this, false);
				$table_data = $view->element('prize_ranking_data', array('data' => $data));

				$response['status'] = 1;
				$response['table_data'] = $table_data;
				$response['paging'] = $paging;
			}
		}

		return json_encode($response);
	}

	private function generate_budget_ranking($year, $month, $page = 1) {
		$data = array();

		$limit = intval(Configure::read('Paging.size'));

		$query = array(
			'conditions' => array(
				'EmployeePrize.year' => $year,
				'EmployeePrize.month' => $month,
				'EmployeePrize.value > 0'
			),
			'joins' => array(
				array(
					'table' => 'employee_prizes',
					'alias' => 'EmployeePrize',
					'type' => 'INNER',
					'conditions' => array(
						'EmployeePrize.employee_id = Employee.id'
					)
				),
				array(
					'table' => 'companies',
					'alias' => 'Company',
					'type' => 'INNER',
					'conditions' => array(
						'Company.id = Employee.company_id'
					)
				)
			),
			'group' => 'Employee.id',
		);

		$count = $this->Employee->find('count', $query);

		$employees = $this->Employee->find('all', array_merge(
			$query,
			array(
				'fields' => array(
					'sum(EmployeePrize.value) as prize',
					'Employee.id', 'Employee.name', 'Employee.avatar',
					'Company.id', 'Company.name'
				),
				'order' => array(
					'prize' => 'desc'
				),
				'limit' => $limit,
				'page' => $page,
				'recursive' => -1
			)
		));

		foreach($employees as $position => $employee) {
			$data[] = array(
				'Employee' => $employee['Employee'],
				'Company' => $employee['Company'],
				'ranking' => array(
					'rank' => ($page - 1) * $limit + $position + 1,
					'prize' => round($employee[0]['prize'], 2)
				)
			);
		}

		$data['paging'] = array(
			'page' => !empty($data) ? $page : 0,
			'pages' => ceil($count / $limit),
			'start' => !empty($data) ? ($page - 1) * $limit + 1 : 0,
			'end' => (
				($page - 1) * $limit
				+ (
					$count - ($page - 1) * $limit <= $limit
					? $count - ($page - 1) * $limit
					: $limit
				)
			),
			'count' => $count,
			'limit' => $limit
		);

		return $data;
	}
}