<?php

class HonobonoSchedule extends AppModel
{
    public $useDbConfig = 'honobono';
    public $useTable = 'honobono_schedules';
    public $primaryKey = 'id';

    public function data_monthly($offices, $date)
    {
        $get_flag_update_sale = $this->get_flag_update_sale();
        $dateQuery = null;

        $data = $dateQuery = array();
        $flag = false;
        $total_sale = 0;
        $current_month = date('Y-m');
        $current_day = date('d');
        foreach ($date as $_d) {
            $_date = date("Y-m", strtotime($_d['year'] . '-' . $_d['month']));
            if (
                $_date == $current_month
                && isset($get_flag_update_sale[$_d['year']][(int)$_d['month']])
                && $get_flag_update_sale[$_d['year']][(int)$_d['month']] == 1
            ) {
                $dateQuery = array(
                    array(
                        'MONTH(HonobonoSchedule.yymm_ymd)' => $_d['month'],
                        'YEAR(HonobonoSchedule.yymm_ymd)' => $_d['year'],
                    )
                );
                $flag = true;
                break;
            }
        }

        if ($dateQuery != null) {
            foreach ($offices as $office) {
                if ($flag) {
                    $end_day = date("t", strtotime($_date));
                    $remuneration_factor = ($office['Office']['remuneration_factor'] > 0) ? $office['Office']['remuneration_factor'] : 1;
                    $region_classification_factor = ($office['Office']['region_classification_factor'] > 0) ? $office['Office']['region_classification_factor'] : 1;

                    $office_remotes = array();
                    foreach ($office['OfficeRemoteLabel'] as $_office_remote) {
                        $office_remotes[] = $_office_remote['office_remotes']['value'];
                    }
                    $dataScheduleDBS = $this->find('all', array(
                        'conditions' => array(
                            'HonobonoSchedule.jigyo_id' => $office_remotes,
                            'OR' => $dateQuery
                        ),
                    ));

                    $office_self_paid = array();
                    foreach ($office['OfficeSelfPaid'] as $_price) {
                        $office_self_paid[$_price['name']] = $_price['price'];
                    }
                    $count_self_paid = 0;
                    foreach ($dataScheduleDBS as $_item) {
                        $unit_self_paid = 0;
                        foreach ($_item['HonobonoSchedule'] as $field => $value) {
                            if (strpos($field, 'day') !== false) {
                                $value_day = explode('day', $field)[1];
                                if ($value_day > $current_day && $value_day <= $end_day) {
                                    if (array_key_exists($value, $office_self_paid)) {
                                        $count_self_paid++;
                                        $unit_self_paid += $office_self_paid[$value];
                                    }
                                }
                            }
                        }
                        $total_sale += ($_item['HonobonoSchedule']['total_tani'] * $remuneration_factor * $region_classification_factor) + $unit_self_paid;
                    }
                    $data[$office['Office']['id']]['sale'] = $total_sale;
                    $data[$office['Office']['id']]['total_self_paid'] = $count_self_paid;
                } else {
                    $data[$office['Office']['id']]['sale'] = 0;
                    $data[$office['Office']['id']]['total_self_paid'] = 0;
                }
            }
            return $data;
        } else {
            return 0;
        }
    }
}