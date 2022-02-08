<?php
App::uses('AppModel', 'Model');

class OfficeBusinessCategory extends AppModel
{

    public $useTable = 'office_business_categories';
    public $primaryKey = 'id';

    public $belongsTo = array(
		'Office' => array(
			'className' => 'Office',
			'foreignKey' => 'office_id',
		),
		'BusinessCategory' => array(
			'className' => 'BusinessCategory',
			'foreignKey' => 'business_category_id',
		)
	);


}