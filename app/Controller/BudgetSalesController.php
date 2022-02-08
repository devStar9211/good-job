<?php

App::uses('CsvContentChunk', 'Controller/Component');

class BudgetSalesController extends AppController
{
    public $uses = array('BudgetSale', 'Office', 'Company', 'SalesDetail', 'LaborCostDetail', 'PastHighestSale', 'HonobonoResult', 'HonobonoSchedule', 'HonobonoRiyouResult' ,'HonobonoKaigoResult', 'Config');

    public $components = array('CsvContentChunk');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    private $csv_data_format = array(
        'office_id' => ['Office ID', '事業所（ID）'],
        'year' => ['Year', '年度'],
        'month' => ['Month', '月'],
        'revenue_1' => ['Sales1', '売上（売上の名前）'],
        'revenue_2' => ['Sales2', '売上（売上の名前）'],
        'revenue_3' => ['Sales3', '売上（売上の名前）'],
        'labor_cost' => ['Labor Cost', '人件費'],
        'overtime_cost' => ['Overtime Cost', '残業費'],
        'expense_1' => ['Expense1', '経費（経費の名前）'],
        'expense_2' => ['Expense2', '経費（経費の名前）'],
        'expense_3' => ['Expense3', '経費（経費の名前）'],
        'expense_4' => ['Expense4', '経費（経費の名前）'],
        'expense_5' => ['Expense5', '経費（経費の名前）'],
        'expense_6' => ['Expense6', '経費（経費の名前）'],
        'expense_7' => ['Expense7', '経費（経費の名前）'],
        'expense_8' => ['Expense8', '経費（経費の名前）'],
        'expense_9' => ['Expense9', '経費（経費の名前）'],
        'expense_10' => ['Expense10', '経費（経費の名前）'],
        'expense_11' => ['Expense11', '経費（経費の名前）'],
        'expense_12' => ['Expense12', '経費（経費の名前）'],
        'expense_13' => ['Expense13', '経費（経費の名前）'],
        'expense_14' => ['Expense14', '経費（経費の名前）'],
        'expense_15' => ['Expense15', '経費（経費の名前）'],
        'expense_16' => ['Expense16', '経費（経費の名前）'],
        'expense_17' => ['Expense17', '経費（経費の名前）'],
        'expense_18' => ['Expense18', '経費（経費の名前）'],
        'expense_19' => ['Expense19', '経費（経費の名前）'],
        'expense_20' => ['Expense20', '経費（経費の名前）'],
        'expense_21' => ['Expense21', '経費（経費の名前）'],
        'expense_22' => ['Expense22', '経費（経費の名前）'],
        'expense_23' => ['Expense23', '経費（経費の名前）'],
        'expense_24' => ['Expense24', '経費（経費の名前）'],
        'expense_25' => ['Expense25', '経費（経費の名前）'],
        'expense_26' => ['Expense26', '経費（経費の名前）'],
        'expense_27' => ['Expense27', '経費（経費の名前）'],
        'expense_28' => ['Expense28', '経費（経費の名前）'],
        'expense_29' => ['Expense29', '経費（経費の名前）'],
        'expense_30' => ['Expense30', '経費（経費の名前）'],
    );

    private function csv_format_title()
    {
        $arr = array();
        foreach ($this->csv_data_format as $alias => $title) {
            $arr[$title[0]] = $title[1];
        }
        return $arr;
    }

    private function csv_format_alias()
    {
        $arr = array();
        foreach ($this->csv_data_format as $alias => $title) {
            $arr[$alias] = $title[0];
        }
        return $arr;
    }

    public function admin_update_sale_config()
    {
        $this->set('title_for_layout', '売上(ほのぼの)');
        $config_key = 'flag_update_sale';
        $flag_update_sale = $this->Config->find('first', array(
            'conditions' => array('Config.key' => $config_key)
        ));
        $flag_update_sale = !empty($flag_update_sale) ? unserialize($flag_update_sale['Config']['value']) : '';
        if (!empty($_GET['year'])) {
            $year = $_GET['year'];
        }else{
            $year = date('Y');
        }

        if ($this->request->is(array('post', 'put'))) {


            $p_data = $this->request->data;

            $year = key($p_data['Config']);
            $flag_update_sale[$year] = $p_data['Config'][$year];


            $this->Config->create();
            $this->Config->set(
                array(
                    'key' => $config_key,
                    'value' => serialize($flag_update_sale)
                )
            );

            if ($this->Config->save()) {
                $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
                $this->redirect(Controller::referer());
            } else {
                $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
            }
        }
        $this->set(compact('flag_update_sale','year'));
    }

    public function admin_past_highest_sales()
    {
        $this->set('title_for_layout', '過去最高売上入力');
        $companies = $offices = $data = array();
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));
        if (
        !empty($_GET['company'])
        ) {
            $data['company_id'] = $_GET['company'];
        } else {
            $data['company_id'] = key($companies);
        }
        if ($this->request->is('post')) {
            $req = $this->request->data;
            if (!empty($req)) {
                $isError = false;
                $datasource = $this->PastHighestSale->getDataSource();
                $datasource->begin();
                try {
                    foreach ($req['PastHighestSale'] as $key => $_item) {
                        if ($_item['value'] != '') {
                            $dataSave = array(
                                'PastHighestSale' => array(
                                    'id' => $_item['id'],
                                    'value' => $this->clean($_item['value']),
                                    'office_id' => $key
                                )
                            );
                            $this->PastHighestSale->create();
                            $saved = $this->PastHighestSale->save($dataSave);
                            if (!$saved) {
                                $isError = true;
                                break;
                            }
                        }

                    }
                } catch (Exception $e) {
                    $isError = true;
                }
                if (!$isError) {
                    $datasource->commit();
                    $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');

                } else {
                    $datasource->rollback();

                    $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');

                }
                $data = $this->admin_generate_past_highest_sales_data($req['company_id']);
                $data['company_id'] = $req['company_id'];
                $this->redirect(Router::url($this->referer(), true));
            }
        } else {
            $data = array_merge($data, $this->admin_generate_past_highest_sales_data($data['company_id']));
        }

        $this->set('data', $data);
        $this->set(compact('companies', 'offices'));

    }

    public function admin_ajax_past_highest_sales_data()
    {
        $this->autoRender = false;
        $response = array(
            'status' => 0,
            'message' => ''
        );
        if ($this->request->is('ajax')) {
            $req = $this->request->data;
            if (!empty($req['company'])) {
                // switch for super admin or company admin
                $offices = $this->admin_generate_past_highest_sales_data($req['company']);
                $view = new View($this, false);
                $table_data = $view->element('past_highest_sales_table', array('data' => $offices));
                $response['status'] = 1;
                $response['table_data'] = $table_data;
            }
        }
        return json_encode($response);
    }

    public function admin_generate_past_highest_sales_data($company_id)
    {
        $conditionsOffice = array();
        if (!empty($company_id)) {
            $conditionsOffice['Office.company_id'] = $company_id;
        }
        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions' => $conditionsOffice,
            'contain' => array('PastHighestSale'),
            'paramType' => 'querystring',
        );

        $offices = $this->Paginator->paginate('Office');
        $data['offices'] = $offices;
        return $data;


    }

    public function admin_revenue_budget()
    {
        $this->set('title_for_layout', '予算入力');

        $companies = $offices = $data = array();

        // get list company
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));
        $data['company_id'] = key($companies);
        $data['date'] = date('Y');
        $data['start_month'] = '';

        if (!empty($_GET['company'])) {
            $data['company_id'] = $_GET['company'];
            if (
            !empty($_GET['office'])
            ) {
                $data['office_id'] = $_GET['office'];
            }
        }
        if (!empty($_GET['date'])) {
            $data['date'] = $_GET['date'];
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
        $data = array_merge($data, $this->admin_generate_budget_monthly_data($data['office_id'], $data['date']));
		// pr($data);die;

        if ($this->request->is('post')) {
            $req = $this->request->data['RevenueBudget'];
            if (!empty($req)) {
                unset($req['e_revenue']['key']);
                unset($req['e_expense']['key']);

                if ($this->BudgetSale->saveBudgetData($req)) {
                    $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
                    $data = $this->admin_generate_budget_monthly_data($req['office'], $req['year']);
                } else {
                    $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
                    $data = array(
                        'office_id' => $req['office'],
                        'year' => $req['year'],
                        'start_month' => $this->get_start_month($req['office']),
                        'budget_labor_cost' => $req['e_labor_cost'],
                        'budget_overtime_cost' => $req['e_overtime'],
                        'revenues' => array(),
                        'expenses' => array()
                    );

                    foreach ($req['e_revenue'] as $item) {
                        while (isset($data['revenues'][$item['name']])) {
                            $item['name'] .= ' ';
                        }
                        $data['revenues'][$item['name']] = $item;
                        unset($data['revenues'][$item['name']]['name']);
                    }
                    foreach ($req['e_expense'] as $item) {
                        while (isset($data['expenses'][$item['name']])) {
                            $item['name'] .= ' ';
                        }
                        $data['expenses'][$item['name']] = $item;
                        unset($data['expenses'][$item['name']]['name']);
                    }
                }

                $data['company_id'] = $req['company'];
                $data['clear'] = $req['clear'];

                $offices = $this->Office->find('list', array(
                    'fields' => array('id', 'name'),
                    'conditions' => array(
                        'company_id' => $data['company_id']
                    ),
                    'order' => array(
                        'created' => 'asc'
                    )
                ));
            }
            $this->redirect(Controller::referer());
        }
        $this->set('data', $data);
        $this->set(compact('companies', 'offices'));
    }

    private function get_start_month($office_id)
    {
        $companyGroup = $this->Office->find('first', array(
            'fields' => array('Office.id', 'Company.company_group_id'),
            'contain' => array(
                'Company' => array(
                    'CompanyGroup' => array(
                        'fields' => array('CompanyGroup.start_month'),
                    )
                )
            ),
            'conditions' => array(
                'Office.id' => $office_id
            ),
            'recursive' => -1
        ));

        $start_month = !empty($companyGroup['Company']['CompanyGroup']['start_month']) ? $companyGroup['Company']['CompanyGroup']['start_month'] : '';
        return $start_month;
    }

    public function admin_revenue_sales()
    {
        $this->set('title_for_layout', '月次売上費用入力');

        $companies = $offices = $data = array();
        $year = date('Y');
        $month = date('m');

        // get list company
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));
        $data['company_id'] = key($companies);

        if (!empty($_GET['company'])) {
            $data['company_id'] = $_GET['company'];
        }
        if (!empty($_GET['date'])) {
            $data['date'] = $_GET['date'];
            $year = date('Y', strtotime($data['date']));
            $month = intval(date('m', strtotime($data['date'])));
        }


        if ($this->request->is('post')) {
            $req = $this->request->data['ExpenseSale'];

            if (!empty($req) && !empty($req['offices'])) {


                unset($req['e_revenue']['key']);
                unset($req['e_expense']['key']);

                if ($this->BudgetSale->saveSalesData($req)) {
                    $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
                    $data = $this->admin_generate_sales_monthly_data($req['company'], $year, $month);
                } else {
                    $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
                    $data = array(
                        'company' => $req['company'],
                        'year' => $year,
                        'month' => $month,
                        'offices' => $req['offices'],
                        'revenues' => array(),
                        'revenues_honobono' => array(),
                        'labor_cost' => $req['e_labor_cost'],
                        'overtime_cost' => $req['e_overtime_cost'],
                        'expenses' => array()
                    );

                    foreach ($req['e_revenue'] as $item) {
                        $data['revenues'][] = $item;
                    }
                    foreach ($req['e_expense'] as $item) {
                        $data['expenses'][] = $item;
                    }
                }

                $data['company_id'] = $req['company'];
            }
            $this->redirect(Controller::referer());
        } else {
            $data = array_merge($data, $this->admin_generate_sales_monthly_data($data['company_id'], $year, $month));
        }

        $this->set('data', $data);
        $this->set(compact('companies', 'offices'));
    }

    public function admin_revenue_budget_monthly_data()
    {
        $this->autoRender = false;

        $response = array(
            'status' => 0,
            'message' => ''
        );

        if ($this->request->is('ajax')) {
            $req = $this->request->data;

            $data = array();
            if (!empty($req['office']) && !empty($req['year'])) {
                // switch for super admin or company admin
                if (1) {
                    $monthly = $this->admin_generate_budget_monthly_data($req['office'], $req['year']);

                    $view = new View($this, false);
                    $table_data = $view->element('budget_revenue_table', array('data' => $monthly));

                    $response['status'] = 1;
                    $response['table_data'] = $table_data;
                } else {

                }
            }
        }

        return json_encode($response);
    }

    public function admin_revenue_sales_monthly_data()
    {
        $this->autoRender = false;

        $response = array(
            'status' => 0,
            'message' => ''
        );

        if ($this->request->is('ajax')) {
            $req = $this->request->data;

            $data = array();
            // switch for super admin or company admin
            if (1) {
                $offices = $this->admin_generate_sales_monthly_data($req['company'], $req['year'], $req['month']);

                $view = new View($this, false);
                $table_data = $view->element('sales_revenue_table', array('data' => $offices));

                $response['status'] = 1;
                $response['table_data'] = $table_data;
            } else {

            }
        }

        return json_encode($response);
    }

    function admin_generate_budget_monthly_data($office_id, $year)
    {
        $data = array(
            'office_id' => $office_id,
            'start_month' => $this->get_start_month($office_id),
            'year' => $year,
            'budget_labor_cost' => array(),
            'budget_overtime_cost' => array(),
            'revenues' => array(),
            'expenses' => array()
        );
        $years = array($year, $year + 1);

        $count_year = 0;
        foreach ($years as $year) {
            $count_year++;

            $budget_sales = $this->BudgetSale->find('all', array(
                'conditions' => array(
                    'office_id' => $office_id,
                    'year' => $year
                ),
                'contain' => array(
                    'Revenue' => array(
                        'conditions' => array(
                            'type' => 0
                        )
                    ),
                    'Expense' => array(
                        'conditions' => array(
                            'type' => 0
                        )
                    )
                )
            ));
//            pr($budget_sales);die;

            foreach ($budget_sales as $value) {
                $month = $count_year == 2 ? $value['BudgetSale']['month'] + 12 : $value['BudgetSale']['month'];


                $data['budget_labor_cost'][$month] = $value['BudgetSale']['budget_labor_cost'];
                $data['budget_overtime_cost'][$month] = $value['BudgetSale']['budget_overtime_cost'];

                foreach ($value['Revenue'] as $revenue) {
                    if (isset($data['revenues'][$revenue['name']])) {
                        $data['revenues'][$revenue['name']][$month] = array(
                            'id' => $revenue['id'],
                            'value' => $revenue['value']
                        );
                    } else {
                        $data['revenues'][$revenue['name']] = array(
                            $month => array(
                                'id' => $revenue['id'],
                                'value' => $revenue['value']
                            )
                        );
                    }
                }

                foreach ($value['Expense'] as $expense) {
                    if (isset($data['expenses'][$expense['name']])) {
                        $data['expenses'][$expense['name']][$month] = array(
                            'id' => $expense['id'],
                            'value' => $expense['value']
                        );
                    } else {
                        $data['expenses'][$expense['name']] = array(
                            $month => array(
                                'id' => $expense['id'],
                                'value' => $expense['value']
                            )
                        );
                    }
                }
            }

            uksort($data['revenues'], 'strcasecmp');
            uksort($data['expenses'], 'strcasecmp');
        }
		//pr($data);die;

        return $data;
    }

    public function admin_generate_sales_monthly_data($company_id, $year, $month)
    {

        $office_expenses = $this->Office->find('all', array(
            'conditions' => array(
                'Office.company_id' => $company_id
            ),
            'contain' => array(
                'BudgetSale' => array(
                    'conditions' => array(
                        'year' => $year,
                        'month' => $month
                    ),
                    'Revenue' => array(
                        'conditions' => array(
                            'type' => 1
                        ),
                        'order' => array(
                            'created' => 'asc'
                        )
                    ),
                    'Expense' => array(
                        'conditions' => array(
                            'type' => 1
                        ),
                        'order' => array(
                            'created' => 'asc'
                        )
                    )
                ),
                'OfficeSelfPaid',
                'OfficeRemoteLabel'=>array(
                    'conditions' => array(
                        'OfficeRemoteLabel.name' => array('jigyo_id_1','jigyo_id_2','jigyo_id_3','jigyo_id_4'),
                        'office_remotes.value <>' => ''
                    ),
                ),
            )
        ));


        $data = array(
            'company' => $company_id,
            'year' => $year,
            'month' => $month,
            'offices' => array(),
            'revenues_honobono' => array(
                '売上(honobono)' => array(
                    'name' => '売上(honobono)',

                )
            ),
            'revenues' => array(),
            'labor_cost' => array(),
            'overtime_cost' => array(),
            'expenses' => array()
        );
        $date = array(
            array(
                'year' => $year,
                'month' => $month,
            )
        );
        // get data from honobono
        $dataSaleMonthlyDBSale = $this->HonobonoResult->data_monthly($office_expenses, $date);
        $dataBudgetMonthlyDBSale = $this->HonobonoSchedule->data_monthly($office_expenses, $date);
        $dataHonobonoRiyouResult = $this->HonobonoRiyouResult->data_monthly($office_expenses, $date);
        $dataHonobonoKaigoResult = $this->HonobonoKaigoResult->data_monthly($office_expenses, $date);

        foreach ($office_expenses as $office){
            $dataHonobono[$office['Office']['id']]['HonobonoResult'] = $dataSaleMonthlyDBSale[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoSchedule'] = $dataBudgetMonthlyDBSale[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoRiyouResult'] = $dataHonobonoRiyouResult[$office['Office']['id']];
            $dataHonobono[$office['Office']['id']]['HonobonoKaigoResult'] = $dataHonobonoKaigoResult[$office['Office']['id']];
        }

        if (!empty($office_expenses)) {
            foreach ($office_expenses as $item) {
                $data['offices'][$item['Office']['id']] = $item['Office']['name'];
                if (!empty($item['BudgetSale'])) {
                    $data['overtime_cost'][$item['Office']['id']] = $item['BudgetSale'][0]['sales_overtime_cost'];
                    $data['labor_cost'][$item['Office']['id']] = $item['BudgetSale'][0]['sales_labor_cost'];

                    foreach ($item['BudgetSale'][0]['Revenue'] as $revenue) {
                        if (!isset($data['revenues'][$revenue['name']])) {
                            $data['revenues'][$revenue['name']] = array('name' => $revenue['name']);
                        }
                        $data['revenues'][$revenue['name']][$item['Office']['id']] = array(
                            'id' => $revenue['id'],
                            'value' => $revenue['value']
                        );
                    }

                    foreach ($item['BudgetSale'][0]['Expense'] as $expense) {
                        if (!isset($data['expenses'][$expense['name']])) {
                            $data['expenses'][$expense['name']] = array('name' => $expense['name']);
                        }

                        $data['expenses'][$expense['name']][$item['Office']['id']] = array(
                            'id' => $expense['id'],
                            'value' => $expense['value']
                        );
                    }
                }

                // doanh số tổng hàng tháng
                $totalSaleHonobono =    $dataHonobono[$item['Office']['id']]['HonobonoSchedule']['sale'] +
                                        $dataHonobono[$item['Office']['id']]['HonobonoResult']['sale'] +
                                        $dataHonobono[$item['Office']['id']]['HonobonoRiyouResult']['sale'] +
                                        $dataHonobono[$item['Office']['id']]['HonobonoKaigoResult']['sale'];

                $data['revenues_honobono']['売上(honobono)'][$item['Office']['id']]['value'] = $totalSaleHonobono;
            }
        }
        uksort($data['revenues'], 'strcasecmp');
        uksort($data['expenses'], 'strcasecmp');
        return $data;
    }

    public function admin_budget_csv_import()
    {
        $this->set('title_for_layout', '予算入力');
        $companies = $offices = array();

        // switch for super admin or company admin
        if (1) {
            // get list company
            $companies = $this->Company->find('list', array(
                'fields' => array('id', 'name'),
                'order' => array(
                    'created' => 'asc'
                )
            ));
        } else {

        }

        $this->set(compact('companies', 'offices'));
    }

    public function admin_sales_csv_import()
    {
        $this->set('title_for_layout', '月次売上費用入力');

        $companies = $offices = array();

        // switch for super admin or company admin
        if (1) {
            // get list company
            $companies = $this->Company->find('list', array(
                'fields' => array('id', 'name'),
                'order' => array(
                    'created' => 'asc'
                )
            ));
        } else {

        }

        $this->set(compact('companies', 'offices'));
    }

    public function admin_import_budget_from_csv()
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
                $method = 'get_content_file_r';
                $valid = array();
                $processer = $this->BudgetSale;
                $procession = 'import_budget';

                $response = $this->CsvContentChunk->get_response($files, $method, $valid, $processer, $procession);
            }
        } else {
            $this->redirect('admin_index');
        }

        return json_encode($response);
    }

    public function admin_import_sales_from_csv()
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
                $method = 'get_content_file_r';
                $valid = array();
                $processer = $this->BudgetSale;
                $procession = 'import_sales';

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

        if (in_array($type, ['budget', 'sales'])) {
            $watting_time = 1;
            $session_key = 'BudgetSales.Csv.Sample.' . $type;
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

            $sample_format = $this->csv_format_title();

            $csv_data = array();
            $row = array();
            foreach ($sample_format as $key => $column) {
                $row[$key] = '';
            }
            $csv_data[] = $row;

            $sample_name = 'sample.csv';
            switch ($type) {
                case 'budget':
                    $sample_name = '予算入力' . '.' . $sample_name;
                    break;
                case 'sales':
                    $sample_name = '月次売上費用入力' . '.' . $sample_name;
                    break;
            }
//            pr($csv_data);die;
            $this->Export->exportCsv($csv_data, $sample_name, null, ',', '"', true);
        } else {
            $this->redirect(array('controller' => 'dashboard', 'action' => 'index', 'admin' => true));
        }
    }

    private function clean($string)
    {
        $string = str_replace('', ',', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }
}