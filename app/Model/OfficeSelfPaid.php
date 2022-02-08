<?php
App::uses('AppModel', 'Model');

class OfficeSelfPaid extends AppModel
{

    public $useTable = 'office_self_paids';
    public $primaryKey = 'id';

    public $belongsTo = array(
		'Office' => array(
			'className' => 'Office',
			'foreignKey' => 'office_id',
		),

	);
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => false,
                'message' => 'Not empty.'
            ),
        ),
        'price' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => false,
                'message' => 'Not empty.'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'required' => false,
                'message' => 'Please enter only numbers.'
            ),

        ),

    );
}