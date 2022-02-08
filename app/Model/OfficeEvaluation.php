<?php
App::uses('AppModel', 'Model');

class OfficeEvaluation extends AppModel
{

    public $useTable = 'office_evaluations';
    public $primaryKey = 'id';

    public $belongsTo = array(
		'Office' => array(
			'className' => 'Office',
			'foreignKey' => 'office_id',
		),
		'Evaluation' => array(
			'className' => 'Evaluation',
			'foreignKey' => 'evaluation_id',
		)
	);
}