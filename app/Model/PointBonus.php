<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 */
class PointBonus extends AppModel
{
    public $useTable = 'point_bonuses';
    public $primaryKey = 'id';
    public $belongsTo = array(
        'Employee' => array(
            'className' => 'Employee',
            'foreignKey' => 'employee_id'
        ),
    );

    public function import_point($chunk)
    {
        $response = array(
            'status' => false,
            'message' => array()
        );
        $dataSource = $this->getDataSource();
        $isError = false;
        try {
            $dataSource->begin();
            $_employee = ClassRegistry::init('Employee');
        } catch (Exception $e) {
            $isError = true;
            $response['message'][] = __('an unknown error occurred');
        }
        if (!$isError) {
            $employee_ids = array();
            foreach ($chunk as $ix => $data) {
                $employee_ids[] = $data['employee_id']['value'];
            }
            foreach ($chunk as $ix => $data) {
                $ix = $ix + 3;
                $skip = false;
                try {
                    $v_employee = $_employee->find('count', array(
                        'conditions' => array(
                            'id' => $data['employee_id']['value']
                        ),
                        'limit' => 1,
                        'recursive' => -1
                    ));
                    if (empty($v_employee)) {
                        $isError = $skip = true;
                        $response['message'][] = (
                            __('couldn\'t find employee with id: %s', $data['employee_id']['value'])
                            . $this->l_c($data['employee_id']['position']['line'], $data['employee_id']['position']['col'])
                        );
                    }
                    if (!$skip) {
                        $pointBonus = $this->find('first', array(
                            'conditions' => array(
                                'employee_id' => $data['employee_id']['value'],
                                'year' => $data['year']['value'],
                                'month' => intval($data['month']['value'])
                            ),
                            'recursive' => -1
                        ));
                        if (!empty($pointBonus)) {
                            if (
                            !$this->delete($pointBonus['PointBonus']['id'])
                            ) {
                                $isError = true;
                            }
                        }
                        if (!$isError) {
                            $this->create();
                            $bonus_yen = $this->clean($data['bonus_yen']['value']);
                            $bonus_point = $bonus_yen;
                            $point_bonus = $this->save(array(
                                'employee_id' => $data['employee_id']['value'],
                                'year' => $data['year']['value'],
                                'month' => intval($data['month']['value']),
                                'bonus_yen' => $bonus_yen,
                                'bonus_point' => $bonus_point,
                            ));
                        }
                        if (empty($point_bonus)) {
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
                $dataSource->rollback();
                $response['status'] = false;
            } else {
                $dataSource->commit();
                $response['status'] = true;
            }
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'][] = __('an unknown error occurred');
            $dataSource->rollback();
        }

        return $response;
    }

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

    public function saveData($data)
    {
        $year = $data['PointBonus']['date'];
        $datasource = $this->getDataSource();
        $isError = false;
        try {
            $datasource->begin();
            if (!empty($data['points'])) {
                $employee_ids = array();
                foreach ($data['points'] as $employee_id => $_employee) {
                    $employee_ids[] = $employee_id;
                }
                $modelEmployee = ClassRegistry::init('Employee');
                $basic_salarys = $modelEmployee->find('list', array(
                    'conditions' => array(
                        'Employee.id' => $employee_ids
                    ),
                    'fields' => array(
                        'Employee.id',
                        'Employee.basic_salary',
                    )
                ));
                foreach ($data['points'] as $employee_id => $_employee) {
                    foreach ($_employee as $key => $item) {
                        $isError = false;
                        $allow_save = true;
                        if ($item['bonus_yen'] != '' || $item['id'] != '') {
                            $bonus_yen = $this->clean($item['bonus_yen']);
                            $bonus_point = $bonus_yen;
                        } else {
                            $allow_save = false;
                        }
                        if ($allow_save) {

                            if ($key > 12) {
                                $month_save = $key - 12;
                                $year_save = $year + 1;
                            } else {
                                $month_save = $key;
                                $year_save = $year;
                            }
                            $dataSave = array(
                                'PointBonus' => array(
                                    'id' => $item['id'],
                                    'employee_id' => $employee_id,
                                    'different_rate' => '',
                                    'bonus_yen' => $bonus_yen,
                                    'bonus_point' => $bonus_point,
                                    'month' => $month_save,
                                    'year' => $year_save
                                )
                            );
                            $saved = $this->save($dataSave);
                            if ($saved) {
                                $isError = false;
                            } else {
                                $isError = true;
                                break;
                            }
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

    private function clean($string)
    {
        $string = str_replace(',', '', $string); // Replaces all spaces with ,.
        return $string; // Removes special chars.
    }
}



