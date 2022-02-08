<?php

class Company extends AppModel {
	public $validate = array(
		'company_group_id' => array(
			'required' => array(
	            'rule' => 'notEmpty',
	            'required' => true,
	            'message' => 'Trường này là bắt buộc.'
	        ),
		),
		'name' => array(
			'required' => array(
	            'rule' => 'notEmpty',
	            'required' => true,
	            'message' => 'Trường này là bắt buộc.'
	        ),
	        'unique' => array(
	            'rule' => 'isUnique',
	            'required' => 'create',
	            'message' => 'Tên công ty đã tồn tài. Hãy thử tên khác'
	        ),
		),
	);

	public $belongsTo = array(
		'CompanyGroup' => array(
			'className' => 'CompanyGroup',
			'foreignKey' => 'company_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	public $hasMany = array(
		'Office' => array(
			'className' => 'Office',
			'foreignKey' => 'company_id'
		)
	);

	public function afterSave($created, $options = array())
    {
        $isError = false;
        $modelOffice = ClassRegistry::init('Office');
        $datasource = $modelOffice->getDataSource();
        $datasource->begin();
        $dataOffice = $modelOffice->find('all', array(
            'conditions' => array(
                'Office.company_id' => $this->data['Company']['id']
            ),
            'recursive' => -1
        ));
        foreach ($dataOffice as $item) {

            $modelOffice->id = $item['Office']['id'];
            $item['Office']['company_id'] = $this->data['Company']['id'];
            $item['Office']['company_group_id'] = $this->data['Company']['company_group_id'];

            $modelOffice->set($item);

            if ( $modelOffice->save() ) {
                $isError = false;
            } else {
                $isError = true;
            }
        }
        if ($isError) {
            $datasource->rollback();
            return false;
        } else {
            $datasource->commit();
            return true;
        }
    }

}