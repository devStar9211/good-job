<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class Position extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'positions';
    public $primaryKey = 'id';
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
                'message' => 'Đã tồn tại bản ghi này.'
            ),
            'between' => array(
                'rule' => array('between', 1, 100),
                'message' => 'Độ dài ký tự nhập vào trong khoảng %d-%d'
            )
        ),

    );
}