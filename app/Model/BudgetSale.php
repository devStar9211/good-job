<?php

class BudgetSale extends AppModel
{
    public $useTable = 'budget_sales';
    public $primaryKey = 'id';

    public $belongsTo = array(
        'Office' => array(
            'className' => 'Office',
            'foreignKey' => 'office_id'
        )
    );

    public $hasMany = array(
        'Expense' => array(
            'className' => 'Expense',
            'foreignKey' => 'budget_sale_id',
            'dependent' => true
        ),
        'Revenue' => array(
            'className' => 'Revenue',
            'foreignKey' => 'budget_sale_id',
            'dependent' => true
        )
    );

    public $validate = array();

    private function l_c($l = null, $c = null)
    {
        $ms = '';

        if ($l !== null) {
            $ms .= '&nbsp;<span style="text-decoration:underline;">' . __('line') . ':&nbsp;' . $l . '</span>';
        }
        if ($c !== null) {
            $ms .= '&nbsp;<span style="text-decoration:underline;">' . __('column') . ':&nbsp;' . $c . '</span>';
        }

        return $ms;
    }

    public function saveBudgetData($data)
    {
        $response = false;
        $today = date("Y-m-d H:i:s");

        $emptyMonth = array();
        for ($i = 1; $i <= 23; $i++) {
            $emptyMonth[$i] = $i;
        }

        foreach ($data['e_revenue'] as $revenue) {
            for ($i = 1; $i <= 23; $i++) {
                if (
                    isset($revenue[$i]['value'])
                    && $revenue[$i]['value'] !== ''
                ) {
                    unset($emptyMonth[$i]);
                }
            }
        }

        for ($i = 1; $i <= 23; $i++) {
            if (
                (isset($data['e_labor_cost'][$i]) && $data['e_labor_cost'][$i] !== '')
                ||
                (isset($data['e_overtime'][$i]) && $data['e_overtime'][$i] !== '')
            ) {
                unset($emptyMonth[$i]);
            }
        }

        foreach ($data['e_expense'] as $expense) {
            for ($i = 1; $i <= 23; $i++) {
                if (isset($expense[$i]['value']) && $expense[$i]['value'] !== '') {
                    unset($emptyMonth[$i]);
                }
            }
        }
        $months = array();
        $years = array();
        $year = $data['year'];
        for ($i = 1; $i <= 23; $i++) {
            if (!in_array($i, $emptyMonth)) {
                if ($i > 12) {
                    $years[$year + 1][] = $i - 12;
                } else {
                    $years[$year][] = $i;
                }
            }
        }
        $datasource = $this->getDataSource();
        $isError = false;
        try {
            $datasource->begin();

            $_revenues = ClassRegistry::init('Revenue');
            $_expenses = ClassRegistry::init('Expense');
            $_sales_details = ClassRegistry::init('SalesDetail');

            $not_empty_budget_revenue = array();
            $not_empty_budget_expense = array();
            $count_year = 0;
            foreach ($years as $_key_year => $_year) {
                $count_year++;
                $cur_budget_sales = $this->find('all', array(
                    'conditions' => array(
                        'office_id' => $data['office'],
                        'year' => $_key_year,
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

                foreach ($cur_budget_sales as $key => $budget) {
                    if (in_array($budget['BudgetSale']['month'], $emptyMonth)) {
                        $count = $_revenues->find('count', array(
                            'conditions' => array(
                                'budget_sale_id' => $budget['BudgetSale']['id'],
                                'type' => 1
                            )
                        ));

                        if ($count == 0) {
                            $count = $_expenses->find('count', array(
                                'conditions' => array(
                                    'budget_sale_id' => $budget['BudgetSale']['id'],
                                    'type' => 1
                                )
                            ));

                            if ($count == 0) {
                                $_revenues->unbindModel(array('belongsTo' => array('BudgetSale')));
                                $b_d = $_revenues->deleteAll(array(
                                    'budget_sale_id' => $budget['BudgetSale']['id'],
                                    'type' => 0
                                ), false);

                                if ($b_d) {
                                    $_expenses->unbindModel(array('belongsTo' => array('BudgetSale')));
                                    $b_d = $_expenses->deleteAll(array(
                                        'budget_sale_id' => $budget['BudgetSale']['id'],
                                        'type' => 0
                                    ), false);

                                    if ($b_d) {
                                        if (
                                            $budget['BudgetSale']['sales_labor_cost'] == 0
                                            && $budget['BudgetSale']['sales_overtime_cost'] == 0
                                        ) {
                                            $b_d = $this->delete($budget['BudgetSale']['id']);
                                        } else {
                                            $this->id = $budget['BudgetSale']['id'];
                                            $b_d = $this->save(array(
                                                'BudgetSale' => array(
                                                    'budget_labor_cost' => 0,
                                                    'budget_overtime_cost' => 0
                                                )
                                            ));
                                        }
                                    }
                                }

                                if (!$b_d) {
                                    $isError = true;
                                    break;
                                }
                            }
                        }

                        if ($count == 0) {
                            unset($cur_budget_sales[$key]);
                        } else {
                            $years[$_key_year][] = $budget['BudgetSale']['month'];
                        }
                    }
                }
                if (!$isError) {
                    foreach ($_year as $month) {
                        $month_in_data = $count_year == 2 ? $month + 12 : $month;
                        $budget_revenues = 0;
                        $budget_expenses = 0;
                        $budget_revenue_id = array();
                        $budget_expense_id = array();

                        $budget_sales = null;
                        foreach ($cur_budget_sales as $budget) {
                            if ($budget['BudgetSale']['month'] == $month) {
                                $budget_sales = $budget;
                                break;
                            }
                        }
                        if (!empty($budget_sales)) {
                            if ($data['clear']) {
                                $_revenues->unbindModel(array('belongsTo' => array('BudgetSale')));
                                $revenues_remove = $_revenues->deleteAll(array(
                                    'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                    'type' => 0
                                ), false);

                                $_expenses->unbindModel(array('belongsTo' => array('BudgetSale')));
                                $expenses_remove = $_expenses->deleteAll(array(
                                    'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                    'type' => 0
                                ), false);
                            }

                            if (
                                $budget_sales['BudgetSale']['budget_labor_cost'] != (double)$data['e_labor_cost'][$month_in_data]
                                || $budget_sales['BudgetSale']['budget_overtime_cost'] != (double)$data['e_overtime'][$month_in_data]
                            ) {
                                $this->id = $budget_sales['BudgetSale']['id'];
                                $b_s_s = $this->save(array(
                                    'BudgetSale' => array(
                                        'budget_labor_cost' => (double)$this->clean($data['e_labor_cost'][$month_in_data]),
                                        'budget_overtime_cost' => (double)$this->clean($data['e_overtime'][$month_in_data]),
                                    )
                                ));
                                if (!empty($b_s_s)) {
                                    $budget_sales['BudgetSale']['budget_labor_cost'] = $b_s_s['BudgetSale']['budget_labor_cost'];
                                    $budget_sales['BudgetSale']['budget_overtime_cost'] = $b_s_s['BudgetSale']['budget_overtime_cost'];
                                } else {
                                    $budget_sales = null;
                                }
                            }
                        } else {
//                            if($month_in_data == true) {
//                                echo $month_in_data;
//                                pr($data['e_labor_cost']);die;}

                            $this->create();
                            $budget_sales = $this->save(array(
                                'office_id' => $data['office'],
                                'month' => $month,
                                'year' => $_key_year,
                                'budget_revenues' => 0,
                                'sales_revenues' => 0,
                                'budget_labor_cost' => (double)$this->clean($data['e_labor_cost'][$month_in_data]),
                                'sales_labor_cost' => 0,
                                'budget_overtime_cost' => (double)$this->clean($data['e_overtime'][$month_in_data]),
                                'sales_overtime_cost' => 0,
                                'budget_expenses' => 0,
                                'sales_expenses' => 0,
                            ));
                        }

                        if ($budget_sales) {
                            $budget_revenues = 0;
                            $budget_expenses = 0;


                            foreach ($data['e_revenue'] as $key_revenue => $value) {
                                if (
                                    empty($value['name'])
                                    || preg_match('/^[\s ]*$/', $value['name'])
                                ) {
                                    $isError = true;
                                    break;
                                } else if (
                                    $value[$month_in_data]['value'] !== ''
                                ) {
                                    $value['name'] = trim($value['name']);

                                    $revenues = null;
                                    if (!empty($budget_sales['Revenue'])) {
                                        foreach ($budget_sales['Revenue'] as $r) {
                                            if (
                                                $value[$month_in_data]['id'] == $r['id']
                                            ) {
                                                $revenues = array('Revenue' => $r);
                                                break;
                                            }
                                        }
                                    }

                                    if (!empty($revenues)) {
                                        if (
                                            $revenues['Revenue']['name'] != $value['name']
                                            || $revenues['Revenue']['value'] != (double)$value[$month_in_data]['value']
                                        ) {
                                            $_revenues->id = $revenues['Revenue']['id'];


                                            $r_s = $_revenues->save(array(
                                                'Revenue' => array(
                                                    'name' => $value['name'],
                                                    'value' => (double)$this->clean($value[$month_in_data]['value']),
                                                )
                                            ));
                                            if (!empty($r_s)) {
                                                $revenues['Revenue']['name'] = $r_s['Revenue']['name'];
                                                $revenues['Revenue']['value'] = $r_s['Revenue']['value'];
                                            } else {
                                                $revenues = null;
                                            }
                                        }
                                    } else {
                                        $_revenues->create();
                                        $revenues = $_revenues->save(array(
                                            'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                            'name' => $value['name'],
                                            'value' => (double)$this->clean($value[$month_in_data]['value']),
                                            'type' => 0,
                                        ));
                                    }

                                    if (!empty($revenues)) {
                                        $not_empty_budget_revenue[] = $revenues['Revenue']['id'];
                                        $budget_revenue_id[] = $revenues['Revenue']['id'];
                                        $budget_revenues += $revenues['Revenue']['value'];
                                    } else {
                                        $isError = true;
                                        break;
                                    }
                                }
                            }

                            if (!$isError) {
                                $_revenues->unbindModel(array('belongsTo' => array('BudgetSale')));
                                $revenues_remove = $_revenues->deleteAll(array(
                                    'id !=' => $budget_revenue_id,
                                    'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                    'type' => 0
                                ), false);

                                foreach ($data['e_expense'] as $key_expense => $value) {
                                    if (
                                        empty($value['name'])
                                        || preg_match('/^[\s ]*$/', $value['name'])
                                    ) {
                                        $isError = true;
                                        break;
                                    } else if (
                                        $value[$month_in_data]['value'] !== ''
                                    ) {
                                        $value['name'] = trim($value['name']);

                                        $expenses = null;
                                        if (!empty($budget_sales['Expense'])) {
                                            foreach ($budget_sales['Expense'] as $r) {
                                                if (
                                                    $value[$month_in_data]['id'] == $r['id']
                                                ) {
                                                    $expenses = array('Expense' => $r);
                                                    break;
                                                }
                                            }
                                        }

                                        if (!empty($expenses)) {
                                            if (
                                                $expenses['Expense']['name'] != $value['name']
                                                || $expenses['Expense']['value'] != (double)$value[$month_in_data]['value']
                                            ) {
                                                $_expenses->id = $expenses['Expense']['id'];
                                                $e_s = $_expenses->save(array(
                                                    'Expense' => array(
                                                        'name' => $value['name'],
                                                        'value' => (double)$this->clean($value[$month_in_data]['value']),
                                                    )
                                                ));

                                                if (!empty($e_s)) {
                                                    $expenses['Expense']['name'] = $e_s['Expense']['name'];
                                                    $expenses['Expense']['value'] = $e_s['Expense']['value'];
                                                } else {
                                                    $expenses = null;
                                                }
                                            }
                                        } else {
                                            $_expenses->create();
                                            $expenses = $_expenses->save(array(
                                                'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                                'name' => $value['name'],
                                                'value' => (double)$this->clean($value[$month_in_data]['value']),
                                                'type' => 0,
                                            ));
                                        }

                                        if (!empty($expenses)) {
                                            $not_empty_budget_expense[] = $expenses['Expense']['id'];
                                            $budget_expense_id[] = $expenses['Expense']['id'];
                                            $budget_expenses += $expenses['Expense']['value'];
                                        } else {
                                            $isError = true;
                                            break;
                                        }
                                    }
                                }

                                if (!$isError) {
                                    $_expenses->unbindModel(array('belongsTo' => array('BudgetSale')));
                                    $expenses_remove = $_expenses->deleteAll(array(
                                        'id !=' => $budget_expense_id,
                                        'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                        'type' => 0
                                    ), false);

                                    $this->id = $budget_sales['BudgetSale']['id'];
                                    $b_s_s = $this->save(array(
                                        'BudgetSale' => array(
                                            'budget_revenues' => (double)$this->clean($budget_revenues),
                                            'budget_expenses' => (double)$this->clean($budget_expenses)
                                        )
                                    ));

                                    if (
                                        empty($b_s_s)
                                        || !$revenues_remove
                                        || !$expenses_remove
                                    ) {
                                        $isError = true;
                                    }
                                }
                            }
                        } else {
                            $isError = true;
                        }
                    }
                }
            }

            if ($isError) {
                $datasource->rollback();
                $response = false;
            } else {
                $datasource->commit();
                $response = true;
            }
        } catch (Exception $e) {
            $isError = true;
            $datasource->rollback();
            $response = false;
        }

        return $response;
    }

    private function clean($string)
    {
        $string = str_replace('', ',', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public function saveSalesData($data)
    {
        $response = false;
        $today = date("Y-m-d H:i:s");
        $year = date('Y', strtotime($data['year-month']));
        $month = intval(date('m', strtotime($data['year-month'])));

        $offices = array();

        foreach ($data['offices'] as $id => $name) {
            $offices[$id] = array(
                'sales_labor_cost' => isset($data['e_labor_cost'][$id]) ? $data['e_labor_cost'][$id] : '',
                'sales_overtime_cost' => isset($data['e_overtime_cost'][$id]) ? $data['e_overtime_cost'][$id] : '',
                'revenues' => array(),
                'expenses' => array()
            );

            foreach ($data['e_revenue'] as $revenue) {
                if (
                    $revenue[$id]['value'] !== ''
                ) {
                    $offices[$id]['revenues'][] = array(
                        'id' => $revenue[$id]['id'],
                        'name' => trim($revenue['name']),
                        'value' => $revenue[$id]['value']
                    );
                }
            }

            foreach ($data['e_expense'] as $expense) {
                if (
                    $expense[$id]['value'] !== ''
                ) {
                    $offices[$id]['expenses'][] = array(
                        'id' => $expense[$id]['id'],
                        'name' => trim($expense['name']),
                        'value' => $expense[$id]['value']
                    );
                }
            }
        }

        $datasource = $this->getDataSource();
        $isError = false;
        try {
            $datasource->begin();

            $_revenues = ClassRegistry::init('Revenue');
            $_expenses = ClassRegistry::init('Expense');

            if (!empty($offices)) {
                foreach ($offices as $id => $office) {
                    $budget_sales = null;
                    $budget_sales = $this->find('first', array(
                        'conditions' => array(
                            'office_id' => $id,
                            'year' => $year,
                            'month' => $month
                        ),
                        'contain' => array(
                            'Revenue' => array(
                                'conditions' => array(
                                    'type' => 1
                                )
                            ),
                            'Expense' => array(
                                'conditions' => array(
                                    'type' => 1
                                )
                            )
                        )
                    ));

                    if (
                        !empty($office['expenses'])
                        || !empty($office['revenues'])
                        || $office['sales_labor_cost'] !== ''
                        || $office['sales_overtime_cost'] !== ''
                    ) {
                        if (empty($budget_sales)) {
                            $this->create();
                            $budget_sales = $this->save(array(
                                'office_id' => $id,
                                'month' => $month,
                                'year' => $year,
                                'budget_revenues' => 0,
                                'sales_revenues' => 0,
                                'budget_labor_cost' => 0,
                                'sales_labor_cost' => (double)$this->clean($office['sales_labor_cost']),
                                'budget_overtime_cost' => 0,
                                'sales_overtime_cost' => (double)$this->clean($office['sales_overtime_cost']),
                                'budget_expenses' => 0,
                                'sales_expenses' => 0,
                            ));
                            if (!empty($budget_sales)) {
                                $budget_sales['Expense'] = array();
                            }
                        }

                        if (!empty($budget_sales)) {
                            $revenue_id = array();
                            $expense_id = array();
                            $total_revenue_value = 0;
                            $total_expense_value = 0;

                            foreach ($office['revenues'] as $revenue) {
                                $sales_revenues = null;

                                if (
                                    !empty($revenue['name'])
                                    && !empty($revenue['id'])
                                ) {
                                    foreach ($budget_sales['Revenue'] as $re) {
                                        if ($revenue['id'] == $re['id']) {
                                            if (
                                                $revenue['name'] != $re['name']
                                                || (double)$revenue['value'] != $re['value']
                                            ) {
                                                $_revenues->id = $re['id'];
                                                $sales_revenues = $_revenues->save(array(
                                                    'Revenue' => array(
                                                        'name' => $revenue['name'],
                                                        'value' => (double)$this->clean($revenue['value'])
                                                    )
                                                ));

                                                if ($sales_revenues) {
                                                    $sales_revenues['Revenue']['id'] = $re['id'];
                                                }
                                            } else {
                                                $sales_revenues = array('Revenue' => $re);
                                            }

                                            break;
                                        }
                                    }
                                } else if (
                                !empty($revenue['name'])
                                ) {
                                    $_revenues->create();
                                    $sales_revenues = $_revenues->save(array(
                                        'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                        'type' => 1,
                                        'name' => $revenue['name'],
                                        'value' => (double)$this->clean($revenue['value'])
                                    ));
                                }

                                if (!empty($sales_revenues)) {
                                    $revenue_id[] = $sales_revenues['Revenue']['id'];
                                    $total_revenue_value += $sales_revenues['Revenue']['value'];
                                } else {
                                    $isError = true;
                                }
                            }

                            foreach ($office['expenses'] as $expense) {
                                $sales_expenses = null;

                                if (
                                    !empty($expense['name'])
                                    && !empty($expense['id'])
                                ) {
                                    foreach ($budget_sales['Expense'] as $ex) {
                                        if ($expense['id'] == $ex['id']) {
                                            if (
                                                $expense['name'] != $ex['name']
                                                || (double)$expense['value'] != $ex['value']
                                            ) {
                                                $_expenses->id = $ex['id'];
                                                $sales_expenses = $_expenses->save(array(
                                                    'Expense' => array(
                                                        'name' => $expense['name'],
                                                        'value' => (double)$this->clean($expense['value'])
                                                    )
                                                ));

                                                if ($sales_expenses) {
                                                    $sales_expenses['Expense']['id'] = $ex['id'];
                                                }
                                            } else {
                                                $sales_expenses = array('Expense' => $ex);
                                            }

                                            break;
                                        }
                                    }
                                } else if (
                                !empty($expense['name'])
                                ) {
                                    $_expenses->create();
                                    $sales_expenses = $_expenses->save(array(
                                        'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                        'type' => 1,
                                        'name' => $expense['name'],
                                        'value' => (double)$this->clean($expense['value'])
                                    ));
                                }

                                if (!empty($sales_expenses)) {
                                    $expense_id[] = $sales_expenses['Expense']['id'];
                                    $total_expense_value += $sales_expenses['Expense']['value'];
                                } else {
                                    $isError = true;
                                }
                            }

                            if (!$isError) {
                                $this->id = $budget_sales['BudgetSale']['id'];
                                if (
                                $this->save(array(
                                    'BudgetSale' => array(
                                        'sales_labor_cost' => (double)$this->clean($office['sales_labor_cost']),
                                        'sales_overtime_cost' => (double)$this->clean($office['sales_overtime_cost']),
                                        'sales_revenues' => (double)$this->clean($total_revenue_value),
                                        'sales_expenses' => (double)$this->clean($total_expense_value)
                                    )
                                ))
                                ) {
                                    $_revenues->unbindModel(array('belongsTo' => 'BudgetSale'));
                                    if (
                                    !$_revenues->deleteAll(array(
                                        'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                        'id !=' => $revenue_id,
                                        'type' => 1
                                    ))
                                    ) {
                                        $isError = true;
                                        break;
                                    }

                                    $_expenses->unbindModel(array('belongsTo' => 'BudgetSale'));
                                    if (
                                    !$_expenses->deleteAll(array(
                                        'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                        'id !=' => $expense_id,
                                        'type' => 1
                                    ))
                                    ) {
                                        $isError = true;
                                        break;
                                    }
                                } else {
                                    $isError = true;
                                    break;
                                }
                            } else {
                                $isError = true;
                                break;
                            }
                        } else {
                            $isError = true;
                            break;
                        }
                    } else if (
                    !empty($budget_sales)
                    ) {
                        $_expenses->unbindModel(array('belongsTo' => 'BudgetSale'));
                        $_revenues->unbindModel(array('belongsTo' => 'BudgetSale'));
                        if (
                            $_expenses->deleteAll(array(
                                'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                'type' => 1
                            ))
                            && $_revenues->deleteAll(array(
                                'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                'type' => 1
                            ))
                        ) {
                            if (
                                $this->find('count', array(
                                    'conditions' => array(
                                        'BudgetSale.id' => $budget_sales['BudgetSale']['id'],
                                        'OR' => array(
                                            'Revenue.value IS NOT NULL',
                                            'Expense.value IS NOT NULL',
                                            'LaborCostDetail.value IS NOT NULL'
                                        )
                                    ),
                                    'joins' => array(
                                        array(
                                            'table' => 'revenues',
                                            'alias' => 'Revenue',
                                            'type' => 'LEFT',
                                            'conditions' => array(
                                                'Revenue.budget_sale_id = BudgetSale.id',
                                            )
                                        ),
                                        array(
                                            'table' => 'expenses',
                                            'alias' => 'Expense',
                                            'type' => 'LEFT',
                                            'conditions' => array(
                                                'Expense.budget_sale_id = BudgetSale.id',
                                            )
                                        ),
                                        array(
                                            'table' => 'labor_cost_details',
                                            'alias' => 'LaborCostDetail',
                                            'type' => 'LEFT',
                                            'conditions' => array(
                                                'LaborCostDetail.budget_sale_id = BudgetSale.id',
                                            )
                                        )
                                    ),
                                    'recursive' => -1
                                )) == 0
                            ) {
                                if (
                                    $budget_sales['BudgetSale']['budget_labor_cost'] == 0
                                    && $budget_sales['BudgetSale']['budget_overtime_cost'] == 0
                                ) {
                                    if (!$this->delete($budget_sales['BudgetSale']['id'])) {
                                        $isError = true;
                                    }
                                } else {
                                    $this->id = $budget_sales['BudgetSale']['id'];
                                    if (
                                    !$this->save(array(
                                        'BudgetSale' => array(
                                            'sales_labor_cost' => 0,
                                            'sales_overtime_cost' => 0
                                        )
                                    ))
                                    ) {
                                        $isError = true;
                                    }
                                }
                            } else {
                                $this->id = $budget_sales['BudgetSale']['id'];
                                if (
                                !$this->save(array(
                                    'BudgetSale' => array(
                                        'sales_labor_cost' => 0,
                                        'sales_overtime_cost' => 0,
                                        'sales_expenses' => 0
                                    )
                                ))
                                ) {
                                    $isError = true;
                                }
                            }
                        } else {
                            $isError = true;
                        }
                    }
                }
            } else {
                $isError = true;
            }

            if ($isError) {
                $datasource->rollback();
                $response = false;
            } else {
                $datasource->commit();
                $response = true;
            }
        } catch (Exception $e) {
            $isError = true;
            $datasource->rollback();
            $response = false;
        }

        return $response;
    }

    public function import_budget($chunk)
    {
        $response = array(
            'status' => false,
            'message' => array()
        );
        $today = date("Y-m-d H:i:s");

        $datasource = $this->getDataSource();
        $isError = false;

        try {
            $datasource->begin();

            $_company = ClassRegistry::init('Company');
            $_office = ClassRegistry:: init('Office');
            $_revenues = ClassRegistry::init('Revenue');
            $_expenses = ClassRegistry::init('Expense');
        } catch (Exception $e) {
            $isError = true;
            $response['message'][] = __('an unknown error occurred');
        }

        if (!$isError) {
            foreach ($chunk as $ix => $data) {
                $ix = $ix + 3;
                $skip = false;

                try {
                    $v_office = $_office->find('count', array(
                        'conditions' => array(
                            'id' => $data['office_id']['value']
                        ),
                        'limit' => 1,
                        'recursive' => -1
                    ));

                    if (empty($v_office)) {
                        $isError = $skip = true;
                        $response['message'][] = (
                            __('couldn\'t find office with id: %s', $data['office_id']['value'])
                            . $this->l_c($data['office_id']['position']['line'], $data['office_id']['position']['col'])

                        );
                    }

                    if (!$skip) {
                        $budget_sales = $this->find('first', array(
                            'conditions' => array(
                                'office_id' => $data['office_id']['value'],
                                'year' => $data['year']['value'],
                                'month' => intval($data['month']['value'])
                            ),
                            'recursive' => -1
                        ));

                        if (empty($budget_sales)) {
                            $this->create();
                            $budget_sales = $this->save(array(
                                'office_id' => $data['office_id']['value'],
                                'year' => $data['year']['value'],
                                'month' => intval($data['month']['value']),
                                'budget_revenues' => 0,
                                'sales_revenues' => 0,
                                'budget_labor_cost' => 0,
                                'sales_labor_cost' => 0,
                                'budget_overtime_cost' => 0,
                                'sales_overtime_cost' => 0,
                                'budget_expenses' => 0,
                                'sales_expenses' => 0,
                            ));
                        }

                        if (!empty($budget_sales)) {
                            $total_revenue = $total_expense = 0;

                            $_revenues->unbindModel(array('belongsTo' => array('BudgetSale')));
                            if (
                            $_revenues->deleteAll(array(
                                'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                'type' => 0
                            ))
                            ) {
                                foreach ($data['revenues'] as $name => $sale) {
                                    $_revenues->create();
                                    if (
                                    $_revenues->save(array(
                                        'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                        'type' => 0,
                                        'name' => trim($name),
                                        'value' => (double)$this->clean($sale['value']),
                                    ))
                                    ) {
                                        $total_revenue += (double)$sale['value'];
                                    } else {
                                        $isError = true;
                                        $response['message'][] = (
                                            __('couldn\'t update sales: "%s"', $name)
                                            . $this->l_c($sale['position']['line'], $sale['position']['col'])
                                        );
                                    }
                                }
                            } else {
                                $isError = true;
                                $response['message'][] = __('couldn\'t update sales') . $this->l_c($ix);
                            }

                            $_expenses->unbindModel(array('belongsTo' => array('BudgetSale')));
                            if (
                            $_expenses->deleteAll(array(
                                'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                'type' => 0
                            ))
                            ) {
                                foreach ($data['expenses'] as $name => $expense) {
                                    $_expenses->create();
                                    if (
                                    $_expenses->save(array(
                                        'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                        'type' => 0,
                                        'name' => trim($name),
                                        'value' => (double)$this->clean($expense['value']),
                                    ))
                                    ) {
                                        $total_expense += (double)$expense['value'];
                                    } else {
                                        $isError = true;
                                        $response['message'][] = (
                                            __('couldn\'t update expense: "%s"', $name)
                                            . $this->l_c($expense['position']['line'], $expense['position']['col'])
                                        );
                                    }
                                }
                            } else {
                                $isError = true;
                                $response['message'][] = __('couldn\'t update expense') . $this->l_c($ix);
                            }

                            if (!$isError) {
                                $this->id = $budget_sales['BudgetSale']['id'];
                                if (
                                !$this->save(array(
                                    'BudgetSale' => array(
                                        'budget_revenues' => (double)$this->clean($total_revenue),
                                        'budget_labor_cost' => (double)$this->clean($data['labor_cost']['value']),
                                        'budget_overtime_cost' => (double)$this->clean($data['overtime_cost']['value']),
                                        'budget_expenses' => (double)$this->clean($total_expense),
                                    )
                                ))
                                ) {
                                    $isError = true;
                                    $response['message'][] = __('couldn\'t update record') . $this->l_c($ix);
                                }
                            }
                        } else {
                            $isError = true;
                            $response['message'][] = __('couldn\'t update record') . $this->l_c($ix);
                        }
                    }
                } catch (Exception $e) {
                    $isError = true;
                    $response['message'][] = __('an unknown error occurred') . $this->l_c($ix);
                    $response['message'][] = $e->getMessage();
                }
            }
        }

        try {
            if ($isError) {
                $datasource->rollback();
                $response['status'] = false;
            } else {
                $datasource->commit();
                $response['status'] = true;
            }
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'][] = __('an unknown error occurred');
            $datasource->rollback();
        }

        return $response;
    }

    public function import_sales($chunk)
    {
        $response = array(
            'status' => false,
            'message' => array()
        );
        $today = date("Y-m-d H:i:s");

        $datasource = $this->getDataSource();
        $isError = false;

        try {
            $datasource->begin();

            $_company = ClassRegistry::init('Company');
            $_office = ClassRegistry:: init('Office');
            $_revenues = ClassRegistry::init('Revenue');
            $_expenses = ClassRegistry::init('Expense');
        } catch (Exception $e) {
            $isError = true;
            $response['message'][] = __('an unknown error occurred');
        }

        if (!$isError) {
            foreach ($chunk as $ix => $data) {
                $ix = $ix + 3;
                $skip = false;

                try {
                    $v_office = $_office->find('count', array(
                        'conditions' => array(
                            'id' => $data['office_id']['value']
                        ),
                        'limit' => 1,
                        'recursive' => -1
                    ));

                    if (empty($v_office)) {
                        $isError = $skip = true;
                        $response['message'][] = (
                            __('couldn\'t find office with id: %s', $data['office_id']['value'])
                            . $this->l_c($data['office_id']['position']['line'], $data['office_id']['position']['col'])

                        );
                    }

                    if (!$skip) {
                        $budget_sales = $this->find('first', array(
                            'conditions' => array(
                                'office_id' => $data['office_id']['value'],
                                'year' => $data['year']['value'],
                                'month' => intval($data['month']['value'])
                            ),
                            'recursive' => -1
                        ));

                        if (empty($budget_sales)) {
                            $this->create();
                            $budget_sales = $this->save(array(
                                'office_id' => $data['office_id']['value'],
                                'year' => $data['year']['value'],
                                'month' => intval($data['month']['value']),
                                'budget_revenues' => 0,
                                'sales_revenues' => 0,
                                'budget_labor_cost' => 0,
                                'sales_labor_cost' => 0,
                                'budget_overtime_cost' => 0,
                                'sales_overtime_cost' => 0,
                                'budget_expenses' => 0,
                                'sales_expenses' => 0,
                            ));
                        }

                        if (!empty($budget_sales)) {
                            $total_revenue = $total_expense = 0;

                            $_revenues->unbindModel(array('belongsTo' => array('BudgetSale')));
                            if (
                            $_revenues->deleteAll(array(
                                'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                'type' => 1
                            ))
                            ) {
                                foreach ($data['revenues'] as $name => $sale) {
                                    $_revenues->create();
                                    if (
                                    $_revenues->save(array(
                                        'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                        'type' => 1,
                                        'name' => trim($name),
                                        'value' => (double)$this->clean($sale['value']),
                                    ))
                                    ) {
                                        $total_revenue += (double)$sale['value'];
                                    } else {
                                        $isError = true;
                                        $response['message'][] = (
                                            __('couldn\'t update sales: "%s"', $name)
                                            . $this->l_c($sale['position']['line'], $sale['position']['col'])
                                        );
                                    }
                                }
                            } else {
                                $isError = true;
                                $response['message'][] = __('couldn\'t update sales') . $this->l_c($ix);
                            }

                            $_expenses->unbindModel(array('belongsTo' => array('BudgetSale')));
                            if (
                            $_expenses->deleteAll(array(
                                'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                'type' => 1
                            ))
                            ) {
                                foreach ($data['expenses'] as $name => $expense) {
                                    $_expenses->create();
                                    if (
                                    $_expenses->save(array(
                                        'budget_sale_id' => $budget_sales['BudgetSale']['id'],
                                        'type' => 1,
                                        'name' => trim($name),
                                        'value' => (double)$this->clean($expense['value']),
                                    ))
                                    ) {
                                        $total_expense += (double)$expense['value'];
                                    } else {
                                        $isError = true;
                                        $response['message'][] = (
                                            __('couldn\'t update expense: "%s"', $name)
                                            . $this->l_c($expense['position']['line'], $expense['position']['col'])
                                        );
                                    }
                                }
                            } else {
                                $isError = true;
                                $response['message'][] = __('couldn\'t update expense') . $this->l_c($ix);
                            }

                            if (!$isError) {
                                $this->id = $budget_sales['BudgetSale']['id'];
                                if (
                                !$this->save(array(
                                    'BudgetSale' => array(
                                        'sales_revenues' => (double)$this->clean($total_revenue),
                                        'sales_labor_cost' => (double)$this->clean($data['labor_cost']['value']),
                                        'sales_overtime_cost' => (double)$this->clean($data['overtime_cost']['value']),
                                        'sales_expenses' => (double)$this->clean($total_expense),
                                    )
                                ))
                                ) {
                                    $isError = true;
                                    $response['message'][] = __('couldn\'t update record') . $this->l_c($ix);
                                }
                            }
                        } else {
                            $isError = true;
                            $response['message'][] = __('couldn\'t update record') . $this->l_c($ix);
                        }
                    }
                } catch (Exception $e) {
                    $isError = true;
                    $response['message'][] = __('an unknown error occurred') . $this->l_c($ix);
                    $response['message'][] = $e->getMessage();
                }
            }
        }

        try {
            if ($isError) {
                $datasource->rollback();
                $response['status'] = false;
            } else {
                $datasource->commit();
                $response['status'] = true;
            }
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'][] = __('an unknown error occurred');
            $datasource->rollback();
        }

        return $response;
    }

    public function calculate_profit($budgetSale, $totalSaleHonobono, $currentYearBudgetSale, $currentYearTotalSaleHonobono)
    {
        // calculate profit monthly
        $budget_revenues = $budgetSale['budget_revenues'];
        $budget_expenses = $budgetSale['budget_expenses'];
        $budget_labor_cost = $budgetSale['budget_labor_cost'];
        $budget_overtime_cost = $budgetSale['budget_overtime_cost'];

        $sales_revenues = $budgetSale['sales_revenues'];
        $sales_expenses = $budgetSale['sales_expenses'];
        $sales_labor_cost = $budgetSale['sales_labor_cost'];
        $sales_overtime_cost = $budgetSale['sales_overtime_cost'];


        $budget = ($budget_revenues) - ($budget_labor_cost + $budget_overtime_cost + $budget_expenses);
        $sales = ($sales_revenues + $totalSaleHonobono) - ($sales_labor_cost + $sales_overtime_cost + $sales_expenses);
        $rates = !empty($budget) && !empty($sales) && $budget > 0 && $sales > 0
            ? ((double)$sales / (double)$budget) * 100
            : '-';

        $excess_profit = $sales - $budget;


        // calculator profit current year
        $current_year_budget_revenues = $currentYearBudgetSale['current_year_budget_revenues'];
        $current_year_budget_expenses = $currentYearBudgetSale['current_year_budget_expenses'];
        $current_year_budget_labor_cost = $currentYearBudgetSale['current_year_budget_labor_cost'];
        $current_year_budget_overtime_cost = $currentYearBudgetSale['current_year_budget_overtime_cost'];

        $current_year_sales_revenues = $currentYearBudgetSale['current_year_sales_revenues'];
        $current_year_sales_expenses = $currentYearBudgetSale['current_year_sales_expenses'];
        $current_year_sales_labor_cost = $currentYearBudgetSale['current_year_sales_labor_cost'];
        $current_year_sales_overtime_cost = $currentYearBudgetSale['current_year_sales_overtime_cost'];

        if (
            ($current_year_budget_revenues !== '' && $current_year_budget_revenues !== NULL)
            || ($current_year_budget_expenses !== '' && $current_year_budget_expenses !== NULL)
            || ($current_year_budget_labor_cost !== '' && $current_year_budget_labor_cost !== NULL)
            || ($current_year_budget_overtime_cost !== '' && $current_year_budget_overtime_cost !== NULL)
        ) {
            $current_year_budget = ($current_year_budget_revenues) - ($current_year_budget_labor_cost + $current_year_budget_overtime_cost + $current_year_budget_expenses);
        } else {
            $current_year_budget = '0';
        }
        if (
            ($current_year_sales_revenues !== '' && $current_year_sales_revenues !== NULL)
            || ($current_year_sales_expenses !== '' && $current_year_sales_expenses !== NULL)
            || ($current_year_sales_labor_cost !== '' && $current_year_sales_labor_cost !== NULL)
            || ($current_year_sales_overtime_cost !== '' && $current_year_sales_overtime_cost !== NULL)
            || $currentYearTotalSaleHonobono !== NULL
        ) {
            $current_year_sales = ($current_year_sales_revenues + $currentYearTotalSaleHonobono) - ($current_year_sales_labor_cost + $current_year_sales_overtime_cost + $current_year_sales_expenses);
        } else {
            $current_year_sales = '';
        }
        $current_year_excess_profit = $current_year_sales - $current_year_budget;
        $profit = array(
            'budget' => $budget,
            'sales' => $sales,
            'rates' => $rates,
            'excess_profit' => $excess_profit,
            'current_year_sales' => $current_year_sales,
            'current_year_budget' => $current_year_budget,
            'current_year_rates' => (
            !empty($current_year_budget) && !empty($current_year_sales) && $current_year_budget > 0 && $current_year_sales > 0
                ? ((double)$current_year_sales / (double)$current_year_budget) * 100
                : ''
            ),
            'current_year_excess_profit' => $current_year_excess_profit,
        );
        return $profit;
    }


}