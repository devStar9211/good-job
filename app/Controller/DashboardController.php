<?php
App::uses('AppController', 'Controller');
/**
 * Accounts Controller
 *
 * @property Account $Account
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class DashboardController extends AppController {
    public $uses = array('Office', 'BudgetSale', 'Post');

	public function admin_index() {
		$this->set('title_for_layout', 'ダッシュボード');
	}
	public function index() {
        $this->layout = "default";
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
        
        $lastYear = date('Y', strtotime("-1 month"));
        $lastMonth = date('m', strtotime("-1 month"));
        $data = $this->generate_budget_ranking($lastYear, $lastMonth);

        $this->set('data', $data);
		$this->set('posts', $posts);
	}

    private function generate_budget_ranking($year, $month) {
        $previousYear = date('Y', strtotime($year .'-'. $month .'-01 -1 month'));
        $previousMonth = date('m', strtotime($year .'-'. $month .'-01 -1 month'));

        $offices = $this->Office->find('all', array(
            'fields' => array(
                '(BudgetSale.sales_revenues / BudgetSale.budget_revenues) * 100 as revenue',
                'Office.id', 'Office.name',
                'Company.id', 'Company.name'
            ),
            'conditions' => array(
                'BudgetSale.year' => $year,
                'BudgetSale.month' => $month
            ),
            'contain' => array(
                'Employee' => array(
                    'fields' => array('id', 'name', 'avatar'),
                    'conditions' => array(
                        'role' => 'manager'
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
            'order' => array(
                '(BudgetSale.sales_revenues / BudgetSale.budget_revenues) * 100' => 'desc'
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
                if(!empty($data_rank['revenue'])) {
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
                        'rank' => $position + 1
                    ),
                    'previous-month' => (
                        isset($previous_rank[$office['Office']['id']])
                        ? $previous_rank[$office['Office']['id']]
                        : null
                    )
                )
            );
        }

        return $data;
    }
}
