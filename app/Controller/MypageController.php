<?php

App::uses('AppController', 'Controller');

class MypageController extends AppController {
	public $uses = array('Office', 'BudgetSale', 'Post', 'Employee', 'Company', 'EmployeePrize', 'Prize');

	public function beforeFilter() {
		parent::beforeFilter();
        $this->layout = "default";
    }

    function beforeRender()
	{
		parent::beforeRender();
	    $account = $this->Auth->user();
        $frontend['index'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/index');
        $frontend['daily_settlement'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/daily_settlement');
        $frontend['budget_ranking'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/budget_ranking');
        $frontend['budget_sale'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/budget_sale');
        $frontend['user_page'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/user_page');
        $frontend['list_post'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/list_post');
	    $this->set(compact('frontend'));
	}

	public function index() {
		$this->set('title_for_layout', 'home');
		$posts = $this->Post->find('all', array(
			'limit' => 4,
            // 'limit' => '10',
            'fields' => array(
                'Post.id',
                'Post.account_id',
                'Post.type',
                'Post.title',
                'Post.short_description',
                'Post.avatar',
                'Post.status',
                'Post.created',
                'Account.id',
                'Account.username',
                ),
            'order' => array(
                'created' => 'desc'
            ),
            'conditions' => array(
                'Post.type' => 'post',
                'Post.status' => 'Publish',
            ),
		));

        $lastYear = date('Y');
        $lastMonth = date('m');
        $data = $this->generate_budget_ranking($lastYear, $lastMonth, 1, 4);
        unset($data['paging']);
        $this->set('data', $data);
		$this->set('posts', $posts);
	}
	// start daily_settlement
	public function daily_settlement(){
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

		$offices = $this->Office->query('
			SELECT
				`Office`.`id`,
				`Office`.`name`,
				`Company`.`id`,
				`Company`.`name`,
				`BusinessCategories`.`id`,
				`BusinessCategories`.`position`
			FROM
				`offices` AS `Office`
					LEFT JOIN `companies` AS `Company`
						ON `Office`.`company_id` = `Company`.`id`
					LEFT JOIN `office_business_categories` AS `OfficeBusinessCategories`
						ON `OfficeBusinessCategories`.`office_id` = `Office`.`id`
					LEFT JOIN `business_categories` AS `BusinessCategories`
						ON `BusinessCategories`.`id` = `OfficeBusinessCategories`.`business_category_id`
		');

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

			switch(strtolower($office['BusinessCategories']['position'])) {
                case 1:
                    $group = 'first-group'; break;
                case 2:
                    $group = 'second-group'; $i++; break;
                case 3:
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
						/ (double)$data[$group]['summary'][$key]['data']['profit']['past-year']['sales']
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
	// end daily_settlement

	// start budget_ranking
	public function budget_ranking(){
		$this->set('title_for_layout', __('Budget Ranking'));

		$lastYear = date('Y');
		$lastMonth = date('m');

		$data = $this->generate_budget_ranking($lastYear, $lastMonth);
		$paging = $data['paging'];
		unset($data['paging']);

		$this->set('data', $data);
		$this->set('paging', $paging);
		$this->set('year', $lastYear);
		$this->set('month', $lastMonth);
	}
	public function get_budget_ranking_data() {
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
				$page = 1;
				if(isset($req['page'])) { $page = intval($req['page']); }
				if(!($page > 0)) { $page = 1; }

				$data = $this->generate_budget_ranking($req['year'], $req['month'], $page);
				$paging = $data['paging'];
				unset($data['paging']);

				$view = new View($this, false);
				$table_data = $view->element('budget_ranking_data', array('data' => $data));

				$response['status'] = 1;
				$response['table_data'] = $table_data;
				$response['paging'] = $paging;
			}
		}

		return json_encode($response);
	}

	private function generate_budget_ranking($year, $month, $page = 1, $limit = null) {
		$previousYear = date('Y', strtotime($year .'-'. $month .'-01 -1 month'));
		$previousMonth = date('m', strtotime($year .'-'. $month .'-01 -1 month'));

		if($limit == null) {
			$limit = intval(Configure::read('Paging.size'));
		}

		$query = array(
			'conditions' => array(
				'BudgetSale.year' => $year,
				'BudgetSale.month' => $month
			),
			'contain' => array(
				'Employee' => array(
					'fields' => array('id', 'name', 'avatar'),
					'conditions' => array(
						'is_manager' => 1
					)
				)
			),
			'joins' => array(
				array(
					'table' => 'companies',
					'alias' => 'Company',
					'type' => 'INNER',
					'conditions' => array(
						'Company.id = Office.company_id'
					)
				),
				array(
					'table' => 'budget_sales',
					'alias' => 'BudgetSale',
					'type' => 'INNER',
					'conditions' => array(
						'BudgetSale.office_id = Office.id'
					)
				)
			),
		);

		$count = $this->Office->find('count', $query);

		$offices = $this->Office->find('all', array_merge(
			$query,
			array(
				'fields' => array(
					'(BudgetSale.sales_revenues / BudgetSale.budget_revenues) * 100 as revenue',
					'Office.id', 'Office.name',
					'Company.id', 'Company.name',
				),
				'order' => array(
					'revenue' => 'desc'
				),
				'limit' => $limit,
				'page' => $page
			)
		));

		$office_ids = array();
		foreach($offices as $office) {
			$office_ids[] = $office['Office']['id'];
		}

		$previous_rank = array();
		if(!empty($office_ids)) {
			$data_previous_rank = $this->Office->query('
				SELECT
					`office_id`,
					`revenue`,
					`position`
				FROM (
					SELECT
						`office_id`,
						((`BudgetSale`.`sales_revenues` / `BudgetSale`.`budget_revenues`) * 100) AS `revenue`,
						@rownum := @rownum + 1 AS `position`
					FROM
						`budget_sales` as `BudgetSale` JOIN (SELECT @rownum := 0) r
					WHERE
						`BudgetSale`.`year` = '. $previousYear .'
						AND `BudgetSale`.`month` = '. $previousMonth .'
					ORDER BY
						`revenue` DESC
				) x
				WHERE
					`office_id` IN ('. implode(',', $office_ids) .')
			');

			foreach($data_previous_rank as $data_rank) {
				if(!empty($data_rank['x']['revenue'])) {
					$previous_rank[$data_rank['x']['office_id']] = array(
						'revenue' => round($data_rank['x']['revenue'], 2),
						'rank' => $data_rank['x']['position']
					);
				}
			}
		}

		$data = array();
		foreach($offices as $position => $office) {
			$data[$position] = array(
				'Office' => $office['Office'],
				'Company' => $office['Company'],
				'Employee' => isset($office['Employee'][0]) ? $office['Employee'][0] : null,
				'ranking' => array(
					'last-month' => array(
						'revenue' => round($office[0]['revenue'], 2),
						'rank' => ($page - 1) * $limit + $position + 1
					),
					'previous-month' => (
						isset($previous_rank[$office['Office']['id']])
						? $previous_rank[$office['Office']['id']]
						: null
					)
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
	// end budget_ranking

	// Start BudgetSale
	public function budget_sale($var_date = null){
		$this->layout = "default";
        $lastYear = date('Y');
        $lastMonth = date('m');
        $data = $this->last_year_comparison($lastMonth, $lastYear, null);
        $paging = $data['paging'];
		unset($data['paging']);

        $new_data = array();
        foreach ($data as $key => $_item) {
            $data_before_last_year = $this->last_year_comparison($_item['Rate']['last_month'], $_item['Rate']['last_year'], $_item['Rate']['office_id']);
            if (!$data_before_last_year) {
                $data_before_last_year = '...';
            }
            $new_data[$key] = $_item;
            $new_data[$key]['LastRate']['position'] = $data_before_last_year;
        }

        $this->set('data', $new_data);
        $this->set('paging', $paging);
	}
	 // for ajax last year comparison
    public function get_last_year_comparison_data()
    {
        $this->autoRender = false;
        $lastYear = date('Y');
        $lastMonth = date('m');
        $response = array(
            'status' => 0,
            'message' => ''
        );

        if ($this->request->is('ajax')) {
            $req = $this->request->data;
            if (
                !empty($req['month']) && !empty($req['year'])
                && (
                    $req['year'] < $lastYear
                    || (
                        $req['year'] == $lastYear
                        && $req['month'] <= $lastMonth
                    )
                )
            ) {
            	$page = 1;
				if(isset($req['page'])) { $page = intval($req['page']); }
				if(!($page > 0)) { $page = 1; }

                $data = $this->last_year_comparison($req['month'], $req['year'], null, $page);
            	$paging = $data['paging'];
				unset($data['paging']);

                $new_data = array();
                if ($data) {

                    foreach ($data as $key => $_item) {
                        $data_before_last_year = $this->last_year_comparison($_item['Rate']['last_month'], $_item['Rate']['last_year'], $_item['Rate']['office_id']);
                        if (!$data_before_last_year) {
                            $data_before_last_year = '...';
                        }
                        $new_data[$key] = $_item;
                        $new_data[$key]['LastRate']['position'] = $data_before_last_year;
                    }
                }

                $view = new View($this, false);
                $table_data = $view->element('last_year_comparison', array('data' => $new_data));
                
                $response['status'] = 1;
				$response['table_data'] = $table_data;
				$response['paging'] = $paging;
            }
        }

        return json_encode($response);
    }


    private function last_year_comparison($month = null, $year = null, $office_id = '', $page = 1, $limit = null)
    {
    	$office_ids = $this->Office->find('list', array(
    		'fields' => array('id')
    	));

    	$office_ids = implode(',', $office_ids) != '' ? implode(',', $office_ids) : 0;

		if($limit == null) {
			$limit = intval(Configure::read('Paging.size'));
		}

		$start = ($page - 1) * $limit;

        if ($month == null && $year == null) { return false; }

        $select = '
            SELECT 
                Rate.rate_sales_revenues, 
                Rate.office_id, 
                position, 
                Rate.last_month,
                Rate.last_year,
                Office.name as office_name,
                Company.name as company_name,
                Employee.name as employee_name,
                Employee.avatar as avatar
        ';

        $conditions = '
            FROM (
                SELECT 
                    rate_sales_revenues, 
                    office_id, 
                    @rownum := @rownum + 1 AS `position`,
                    month as last_month,
                    year as last_year
                FROM (
                    SELECT 
                        CurrentTable.office_id,
                        ((CurrentTable.sales_revenues / LastTable.sales_revenues ) *100) as rate_sales_revenues,
                        LastTable.month,
                        LastTable.year
                    FROM 
                        (SELECT * FROM budget_sales where year=' . $year . ' and month=' . $month . ') as CurrentTable ,
                        (SELECT * FROM budget_sales where year=' . ($year - 1) . ' and month=' . $month . ') as LastTable 
                    WHERE CurrentTable.office_id = LastTable.office_id 
                ) Rate JOIN (SELECT @rownum := 0) AS r
                ORDER BY `rate_sales_revenues` DESC
            ) Rate
            LEFT JOIN (SELECT id, company_id, name FROM offices)  AS Office ON Rate.office_id=Office.id 
            LEFT JOIN (SELECT id, name FROM companies)  AS Company ON Company.id=Office.company_id
            LEFT JOIN (SELECT id, office_id, name, is_manager, avatar FROM employees)  AS Employee ON Employee.office_id=Office.id AND Employee.is_manager = 1
            WHERE Office.id IN ('. $office_ids .')
            ORDER BY `Rate`.`rate_sales_revenues` DESC
        ';

        $count = $this->BudgetSale->query('SELECT count(*) AS count'.' '.$conditions);
        $count = $count[0][0]['count'];
        $query = $select.' '.$conditions.' LIMIT '.$start.','.$limit;

        if ($office_id != '') {
            $data = $this->BudgetSale->query(' 
	            SELECT *
	            FROM (
	                ' . $query . '
	            ) Rate
	            WHERE Rate.office_id = ' . $office_id . ' AND Rate.last_month = ' . $month . ' AND Rate.last_year = ' . ($year - 1) . '
            ');

            if (!empty($data)) {
                return $data[0]['Rate']['position'];
            } else {
            	return false;
            }
        } else {
            $data = $this->BudgetSale->query($query);
        }

        // foreach($data as $position => $budget) {
        // 	$data[$position]['Rate']['position'] = $start + $position + 1;
        // }

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
	// End BudgetSale

	// start user page
	public function user_page(){
		$this->set('title_for_layout', __('User Page'));
		 if(empty($this->Auth->user()['Employee']['id'])) {
		 	$this->redirect(array('action' => 'index'));
		 } else {
		 	$employee_id = $this->Auth->user()['Employee']['id'];
		 	$office_id = $this->Auth->user()['Employee']['office_id'] ;
		 }

		$first_prize_name = '人材紹介報酬';
		$second_prize_name = '昨年対比達成賞';
		$third_prize_name = '予算達成賞';

		$prize_types = $this->Prize->find('list', array(
			'fields' => array('name', 'id'),
			'conditions' => array(
				'name' => array($first_prize_name, $second_prize_name, $third_prize_name)
			),
			'recursive' => -1
		));

		$first_prize = isset($prize_types[$first_prize_name]) ? $prize_types[$first_prize_name] : 0;
		$second_prize = isset($prize_types[$second_prize_name]) ? $prize_types[$second_prize_name] : 0;
		$third_prize = isset($prize_types[$third_prize_name]) ? $prize_types[$third_prize_name] : 0;

		$year = date('Y');
		$month = date('m');

		$user_data = $this->generate_user_prize($employee_id, $office_id);

		$prize_data = $this->EmployeePrize->find('list', array(
			'fields' => array(
				'prize_id', 'value'
			),
			'conditions' => array(
				'EmployeePrize.employee_id' => $employee_id,
				'EmployeePrize.year' => $year,
				'EmployeePrize.month' => $month,
				'prize_id' => array($first_prize, $second_prize, $third_prize)
			),
			'group' => 'EmployeePrize.prize_id',
			'recursive' => -1
		));

		$data = array(
			'personal' => array(
				'a' => $user_data[0]['a'],
				'b' => $user_data[0]['b'],
				'c' => $user_data[0]['c'],
				'first_prize' => !empty($prize_data[$first_prize]) ? round($prize_data[$first_prize]) : 0,
				'second_prize' => !empty($prize_data[$second_prize]) ? round($prize_data[$second_prize]) : 0,
				'third_prize' => !empty($prize_data[$third_prize]) ? round($prize_data[$third_prize]) : 0
			),
			'average' => array(
				'a' => $user_data[1]['a'],
				'b' => $user_data[1]['b'],
				'c' => $user_data[1]['c']
			)
		);

		$this->set('data', $data);
	}
	public function get_data_prize() {
		$this->autoRender = 0;

		$response = array(
			'status' => 0,
			'message' => ''
		);

		if($this->request->is('ajax')) {
			if(!empty($this->Auth->user()['Employee']['id'])) {
				$data = $this->generate_user_prize($this->Auth->user()['Employee']['id'], $this->Auth->user()['Employee']['office_id']);
				$response['data'] = $data;
				$response['status'] = 1;
			}
		}

		return json_encode($response);
	}

	private function generate_user_prize($e_id, $o_id) {
		$employee_id = $e_id;
		$office_id = $o_id;

		$first_prize_name = '人材紹介報酬';
		$second_prize_name = '昨年対比達成賞';
		$third_prize_name = '予算達成賞';

		$prize_types = $this->Prize->find('list', array(
			'fields' => array('name', 'id'),
			'conditions' => array(
				'name' => array($first_prize_name, $second_prize_name, $third_prize_name)
			),
			'recursive' => -1
		));

		$first_prize = isset($prize_types[$first_prize_name]) ? $prize_types[$first_prize_name] : 0;
		$second_prize = isset($prize_types[$second_prize_name]) ? $prize_types[$second_prize_name] : 0;
		$third_prize = isset($prize_types[$third_prize_name]) ? $prize_types[$third_prize_name] : 0;

		$year = date('Y');
		$month = date('m');
		$begin_month = 4;
		$end_month = 12;

		if($month < 4) {
			$begin_year = date('Y', strtotime('-1 year'));

			$conditions = array(
				'OR' => array(
					array(
						'AND' => array(
							'EmployeePrize.year = '. $year,
							'EmployeePrize.month <= '. $month
						)
					),
					array(
						'AND' => array(
							'EmployeePrize.year = '. $begin_year,
							'EmployeePrize.month <= '. $end_month,
							'EmployeePrize.month >= '. $begin_month
						)
					)
				)
			);
		} else {
			$begin_year = $year;

			$conditions = array(
				'EmployeePrize.year = '. $year,
				'EmployeePrize.month <= '. $month,
				'EmployeePrize.month >= '. $begin_month
			);
		}

		$last_date = new DateTime($year.'-'.$month.'-'.'01');
		$prev_date = new DateTime($begin_year.'-'.'04-01');

		$count = $last_date->diff($prev_date)->m;

		$data = array(
			array(
				'a' => 0,
				'b' => 0,
				'c' => 0
			),
			array(
				'a' => 0,
				'b' => 0,
				'c' => 0
			)
		);

		$count_employee = $this->Employee->find('count', array(
			'conditions' => array(
				'office_id' => $office_id
			),
			'recursive' => -1
		));

		$estimate = $this->EmployeePrize->find('all', array(
			'fields' => array(
				'sum(EmployeePrize.value) / '. $count .' * 12 as value'
			),
			'conditions' => array_merge(
				$conditions, array(
					'EmployeePrize.employee_id' => $employee_id
				)
			),
			'group' => 'EmployeePrize.employee_id',
			'recursive' => -1
		));

		$estimates = $this->EmployeePrize->find('all', array(
			'fields' => array(
				'(sum(EmployeePrize.value) / '. $count .' * 12) / '. $count_employee .' as value'
			),
			'conditions' => array_merge(
				$conditions, array(
					'Employee.office_id' => $office_id
				)
			),
			'joins' => array(
				array(
					'table' => 'employees',
					'alias' => 'Employee',
					'type' => 'INNER',
					'conditions' => array(
						'Employee.id = EmployeePrize.employee_id'
					)
				)
			),
			'recursive' => -1
		));

		if(!empty($estimate)) { $data[0]['a'] = round($estimate[0][0]['value']); }
		if(!empty($estimates)) { $data[1]['a'] = round($estimates[0][0]['value']); }

		$second_bar = $this->EmployeePrize->find('all', array(
			'fields' => array(
				'sum(EmployeePrize.value) as value'
			),
			'conditions' => array(
				'EmployeePrize.employee_id' => $employee_id,
				'EmployeePrize.prize_id' => array($first_prize, $second_prize),
				'EmployeePrize.year' => $year,
				'EmployeePrize.month' => $month
			),
			'joins' => array(
				array(
					'table' => 'employees',
					'alias' => 'Employee',
					'type' => 'INNER',
					'conditions' => array(
						'Employee.id = EmployeePrize.employee_id'
					)
				)
			),
			'group' => 'EmployeePrize.employee_id',
			'recursive' => -1
		));

		$second_bars = $this->EmployeePrize->find('all', array(
			'fields' => array(
				'sum(EmployeePrize.value) / '. $count_employee .' as value'
			),
			'conditions' => array(
				'Employee.office_id' => $office_id,
				'EmployeePrize.prize_id' => array($first_prize, $second_prize),
				'EmployeePrize.year' => $year,
				'EmployeePrize.month' => $month
			),
			'joins' => array(
				array(
					'table' => 'employees',
					'alias' => 'Employee',
					'type' => 'INNER',
					'conditions' => array(
						'Employee.id = EmployeePrize.employee_id'
					)
				)
			),
			'recursive' => -1
		));

		if(!empty($second_bar)) { $data[0]['b'] = round($second_bar[0][0]['value']); }
		if(!empty($second_bars)) { $data[1]['b'] = round($second_bars[0][0]['value']); }

		$third_bar = $this->EmployeePrize->find('all', array(
			'fields' => array(
				'sum(EmployeePrize.value) as value'
			),
			'conditions' => array(
				'EmployeePrize.employee_id' => $employee_id,
				'EmployeePrize.prize_id' => array($third_prize),
				'EmployeePrize.year' => $year,
				'EmployeePrize.month' => $month
			),
			'joins' => array(
				array(
					'table' => 'employees',
					'alias' => 'Employee',
					'type' => 'INNER',
					'conditions' => array(
						'Employee.id = EmployeePrize.employee_id'
					)
				)
			),
			'group' => 'EmployeePrize.employee_id',
			'recursive' => -1
		));

		$third_bars = $this->EmployeePrize->find('all', array(
			'fields' => array(
				'sum(EmployeePrize.value) / '. $count_employee .' as value'
			),
			'conditions' => array(
				'Employee.office_id' => $office_id,
				'EmployeePrize.prize_id' => array($third_prize),
				'EmployeePrize.year' => $year,
				'EmployeePrize.month' => $month
			),
			'joins' => array(
				array(
					'table' => 'employees',
					'alias' => 'Employee',
					'type' => 'INNER',
					'conditions' => array(
						'Employee.id = EmployeePrize.employee_id'
					)
				)
			),
			'recursive' => -1
		));

		if(!empty($third_bar)) { $data[0]['c'] = round($third_bar[0][0]['value']); }
		if(!empty($third_bars)) { $data[1]['c'] = round($third_bars[0][0]['value']); }

		return $data;
	}
	// end user page

	// start post
	public function list_post(){
		$this->set('title_for_layout', __('Post'));
        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            // 'limit' => '1',
            'fields' => array(
                'Post.id',
                'Post.account_id',
                'Post.type',
                'Post.title',
                'Post.short_description',
                'Post.avatar',
                'Post.status',
                'Post.created',
                'Account.id',
                'Account.username',
                ),
            'order' => array(
                'created' => 'desc'
            ),
            'conditions' => array(
                'Post.type' => 'post',
                'Post.status' => 'Publish',
            ),
        );
        $this->set('posts', $this->Paginator->paginate('Post'));
	}
	//load du lieu index frontend
    public function loadPost(){
    	$this->layout = "ajax";
        $page = $this->request->query['page'];
        try {
        	$this->Paginator->settings = array(
           		'limit' => Configure::read('Paging.size'),
	            'fields' => array(
	                'Post.id',
	                'Post.account_id',
	                'Post.type',
	                'Post.title',
	                'Post.short_description',
	                'Post.avatar',
	                'Post.status',
	                'Post.created',
	                'Account.id',
	                'Account.username',
	                ),
	            'order' => array(
	                'created' => 'desc'
	            ),
	            'conditions' => array(
	                'Post.type' => 'post',
	                'Post.status' => 'Publish',
	            ),
	            'page' => $page
	        );
            $post = $this->Paginator->paginate('Post');
            $this->set('posts', $post);
        } catch (Exception $e) {
            die;
        }
    }

    public function post_view($post_title = null , $id = null){
        if (!$this->Post->exists($id)) {
            throw new NotFoundException(__('Invalid post'));
        }
        $options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
        $post = $this->Post->find('first', $options);

        $category_id = array();

        if (isset($post['PostCategory'])) {
            foreach ($post['PostCategory'] as $pc) {
                if (empty($category_id)) {
                   $category_id[0] = $pc['category_id'];
                }else {
                    $category_id[count($category_id)] = $pc['category_id'];
                }
            }
        }

        $conditions = array();
        $conditions = array_merge($conditions, array(
            'Post.status'=>'Publish',
            "NOT" => array( "Post.id" => $id),
        ));
        if (!empty($search['category_id'])) {
            if (count($category_id) > 1) {
                $conditions = array_merge($conditions, array(
                    'PostCategory.category_id in' => $category_id,
                ));
            }else {
                $conditions = array_merge($conditions, array(
                    'PostCategory.category_id' => $category_id[0],
                ));
            }
        }

        $post_sames = $this->Post->find('all', array(
            'limit' => Configure::read('Paging.size'),
            'fields' => array(
                'DISTINCT(Post.id)',
                'Post.id',
                'Post.account_id',
                'Post.type',
                'Post.title',
                'Post.short_description',
                'Post.avatar',
                'Post.status',
                'Post.created',
                ),
            'paramType' => 'querystring',
            'order' => array(
                'created' => 'desc'
            ),
            'joins' => array(
                array(
                    'table'=> 'post_categories',
                    'alias' => 'PostCategory',
                    'type' => 'LEFT',
                    'conditions' =>array(
                        'PostCategory.post_id = Post.id'
                    )
                ),
            ),
            'contain' => array(
                'Category'=>array(
                    'fields' => array('id', 'name'),
                ),
                'PostCategory'=>array(
                    'fields' => array('id', 'post_id', 'category_id'),
                ),
            ),
            'conditions' =>$conditions
        ));

        $this->set('title_for_layout', $post['Post']['title']);
        $this->set('post', $post);
        $this->set('post_sames', $post_sames);
    }
    // end post
}