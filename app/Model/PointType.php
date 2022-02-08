<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class PointType extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'point_types';
    public $primaryKey = 'id';
    public $hasMany = array(
        'PointDetail' => array(
            'className' => 'PointDetail',
            'foreignKey' => 'point_type_id',
            'dependent' => true
        ),

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