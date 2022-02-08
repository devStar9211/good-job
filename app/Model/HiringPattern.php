<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class HiringPattern extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'hiring_patterns';
    public $primaryKey = 'id';

    public $hasMany = array(
		'OfficeEvaluation' => array(
			'className' => 'Employee',
			'foreignKey' => 'hiring_pattern_id',
            'dependent' => true
		)
	);

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Không được để trống trường này.'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'required' => 'create',
                'message' => 'Tên đã được sử dụng, hãy thử tên khác.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'message' => 'Tối đa 100 ký tự.'
            ),
        ),
    );

}