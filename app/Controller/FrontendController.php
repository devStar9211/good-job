<?php

App::uses('AppController', 'Controller');

class FrontendController extends AppController
{
    public $uses = array('Company', 'Office', 'BudgetSale', 'Post', 'Employee', 'Company', 'EmployeePrize', 'Prize', 'HonobonoResult', 'HonobonoSchedule', 'BonusQuarter', 'Config', 'PointHeader', 'PointDetail', 'HonobonoRiyouResult', 'HonobonoKaigoResult', 'OfficeRemote', 'Config', 'PointBonus', 'CompanyGroup', 'OfficeManager', 'Session', 'Account');
    public $components = array('Base');

    public function beforeFilter()
    {
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
        $frontend['quarter_budget_ranking'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/quarter_budget_ranking');
        $frontend['budget_sale'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/budget_sale');
        $frontend['user_page'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/user_page');
        $frontend['my_page'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/my_page');
        $frontend['all_bonus'] = $this->Acl->check(array('Account' => $account), 'controllers/frontend/all_bonus');
        $frontend['list_post'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/list_post');
        $frontend['active'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/active');
        $frontend['salary_detail'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/salary_detail');
        $this->set(compact('frontend'));


    }

    public function index()
    {
        $this->set('title_for_layout', 'home');

        $homePostNumberConfig = $this->Config->find('first', array(
            'conditions' => array('Config.key' => 'home_post_number')
        ));
        $homePostNumberConfig = !empty($homePostNumberConfig) ? $homePostNumberConfig['Config']['value'] : 7;

        $posts = $this->Post->find('all', array(
            'limit' => $homePostNumberConfig,
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
        $dateQuery['date'] = array(
            'timeset' => array(
                array(
                    'BudgetSale.month' => $lastMonth,
                    'BudgetSale.year' => $lastYear,
                )
            ),
            'honobono' => array(
                array(
                    'month' => $lastMonth,
                    'year' => $lastYear,
                )
            ),
            'date_manager' => array(
                'from' => null,
                'to' => date('Y-m-1'),
            )
        );
        $dateQuery['previous_date'] = array(
            'timeset' => array(
                array(
                    'BudgetSale.month' => date('m', strtotime($lastYear . '-' . $lastMonth . '-01 -1 month')),
                    'BudgetSale.year' => date('Y', strtotime($lastYear . '-' . $lastMonth . '-01 -1 month')),
                )
            ),
            'honobono' => array(
                array(
                    'month' => date('m', strtotime($lastYear . '-' . $lastMonth . '-01 -1 month')),
                    'year' => date('Y', strtotime($lastYear . '-' . $lastMonth . '-01 -1 month')),
                )
            ),
        );

        $data = $this->generate_budget_ranking($dateQuery);
        unset($data['paging']);

        $this->set('data', $data);
        $this->set('posts', $posts);

    }

    public function calendar()
    {


    }

    // start daily_settlement
    public function daily_settlement()
    {
        $this->set('title_for_layout', __('Daily Settlement'));
        $year = date('Y');
        $month = date('m');
        if (!empty($_GET['date'])) {
            $year = date('Y', strtotime($_GET['date']));
            $month = date('m', strtotime($_GET['date']));
        }
        $gridConfigColor = $this->Config->find('first', array(
            'conditions' => array('Config.key' => 'daily_settlement_grid_color')
        ));
        $gridConfigColor = !empty($gridConfigColor) ? unserialize($gridConfigColor['Config']['value']) : '';
        $data = $this->generate_daily_settlement_data($year, $month);
        $this->set('data', $data['data']);
        $this->set('last_row', $data['last_row']);
        $this->set('year', $year);
        $this->set('month', $month);
        $user = $this->Auth->user();
        $gridConfig = $this->Base->getGridConfig($user);
        $this->set('gridConfig', $gridConfig);
        $this->set('gridConfigColor', $gridConfigColor);
    }

    // start my page
    public function my_page()
    {
        $this->set('title_for_layout', __('My Page'));
        $user_login = $this->Auth->user();
        $employee_id = $user_login['Employee']['id'];

        $companyGroup = $this->CompanyGroup->find('first', array(
            'conditions' => array(
                'CompanyGroup.id' => 1
            ),
            'recursive' => -1
        ));
        $start_month = $companyGroup['CompanyGroup']['start_month'];
        $date_quarters = $this->Base->get_quarter($start_month, '');
        $current_year = $date_quarters['current_quarter']['year_quarter'];

        if (isset($_GET['date'])) {
            $date = $_GET['date'];
            $_get_quarter = explode('-Q', $date);
            $year_select = $_get_quarter[0];
            $quarter_select = $_get_quarter[1];
        } else {
            $year_select = $date_quarters['current_quarter']['year_quarter'];
            $quarter_select = $date_quarters['current_quarter']['quarter'];
        }

        $date_quarters = $this->Base->get_quarter_by_date($start_month, '', $quarter_select, $year_select);
        $end_date_of_quarter = end($date_quarters['quarter_select']['date']);
        $date_query = array(
            array(
                'year <=' => $end_date_of_quarter['year'],
                'month <=' => $end_date_of_quarter['month'],
            ),
            array(
                'year <=' => $end_date_of_quarter['year'] - 1,
                'month <=' => 12,
            ),
        );

        $this->PointDetail->virtualFields = array(
            'min_year' => 'min(year)'
        );
        $get_min_year = $this->PointDetail->find('first', array(
            'fields' => array(
                'min_year'
            ),
        ));
        if (!empty($get_min_year)) {
            $min_year = $get_min_year['PointDetail']['min_year'];
        } else {
            $min_year = $current_year;
        }

        $quarter_choice = array();
        for ($y = $min_year; $y <= $current_year; $y++) {
            for ($q = 1; $q <= 4; $q++) {
                $quarter_choice[$y . '-Q' . $q] = $y . '年第' . $q . '四半期';
            }
        }

        if (empty($employee_id)) {
            $this->redirect(array('action' => 'index'));
        } else {
            // get current rank
            $current_rank = $this->Employee->get_current_rank($employee_id, null);
            $earned_points = $this->get_earned_points($employee_id, $date_query);
            $earned_points_total = $this->get_earned_points_total($employee_id, $date_quarters['quarter_select']['date']);
            $earned_points_earch_quarter = $this->earned_points_earch_quarter($employee_id, $date_quarters['quarter_select']['date']);
            $this->set(compact('current_rank', 'earned_points', 'earned_points_earch_quarter', 'date_quarters', 'quarter_choice', 'quarter_select', 'year_select', 'earned_points_total'));
        }
    }

    // get earned points for employee
    private function earned_points_earch_quarter($employee_id, $date_quarter_query)
    {
        $data = array('total' => 0, 'total_point_with_bonus' => 0, 'detail' => '');
        $keyChar = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'k', 'l', 'm', 'n', 'o', 's', 'q', 'r', 't', 'u', 'v', 'x', 'y', 'z');

        foreach ($keyChar as $k => $_char) {
            $init_key[$k + 1]['key'] = $_char;
        }
        $this->PointDetail->virtualFields = array(
            'subtotal' => 'SUM(PointDetail.value)'
        );

        $parameters = array(
            'contain' => array(
                'PointType'
            ),
            'conditions' => array(
                'PointDetail.employee_id' => $employee_id,
            ),
            'group' => 'PointDetail.point_type_id',
        );

        if (!empty($date_quarter_query)) {
            $parameters['conditions']['OR'] = $date_quarter_query;
        }
        $point_groups = $this->PointDetail->find('all', $parameters);
        $i = 0;

        foreach ($point_groups as $_point_detail) {
            $i++;
            $data['group']['key'][] = $init_key[$i]['key'];
            $data['group']['label'][] = $_point_detail['PointType']['name'];
            $data['group']['color'][] = !empty($_point_detail['PointType']['color']) ? $_point_detail['PointType']['color'] : '#ffffff';
            $subtotal = $_point_detail['PointDetail']['subtotal'] != '' ? $_point_detail['PointDetail']['subtotal'] : 0;
            $data['group']['value'][] = $init_key[$i]['key'] . ':' . $subtotal;
            $data['total'] += $subtotal;
        }

        //total point of bonus base
        $this->PointBonus->virtualFields = array(
            'total' => 'SUM(PointBonus.bonus_point)'
        );
        $parameters = array(
            'conditions' => array(
                'PointBonus.employee_id' => $employee_id,
                'PointBonus.bonus_point <>' => null,
            ),
            'recursive' => -1
        );
        if (!empty($date_quarter_query)) {
            $parameters['conditions']['OR'] = $date_quarter_query;
        }
        $pointBonusQuarterTotal = $this->PointBonus->find('first', $parameters);
        if (!empty($pointBonusQuarterTotal)) {
            $total = $pointBonusQuarterTotal['PointBonus']['total'] != '' ? $pointBonusQuarterTotal['PointBonus']['total'] : 0;
            $data['total_point_with_bonus'] += $data['total'] + $total;
        }

        // get detail bonus base
        $this->PointBonus->virtualFields = null;
        $parameters = array(
            'conditions' => array(
                'PointBonus.employee_id' => $employee_id,
                'PointBonus.bonus_point <>' => null,
            ),
            'recursive' => -1
        );
        if (!empty($date_quarter_query)) {
            $parameters['conditions']['OR'] = $date_quarter_query;
        }
        $pointBonusQuarterTotal = $this->PointBonus->find('all', $parameters);
        foreach ($pointBonusQuarterTotal as $_point_detail) {
            $_date = date('Y-m-d', strtotime($_point_detail['PointBonus']['created'])) . ' 基本ボーナス（' . $_point_detail['PointBonus']['month'] . '月）';
            $data['detail'][] = $_date . number_format($_point_detail['PointBonus']['bonus_point'], 0, '.', ',') . '<i class="fa">円</i>';
        }
        return $data;
    }

    private function get_earned_points($employee_id, $date_quarter_query = null)
    {
        $data = array('total' => 0, 'group' => '', 'total_point_with_bonus' => 0, 'detail' => '');
        // total point on table point_details
        $this->PointDetail->virtualFields = array(
            'subtotal' => 'SUM(PointDetail.value)'
        );
        $parameters = array(
            'contain' => array(
                'PointType'
            ),
            'conditions' => array(
                'PointDetail.employee_id' => $employee_id,
                'PointDetail.value <>' => null,
            ),
            'group' => 'PointDetail.point_type_id',
        );
        if (!empty($date_quarter_query)) {
            $parameters['conditions']['OR'] = $date_quarter_query;
        }
        $point_groups = $this->PointDetail->find('all', $parameters);

        $i = 0;
        foreach ($point_groups as $_point_detail) {
            $i++;
            $data['group']['label'][] = $_point_detail['PointType']['name'];
            $data['group']['color'][] = !empty($_point_detail['PointType']['color']) ? $_point_detail['PointType']['color'] : '#ffffff';
            $subtotal = $_point_detail['PointDetail']['subtotal'] != '' ? $_point_detail['PointDetail']['subtotal'] : 0;
            $data['group']['value'][] = '{label:"' . $_point_detail['PointType']['name'] . '", value:' . $subtotal . '}';
            $data['total'] += $subtotal;
        }

        //total point on table bonus_quarter
        $this->PointBonus->virtualFields = array(
            'total' => 'SUM(PointBonus.bonus_point)'
        );
        $parameters = array(
            'conditions' => array(
                'PointBonus.employee_id' => $employee_id,
            ),
            'recursive' => -1
        );
        if (!empty($date_quarter_query)) {
            $parameters['conditions']['OR'] = $date_quarter_query;
        }
        $pointBonusQuarterTotal = $this->PointBonus->find('first', $parameters);
        if (!empty($pointBonusQuarterTotal)) {
            $total = $pointBonusQuarterTotal['PointBonus']['total'] != '' ? $pointBonusQuarterTotal['PointBonus']['total'] : 0;
            $data['total_point_with_bonus'] += $data['total'] + $total;
        }

        // get list point detail
        $this->PointDetail->virtualFields = null;
        $parameters = array(
            'contain' => array(
                'PointType'
            ),
            'conditions' => array(
                'PointDetail.employee_id' => $employee_id,
                'PointDetail.value <>' => null,
            ),
        );
        if (!empty($date_quarter_query)) {
            $parameters['conditions']['OR'] = $date_quarter_query;
        }
        $point_details = $this->PointDetail->find('all', $parameters);

        foreach ($point_details as $_point_detail) {
            $_date = date('Y-m-d', strtotime($_point_detail['PointDetail']['created'])) . ' ' . $_point_detail['PointType']['name'] . '（' . $_point_detail['PointDetail']['month'] . '月）';
            $data['list_point_detail'][] = $_date . number_format($_point_detail['PointDetail']['value'], 0, '.', ',') . '<i class="fa">pt</i>';
        }

        // get list point bunus
        $this->PointBonus->virtualFields = null;
        $parameters = array(
            'conditions' => array(
                'PointBonus.employee_id' => $employee_id,
                'PointBonus.bonus_point <>' => null,
            ),
            'recursive' => -1
        );
        if (!empty($date_quarter_query)) {
            $parameters['conditions']['OR'] = $date_quarter_query;
        }
        $pointBonusQuarterTotal = $this->PointBonus->find('all', $parameters);
        foreach ($pointBonusQuarterTotal as $_point_detail) {
            $_date = date('Y-m-d', strtotime($_point_detail['PointBonus']['created'])) . ' 基本ボーナス（' . $_point_detail['PointBonus']['month'] . '月）';
            $data['list_point_bonus'][] = $_date . number_format($_point_detail['PointBonus']['bonus_point'], 0, '.', ',') . '<i class="fa">円</i>';
        }
        return $data;
    }

    private function get_earned_points_total($employee_id, $date_quarter_query = null)
    {
        $data = array('total' => 0, 'group' => '', 'total_point_with_bonus' => 0, 'detail' => '');
        // total point on table point_details
        $this->PointDetail->virtualFields = array(
            'total' => 'SUM(PointDetail.value)'
        );
        $parameters = array(
            'conditions' => array(
                'PointDetail.employee_id' => $employee_id,
            ),
            'recursive' => -1
        );
        if (!empty($date_quarter_query)) {
            $parameters['conditions']['OR'] = $date_quarter_query;
        }
        $earn_points = $this->PointDetail->find('first', $parameters);
        if (!empty($earn_points)) {
            $data['total_point_with_bonus'] += $earn_points['PointDetail']['total'];
        }

        //total point on table bonus_quarter
        $this->PointBonus->virtualFields = array(
            'total' => 'SUM(PointBonus.bonus_point)'
        );
        $parameters = array(
            'conditions' => array(
                'PointBonus.employee_id' => $employee_id,
            ),
            'recursive' => -1
        );
        if (!empty($date_quarter_query)) {
            $parameters['conditions']['OR'] = $date_quarter_query;
        }
        $pointBonusQuarterTotal = $this->PointBonus->find('first', $parameters);
        if (!empty($pointBonusQuarterTotal)) {
            $data['total_point_with_bonus'] = $data['total_point_with_bonus'] + $pointBonusQuarterTotal['PointBonus']['total'];
        }


//        pr($earn_points);
//        pr($pointBonusQuarterTotal);
//        die;

        return $data;
    }

    // Function get all data bonus of employee
    public function all_point()
    {
        $this->set('title_for_layout', __('Cumulative earned points'));
        $user_login = $this->Auth->user();
        $employee_id = $user_login['Employee']['id'];

        $earned_points = $this->get_earned_points($employee_id, null);

        $this->set(compact('earned_points'));
    }

    public function point_bonus()
    {
        $this->set('title_for_layout', __('Cumulative Earned Basic Bonus'));
        $user_login = $this->Auth->user();
        $employee_id = $user_login['Employee']['id'];
        $date_quarters = $date_quarters = $this->Base->get_quarter(4);;

        // get current rank
        $earned_points = $this->get_earned_points($employee_id, null);

        $this->set(compact('earned_points'));
    }


    public function get_daily_data()
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
                $data = $this->generate_daily_settlement_data($req['year'], $req['month']);
                $view = new View($this, false);
                $user = $this->Auth->user();
                $gridConfig = $this->Base->getGridConfig($user);
                $table_data = $view->element('daily_settlement_data', array('data' => $data['data'], 'last_row' => $data['last_row'], 'gridConfig' => $gridConfig));

                $response['status'] = 1;
                $response['table_data'] = $table_data;
            }
        }
        return json_encode($response);
    }


    private function generate_daily_settlement_data($year, $month)
    {
        $pastYear = date('Y', strtotime($year . '-01-01 -1 year'));
        $offices = $this->Office->find('all', array(
            'fields' => array(
                'Office.id', 'Office.name', 'Office.office_number', 'Office.day_capacity', 'Office.remuneration_factor', 'Office.region_classification_factor', 'Office.display_in_budget_ranking', 'Office.sortable',
                'Company.id', 'Company.name',
                'OfficeGroup.id',
                'OfficeGroup.position',
                'CompanyGroup.start_month',
                'PastHighestSale.id', 'PastHighestSale.value',
            ),
            'conditions' => array(
                'Company.company_group_id' => 1,
            ),
            'contain' => array(
                'OfficeSelfPaid',
                'OfficeRemoteLabel' => array(
                    'conditions' => array(
                        'OfficeRemoteLabel.name' => array('jigyo_id_1', 'jigyo_id_2', 'jigyo_id_3', 'jigyo_id_4'),
                        'office_remotes.value <>' => ''
                    ),
                ),
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
                        'Office.company_group_id = CompanyGroup.id'
                    )
                ),
                array(
                    'table' => 'office_groups',
                    'alias' => 'OfficeGroup',
                    'type' => 'INNER',
                    'conditions' => array(
                        'OfficeGroup.id = Office.office_group_id'
                    )
                ),
                array(
                    'table' => 'past_highest_sales',
                    'alias' => 'PastHighestSale',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Office.id = PastHighestSale.office_id'
                    )
                ),
            ),
            'recursive' => -1
        ));


        $office_ids = array();
        foreach ($offices as $office) {
            $office_ids[] = $office['Office']['id'];
        }

        $start_month = $office['CompanyGroup']['start_month'];
        $monthsOfYear1 = $monthsOfYear2 = array();
        $year1 = $year2 = null;

        for ($i = 1; $i < $start_month; $i++) {
            if ($month >= 1 && $month < $start_month && $i <= $month) {
                $year2 = $year;
                $monthsOfYear2[] = $i;
            }
        }

        for ($i = $start_month; $i <= 12; $i++) {
            if ($month >= $start_month && $month <= 12 && $i <= $month) {
                $year1 = $year;
                $monthsOfYear1[] = $i;
                if ($i == $month) break;
            } else {
                $year1 = $year - 1;
                $monthsOfYear1[] = $i;
            }
        }

        $this->BudgetSale->virtualFields = array(
            'current_year_budget_revenues' => 'SUM(BudgetSale.budget_revenues)',
            'current_year_budget_expenses' => 'SUM(BudgetSale.budget_expenses)',
            'current_year_budget_labor_cost' => 'SUM(BudgetSale.budget_labor_cost)',
            'current_year_budget_overtime_cost' => 'SUM(BudgetSale.budget_overtime_cost)',

            'current_year_sales_revenues' => 'SUM(BudgetSale.sales_revenues)',
            'current_year_sales_expenses' => 'SUM(BudgetSale.sales_expenses)',
            'current_year_sales_labor_cost' => 'SUM(BudgetSale.sales_labor_cost)',
            'current_year_sales_overtime_cost' => 'SUM(BudgetSale.sales_overtime_cost)',
        );
        $dataCurrentYearBudgetSale = $this->BudgetSale->find('all', array(
            'fields' => array(
                'BudgetSale.office_id',
                'BudgetSale.current_year_budget_revenues',
                'BudgetSale.current_year_sales_revenues',

                'BudgetSale.current_year_budget_revenues',
                'BudgetSale.current_year_budget_expenses',
                'BudgetSale.current_year_budget_labor_cost',
                'BudgetSale.current_year_budget_overtime_cost',

                'BudgetSale.current_year_sales_revenues',
                'BudgetSale.current_year_sales_expenses',
                'BudgetSale.current_year_sales_labor_cost',
                'BudgetSale.current_year_sales_overtime_cost',
            ),
            'conditions' => array(
                'office_id' => $office_ids,
                'OR' => array(
                    array(
                        'year' => $year1,
                        'month' => $monthsOfYear1
                    ),
                    array(
                        'year' => $year2,
                        'month' => $monthsOfYear2
                    ),
                )
            ),
            'group' => 'BudgetSale.office_id',
            'recursive' => -1
        ));


        $this->BudgetSale->virtualFields = null;
        $currentYearBudgetSale = array();
        foreach ($dataCurrentYearBudgetSale as $budgetSale) {
            $currentYearBudgetSale[$budgetSale['BudgetSale']['office_id']] = $budgetSale['BudgetSale'];
        }


        // get data from honobono monthly
        $date = array(
            array(
                'month' => $month,
                'year' => $year,
            )
        );
        $dataHonobonoSchedule = $this->HonobonoSchedule->data_monthly($offices, $date);
        $dataHonobonoResult = $this->HonobonoResult->data_monthly($offices, $date);
        $dataHonobonoRiyouResult = $this->HonobonoRiyouResult->data_monthly($offices, $date);
        $dataHonobonoKaigoResult = $this->HonobonoKaigoResult->data_monthly($offices, $date);
        $dataHonobono = array();
        foreach ($offices as $office) {
            $dataHonobono[$office['Office']['id']]['HonobonoSchedule'] = $dataHonobonoSchedule[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoResult'] = $dataHonobonoResult[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoRiyouResult'] = $dataHonobonoRiyouResult[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoKaigoResult'] = $dataHonobonoKaigoResult[$office['Office']['id']];
        }

        // get data from honobono current year


        if ($year1 != null && $monthsOfYear1 != null) {
            $dateCurrentYear[$year1] = $monthsOfYear1;
        }
        if ($year2 != null && $monthsOfYear2 != null) {
            $dateCurrentYear[$year2] = $monthsOfYear2;
        }

        $dataCurrentYearHonobonoSchedule = $dataHonobonoSchedule;
        $dataCurrentYearHonobonoResult = $this->HonobonoResult->data_current_year($offices, $dateCurrentYear);
        $dataCurrentYearHonobonoRiyouResult = $this->HonobonoRiyouResult->data_current_year($offices, $dateCurrentYear);
        $dataCurrentYearHonobonoKaigoResult = $this->HonobonoKaigoResult->data_current_year($offices, $dateCurrentYear);

        $dataCurrentYearHonobono = array();
        foreach ($offices as $office) {
            $dataHonobono[$office['Office']['id']]['HonobonoSchedule'] = $dataHonobonoSchedule[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoResult'] = $dataHonobonoResult[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoRiyouResult'] = $dataHonobonoRiyouResult[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoKaigoResult'] = $dataHonobonoKaigoResult[$office['Office']['id']];

            $dataCurrentYearHonobono[$office['Office']['id']]['CurrentYearHonobonoSchedule'] = $dataCurrentYearHonobonoSchedule[$office['Office']['id']];
            $dataCurrentYearHonobono[$office['Office']['id']]['CurrentYearHonobonoResult'] = $dataCurrentYearHonobonoResult[$office['Office']['id']];
            $dataCurrentYearHonobono[$office['Office']['id']]['CurrentYearHonobonoRiyouResult'] = $dataCurrentYearHonobonoRiyouResult[$office['Office']['id']];
            $dataCurrentYearHonobono[$office['Office']['id']]['CurrentYearHonobonoKaigoResult'] = $dataCurrentYearHonobonoKaigoResult[$office['Office']['id']];
        }


        // get data for last month
        $lastMonthBudgetSale = array();
        $dataLastMonthBudgetSale = $this->BudgetSale->find('all', array(
            'conditions' => array(
                'office_id' => $office_ids,
                'year' => $year,
                'month' => $month
            ),
            'recursive' => -1
        ));
        foreach ($dataLastMonthBudgetSale as $budgetSale) {
            $lastMonthBudgetSale[$budgetSale['BudgetSale']['office_id']] = $budgetSale['BudgetSale'];
        }

        // get data for past year
        $pastYearBudgetSale = array();
        $dataPastYearBudgetSale = $this->BudgetSale->find('all', array(
            'conditions' => array(
                'office_id' => $office_ids,
                'year' => $pastYear,
                'month' => $month
            ),
            'recursive' => -1
        ));
        foreach ($dataPastYearBudgetSale as $budgetSale) {
            $pastYearBudgetSale[$budgetSale['BudgetSale']['office_id']] = $budgetSale['BudgetSale'];
        }

        // get data honobono month for past year
        $datePastYear = array(
            array(
                'month' => $month,
                'year' => $pastYear,
            )
        );
        $dataPastYearHonobonoSchedule = $this->HonobonoSchedule->data_monthly($offices, $datePastYear);
        $dataPastYearHonobonoResult = $this->HonobonoResult->data_monthly($offices, $datePastYear);
        $dataPastYearHonobonoRiyouResult = $this->HonobonoRiyouResult->data_monthly($offices, $datePastYear);
        $dataPastYearHonobonoKaigoResult = $this->HonobonoKaigoResult->data_monthly($offices, $datePastYear);
        $dataPastYearHonobono = array();
        foreach ($offices as $office) {
            $dataPastYearHonobono[$office['Office']['id']]['HonobonoSchedule'] = $dataPastYearHonobonoSchedule[$office['Office']['id']];
            $dataPastYearHonobono[$office['Office']['id']]['HonobonoResult'] = $dataPastYearHonobonoResult[$office['Office']['id']];
            $dataPastYearHonobono[$office['Office']['id']]['HonobonoRiyouResult'] = $dataPastYearHonobonoRiyouResult[$office['Office']['id']];
            $dataPastYearHonobono[$office['Office']['id']]['HonobonoKaigoResult'] = $dataPastYearHonobonoKaigoResult[$office['Office']['id']];
        }


        $summary = array(
            'revenue' => array(
                'budget' => '',
                'sales' => '',
                'rates' => '',
                'current_year_sales' => '',
                'current_year_budget' => '',
                'current_year_rates' => '',
            ),
            'profit' => array(
                'last-month' => array(
                    'budget' => '',
                    'sales' => '',
                    'rates' => '',
                    'current_year_sales' => '',
                    'current_year_budget' => '',
                    'current_year_rates' => '',
                ),
                'past-year' => array(
                    'budget' => '',
                    'sales' => '',
                    'rates' => '',
                    'current_year_sales' => '',
                    'current_year_budget' => '',
                    'current_year_rates' => '',
                ),
                'past-year-compare' => ''
            ),
            'labor_cost' => array(
                'budget' => '',
                'sales' => '',
                'current_year_budget' => '',
                'current_year_sales' => '',
                'past_sales' => '',
                'budget_overtime' => '',
                'sales_overtime' => '',
                'total_expense' => '',
                'rates' => '',
                'past-year-compare' => ''
            ),
            'past_highest_sale' => array(
                'sale' => ''
            ),
            'db_sale' => array(
                'day_capacity_monthly' => '',
                'rate_of_operation' => '',
                'avg_nursing_care_level' => '',
                'total_user_and_self_paid' => '',
                'total_nursing_care_level' => '',
                'number_of_user' => ''
            )
        );
        $companyGroup = $this->Company->find('all', array(
            'fields' => array('Company.id'),
            'conditions' => array(
                'Company.company_group_id' => 1
            ),
            'recursive' => -1
        ));
        foreach ($companyGroup as $_company) {
            $data[$_company['Company']['id']] = array('office' => array(), 'summary' => array(
                array(
                    'alias' => __('subtotal'),
                    'data' => $summary
                )
            ));
        }
        $last_row = key(array_slice($companyGroup, -1, 1, TRUE)) + 1;
        $summaries = array(
            'alias' => __('total'),
            'data' => $summary
        );
        $i = 1;

        foreach ($offices as $office) {
            $data_row = $this->generate_daily_settlement_row(
                $office,
                (
                isset($lastMonthBudgetSale[$office['Office']['id']])
                    ? $lastMonthBudgetSale[$office['Office']['id']]
                    : null
                ),
                (
                isset($pastYearBudgetSale[$office['Office']['id']])
                    ? $pastYearBudgetSale[$office['Office']['id']]
                    : null
                ),
                $month,
                $year,
                isset($currentYearBudgetSale[$office['Office']['id']])
                    ? $currentYearBudgetSale[$office['Office']['id']]
                    : null,
                $dataHonobono[$office['Office']['id']],
                $dataCurrentYearHonobono[$office['Office']['id']],
                $dataPastYearHonobono[$office['Office']['id']]
            );
            $office_ranking[$office['Office']['id']] = $data_row;
        }
        $office_ranking = $this->array_sort_budget_ranking_data($office_ranking, SORT_DESC);

        foreach ($offices as $office) {
            $data_row = $office_ranking[$office['Office']['id']];
            $group = $office['Company']['id'];
            $data[$group]['office'][] = $data_row;
            if ($data_row['revenue']['budget'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['revenue']['budget'] += $data_row['revenue']['budget'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['revenue']['budget'] = (
                                $data[$group]['summary'][1]['data']['revenue']['budget']
                                * ($i - 1)
                                + $data_row['revenue']['budget']
                            ) / $i;
                    }
                }
                $summaries['data']['revenue']['budget'] += $data_row['revenue']['budget'];
            }

            if ($data_row['revenue']['sales'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['revenue']['sales'] += $data_row['revenue']['sales'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['revenue']['sales'] = (
                                $data[$group]['summary'][1]['data']['revenue']['sales']
                                * ($i - 1)
                                + $data_row['revenue']['sales']
                            ) / $i;
                    }
                }
                $summaries['data']['revenue']['sales'] += $data_row['revenue']['sales'];
            }

            if ($data_row['revenue']['current_year_sales'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['revenue']['current_year_sales'] += $data_row['revenue']['current_year_sales'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['revenue']['current_year_sales'] = (
                                $data[$group]['summary'][1]['data']['revenue']['current_year_sales']
                                * ($i - 1)
                                + $data_row['revenue']['current_year_sales']
                            ) / $i;
                    }
                }
                $summaries['data']['revenue']['current_year_sales'] += $data_row['revenue']['current_year_sales'];
            }

            if ($data_row['revenue']['current_year_budget'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['revenue']['current_year_budget'] += $data_row['revenue']['current_year_budget'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['revenue']['current_year_budget'] = (
                                $data[$group]['summary'][1]['data']['revenue']['current_year_budget']
                                * ($i - 1)
                                + $data_row['revenue']['current_year_budget']
                            ) / $i;
                    }
                }
                $summaries['data']['revenue']['current_year_budget'] += $data_row['revenue']['current_year_budget'];
            }


            if ($data_row['profit']['last-month']['budget'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['profit']['last-month']['budget'] += $data_row['profit']['last-month']['budget'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['profit']['last-month']['budget'] = (
                                $data[$group]['summary'][1]['data']['profit']['last-month']['budget']
                                * ($i - 1)
                                + $data_row['profit']['last-month']['budget']
                            ) / $i;
                    }
                }
                $summaries['data']['profit']['last-month']['budget'] += $data_row['profit']['last-month']['budget'];
            }
            if ($data_row['profit']['last-month']['sales'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['profit']['last-month']['sales'] += $data_row['profit']['last-month']['sales'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['profit']['last-month']['sales'] = (
                                $data[$group]['summary'][1]['data']['profit']['last-month']['sales']
                                * ($i - 1)
                                + $data_row['profit']['last-month']['sales']
                            ) / $i;
                    }
                }
                $summaries['data']['profit']['last-month']['sales'] += $data_row['profit']['last-month']['sales'];
            }
            ///////////////////////////////
            if ($data_row['profit']['last-month']['current_year_budget'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['profit']['last-month']['current_year_budget'] += $data_row['profit']['last-month']['current_year_budget'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['profit']['last-month']['current_year_budget'] = (
                                $data[$group]['summary'][1]['data']['profit']['last-month']['current_year_budget']
                                * ($i - 1)
                                + $data_row['profit']['last-month']['current_year_budget']
                            ) / $i;
                    }
                }
                $summaries['data']['profit']['last-month']['current_year_budget'] += $data_row['profit']['last-month']['current_year_budget'];
            }
            if ($data_row['profit']['last-month']['current_year_sales'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['profit']['last-month']['current_year_sales'] += $data_row['profit']['last-month']['current_year_sales'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['profit']['last-month']['current_year_sales'] = (
                                $data[$group]['summary'][1]['data']['profit']['last-month']['current_year_sales']
                                * ($i - 1)
                                + $data_row['profit']['last-month']['current_year_sales']
                            ) / $i;
                    }
                }
                $summaries['data']['profit']['last-month']['current_year_sales'] += $data_row['profit']['last-month']['current_year_sales'];
            }
            ///////////////////////////////

            if ($data_row['profit']['past-year']['budget'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['profit']['past-year']['budget'] += $data_row['profit']['past-year']['budget'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['profit']['past-year']['budget'] = (
                                $data[$group]['summary'][1]['data']['profit']['past-year']['budget']
                                * ($i - 1)
                                + $data_row['profit']['past-year']['budget']
                            ) / $i;
                    }
                }
                $summaries['data']['profit']['past-year']['budget'] += $data_row['profit']['past-year']['budget'];
            }
            if ($data_row['profit']['past-year']['sales'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['profit']['past-year']['sales'] += $data_row['profit']['past-year']['sales'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['profit']['past-year']['sales'] = (
                                $data[$group]['summary'][1]['data']['profit']['past-year']['sales']
                                * ($i - 1)
                                + $data_row['profit']['past-year']['sales']
                            ) / $i;
                    }
                }
                $summaries['data']['profit']['past-year']['sales'] += $data_row['profit']['past-year']['sales'];
            }

            if ($data_row['labor_cost']['budget'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['labor_cost']['budget'] += $data_row['labor_cost']['budget'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['labor_cost']['budget'] = (
                                $data[$group]['summary'][1]['data']['labor_cost']['budget']
                                * ($i - 1)
                                + $data_row['labor_cost']['budget']
                            ) / $i;
                    }
                }
                $summaries['data']['labor_cost']['budget'] += $data_row['labor_cost']['budget'];
            }
            if ($data_row['labor_cost']['sales'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['labor_cost']['sales'] += $data_row['labor_cost']['sales'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['labor_cost']['sales'] = (
                                $data[$group]['summary'][1]['data']['labor_cost']['sales']
                                * ($i - 1)
                                + $data_row['labor_cost']['sales']
                            ) / $i;
                    }
                }
                $summaries['data']['labor_cost']['sales'] += $data_row['labor_cost']['sales'];
            }
            if ($data_row['labor_cost']['past_sales'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['labor_cost']['past_sales'] += $data_row['labor_cost']['past_sales'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['labor_cost']['past_sales'] = (
                                $data[$group]['summary'][1]['data']['labor_cost']['past_sales']
                                * ($i - 1)
                                + $data_row['labor_cost']['past_sales']
                            ) / $i;
                    }
                }
                $summaries['data']['labor_cost']['past_sales'] += $data_row['labor_cost']['past_sales'];
            }
            if ($data_row['labor_cost']['budget_overtime'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['labor_cost']['budget_overtime'] += $data_row['labor_cost']['budget_overtime'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['labor_cost']['budget_overtime'] = (
                                $data[$group]['summary'][1]['data']['labor_cost']['budget_overtime']
                                * ($i - 1)
                                + $data_row['labor_cost']['budget_overtime']
                            ) / $i;
                    }
                }
                $summaries['data']['labor_cost']['budget_overtime'] += $data_row['labor_cost']['budget_overtime'];
            }
            if ($data_row['labor_cost']['sales_overtime'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['labor_cost']['sales_overtime'] += $data_row['labor_cost']['sales_overtime'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['labor_cost']['sales_overtime'] = (
                                $data[$group]['summary'][1]['data']['labor_cost']['sales_overtime']
                                * ($i - 1)
                                + $data_row['labor_cost']['sales_overtime']
                            ) / $i;
                    }
                }
                $summaries['data']['labor_cost']['sales_overtime'] += $data_row['labor_cost']['sales_overtime'];
            }
            if ($data_row['labor_cost']['total_expense'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['labor_cost']['total_expense'] += $data_row['labor_cost']['total_expense'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['labor_cost']['total_expense'] = (
                                $data[$group]['summary'][1]['data']['labor_cost']['total_expense']
                                * ($i - 1)
                                + $data_row['labor_cost']['total_expense']
                            ) / $i;
                    }
                }
                $summaries['data']['labor_cost']['total_expense'] += $data_row['labor_cost']['total_expense'];
            }

            // for DB Sale
            if ($data_row['db_sale']['number_of_user'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['db_sale']['number_of_user'] += $data_row['db_sale']['number_of_user'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['db_sale']['number_of_user'] = (
                                $data[$group]['summary'][1]['data']['db_sale']['number_of_user']
                                * ($i - 1)
                                + $data_row['db_sale']['number_of_user']
                            ) / $i;
                    }
                }
                $summaries['data']['db_sale']['number_of_user'] += $data_row['db_sale']['number_of_user'];
            }
            if ($data_row['db_sale']['day_capacity_monthly'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['db_sale']['day_capacity_monthly'] += $data_row['db_sale']['day_capacity_monthly'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['db_sale']['day_capacity_monthly'] = (
                                $data[$group]['summary'][1]['data']['db_sale']['day_capacity_monthly']
                                * ($i - 1)
                                + $data_row['db_sale']['day_capacity_monthly']
                            ) / $i;
                    }
                }
                $summaries['data']['db_sale']['day_capacity_monthly'] += $data_row['db_sale']['day_capacity_monthly'];
            }
            if ($data_row['db_sale']['total_user_and_self_paid'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['db_sale']['total_user_and_self_paid'] += $data_row['db_sale']['total_user_and_self_paid'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['db_sale']['total_user_and_self_paid'] = (
                                $data[$group]['summary'][1]['data']['db_sale']['total_user_and_self_paid']
                                * ($i - 1)
                                + $data_row['db_sale']['total_user_and_self_paid']
                            ) / $i;
                    }
                }
                $summaries['data']['db_sale']['total_user_and_self_paid'] += $data_row['db_sale']['total_user_and_self_paid'];
            }
            if ($data_row['db_sale']['total_nursing_care_level'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['db_sale']['total_nursing_care_level'] += $data_row['db_sale']['total_nursing_care_level'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['db_sale']['total_nursing_care_level'] = (
                                $data[$group]['summary'][1]['data']['db_sale']['total_nursing_care_level']
                                * ($i - 1)
                                + $data_row['db_sale']['total_nursing_care_level']
                            ) / $i;
                    }
                }
                $summaries['data']['db_sale']['total_nursing_care_level'] += $data_row['db_sale']['total_nursing_care_level'];
            }
            if ($data_row['past_highest_sale']['sale'] !== '') {
                if (isset($data[$group]['summary'])) {
                    $data[$group]['summary'][0]['data']['past_highest_sale']['sale'] += $data_row['past_highest_sale']['sale'];
                    if ($group == 'second-group') {
                        $data[$group]['summary'][1]['data']['past_highest_sale']['sale'] = (
                                $data[$group]['summary'][1]['data']['past_highest_sale']['sale']
                                * ($i - 1)
                                + $data_row['past_highest_sale']['sale']
                            ) / $i;
                    }
                }
                $summaries['data']['past_highest_sale']['sale'] += $data_row['past_highest_sale']['sale'];
            }


        }


        $data[$last_row]['summary'][] = $summaries;


        foreach ($data as $group => $data_group) {
            foreach ($data[$group]['summary'] as $key => $summary) {
                if (
                    !empty($data[$group]['summary'][$key]['data']['revenue']['budget'])
                    && !empty($data[$group]['summary'][$key]['data']['revenue']['sales'])
                ) {
                    if(
                        $data[$group]['summary'][$key]['data']['revenue']['sales'] < 0
                        && $data[$group]['summary'][$key]['data']['revenue']['budget'] < 0
                    ){
                        $var1 = -1;
                    }else{
                        $var1 = 1;
                    }

                    $data[$group]['summary'][$key]['data']['revenue']['rates'] = (
                            (double)$data[$group]['summary'][$key]['data']['revenue']['sales']
                            / (double)$data[$group]['summary'][$key]['data']['revenue']['budget']
                        ) * 100*$var1;
                }

                if (
                    !empty($data[$group]['summary'][$key]['data']['revenue']['current_year_budget'])
                    && !empty($data[$group]['summary'][$key]['data']['revenue']['current_year_sales'])
                ) {
                    $data[$group]['summary'][$key]['data']['revenue']['current_year_rates'] = (
                            (double)$data[$group]['summary'][$key]['data']['revenue']['current_year_sales']
                            / (double)$data[$group]['summary'][$key]['data']['revenue']['current_year_budget']
                        ) * 100;
                }

                if (
                    !empty($data[$group]['summary'][$key]['data']['profit']['last-month']['budget'])
                    && !empty($data[$group]['summary'][$key]['data']['profit']['last-month']['sales'])
                ) {
                    if(
                        $data[$group]['summary'][$key]['data']['profit']['last-month']['sales'] < 0
                        && $data[$group]['summary'][$key]['data']['profit']['last-month']['budget'] < 0
                    ){
                        $var1 = -1;
                    }else{
                        $var1 = 1;
                    }

                    $data[$group]['summary'][$key]['data']['profit']['last-month']['rates'] = (
                            (double)$data[$group]['summary'][$key]['data']['profit']['last-month']['sales']
                            / (double)$data[$group]['summary'][$key]['data']['profit']['last-month']['budget']
                        ) * 100*$var1;

                }

                if (
                    !empty($data[$group]['summary'][$key]['data']['profit']['last-month']['current_year_budget'])
                    && !empty($data[$group]['summary'][$key]['data']['profit']['last-month']['current_year_sales'])
                ) {
                    $data[$group]['summary'][$key]['data']['profit']['last-month']['current_year_rates'] = (
                            (double)$data[$group]['summary'][$key]['data']['profit']['last-month']['current_year_sales']
                            / (double)$data[$group]['summary'][$key]['data']['profit']['last-month']['current_year_budget']
                        ) * 100;
                }


                if (
                    !empty($data[$group]['summary'][$key]['data']['profit']['past-year']['budget'])
                    && !empty($data[$group]['summary'][$key]['data']['profit']['past-year']['sales'])
                ) {
                    $data[$group]['summary'][$key]['data']['profit']['past-year']['rates'] = (
                            (double)$data[$group]['summary'][$key]['data']['profit']['past-year']['sales']
                            / (double)$data[$group]['summary'][$key]['data']['profit']['past-year']['budget']
                        ) * 100;
                }

                if (
                    !empty($data[$group]['summary'][$key]['data']['profit']['last-month']['sales'])
                    && !empty($data[$group]['summary'][$key]['data']['profit']['past-year']['sales'])
                ) {
                    $data[$group]['summary'][$key]['data']['profit']['past-year-compare'] = (
                            (double)$data[$group]['summary'][$key]['data']['profit']['last-month']['sales']
                            / (double)$data[$group]['summary'][$key]['data']['profit']['past-year']['sales']
                        ) * 100;
                }

                if (
                    !empty($data[$group]['summary'][$key]['data']['labor_cost']['budget'])
                    && !empty($data[$group]['summary'][$key]['data']['labor_cost']['sales'])
                ) {
                    $data[$group]['summary'][$key]['data']['labor_cost']['rates'] = (
                            (double)$data[$group]['summary'][$key]['data']['labor_cost']['sales']
                            / (double)$data[$group]['summary'][$key]['data']['labor_cost']['budget']
                        ) * 100;

                    if (!empty($data[$group]['summary'][$key]['data']['labor_cost']['past_sales'])) {
                        $data[$group]['summary'][$key]['data']['labor_cost']['past-year-compare'] = (
                                (double)$data[$group]['summary'][$key]['data']['labor_cost']['sales']
                                / (double)$data[$group]['summary'][$key]['data']['labor_cost']['past_sales']
                            ) * 100;
                    }
                }

                // for DB Sale
                if (
                    !empty($data[$group]['summary'][$key]['data']['db_sale']['total_user_and_self_paid'])
                    && !empty($data[$group]['summary'][$key]['data']['db_sale']['day_capacity_monthly'])
                ) {
                    $data[$group]['summary'][$key]['data']['db_sale']['rate_of_operation'] = (
                            (double)$data[$group]['summary'][$key]['data']['db_sale']['total_user_and_self_paid']
                            / (double)$data[$group]['summary'][$key]['data']['db_sale']['day_capacity_monthly']
                        ) * 100;
                }
                if (
                    !empty($data[$group]['summary'][$key]['data']['db_sale']['total_nursing_care_level'])
                    && !empty($data[$group]['summary'][$key]['data']['db_sale']['total_user_and_self_paid'])
                ) {
                    $data[$group]['summary'][$key]['data']['db_sale']['avg_nursing_care_level'] = (
                        (double)$data[$group]['summary'][$key]['data']['db_sale']['total_nursing_care_level']
                        / (double)$data[$group]['summary'][$key]['data']['db_sale']['total_user_and_self_paid']
                    );
                }
            }
        }
        // delete "average group"
        unset($data['second-group']['summary']['1']);
        return array('last_row' => $last_row, 'data' => $this->array_sort_daily_settlement_data($data, SORT_DESC));
    }

    // sort order on page daily settlement
    private function array_sort_daily_settlement_data($data, $order = SORT_ASC)
    {
        foreach ($data as $k_dt => $_item) {
            $new_array = array();
            $sortable_array_before = $sortable_array_middle = $sortable_array_after = array();
            if (count($_item['office']) > 0) {
                foreach ($_item['office'] as $k => $v) {
                    switch ($v['office']['sortable']) {
                        case 1 :
                            $sortable_array_before[$k] = $v['profit']['last-month']['rates'];
                            break;
                        case 2 :
                            $sortable_array_middle[$k] = $v['profit']['last-month']['rates'];
                            break;
                        case 3 :
                            $sortable_array_after[$k] = $v['profit']['last-month']['rates'];
                            break;
                    }
                }
                switch ($order) {
                    case SORT_ASC:
                        asort($sortable_array_before);
                        asort($sortable_array_middle);
                        asort($sortable_array_after);
                        break;
                    case SORT_DESC:
                        arsort($sortable_array_before);
                        arsort($sortable_array_middle);
                        arsort($sortable_array_after);
                        break;
                }
                $sortable_array = $sortable_array_before + $sortable_array_middle + $sortable_array_after;
                foreach ($sortable_array as $k => $v) {
                    $new_array[$k] = $_item['office'][$k];
                }
                $data[$k_dt]['office'] = $new_array;
            }
        }
        return $data;
    }

    private function generate_daily_settlement_row($office, $budgetSale, $pastBudgetSale, $month, $year, $currentYearBudgetSale, $dataHonobono, $dataCurrentYearHonobono, $dataPastYearHonobono)
    {

        $date = $year . '-' . $month;
        $end_day_of_month = date("t", strtotime($date));
        $dayCapacity = $office['Office']['day_capacity'];

        // total sale of month
        $totalSaleHonobono = +$dataHonobono['HonobonoSchedule']['sale']
            + $dataHonobono['HonobonoResult']['sale']
            + $dataHonobono['HonobonoRiyouResult']['sale']
            + $dataHonobono['HonobonoKaigoResult']['sale'];
        $revenueTotalSales = $budgetSale['sales_revenues'] + $totalSaleHonobono;
        // excess revenue sale
        $excess_revenue = $revenueTotalSales - $budgetSale['budget_revenues'];

        /* Nursing Care Level */
        $totalNursingCareLevel = $dataHonobono['HonobonoResult']['total_nursing_care_level'];
        $totalUser = $dataHonobono['HonobonoResult']['total_user'];
        $totalUsingSelfPaid = $dataHonobono['HonobonoSchedule']['total_self_paid'];
        $avgNursingCareLevel = !empty($totalNursingCareLevel) ? ($totalNursingCareLevel / $totalUser + $totalUsingSelfPaid) : '';

        /* rate of operation */
        $dayCapacityMonthly = $dayCapacity * $end_day_of_month;
        $totalUserAndSelfPaid = $dataHonobono['HonobonoResult']['total_user'] + $dataHonobono['HonobonoSchedule']['total_self_paid'];
        $rateOfOperation = (!empty($totalUserAndSelfPaid) && !empty($dayCapacityMonthly)) ? ($totalUserAndSelfPaid) / ($dayCapacityMonthly) * 100 : '';

        // rate sale of over month
        $revenue_rate =
            !empty($budgetSale)
            && $revenueTotalSales > 0
            && $budgetSale['budget_revenues'] > 0
                ?
                ((double)$revenueTotalSales / (double)$budgetSale['budget_revenues']) * 100
                :
                '';

        // total sale of over year
        $curentYearTotalSaleHonobono = $dataCurrentYearHonobono['CurrentYearHonobonoSchedule']['sale'] + $dataCurrentYearHonobono['CurrentYearHonobonoResult']['sale'] + $dataCurrentYearHonobono['CurrentYearHonobonoRiyouResult']['sale'] + $dataCurrentYearHonobono['CurrentYearHonobonoKaigoResult']['sale'];
        $currentYearTotalSale = $currentYearBudgetSale['current_year_sales_revenues'] + $curentYearTotalSaleHonobono;

        // rate sale of over year
        $currentYearRevenueRate =
            !empty($currentYearBudgetSale)
            && $currentYearTotalSale > 0
            && $currentYearBudgetSale['current_year_budget_revenues'] > 0
                ?
                ($currentYearTotalSale / (double)$currentYearBudgetSale['current_year_budget_revenues']) * 100
                :
                '-';

        // total expense
        $totalExpense = (!empty($budgetSale)) ? ($budgetSale['sales_expenses']) : '0';

        // past year compare
        $revenueTotalSalesPastYear =
            $pastBudgetSale['sales_revenues']
            + $dataPastYearHonobono['HonobonoSchedule']['sale']
            + $dataPastYearHonobono['HonobonoResult']['sale']
            + $dataPastYearHonobono['HonobonoRiyouResult']['sale']
            + $dataPastYearHonobono['HonobonoKaigoResult']['sale'];

        $revenueRatePastYearCompare = $revenueTotalSales > 0 && $revenueTotalSalesPastYear > 0 ? ($revenueTotalSales / $revenueTotalSalesPastYear) * 100 : '';

        $result = array(
            'company' => $office['Company'],
            'office' => $office['Office'],
            'past_highest_sale' => array(
                'id' => $office['PastHighestSale']['id'],
                'sale' => $office['PastHighestSale']['value']
            ),
            'revenue' => array(
                'budget' => !empty($budgetSale['budget_revenues']) ? $budgetSale['budget_revenues'] : 0,
                'sales' => $revenueTotalSales,
                'excess_revenue' => $excess_revenue,
                'rates' => $revenue_rate,
                'past-year-compare' => $revenueRatePastYearCompare,
                'current_year_sales' => $currentYearTotalSale,
                'current_year_budget' => !empty($currentYearBudgetSale['current_year_budget_revenues']) ? $currentYearBudgetSale['current_year_budget_revenues'] : '',
                'current_year_rates' => $currentYearRevenueRate,
            ),
            'profit' => array(
                'last-month' => $this->BudgetSale->calculate_profit($budgetSale, $totalSaleHonobono, $currentYearBudgetSale, $curentYearTotalSaleHonobono),
                'past-year' => $this->BudgetSale->calculate_profit($pastBudgetSale, $totalSaleHonobono, $currentYearBudgetSale, $curentYearTotalSaleHonobono)
            ),
            'labor_cost' => array(
                'budget' => !empty($budgetSale) ? $budgetSale['budget_labor_cost'] : '0',
                'sales' => !empty($budgetSale) ? $budgetSale['sales_labor_cost'] : '0',
                'past_sales' => !empty($pastBudgetSale) ? $pastBudgetSale['sales_labor_cost'] : '0',
                'budget_overtime' => !empty($budgetSale) ? $budgetSale['budget_overtime_cost'] : '0',
                'sales_overtime' => !empty($budgetSale) ? $budgetSale['sales_overtime_cost'] : '0',
                'total_expense' => $totalExpense,
                'rates' => (
                !empty($budgetSale)
                && !empty($budgetSale['sales_labor_cost']) && !empty($budgetSale['budget_labor_cost'])
                    ? (
                        (double)$budgetSale['sales_labor_cost'] / (double)$budgetSale['budget_labor_cost']
                    ) * 100 : '0'
                ),
                'current_year_budget' => !empty($currentYearBudgetSale) ? $currentYearBudgetSale['current_year_budget_labor_cost'] : '0',
                'current_year_sales' => !empty($currentYearBudgetSale) ? $currentYearBudgetSale['current_year_sales_labor_cost'] : '0',
                'past-year-compare' => (
                !empty($budgetSale) && !empty($pastBudgetSale)
                && !empty($budgetSale['sales_labor_cost']) && !empty($pastBudgetSale['sales_labor_cost'])
                    ? (
                        (double)$budgetSale['sales_labor_cost'] / (double)$pastBudgetSale['sales_labor_cost']
                    ) * 100 : '0'
                )
            ),
            'db_sale' => array(
                'sale' => $dataHonobono['HonobonoResult']['sale'],
                'budget' => $dataHonobono['HonobonoSchedule']['sale'],
                'sale_monthly' => $totalSaleHonobono,
                'rate_of_operation' => $rateOfOperation,
                'avg_nursing_care_level' => $avgNursingCareLevel,
                'number_of_user' => $dataHonobono['HonobonoResult']['total_user'],
                'day_capacity_monthly' => $dayCapacityMonthly,
                'total_user' => $dataHonobono['HonobonoResult']['total_user'],
                'total_nursing_care_level' => $dataHonobono['HonobonoResult']['total_nursing_care_level'],
                'total_user_and_self_paid' => $totalUserAndSelfPaid,
            ),
        );
        return $result;
    }

    public function ranking()
    {
        $this->budget_ranking();
        $this->quarter_budget_ranking();
    }

    // start budget ranking
    private function budget_ranking()
    {
        $this->set('title_for_layout', __('Budget Ranking'));
        $lastYear = date('Y');
        $lastMonth = date('m');
        $dateQuery['date'] = array(
            'timeset' => array(
                array(
                    'BudgetSale.month' => $lastMonth,
                    'BudgetSale.year' => $lastYear
                )
            ),
            'honobono' => array(
                array(
                    'month' => $lastMonth,
                    'year' => $lastYear
                )
            ),
            'date_manager' => array(
                'from' => null,
                'to' => date('Y-m-1'),
            )
        );

        $dateQuery['previous_date'] = array(
            'timeset' => array(
                array(
                    'BudgetSale.month' => date('m', strtotime($lastYear . '-' . $lastMonth . '-01 -1 month')),
                    'BudgetSale.year' => date('Y', strtotime($lastYear . '-' . $lastMonth . '-01 -1 month')),
                )
            ),
            'honobono' => array(
                array(
                    'month' => date('m', strtotime($lastYear . '-' . $lastMonth . '-01 -1 month')),
                    'year' => date('Y', strtotime($lastYear . '-' . $lastMonth . '-01 -1 month')),
                )
            ),
        );

        $data = $this->generate_budget_ranking($dateQuery);
        $this->set('data', $data);
        $this->set('year', $lastYear);
        $this->set('month', $lastMonth);
    }

    // start budget ranking quarter
    private function quarter_budget_ranking()
    {
        $this->set('title_for_layout', __('quarterly ranking'));
        $lastYear = date('Y');
        $lastMonth = date('m');

        $companyGroup = $this->CompanyGroup->find('first', array(
            'conditions' => array(
                'CompanyGroup.id' => 1
            ),
            'recursive' => -1
        ));
        $start_month = $companyGroup['CompanyGroup']['start_month'];

        $date_quarters = $this->Base->get_quarter($start_month, 'BudgetSale');
        $date_quarters_honobono = $this->Base->get_quarter($start_month, null);

        $date_quarter_selected = $date_quarters['current_quarter'];
        $date_quarter_honobono_selected = $date_quarters_honobono['current_quarter'];


        // generate quarter choice on view
        $current_year = $date_quarters['current_quarter']['year_quarter'];
        $this->PointDetail->virtualFields = array(
            'min_year' => 'min(year)'
        );
        $get_min_year = $this->PointDetail->find('first', array(
            'fields' => array(
                'min_year'
            ),
        ));
        if (!empty($get_min_year)) {
            $min_year = $get_min_year['PointDetail']['min_year'];
        } else {
            $min_year = $current_year;
        }
        $quarter_choice = array();
        for ($y = $min_year; $y <= $current_year; $y++) {
            for ($q = 1; $q <= 4; $q++) {

                $quarter_choice[$y . '-Q' . $q] = $y . '年第' . $q . '四半期';
                if ($date_quarters['current_quarter']['quarter'] == $q && $y == $current_year)
                    break;
            }
        }

        $end_of_date = end($date_quarter_honobono_selected['date']);
        $dateQuery['date'] = array(
            'timeset' => $date_quarter_selected['date'],
            'honobono' => $date_quarter_honobono_selected['date'],
            'date_manager' => array(
                'from' => null,
                'to' => date('Y-m-1', strtotime($end_of_date['year'] . '-' . $end_of_date['month'] . '-1'))
            )
        );

        $quarter_data = $this->generate_budget_ranking($dateQuery);
        $this->set('quarter_data', $quarter_data);
        $this->set('year', $lastYear);
        $this->set('month', $lastMonth);
        $this->set(compact('quarter_choice', 'date_quarter_selected'));
    }

    // get data for ajax request
    public function get_quarter_budget_ranking_data()
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
            $_get_quarter = explode('-Q', $req['time_quarter']);

            $companyGroup = $this->CompanyGroup->find('first', array(
                'conditions' => array(
                    'CompanyGroup.id' => 1
                ),
                'recursive' => -1
            ));
            $start_month = $companyGroup['CompanyGroup']['start_month'];
            $date_quarters = $this->Base->get_quarter_by_date($start_month, 'BudgetSale', $_get_quarter[1], $_get_quarter[0]);
            $date_quarters_honobono = $this->Base->get_quarter_by_date($start_month, '', $_get_quarter[1], $_get_quarter[0]);
            if ($_get_quarter[0] == $date_quarters['current_quarter']['year_quarter'] && $_get_quarter[1] == $date_quarters['current_quarter']['quarter']) {
                $date_quarter_selected = $date_quarters['current_quarter'];
                $date_quarter_honobono_selected = $date_quarters_honobono['current_quarter'];
            } else {
                $date_quarter_selected = $date_quarters['quarters'][$_get_quarter[1]];
                $date_quarter_honobono_selected = $date_quarters_honobono['quarters'][$_get_quarter[1]];
            }
            $quarter_choice = array();
            foreach ($date_quarters['quarters'] as $_item) {
                if ($_item['quarter'] <= $date_quarters['current_quarter']['quarter']) {
                    $quarter_choice[$_item['year_quarter'] . '-Q' . $_item['quarter']] = $_item['year_quarter'] . '年第' . $_item['quarter'] . '四半期';
                }
            }
            $end_of_date = end($date_quarter_honobono_selected['date']);
            $dateQuery['date'] = array(
                'timeset' => $date_quarter_selected['date'],
                'honobono' => $date_quarter_honobono_selected['date'],
                'date_manager' => array(
                    'from' => null,
                    'to' => date('Y-m-1', strtotime($end_of_date['year'] . '-' . $end_of_date['month'] . '-1'))
                )
            );

            $quarter_data = $this->generate_budget_ranking($dateQuery);
            $view = new View($this, false);
            $table_data = $view->element('budget_ranking_quarter_data', array('quarter_data' => $quarter_data));
            $response['status'] = 1;
            $response['table_data'] = $table_data;
        }
        return json_encode($response);
    }


    public function get_budget_ranking_data()
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
                $dateQuery['date'] = array(
                    'timeset' => array(
                        array(
                            'BudgetSale.month' => $req['month'],
                            'BudgetSale.year' => $req['year']
                        )
                    ),
                    'honobono' => array(
                        array(
                            'month' => $req['month'],
                            'year' => $req['year']
                        )
                    ),
                    'date_manager' => array(
                        'from' => null,
                        'to' => date('Y-m-1', strtotime($req['year'] . '-' . $req['month'] . '-01'))
                    ),
                );

                $dateQuery['previous_date'] = array(
                    'timeset' => array(
                        array(
                            'BudgetSale.month' => date('m', strtotime($req['year'] . '-' . $req['month'] . '-01 -1 month')),
                            'BudgetSale.year' => date('Y', strtotime($req['year'] . '-' . $req['month'] . '-01 -1 month')),
                        )
                    ),
                    'honobono' => array(
                        array(
                            'month' => date('m', strtotime($req['year'] . '-' . $req['month'] . '-01 -1 month')),
                            'year' => date('Y', strtotime($req['year'] . '-' . $req['month'] . '-01 -1 month')),
                        )
                    ),

                );

                $data = $this->generate_budget_ranking($dateQuery);
                $view = new View($this, false);
                $table_data = $view->element('budget_ranking_data', array('data' => $data));
                $response['status'] = 1;
                $response['table_data'] = $table_data;
            }
        }
        return json_encode($response);
    }

    private function generate_budget_ranking($date)
    {
        $data['office'] = $this->generate_budget_ranking_data($date['date']);
        // previous ranking
        $previous_ranking = !empty($date['previous_date']) ? $this->generate_budget_ranking_data($date['previous_date']) : array();
        foreach ($data['office'] as $key => $office) {
            foreach ($previous_ranking as $prev_key => $prev_ranking) {
                if ($prev_ranking['office']['id'] == $office['office']['id']) {
                    $data['office'][$key]['previous_ranking'] = $prev_ranking['ranking'];
                }
            }
        }
        return $data;
    }

    private function generate_budget_ranking_data($date)
    {

        $query = array(
            'fields' => array(
                'Office.id', 'Office.name', 'Office.office_number', 'Office.day_capacity', 'Office.remuneration_factor', 'Office.region_classification_factor', 'Office.display_in_budget_ranking',
                'Company.id', 'Company.name',
                'PastHighestSale.id', 'PastHighestSale.value',
//                'OfficeManager.*'
            ),
            'conditions' => array(
                'Company.company_group_id' => 1,
                'Office.display_in_budget_ranking' => 1,
            ),
            'contain' => array(
                'OfficeSelfPaid',
                'OfficeRemoteLabel' => array(
                    'conditions' => array(
                        'OfficeRemoteLabel.name' => array('jigyo_id_1', 'jigyo_id_2', 'jigyo_id_3', 'jigyo_id_4'),
                        'office_remotes.value <>' => ''
                    ),
                ),
//                'Employee' => array(
//                    'fields' => array('id', 'name', 'avatar'),
//                    'conditions' => array(
//                        'is_manager' => 1
//                    )
//                ),
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
                    'table' => 'past_highest_sales',
                    'alias' => 'PastHighestSale',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Office.id = PastHighestSale.office_id'
                    )
                ),
//                array(
//                    'table' => 'office_managers',
//                    'alias' => 'OfficeManager',
//                    'type' => 'LEFT',
//                    'conditions' => array(
//                        'Office.id = OfficeManager.office_id',
//                        'OfficeManager.status=1',
//                    )
//                ),

            ),
            'recursive' => -1,
        );

        $offices = $this->Office->find('all', $query);


        $office_ids = array();
        foreach ($offices as $office) {
            $office_ids[] = $office['Office']['id'];
        }

        if (sizeof($date['timeset']) > 1) {
            $conditionsBudgetSale = array(
                'office_id' => $office_ids,
                'OR' => $date['timeset'],
            );
        } else {
            $conditionsBudgetSale = array(
                'office_id' => $office_ids,
                'OR' => $date['timeset'],
            );
        }

        $this->BudgetSale->virtualFields = array(
            'budget_revenues' => 'SUM(BudgetSale.budget_revenues)',
            'budget_expenses' => 'SUM(BudgetSale.budget_expenses)',
            'budget_labor_cost' => 'SUM(BudgetSale.budget_labor_cost)',
            'budget_overtime_cost' => 'SUM(BudgetSale.budget_overtime_cost)',
            'sales_revenues' => 'SUM(BudgetSale.sales_revenues)',
            'sales_expenses' => 'SUM(BudgetSale.sales_expenses)',
            'sales_labor_cost' => 'SUM(BudgetSale.sales_labor_cost)',
            'sales_overtime_cost' => 'SUM(BudgetSale.sales_overtime_cost)',
        );

        $lastMonthBudgetSale = array();
        $data_lastMonthBudgetSale = $this->BudgetSale->find('all', array(
            'conditions' => $conditionsBudgetSale,
            'group' => 'BudgetSale.office_id',
            'recursive' => -1
        ));

        foreach ($data_lastMonthBudgetSale as $budgetSale) {
            $lastMonthBudgetSale[$budgetSale['BudgetSale']['office_id']] = $budgetSale['BudgetSale'];
        }

        // get data from honobono monthly
        $dataHonobonoSchedule = $this->HonobonoSchedule->data_monthly($offices, $date['honobono']);
        $dataHonobonoResult = $this->HonobonoResult->data_monthly($offices, $date['honobono']);
        $dataHonobonoRiyouResult = $this->HonobonoRiyouResult->data_monthly($offices, $date['honobono']);
        $dataHonobonoKaigoResult = $this->HonobonoKaigoResult->data_monthly($offices, $date['honobono']);


        foreach ($offices as $office) {
            $dataHonobono[$office['Office']['id']]['HonobonoSchedule'] = $dataHonobonoSchedule[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoResult'] = $dataHonobonoResult[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoRiyouResult'] = $dataHonobonoRiyouResult[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoKaigoResult'] = $dataHonobonoKaigoResult[$office['Office']['id']];

            $data_row = $this->generate_ranking_row(
                $office,
                (isset($lastMonthBudgetSale[$office['Office']['id']]) ? $lastMonthBudgetSale[$office['Office']['id']] : null),
                $dataHonobono[$office['Office']['id']]
            );

            $office_ranking[$office['Office']['id']] = $data_row;

            if (isset($date['date_manager'])) {
                $date_office_manager = $date['date_manager'];

                $closest_office_manager = $this->get_closest_office_manager($office['Office']['id'], $date_office_manager);
                $office_ranking[$office['Office']['id']]['employee'] = !empty($closest_office_manager['Employee']) ? $closest_office_manager['Employee'] : '';
            }

        }

        return $this->array_sort_budget_ranking_data($office_ranking, SORT_DESC);
    }


    private function get_closest_office_manager($office_id, $date_between)
    {
        $parameters = array(
            'fields' => array(
                'OfficeManager.*',
                'Employee.id',
                'Employee.name',
                'Employee.avatar',
            ),
            'conditions' => array(
                'OfficeManager.office_id' => $office_id,
            ),
            'joins' => array(
                array(
                    'table' => 'employees',
                    'alias' => 'Employee',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Employee.id = OfficeManager.employee_id',

                    )
                ),
            ),
            'order' => array(
                'OfficeManager.date' => 'desc'
            )

        );
        if ($date_between != null) {
            if ($date_between['from'] == null) {
                $parameters['conditions']['OfficeManager.date <='] = $date_between['to'];
            } else {
                $parameters['conditions']['OfficeManager.date >='] = $date_between['from'];
                $parameters['conditions']['OfficeManager.date <='] = $date_between['to'];
            }

        }

        $officeManager = $this->OfficeManager->find('first', $parameters);
        return $officeManager;
    }

    // end budget_ranking
    private function generate_ranking_row($office, $budgetSale, $dataHonobono)
    {
        // total sale of month
        $totalSaleHonobono = +$dataHonobono['HonobonoSchedule']['sale']
            + $dataHonobono['HonobonoResult']['sale']
            + $dataHonobono['HonobonoRiyouResult']['sale']
            + $dataHonobono['HonobonoKaigoResult']['sale'];
        $result = array(
            'company' => $office['Company'],
            'office' => $office['Office'],
            'profit' => array(
                'last-month' => $this->BudgetSale->calculate_profit($budgetSale, $totalSaleHonobono, null, null),
            ),
        );
        return $result;
    }


    private function array_sort_budget_ranking_data($data, $order = SORT_ASC)
    {
        $new_array = $sortable_array_positive = $sortable_array_negative = $none_sortable_array = array();
        foreach ($data as $k => $v) {
            if (
                $v['office']['display_in_budget_ranking'] == 1
            ) {
                if ($v['profit']['last-month']['rates'] > 0) {
                    $sortable_array_positive[$k] = $v['profit']['last-month']['rates'];
                } else {
                    $sortable_array_negative[$k] = $v['profit']['last-month']['excess_profit'];
                }
            } else {
                $none_sortable_array[$k] = 0;
            }
        }
        switch ($order) {
            case SORT_ASC:
                asort($sortable_array_positive);
                asort($sortable_array_negative);
                break;
            case SORT_DESC:
                arsort($sortable_array_positive);
                arsort($sortable_array_negative);
                break;
        }
        $array_merge = $sortable_array_positive + $sortable_array_negative + $none_sortable_array;
        $i = 0;
        foreach ($array_merge as $k => $v) {
            $i++;
            $new_array[$k] = $data[$k];
            $new_array[$k]['ranking'] = $new_array[$k]['office']['display_in_budget_ranking'] == 1 ? $i : '';
        }
        return $new_array;
    }


    // Start BudgetSale
    public function budget_sale($var_date = null)
    {
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
                if (isset($req['page'])) {
                    $page = intval($req['page']);
                }
                if (!($page > 0)) {
                    $page = 1;
                }

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

        if ($limit == null) {
            $limit = intval(Configure::read('Paging.size'));
        }

        $start = ($page - 1) * $limit;

        if ($month == null && $year == null) {
            return false;
        }

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
            WHERE Office.id IN (' . $office_ids . ')
            ORDER BY `Rate`.`rate_sales_revenues` DESC
        ';

        $count = $this->BudgetSale->query('SELECT count(*) AS count' . ' ' . $conditions);
        $count = $count[0][0]['count'];
        $query = $select . ' ' . $conditions . ' LIMIT ' . $start . ',' . $limit;

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
        //  $data[$position]['Rate']['position'] = $start + $position + 1;
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
    public function user_page()
    {
        $this->set('title_for_layout', __('User Page'));
        if (empty($this->Auth->user()['Employee']['id'])) {
            $this->redirect(array('action' => 'index'));
        } else {
            $employee_id = $this->Auth->user()['Employee']['id'];
            $office_id = $this->Auth->user()['Employee']['office_id'];
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
                'first_prize' => !empty($prize_data[$first_prize]) ? $prize_data[$first_prize] : 0,
                'second_prize' => !empty($prize_data[$second_prize]) ? $prize_data[$second_prize] : 0,
                'third_prize' => !empty($prize_data[$third_prize]) ? $prize_data[$third_prize] : 0
            ),
            'average' => array(
                'a' => $user_data[1]['a'],
                'b' => $user_data[1]['b'],
                'c' => $user_data[1]['c']
            )
        );

        $this->set('data', $data);
    }

    public function get_data_prize()
    {
        $this->autoRender = 0;

        $response = array(
            'status' => 0,
            'message' => ''
        );

        if ($this->request->is('ajax')) {
            if (!empty($this->Auth->user()['Employee']['id'])) {
                $data = $this->generate_user_prize($this->Auth->user()['Employee']['id'], $this->Auth->user()['Employee']['office_id']);
                $response['data'] = $data;
                $response['status'] = 1;
            }
        }

        return json_encode($response);
    }


    private function generate_user_prize($e_id, $o_id)
    {
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
        $start_month = Configure::read('start_month');

        $begin_month = $start_month;
        $end_month = 12;

        if ($month < $start_month) {
            $begin_year = date('Y', strtotime('-1 year'));

            $conditions = array(
                'OR' => array(
                    array(
                        'AND' => array(
                            'EmployeePrize.year = ' . $year,
                            'EmployeePrize.month <= ' . $month
                        )
                    ),
                    array(
                        'AND' => array(
                            'EmployeePrize.year = ' . $begin_year,
                            'EmployeePrize.month <= ' . $end_month,
                            'EmployeePrize.month >= ' . $begin_month
                        )
                    )
                )
            );
        } else {
            $begin_year = $year;

            $conditions = array(
                'EmployeePrize.year = ' . $year,
                'EmployeePrize.month <= ' . $month,
                'EmployeePrize.month >= ' . $begin_month
            );
        }

        $last_date = new DateTime($year . '-' . $month . '-' . '01');
        $prev_date = new DateTime($begin_year . '-' . '04-01');

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
                'sum(EmployeePrize.value) / ' . $count . ' * 12 as value'
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
                '(sum(EmployeePrize.value) / ' . $count . ' * 12) / ' . $count_employee . ' as value'
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

        if (!empty($estimate)) {
            $data[0]['a'] = $estimate[0][0]['value'];
        }
        if (!empty($estimates)) {
            $data[1]['a'] = $estimates[0][0]['value'];
        }

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
                'sum(EmployeePrize.value) / ' . $count_employee . ' as value'
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

        if (!empty($second_bar)) {
            $data[0]['b'] = $second_bar[0][0]['value'];
        }
        if (!empty($second_bars)) {
            $data[1]['b'] = $second_bars[0][0]['value'];
        }

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
                'sum(EmployeePrize.value) / ' . $count_employee . ' as value'
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

        if (!empty($third_bar)) {
            $data[0]['c'] = $third_bar[0][0]['value'];
        }
        if (!empty($third_bars)) {
            $data[1]['c'] = $third_bars[0][0]['value'];
        }

        return $data;
    }
    // end user page

    // start post
    public function list_post()
    {
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
    public function loadPost()
    {
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

    public function post_view($post_title = null, $id = null)
    {
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
                } else {
                    $category_id[count($category_id)] = $pc['category_id'];
                }
            }
        }

        $conditions = array();
        $conditions = array_merge($conditions, array(
            'Post.status' => 'Publish',
            "NOT" => array("Post.id" => $id),
        ));
        if (!empty($search['category_id'])) {
            if (count($category_id) > 1) {
                $conditions = array_merge($conditions, array(
                    'PostCategory.category_id in' => $category_id,
                ));
            } else {
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
                    'table' => 'post_categories',
                    'alias' => 'PostCategory',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'PostCategory.post_id = Post.id'
                    )
                ),
            ),
            'contain' => array(
                'Category' => array(
                    'fields' => array('id', 'name'),
                ),
                'PostCategory' => array(
                    'fields' => array('id', 'post_id', 'category_id'),
                ),
            ),
            'conditions' => $conditions
        ));

        $this->set('title_for_layout', $post['Post']['title']);
        $this->set('post', $post);
        $this->set('post_sames', $post_sames);
    }

    // end post

    public function active()
    {
        $code = $_GET["code"];
        $acc = $this->Account->find('first', array(
            'conditions' => array('code' => $code),
            'recursive' => -1
        ));
        if (!empty($acc)) {
            if (empty($acc['Account']['active'])) {
                $this->Account->save(array(
                    'id' => $acc['Account']['id'],
                    'active' => 1
                ));
            }
            $this->Base->rewriteAuthSession($acc['Account']['id']);
            $this->Session->write('User.email', 1);
            $this->Session->setFlash(__('アカウントが有効化されました！'), 'flashmessage', array('type' => 'success'), 'error');
        }

        $this->redirect('/');
    }

    public function salary_detail()
    {

    }

}