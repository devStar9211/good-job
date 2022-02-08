<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class OfficeRemoteLabel extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'office_remote_labels';
    public $primaryKey = 'id';
    public $hasMany = array(
        'OfficeRemote' => array(
            'className' => 'OfficeRemote',
            'foreignKey' => 'office_remote_label_id',
        ),
    );
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Tên không được để trống.'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'required' => 'create',
                'message' => 'Tên đã được sử dụng, hãy thử tên khác.'
            ),
            'between' => array(
                'rule' => array('between', 1, 100),
                'message' => 'Độ dài ký tự nhập vào trong khoảng %d-%d'
            )
        ),
    );

    public function saveData($data)
    {

        $datasource = $this->getDataSource();
        $isError = false;
        try {
            $datasource->begin();
            foreach ($data as $id => $label) {

                    $dataSave = array(
                        'OfficeRemoteLabel' => array(
                            'id' => $id,
                            'name' => $label,
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
    }

}