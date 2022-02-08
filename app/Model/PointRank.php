<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class PointRank extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'point_ranks';
    public $primaryKey = 'id';

    public $belongsTo = array(

        'Stage' => array(
            'className' => 'Stage',
            'foreignKey' => 'stage_id'
        ),

    );
    public $hasAndBelongsToMany = array(
        'Occupation' =>
            array(
                'className' => 'Occupation',
                'joinTable' => 'point_rank_occupations',
                'foreignKey' => 'point_rank_id',
                'associationForeignKey' => 'occupation_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'with' => 'point_rank_occupations'
            ),
    );

}