<?php

class Expense extends AppModel {
	public $useTable = 'expenses';
	public $primaryKey = 'id';

	public $belongsTo = array(
		'BudgetSale' => array(
			'className' => 'BudgetSale',
			'foreignKey' => 'budget_sale_id'
		)
	);
}