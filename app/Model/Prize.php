<?php

class Prize extends AppModel {
	public $useTable = 'prizes';
	public $primaryKey = 'id';

	public $hasMany = array(
		'EmployeePrize' => array(
			'className' => 'EmployeePrize',
			'foreignKey' => 'prize_id'
		)
	);

	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'message' => 'please fill out this field.'
			),
			'notDuplicate' => array(
				'rule' => 'notDuplicate',
				'message' => 'the prize name is already exists.'
			),
            'between' => array(
                'rule' => array('between', 1, 100),
                'message' => 'Độ dài ký tự nhập vào trong khoảng %d-%d'
            )
		),
	);

	public function notDuplicate($name) {
    	return $this->find('count', array('conditions' => array('name' => $name), 'recursive' => -1)) == 0;
    }
}