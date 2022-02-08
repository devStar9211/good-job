<?php

class EmployeeOccupation extends AppModel {
	public $useTable = 'employee_occupations';
	public $primaryKey = 'id';

	public $belongsTo = array(
		'Employee' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_id',
		),
		'Occupation' => array(
			'className' => 'Occupation',
			'foreignKey' => 'occupation_id'
		)
	);
}