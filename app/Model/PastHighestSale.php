<?php
App::uses('AppModel', 'Model');

class PastHighestSale extends AppModel
{

    public $useTable = 'past_highest_sales';
    public $primaryKey = 'id';

//    public $belongsTo = array(
//		'Office' => array(
//			'className' => 'Office',
//			'foreignKey' => 'office_id',
//		),
//
//	);

}