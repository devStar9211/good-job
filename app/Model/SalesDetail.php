<?php

class SalesDetail extends AppModel {
	public $useTable = 'sales_details';
	public $primaryKey = 'id';

	public $belongsTo = array(
		'Revenue' => array(
			'className' => 'Revenue',
			'foreignKey' => 'revenue_id'
		)
	);
}