<?php
App::uses('AppModel', 'Model');

class AdditionJudgment extends AppModel
{

    public $useTable = 'addition_judgments';
    public $primaryKey = 'id';

    public $hasMany = array(
		'OfficeAdditionJudgment' => array(
			'className' => 'OfficeAdditionJudgment',
			'foreignKey' => 'addition_judgment_id',
            'dependent' => true
		)
	);

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Tên không được để trống.'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'required' => 'create',
                'message' => 'Tên đã được sử dụng, hãy thử tên khác.'
            ),
            'between' => array(
                'rule' => array('between', 1, 100),
                'message' => 'Độ dài ký tự nhập vào trong khoảng %d-%d'
            )
        ),
    );

}