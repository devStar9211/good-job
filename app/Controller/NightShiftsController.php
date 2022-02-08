<?php
/**
 * Home Controller
 *
 */
App::uses('WidgetController','Controller');

class NightShiftsController extends WidgetController
{
	public function admin_index($id = null) {

        $this->set("title_for_layout",__('Đăng ký trợ cấp làm đêm DB'));
        $model = 'NightShift';
        $controller = 'night_shifts';
        $this->uses[] = $model;
        if(!empty($this->data)){
            $saved = $this->admin_save($model,$this->data);
        }else if($id != ''){
            $saved = $this->admin_delete($model, $id) ;
        }
        if(!empty($saved['flash'])){
                $this->Session->setFlash($saved['flash']['message'], 'flashmessage', array('type' => $saved['flash']['type']), $saved['flash']['type']);
            $this->redirect('/admin/'.$controller);
        }
        $data = $this->{$model}->find("all", array(
//            'order' => array('Occupation.number' => 'asc')

        ));
        $this->set("collection",$data);
        $config_field = array(
            'model' => $model,
            'controller' => $controller,
            'action' => 'save',
            'fields' => array(
                'id'=> 'id',
                'name' => 'name',
                'price' => 'price',
            ),
        );
        $this->set("config_field",$config_field);
	}

	public function admin_save($model, $postdata)
    {
        $response = false;
        $isError = false;
        $datasource = $this->{$model}->getDataSource();
        $datasource->begin();
        try {
            if (!empty($model) && !empty($postdata) ) {
                $dataSave = null;
                if($postdata['EditData']['id'] > 0){
                    $dataSave['id']= $postdata['EditData']['id'];
                    $dataSave['name']= trim($postdata['FormData']['name'][$postdata['EditData']['id']]);
                    $dataSave['price']= $postdata['FormData']['price'][$postdata['EditData']['id']];
                }else{
                    $dataSave['id']= '';
                    $dataSave['name']= trim($postdata['EditData']['name']);
                    $dataSave['price']= $postdata['EditData']['price'];
                }
                    $this->{$model}->create();
                    $saved = $this->{$model}->save($dataSave);
                    if ($saved) {
                        $response['data'] = $saved;
                        $isError = false;
                        $response['flash']['message'] = __('Saved');
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
}


