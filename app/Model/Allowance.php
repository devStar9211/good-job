<?php

class Allowance extends AppModel {
	public $useTable = 'allowances';
	public $primaryKey = 'id';

	public $hasMany = array(
		'EmployeeAllowance' => array(
			'className' => 'EmployeeAllowance',
			'foreignKey' => 'allowance_id',
			'dependent' => true
		)
	);
}