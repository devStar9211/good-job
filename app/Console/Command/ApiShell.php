<?php
App::uses("ComponentCollection", "Controller");

class ApiShell extends AppShell
{
    public $uses = array('Office', 'BudgetSale', 'PointDetail', 'Employee', 'HonobonoResult', 'HonobonoSchedule', 'HonobonoRiyouResult', 'HonobonoKaigoResult');

    public function main()
    {
        $this->get_expense();
        $this->get_achivement_point();
    }

    public function get_expense()
    {
        echo "Start update api: " . date('Y-m-d H:i:s') . " \n";
        try {
            $ofices = $this->Office->find('all', array(
                'fields' => array('id', 'api_shift_office_id'),
                'contain' => false
            ));
            $year = date('Y');
            $month = date('m');

            $dates = array(
                        array(
                            'month' => date('m', strtotime('-1 month')),
                            'year' => date('Y', strtotime('-1 month'))
                        ),
                        array(
                            'month' => date('m'),
                            'year' => date('Y')
                        ),
                        array(
                            'month' => date('m', strtotime('+1 month')),
                            'year' => date('Y', strtotime('+1 month'))
                        ),
                    );
            foreach ($dates as $_date) {
                foreach ($ofices as $key => $ofice) {
                    if (!empty($ofice['Office']['id'])) {
                        $url = "https://shift.good-job.online/api/monthly_cost/" . $ofice['Office']['id'] . '/' . $_date['year'] . $_date['month'];
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_NOBODY, true);
                        curl_exec($ch);
                        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        if ($code == 200) {
                            $json = file_get_contents($url);
                            $obj = json_decode($json, true);

                            $budget_sale = $this->BudgetSale->find('first', array(
                                'conditions' => array(
                                    'BudgetSale.office_id' => $ofice['Office']['id'],
                                    'BudgetSale.month' => $_date['month'],
                                    'BudgetSale.year' => $_date['year'],
                                ),
                                'contain' => false
                            ));
                            $data = array();
                            if (!empty($budget_sale)) {
                                $data = $budget_sale;
                                $data['BudgetSale']['sales_labor_cost'] = $obj['data']['cost'];
                                $data['BudgetSale']['sales_overtime_cost'] = $obj['data']['overtime_cost'];
                                $data['BudgetSale']['updated'] = date('Y-m-d H:i:s');
                            } else {
                                $data['BudgetSale']['id'] = null;
                                $data['BudgetSale']['office_id'] = $ofice['Office']['id'];
                                $data['BudgetSale']['month'] = $_date['month'];
                                $data['BudgetSale']['year'] = $_date['year'];
                                $data['BudgetSale']['budget_revenues'] = 0;
                                $data['BudgetSale']['sales_revenues'] = 0;
                                $data['BudgetSale']['budget_labor_cost'] = 0;
                                $data['BudgetSale']['sales_labor_cost'] = $obj['data']['cost'];
                                $data['BudgetSale']['budget_overtime_cost'] = 0;
                                $data['BudgetSale']['sales_overtime_cost'] = $obj['data']['overtime_cost'];
                                $data['BudgetSale']['budget_expenses'] = 0;
                                $data['BudgetSale']['sales_expenses'] = 0;
                                $data['BudgetSale']['created'] = date('Y-m-d H:i:s');
                                $data['BudgetSale']['updated'] = date('Y-m-d H:i:s');
                            }
                            if ($this->BudgetSale->save($data)) {
                                echo "update BudgetSale success\n";
                            } else {
                                echo "update BudgetSale  error \n";
                            }
                            // $status = true;
                        } else {
                            echo "not found:" . $url . " \n";
                            // $status = false;
                        }
                        curl_close($ch);
                    }
                }
            }
        } catch (Exception  $e) {
            echo "error " . $e->getMessage() . " \n";
        }
        echo "End update api: " . date('Y-m-d H:i:s') . " \n";
    }

    public function get_achivement_point()
    {
//        echo "Start update api: ".date('Y-m-d H:i:s')." \n";
        try {
            $ofices = $this->Office->find('all', array(
                'fields' => array('Office.id', 'Office.remuneration_factor', 'Office.region_classification_factor'),
                'contain' => array(
                    'OfficeSelfPaid'
                ),
            ));
            $year = date('Y');
            $month = date('m');
            $this->PointDetail->deleteAll(array(
                'PointDetail.point_type_id' => 1,
                'PointDetail.year' => $year,
                'PointDetail.month' => $month,
            ));
            foreach ($ofices as $key => $office) {
                if (!empty($office['Office']['id'])) {
                    $url = "https://shift.good-job.online/api/working_time/" . $office['Office']['id'] . '/' . $year . $month;
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    curl_exec($ch);
                    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if ($code == 200) {
                        $json = file_get_contents($url);
                        $obj = json_decode($json, true);
                        $data = array(
                            'point_type' => 1,
                            'date' => $year . '-' . $month,
                            'point' => '',
                        );
                        foreach ($obj['data']['employees'] as $_employee_id => $_employee) {
                            $work_time = $_employee['day_shift_total_time'] + $_employee['night_shift_total_time'] - ($_employee['day_shift_break_time'] + $_employee['night_shift_break_time']);
                            if ($work_time > 0) {
                                $working_time = 1;
                            } else {
                                $working_time = 1;
                            }
                            $this->Employee->validate = null;
                            $this->Employee->id = $_employee_id;
                            if (!$this->Employee->saveField('working_time_id', $working_time)) {
                                break;
                            }else{
                                echo "Update workingtime success \n";
                            }

                        }

                    } else {
                        echo "not found:" . $url . " \n";
                    }
                    curl_close($ch);
                }
            }
            die;
        } catch (Exception  $e) {
            echo "error " . $e->getMessage() . " \n";
        }
        echo "End update api: " . date('Y-m-d H:i:s') . " \n";
    }


}