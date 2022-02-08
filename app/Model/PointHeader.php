<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class PointHeader extends AppModel
{

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'point_headers';
    public $primaryKey = 'id';

    public $virtualFields = array(
        'value' => 'SELECT SUM(point_details.value) FROM point_details where point_details.point_header_id = PointHeader.id',
    );

    public $validate = array();
    public $hasMany = array(
        'PointDetail' => array(
            'className' => 'PointDetail',
            'foreignKey' => 'point_header_id',
            'dependent' => true
        ),

    );

    public $belongsTo = array(
        'Employee' => array(
            'className' => 'Employee',
            'foreignKey' => 'employee_id'
        ),

    );


}