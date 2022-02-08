<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class EmailNotification extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'email_notifications';
	public $primaryKey = 'id';

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Tên không được để trống.'
            ),
//            'unique' => array(
//                'rule' => 'isUnique',
//                'required' => 'create',
//                'message' => 'ユーザ名が存在していている。'
//            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'メールは無効です。',
                'allowEmpty' => true
            )
        ),
    );
}