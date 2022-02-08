<?php

class LaborCostDetail extends AppModel {
	public $useTable = 'labor_cost_details';
	public $primaryKey = 'id';

	public $belongsTo = array(
		'BudgetSale' => array(
			'className' => 'BudgetSale',
			'foreignKey' => 'budget_sale_id'
		)
	);
}