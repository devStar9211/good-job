<?php

class ComparisonsController extends AppController
{
    public $uses = array('BudgetSale');

    public function index($var_date = null)
    {
        $this->layout = "default";
        $lastYear = date('Y');
        $lastMonth = date('m');
//        $data = $this->last_year_comparison(1, 2019, null);
        $data = $this->last_year_comparison($lastMonth, $lastYear, null);
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
//              $data = $this->last_year_comparison(1, 2010, null);
                $data = $this->last_year_comparison($req['month'], $req['year'], null);
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
            }
        }

        return json_encode($response);
    }


    private function last_year_comparison($month = null, $year = null, $office_id = '')
    {
        if ($month == null && $year == null) return false;
        $query = '
        
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
                       )  Rate JOIN (SELECT @rownum := 0) AS r
                       ORDER BY `rate_sales_revenues` DESC
                    )   Rate
                    LEFT JOIN (SELECT id, company_id, name FROM offices)  AS Office ON Rate.office_id=Office.id 
                    LEFT JOIN (SELECT id, name FROM companies)  AS Company ON Company.id=Office.company_id
                    LEFT JOIN (SELECT id, office_id, name, is_manager, avatar FROM employees)  AS Employee ON Employee.office_id=Office.id AND Employee.is_manager = 1
                    ORDER BY `rate_sales_revenues` DESC
        ';
        if ($office_id != '') {
            $data = $this->BudgetSale->query(' 
            SELECT *
            FROM (
                ' . $query . '
            ) Rate
            WHERE Rate.office_id = ' . $office_id . ' AND Rate.last_month = ' . $month . ' AND Rate.last_year = ' . ($year - 1) . '
            '
            );
            if (!empty($data)) {
                return $data[0]['Rate']['position'];
            }
        } else {
            $data = $this->BudgetSale->query($query);
            if (!empty($data)) {
                return $data;
            }
        }


        return false;
    }
}
