<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class Evaluation extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'evaluations';
    public $primaryKey = 'id';

    public $hasMany = array(
		'OfficeEvaluation' => array(
			'className' => 'OfficeEvaluation',
			'foreignKey' => 'evaluation_id',
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