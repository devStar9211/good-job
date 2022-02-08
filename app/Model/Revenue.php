<?php

class Revenue extends AppModel {
	public $useTable = 'revenues';
	public $primaryKey = 'id';

	public $belongsTo = array(
		'BudgetSale' => array(
			'className' => 'BudgetSale',
			'foreignKey' => 'budget_sale_id'
		)
	);
}