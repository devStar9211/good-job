<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class OfficeRemote extends AppModel
{

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'office_remotes';
    public $primaryKey = 'id';

    public function saveData($data)
    {

        $datasource = $this->getDataSource();
        $isError = false;
        try {
            $datasource->begin();
            foreach ($data as $office_id => $office_remotes) {
                foreach ($office_remotes as $office_remote_item) {
                    $isError = false;
                    $allow_save = true;
                    $dataSave = array(
                        'OfficeRemote' => array(
                            'id' => $office_remote_item['id'],
                            'office_id' => $office_id,
                            'office_remote_label_id' => $office_remote_item['office_remote_label_id'],
                            'value' => $office_remote_item['value'],
                        )
                    );
                    $saved = $this->save($dataSave);
                    if ($saved) {
                        $isError = false;
                    } else {
                        $isError = true;
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
            $datasource->rollback();
            $response = false;
        }

        return $response;
    }
}