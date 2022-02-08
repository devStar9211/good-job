<?php

class Office extends AppModel
{
    public $useTable = 'offices';
    public $primaryKey = 'id';

    public $hasOne = array(
        'PastHighestSale' => array(
            'className' => 'PastHighestSale',
            'foreignKey' => 'office_id'
        )
    );

    public $belongsTo = array(
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'company_id',
        ),
        'Division' => array(
            'className' => 'Division',
            'foreignKey' => 'division_id',
        ),
        'OfficeGroup' => array(
            'className' => 'OfficeGroup',
            'foreignKey' => 'office_group_id',
        ),

    );

    public $hasMany = array(
        'BudgetSale' => array(
            'className' => 'BudgetSale',
            'foreignKey' => 'office_id',
        ),
        'Employee' => array(
            'className' => 'Employee',
            'foreignKey' => 'office_id',
        ),
        'OfficeEvaluation' => array(
            'className' => 'OfficeEvaluation',
            'foreignKey' => 'office_id',
            'dependent' => true
        ),

        'OfficeSelfPaid' => array(
            'className' => 'OfficeSelfPaid',
            'foreignKey' => 'office_id',
            'dependent' => true

        ),
        'OfficeAdditionJudgment' => array(
            'className' => 'OfficeAdditionJudgment',
            'foreignKey' => 'office_id',
            'dependent' => true
        ),
        'OfficeBusinessCategory' => array(
            'className' => 'OfficeBusinessCategory',
            'foreignKey' => 'office_id'
        ),
        'PastHighestSale' => array(
            'className' => 'PastHighestSale',
            'foreignKey' => 'office_id'
        ),
    );

    public $hasAndBelongsToMany = array(
        'Evaluation' =>
            array(
                'className' => 'Evaluation',
                'joinTable' => 'office_evaluations',
                'foreignKey' => 'office_id',
                'associationForeignKey' => 'evaluation_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'with' => 'office_evaluations'
            ),

        'AdditionJudgment' =>
            array(
                'className' => 'AdditionJudgment',
                'joinTable' => 'office_addition_judgments',
                'foreignKey' => 'office_id',
                'associationForeignKey' => 'addition_judgment_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'with' => 'office_addition_judgments'
            ),
        'BusinessCategory' =>
            array(
                'className' => 'BusinessCategory',
                'joinTable' => 'office_business_categories',
                'foreignKey' => 'office_id',
                'associationForeignKey' => 'business_category_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'with' => 'office_business_categories'
            ),
        'OfficeRemoteLabel' =>
            array(
                'className' => 'OfficeRemoteLabel',
                'joinTable' => 'office_remotes',
                'foreignKey' => 'office_id',
                'associationForeignKey' => 'office_remote_label_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'with' => 'office_remotes'
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
        'company_id' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => false,
                'message' => 'Not empty.'
            ),
        ),
        'company_group_id' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => false,
                'message' => 'Not empty.'
            ),
        ),
        'division_id' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => false,
                'message' => 'Not empty.'
            ),
        ),
        'postal_code' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => false,
                'message' => 'Not empty.'
            ),
            'rule2' => array(
                'rule' => array('maxLength', 10),
                'message' => 'Tối đa 10 ký tự'
            ),


        ),
//        'office_group_id' => array(
//            'required' => array(
//                'rule' => 'notEmpty',
//                'required' => false,
//                'message' => 'Not empty.'
//            ),
//        ),
        'office_number' => array(
            'unique' => array(
                'rule' => 'isUnique',
                'required' => 'create',
                'message' => 'office number đã tồn tại. '
            ),
        ),
        'api_shift_office_id' => array(
            'unique' => array(
                'rule' => 'isUnique',
                'required' => 'create',
                'message' => 'office id đã tồn tại. '
            ),
        ),
    );

    public function afterSave($created, $options = array())
    {
        $isError = false;
        $modelEmployee = ClassRegistry::init('Employee');
        $datasource = $modelEmployee->getDataSource();
        $datasource->begin();

        $dataEmployee = $modelEmployee->find('all', array(
            'conditions' => array(
                'Employee.office_id' => $this->data['Office']['id']
            ),
            'recursive' => -1
        ));
        foreach ($dataEmployee as $item) {
            $modelEmployee->id = $item['Employee']['id'];
            if(
                isset($this->data['Office']['company_id'])
                && isset($this->data['Office']['company_group_id'])
            ) {
                if (
                    $modelEmployee->saveField('company_id', $this->data['Office']['company_id'], true)
                    && $modelEmployee->saveField('company_group_id', $this->data['Office']['company_group_id'], true)
                ) {
                    $isError = false;
                } else {
                    $isError = true;
                }
            }
        }
        if ($isError) {
            $datasource->rollback();
            return false;
        } else {
            $datasource->commit();
            return true;
        }
    }


}