<?php

/**
 * Home Controller
 *
 */
class WidgetController extends AppController
{
    public $uses;
    public $helpers = array('Session', 'CKForm', 'Html', 'Grid');

    public $setModel;
    public $postdata;
    public $setFlash = array('message' => '', 'type' => '');

    public $message;
    public $message_type;

    public function admin_save($model, $postdata)
    {
        $response = false;
        $isError = false;
        $datasource = $this->{$model}->getDataSource();
        $datasource->begin();
        try {

            if (!empty($model) && !empty($postdata)) {
                $dataSave = null;
                if ($postdata['EditData']['id'] > 0) {
                    $dataSave['id'] = $postdata['EditData']['id'];
                    $dataSave['name'] = trim($postdata['FormData']['name'][$postdata['EditData']['id']]);

                } else {
                    $dataSave['id'] = '';
                    $dataSave['name'] = trim($postdata['EditData']['name']);
                }
                $this->{$model}->create();
                $saved = $this->{$model}->save($dataSave);
                if ($saved) {
                    $response['data'] = $saved;
                    $isError = false;
                    $response['flash']['message'] = __('Item saved');
                    $response['flash']['type'] = 'success';
                } else {

                    $isError = true;
                    $errorMessage = array();
                    $validationErrors = $this->{$model}->validationErrors;
                    foreach ($validationErrors as $key => $value) {
                        $errorMessage[] = $value[0];
                    }
                    $errorMessage = implode(' ', $errorMessage);
                    $response['flash']['message'] = $errorMessage;
                    $response['flash']['type'] = 'error';
                }
            }
        } catch (Exception $e) {
            $response['flash']['message'] = __('The item could not be saved. Please try again.');
            $response['flash']['type'] = 'error';
        }
        if ($isError) {
            $datasource->rollback();
        } else {
            $datasource->commit();
        }
        return $response;
    }

    public function admin_delete($model, $id = null)
    {
        $response = false;
        $isError = false;
        $datasource = $this->{$model}->getDataSource();
        $datasource->begin();
        try {
            if (!empty($model)) {
                $data = $this->{$model}->read(null, $id);
                if (!empty($data)) {

                    if ($this->{$model}->delete($id)) {
                        $isError = false;
                        $response['flash']['message'] = __('Item saved');
                        $response['flash']['type'] = 'success';

                    } else {
                        $isError = true;
                        $response['flash']['message'] = __('Không thể xóa bản ghi này, dữ liệu đang được sử dụng ở nơi khác.');
                        $response['flash']['type'] = 'warning';

                    }

                }
            }
        } catch (Exception $e) {
            $isError = true;
            $response['flash']['message'] = __('Bản ghi này đang được sử dụng ở nơi khác.');
            $response['flash']['type'] = 'error';
        }
        if ($isError) {
            $datasource->rollback();
        } else {
            $datasource->commit();
        }
        return $response;
    }
}