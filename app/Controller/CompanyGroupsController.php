<?php

App::uses('WidgetController', 'Controller');

class CompanyGroupsController extends WidgetController
{
    public $uses = array('CompanyGroup', 'GroupPermission');

    public function admin_index($id = null)
    {
        $this->set("title_for_layout", __('Company group'));
        $model = 'CompanyGroup';
        $controller = 'company_groups';
        $this->uses[] = $model;
        if (!empty($this->data)) {
            $saved = $this->admin_save($model, $this->data);
        } else if ($id != '') {
            $saved = $this->admin_delete($model, $id);
        }
        if (!empty($saved['flash'])) {
            $this->Session->setFlash($saved['flash']['message'], 'flashmessage', array('type' => $saved['flash']['type']), $saved['flash']['type']);
            $this->redirect('/admin/' . $controller);
        }
        $data = $this->{$model}->find("all", array(
            'conditions' => array()
        ));
        $this->set("collection", $data);
        $config_field = array(
            'model' => $model,
            'controller' => $controller,
            'action' => 'save',
            'fields' => array(
                'id' => 'id',
                'name' => 'name',
                'start_month' => 'start_month'
            ),
        );
        $start_months = array(''=>__('Business start month'));
        for($i = 1; $i <= 12; $i++){
            $start_months[$i] = $i.'月';
        }


        $this->set(compact("config_field", "start_months"));
    }

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
                    $dataSave['start_month'] = $postdata['FormData']['start_month'][$postdata['EditData']['id']];
                    $dataSave['group_permission_id'] = trim($postdata['FormData']['group_permission_id'][$postdata['EditData']['id']]);

                } else {
                    $dataSave['id'] = '';
                    $dataSave['name'] = trim($postdata['EditData']['name']);
                    $dataSave['start_month'] = trim($postdata['EditData']['start_month']);
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
        $datasource = $this->CompanyGroup->getDataSource();
        $datasource->begin();
        try {
            if (!empty($model)) {
                $data = $this->CompanyGroup->read(null, $id);
                if (!empty($data)) {

                    if ($this->CompanyGroup->delete($id) && $this->GroupPermission->delete($data['CompanyGroup']['group_permission_id'])) {
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


