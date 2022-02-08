<?php

class EmployeeLicense extends AppModel {
	public $useTable = 'employee_licenses';
	public $primaryKey = 'id';

	public $belongsTo = array(
		'Employee' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_id',
		),
		'License' => array(
			'className' => 'License',
			'foreignKey' => 'license_id'
		)
	);
}