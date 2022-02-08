<?php

App::uses('FrontendController', 'Controller');

class DailySettlementController extends FrontendController {
	public $uses = array('Office', 'BudgetSale');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index() {
		$this->set('title_for_layout', __('Daily Settlement'));

		$lastYear = date('Y');
		$lastMonth = date('m');

		$data = $this->generate_daily_settlement_data($lastYear, $lastMonth);

		$this->set('data', $data);
		$this->set('year', $lastYear);
		$this->set('month', $lastMonth);
	}

	public function get_daily_data() {
		$this->autoRender = false;

		$lastYear = date('Y');
		$lastMonth = date('m');

		$response = array(
			'status' => 0,
			'message' => ''
		);

		if($this->request->is('ajax')) {
			$req = $this->request->data;
			if(
				!empty($req['month']) && !empty($req['year'])
				&& (
					$req['year'] < $lastYear
					|| (
						$req['year'] == $lastYear
						&& $req['month'] <= $lastMonth
					)
				)
			) {
				$data = $this->generate_daily_settlement_data($req['year'], $req['month']);
				$view = new View($this, false);
				$table_data = $view->element('daily_settlement_data', array('data' => $data));

				$response['status'] = 1;
				$response['table_data'] = $table_data;
			}
		}

		return json_encode($response);
	}

	private function generate_daily_settlement_data($year, $month) {
		$pastYear = date('Y', strtotime($year .'-01-01 -1 year'));

		$offices = $this->Office->find('all', array(
			'fields' => array(
				'Office.id', 'Office.name',
				'Company.id', 'Company.name',
				'CompanyGroup.id', 'CompanyGroup.name'
			),
			'conditions' => array(
				
			),
			'joins' => array(
				array(
					'table' => 'companies',
					'alias' => 'Company',
					'type' => 'INNER',
					'conditions' => array(
						'Office.company_id = Company.id'
					)
				),
				array(
					'table' => 'company_groups',
					'alias' => 'CompanyGroup',
					'type' => 'INNER',
					'conditions' => array(
						'Company.company_group_id = CompanyGroup.id'
					)
				)
			),
			'recursive' => -1
		));

		$office_ids = array();
		foreach($offices as $office) {
			$office_ids[] = $office['Office']['id'];
		}

		$lastMonthBudgetSale = array();
		$data_lastMonthBudgetSale = $this->BudgetSale->find('all', array(
			'conditions' => array(
				'office_id' => $office_ids,
				'year' => $year,
				'month' => $month
			),
			'recursive' => -1
		));
		foreach($data_lastMonthBudgetSale as $budgetSale) {
			$lastMonthBudgetSale[$budgetSale['BudgetSale']['office_id']] = $budgetSale['BudgetSale'];
		}

		$pastYearBudgetSale = array();
		$data_pastYearBudgetSale = $this->BudgetSale->find('all', array(
			'conditions' => array(
				'office_id' => $office_ids,
				'year' => $pastYear,
				'month' => $month
			),
			'recursive' => -1
		));
		foreach($data_pastYearBudgetSale as $budgetSale) {
			$pastYearBudgetSale[$budgetSale['BudgetSale']['office_id']] = $budgetSale['BudgetSale'];
		}

		$summary = array(
			'revenue' => array(
				'budget' => '',
				'sales' => '',
				'rates' => '',
			),
			'profit' => array(
				'last-month' => array(
					'budget' => '',
					'sales' => '',
					'rates' => ''
				),
				'past-year' => array(
					'budget' => '',
					'sales' => '',
					'rates' => ''
				),
				'past-year-compare' => ''
			),
			'labor_cost' => array(
				'budget' => '',
				'sales' => '',
				'past_sales' => '',
				'budget_overtime' => '',
				'sales_overtime' => '',
				'rates' => '',
				'past-year-compare' => ''
			)
		);

		$data = array(
			'first-group' => array('office' => array(), 'summary' => array(
				array(
					'alias' => __('total'), 
					'data' => $summary
				)
			)),
			'second-group' => array('office' => array(), 'summary' => array(
				array(
					'alias' => __('subtotal'),
					'data' => $summary
				),
				array(
					'alias' => __('average group'),
					'data' => $summary
				)
			)),
			'third-group' => array('office' => array()),
		);

		$summaries = array(
			'alias' => __('accumulated entity'),
			'data' => $summary
		);

		$i = 1;
		foreach($offices as $office) {
			$data_row = $this->generate_daily_settlement_row(
				$office,
				(
					isset($lastMonthBudgetSale[$office['Office']['id']])
					? $lastMonthBudgetSale[$office['Office']['id']]
					: null
				),(
					isset($pastYearBudgetSale[$office['Office']['id']])
					? $pastYearBudgetSale[$office['Office']['id']]
					: null
				)
			);

			switch(strtolower($office['CompanyGroup']['name'])) {
				case strtolower('ケアギバージャパン株式会社'):
					$group = 'first-group'; break;
				case strtolower('グループ内企業'):
					$group = 'second-group'; $i++; break;
				case strtolower('グループ外企業'):
					$group = 'third-group'; $i++; break;
				default:
					$group = 'third-group'; break;
			}

			$data[$group]['office'][] = $data_row;

			if($data_row['revenue']['budget'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['revenue']['budget'] += $data_row['revenue']['budget'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['revenue']['budget'] = round((
							$data[$group]['summary'][1]['data']['revenue']['budget']
							* ($i - 1)
							+ $data_row['revenue']['budget']
						) / $i, 2);
					}
				}
				$summaries['data']['revenue']['budget'] += $data_row['revenue']['budget'];
			}
			if($data_row['revenue']['sales'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['revenue']['sales'] += $data_row['revenue']['sales'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['revenue']['sales'] = round((
							$data[$group]['summary'][1]['data']['revenue']['sales']
							* ($i - 1)
							+ $data_row['revenue']['sales']
						) / $i, 2);
					}
				}
				$summaries['data']['revenue']['sales'] += $data_row['revenue']['sales'];
			}

			if($data_row['profit']['last-month']['budget'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['profit']['last-month']['budget'] += $data_row['profit']['last-month']['budget'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['profit']['last-month']['budget'] = round((
							$data[$group]['summary'][1]['data']['profit']['last-month']['budget']
							* ($i - 1)
							+ $data_row['profit']['last-month']['budget']
						) / $i, 2);
					}
				}
				$summaries['data']['profit']['last-month']['budget'] += $data_row['profit']['last-month']['budget'];
			}
			if($data_row['profit']['last-month']['sales'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['profit']['last-month']['sales'] += $data_row['profit']['last-month']['sales'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['profit']['last-month']['sales'] = round((
							$data[$group]['summary'][1]['data']['profit']['last-month']['sales']
							* ($i - 1)
							+ $data_row['profit']['last-month']['sales']
						) / $i, 2);
					}
				}
				$summaries['data']['profit']['last-month']['sales'] += $data_row['profit']['last-month']['sales'];
			}
			if($data_row['profit']['past-year']['budget'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['profit']['past-year']['budget'] += $data_row['profit']['past-year']['budget'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['profit']['past-year']['budget'] = round((
							$data[$group]['summary'][1]['data']['profit']['past-year']['budget']
							* ($i - 1)
							+ $data_row['profit']['past-year']['budget']
						) / $i, 2);
					}
				}
				$summaries['data']['profit']['past-year']['budget'] += $data_row['profit']['past-year']['budget'];
			}
			if($data_row['profit']['past-year']['sales'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['profit']['past-year']['sales'] += $data_row['profit']['past-year']['sales'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['profit']['past-year']['sales'] = round((
							$data[$group]['summary'][1]['data']['profit']['past-year']['sales']
							* ($i - 1)
							+ $data_row['profit']['past-year']['sales']
						) / $i, 2);
					}
				}
				$summaries['data']['profit']['past-year']['sales'] += $data_row['profit']['past-year']['sales'];
			}

			if($data_row['labor_cost']['budget'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['labor_cost']['budget'] += $data_row['labor_cost']['budget'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['labor_cost']['budget'] = round((
							$data[$group]['summary'][1]['data']['labor_cost']['budget']
							* ($i - 1)
							+ $data_row['labor_cost']['budget']
						) / $i, 2);
					}
				}
				$summaries['data']['labor_cost']['budget'] += $data_row['labor_cost']['budget'];
			}
			if($data_row['labor_cost']['sales'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['labor_cost']['sales'] += $data_row['labor_cost']['sales'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['labor_cost']['sales'] = round((
							$data[$group]['summary'][1]['data']['labor_cost']['sales']
							* ($i - 1)
							+ $data_row['labor_cost']['sales']
						) / $i, 2);
					}
				}
				$summaries['data']['labor_cost']['sales'] += $data_row['labor_cost']['sales'];
			}
			if($data_row['labor_cost']['past_sales'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['labor_cost']['past_sales'] += $data_row['labor_cost']['past_sales'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['labor_cost']['past_sales'] = round((
							$data[$group]['summary'][1]['data']['labor_cost']['past_sales']
							* ($i - 1)
							+ $data_row['labor_cost']['past_sales']
						) / $i, 2);
					}
				}
				$summaries['data']['labor_cost']['past_sales'] += $data_row['labor_cost']['past_sales'];
			}
			if($data_row['labor_cost']['budget_overtime'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['labor_cost']['budget_overtime'] += $data_row['labor_cost']['budget_overtime'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['labor_cost']['budget_overtime'] = round((
							$data[$group]['summary'][1]['data']['labor_cost']['budget_overtime']
							* ($i - 1)
							+ $data_row['labor_cost']['budget_overtime']
						) / $i, 2);
					}
				}
				$summaries['data']['labor_cost']['budget_overtime'] += $data_row['labor_cost']['budget_overtime'];
			}
			if($data_row['labor_cost']['sales_overtime'] !== '') {
				if(isset($data[$group]['summary'])) {
					$data[$group]['summary'][0]['data']['labor_cost']['sales_overtime'] += $data_row['labor_cost']['sales_overtime'];
					if($group == 'second-group') {
						$data[$group]['summary'][1]['data']['labor_cost']['sales_overtime'] = round((
							$data[$group]['summary'][1]['data']['labor_cost']['sales_overtime']
							* ($i - 1)
							+ $data_row['labor_cost']['sales_overtime']
						) / $i, 2);
					}
				}
				$summaries['data']['labor_cost']['sales_overtime'] += $data_row['labor_cost']['sales_overtime'];
			}
		}

		$data['third-group']['summary'] = array($summaries);

		foreach($data as $group => $data_group) {
			foreach($data[$group]['summary'] as $key => $summary) {
				if(
					!empty($data[$group]['summary'][$key]['data']['revenue']['budget'])
					&& !empty($data[$group]['summary'][$key]['data']['revenue']['sales'])
				) {
					$data[$group]['summary'][$key]['data']['revenue']['rates'] = round((
						(double)$data[$group]['summary'][$key]['data']['revenue']['sales']
						/ (double)$data[$group]['summary'][$key]['data']['revenue']['budget']
					) * 100, 2);
				}

				if(
					!empty($data[$group]['summary'][$key]['data']['profit']['last-month']['budget'])
					&& !empty($data[$group]['summary'][$key]['data']['profit']['last-month']['sales'])
				) {
					$data[$group]['summary'][$key]['data']['profit']['last-month']['rates'] = round((
						(double)$data[$group]['summary'][$key]['data']['profit']['last-month']['sales']
						/ (double)$data[$group]['summary'][$key]['data']['profit']['last-month']['budget']
					) * 100, 2);
				}

				if(
					!empty($data[$group]['summary'][$key]['data']['profit']['past-year']['budget'])
					&& !empty($data[$group]['summary'][$key]['data']['profit']['past-year']['sales'])
				) {
					$data[$group]['summary'][$key]['data']['profit']['past-year']['rates'] = round((
						(double)$data[$group]['summary'][$key]['data']['profit']['past-year']['sales']
						/ (double)$data[$group]['summary'][$key]['data']['profit']['past-year']['budget']
					) * 100, 2);
				}

				if(
					!empty($data[$group]['summary'][$key]['data']['profit']['last-month']['sales'])
					&& !empty($data[$group]['summary'][$key]['data']['profit']['past-year']['sales'])
				) {
					$data[$group]['summary'][$key]['data']['profit']['past-year-compare'] = round((
						(double)$data[$group]['summary'][$key]['data']['profit']['last-month']['sales']
						/ (double)$data[$group]['summary'][$key]['data']['profit']['past-year']['budget']
					) * 100, 2);
				}

				if(
					!empty($data[$group]['summary'][$key]['data']['labor_cost']['budget'])
					&& !empty($data[$group]['summary'][$key]['data']['labor_cost']['sales'])
				) {
					$data[$group]['summary'][$key]['data']['labor_cost']['rates'] = round((
						(double)$data[$group]['summary'][$key]['data']['labor_cost']['sales']
						/ (double)$data[$group]['summary'][$key]['data']['labor_cost']['budget']
					) * 100, 2);

					if(!empty($data[$group]['summary'][$key]['data']['labor_cost']['past_sales'])) {
						$data[$group]['summary'][$key]['data']['labor_cost']['past-year-compare'] = round((
							(double)$data[$group]['summary'][$key]['data']['labor_cost']['sales']
							/ (double)$data[$group]['summary'][$key]['data']['labor_cost']['past_sales']
						) * 100, 2);
					}
				}
			}
		}

		return $data;
	}

	private function generate_daily_settlement_row($office, $budgetSale, $pastBudgetSale) {
		$result = array(
			'company' => array('id' => $office['Company']['id'], 'name' => $office['Company']['name']),
			'office' => array('id' => $office['Office']['id'], 'name' => $office['Office']['name']),
			'revenue' => array(
				'budget' => !empty($budgetSale) ? $budgetSale['budget_revenues'] : '',
				'sales' => !empty($budgetSale) ? $budgetSale['sales_revenues'] : '',
				'rates' => (
					!empty($budgetSale)
					&& !empty($budgetSale['budget_revenues'])
					&& !empty($budgetSale['sales_revenues'])
					? round((
						(double)$budgetSale['sales_revenues'] / (double)$budgetSale['budget_revenues']
					) * 100, 2) : ''
				),
				'past-year-compare' => (
					!empty($budgetSale) && !empty($pastBudgetSale)
					&& !empty($budgetSale['sales_revenues']) && !empty($pastBudgetSale['sales_revenues'])
					? round((
						(double)$budgetSale['sales_revenues'] / (double)$pastBudgetSale['sales_revenues']
					) * 100, 2) : ''
				)
			),
			'profit' => array(
				'last-month' => $this->calculate_profit($budgetSale),
				'past-year' => $this->calculate_profit($pastBudgetSale)
			),
			'labor_cost' => array(
				'budget' => !empty($budgetSale) ? $budgetSale['budget_labor_cost'] : '',
				'sales' => !empty($budgetSale) ? $budgetSale['sales_labor_cost'] : '',
				'past_sales' => !empty($pastBudgetSale) ? $pastBudgetSale['sales_labor_cost'] : '',
				'budget_overtime' => !empty($budgetSale) ? $budgetSale['budget_overtime_cost'] : '',
				'sales_overtime' => !empty($budgetSale) ? $budgetSale['sales_overtime_cost'] : '',
				'rates' => (
					!empty($budgetSale)
					&& !empty($budgetSale['sales_labor_cost']) && !empty($budgetSale['budget_labor_cost'])
					? round((
						(double)$budgetSale['sales_labor_cost'] / (double)$budgetSale['budget_labor_cost']
					) * 100, 2) : ''
				),
				'past-year-compare' => (
					!empty($budgetSale) && !empty($pastBudgetSale)
					&& !empty($budgetSale['sales_labor_cost']) && !empty($pastBudgetSale['sales_labor_cost'])
					? round((
						(double)$budgetSale['sales_labor_cost'] / (double)$pastBudgetSale['sales_labor_cost']
					) * 100, 2) : ''
				)
			),
		);

		return $result;
	}

	private function calculate_profit($budgetSale) {
		$budget_revenues = $budgetSale['budget_revenues'];
		$budget_expenses = $budgetSale['budget_expenses'];
		$budget_labor_cost = $budgetSale['budget_labor_cost'];
		$budget_overtime_cost = $budgetSale['budget_overtime_cost'];

		$sales_revenues = $budgetSale['sales_revenues'];
		$sales_expenses = $budgetSale['sales_expenses'];
		$sales_labor_cost = $budgetSale['sales_labor_cost'];
		$sales_overtime_cost = $budgetSale['sales_overtime_cost'];

		if(
			($budget_revenues !== '' && $budget_revenues !== NULL)
			|| ($budget_expenses !== '' && $budget_expenses !== NULL)
			|| ($budget_labor_cost !== '' && $budget_labor_cost !== NULL)
			|| ($budget_overtime_cost !== '' && $budget_overtime_cost !== NULL)
		) {
			$budget = $budget_revenues - ($budget_labor_cost + $budget_overtime_cost + $budget_expenses);
		} else {
			$budget = '';
		}
		
		if(
			($sales_revenues !== '' && $sales_revenues !== NULL)
			|| ($sales_expenses !== '' && $sales_expenses !== NULL)
			|| ($sales_labor_cost !== '' && $sales_labor_cost !== NULL)
			|| ($sales_overtime_cost !== '' && $sales_overtime_cost !== NULL)
		) {	
			$sales = $sales_revenues - ($sales_labor_cost + $sales_overtime_cost + $sales_expenses);
		} else {
			$sales = '';
		}

		$profit = array(
			'budget' => $budget,
			'sales' => $sales,
			'rates' => (
				!empty($budget) && !empty($sales)
				? round(((double)$sales / (double)$budget) * 100, 2)
				: ''
			)
		);

		return $profit;
	}
}