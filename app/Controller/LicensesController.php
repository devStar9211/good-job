<?php
/**
 * Home Controller
 *
 */
App::uses('WidgetController','Controller');

class LicensesController extends WidgetController
{
	public function admin_index($id = null) {
        $this->set("title_for_layout",__('License DB registration'));
        $model = 'License';
        $controller = 'licenses';
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
        $data = $this->{$model}->find("all");
        $this->set("collection",$data);
        $config_field = array(
            'model' => $model,
            'controller' => $controller,
            'action' => 'save',
            'fields' => array(
                'id'=> 'id',
                'name' => 'name'
            ),
        );
        $this->set("config_field",$config_field);
        $this->render('/Widget/admin_index');
	}
}


