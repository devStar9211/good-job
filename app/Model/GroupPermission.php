<?php
App::uses('AppModel', 'Model');

/**
 * Group Model
 *
 * @property User $User
 */
class GroupPermission extends AppModel
{

    public $actsAs = array('Acl' => array('type' => 'requester'));

    public $hasMany = array(
        'Account' => array(
            'className' => 'Account',
            'foreignKey' => 'group_permission_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'CompanyGroup' => array(
            'className' => 'CompanyGroup',
            'foreignKey' => 'group_permission_id',
            'dependent' => true
        ),
    );

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => false,
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




    public function parentNode()
    {
        return null;
    }

}