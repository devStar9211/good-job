<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class PointDetail extends AppModel
{

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'point_details';
    public $primaryKey = 'id';
    public $virtualFields = array(
        'total' => 'SELECT SUM(point_details.value) FROM point_details',
    );

    public $belongsTo = array(
        'Employee' => array(
            'className' => 'Employee',
            'foreignKey' => 'employee_id'
        ),
        'PointType' => array(
            'className' => 'PointType',
            'foreignKey' => 'point_type_id'
        ),

    );

    public $validate = array(

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
                        $point = $this->find('first', array(
                            'conditions' => array(
                                'employee_id' => $data['employee_id']['value'],
                                'point_type_id' => $data['point_type_id']['value'],
                                'year' => $data['year']['value'],
                                'month' => intval($data['month']['value'])
                            ),
                            'recursive' => -1
                        ));
                        if (!empty($point)) {
                            if (
                            !$this->delete($point['PointDetail']['id'])
                            ) {
                                $isError = true;
                            }
                        }
                        if (!$isError) {
                            $this->create();
                            $value = $this->clean($data['value']['value']);

                            $point = $this->save(array(
                                'employee_id' => $data['employee_id']['value'],
                                'point_type_id' => $data['point_type_id']['value'],
                                'year' => $data['year']['value'],
                                'month' => intval($data['month']['value']),
                                'value' => $value,
                            ));
                        }
                        if (empty($point)) {
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

    public function savePointAPIData($data, $year, $month)
    {
        $PointHeader = ClassRegistry::init('PointHeader');
        if (!empty($data['point'])) {
            foreach ($data['point'] as $id_employee => $_employee) {
                $isError = false;
                $datasource = $this->getDataSource();
                try {
                    $datasource->begin();
                    $value_point = $_employee['value'];
                    if ($value_point != '') {
                        $point_header = $PointHeader->find('first', array(
                            'conditions'=>array(
                                'PointHeader.employee_id' => $id_employee
                            ),
                            'recursive'=>-1
                        ));
                        if(!empty($point_header)){
                            $point_header_id = $point_header['PointHeader']['id'];
                        }else{
                            $dataSavePointHeader = array('PointHeader' => array(
                                'id' => null,
                                'employee_id' => $id_employee,
                            ));
                            $saved = $PointHeader->save($dataSavePointHeader);
                            if ($saved) {
                                $isError = false;
                                $point_header_id = $saved['PointHeader']['id'];
                            } else {
                                $isError = true;
                            }
                        }
                        if (!$isError) {
                            $value_point_detail = $value_point != '' ? (double)$this->clean($value_point) : null;
                            $dataSavePointDetail = array(
                                'PointDetail' => array(
                                    'point_type_id' => $data['point_type'],
                                    'point_header_id' => $point_header_id,
                                    'value' => $value_point_detail,
                                    'month' => $month,
                                    'year' => $year
                                )
                            );
                            $this->create();
                            $saved = $this->save($dataSavePointDetail);
                            if ($saved) {
                                $isError = false;
                            } else {
                                $isError = true;
                            }
                        }
                    }

                    if ($isError) {
                        $datasource->rollback();
                    } else {
                        $datasource->commit();
                    }

                } catch (Exception $e) {
                    $datasource->rollback();
                }
            }
        }
        return true;
    }


    public function savePointInputData($data)
    {
        $year = date('Y', strtotime($data['date']));
        $month = date('m', strtotime($data['date']));
        $datasource = $this->getDataSource();
        $isError = false;
//        try {
            $datasource->begin();
            if (!empty($data['point'])) {
                foreach ($data['point'] as $id_employee => $_employee) {
                    $isError = false;
                    $allow_save = true;
                    if ($_employee['value'] != '' || $_employee['id'] != '') {
                        $value_point_detail = $_employee['value'];
                    } else {
                        $allow_save = false;
                    }
                    if ($allow_save) {

//                            pr($data);die;
                            $value_point_detail = $value_point_detail != '' ? (double)$this->clean($value_point_detail) : null;
                            $dataSavePointDetail = array(
                                'PointDetail' => array(
                                    'id' => $_employee['id'],
                                    'point_type_id' => $data['point_type'],
                                    'employee_id' => $id_employee,
                                    'value' => $value_point_detail,
                                    'month' => $month,
                                    'year' => $year
                                )
                            );
                            $saved = $this->save($dataSavePointDetail);
                            if ($saved) {
                                $isError = false;
                            } else {
                                $isError = true;
                                break;
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
//        } catch (Exception $e) {
//            $isError = true;
//            $datasource->rollback();
//            $response = false;
//        }
        return $response;
    }


    public function afterSave_bak($created, $options = Array())
    {
        if (!empty($this->data['PointDetail'])) {
            $datasource = $this->getDataSource();
            $isError = false;
            try {
                $datasource->begin();
                $modelPointDetail = ClassRegistry::init('PointDetail');
                $modelPointHeader = ClassRegistry::init('PointHeader');
                $modelPointDetail->virtualFields = array(
                    'sum_value' => 'SUM(PointDetail.value)'
                );

                $dataPointDetail = $this->data['PointDetail'];
                $point_header_id = $dataPointDetail['point_header_id'];
                $dataPointDetail = $modelPointDetail->find('all', array(
                        'conditions' => array(
                            'PointDetail.point_header_id' => $point_header_id
                        ),
                        'fields' => array('PointDetail.point_header_id', 'PointDetail.sum_value'),
                        'recursive' => -1
                    )
                );
                $modelPointHeader->id = $dataPointDetail[0]['PointDetail']['point_header_id'];
                $saved = $modelPointHeader->saveField('value', $dataPointDetail[0]['PointDetail']['sum_value']);
                if ($saved) {
                    $isError = false;
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
                $datasource->rollback();
                $response = false;
            }
            return $response;
        } else {
            return true;
        }


    }

    private function clean($string)
    {
        $string = str_replace(',', '', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
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

}