<?php
App::uses('AppModel', 'Model');

class OfficeAdditionJudgment extends AppModel
{

    public $useTable = 'office_addition_judgments';
    public $primaryKey = 'id';

    public $belongsTo = array(
		'Office' => array(
			'className' => 'Office',
			'foreignKey' => 'office_id',
		),
		'AdditionJudgment' => array(
			'className' => 'AdditionJudgment',
			'foreignKey' => 'addition_judgment_id',
		)
	);


}