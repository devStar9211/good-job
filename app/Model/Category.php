<?php
App::uses('AppModel', 'Model');
/**
 * Category Model
 *
 * @property Category $ParentCategory
 * @property Category $ChildCategory
 */
class Category extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'parent_id' => array(
			// 'numeric' => array(
			// 	'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			// ),
		),
		'name' => array(
			'required' => array(
	            'rule' => 'notEmpty',
	            'required' => false,
	            'message' => 'Trường này là bắt buộc.'
	        ),
	        'unique' => array(
	            'rule' => 'isUnique',
	            'required' => 'create',
	            'message' => 'Đã được sử dụng. Hãy thử tên khác'
	        ),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ParentCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ChildCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
 //    public $hasAndBelongsToMany = array(
	// 	'Post' => array(
	// 		'className' => 'Post',
	// 		'joinTable' => 'post_categories',
	// 		'foreignKey' => 'Category_id',
	// 		'associationForeignKey' => 'post_id',
	// 		'unique' => 'keepExisting',
	// 		'conditions' => '',
	// 		'fields' => '',
	// 		'order' => '',
	// 		'limit' => '',
	// 		'offset' => '',
	// 		'finderQuery' => '',
	// 	)
	// );

}
