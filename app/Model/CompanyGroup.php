<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 *
 * @property Image $Image
 */
class CompanyGroup extends AppModel
{
    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'company_groups';
    public $primaryKey = 'id';

    public $hasMany = array(
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'company_group_id',
        )
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
        'group_permission_id' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => false,
                'message' => 'Nhóm quyền không được để trống.'
            )
        )
    );

    public function beforeSave($options = array())
    {
        $data = array();
        if (!empty($this->data['CompanyGroup'])) {
            $data['GroupPermission']['name'] = $this->data['CompanyGroup']['name'];
            if (!empty($this->data['CompanyGroup']['group_permission_id']) ) {
                $data['GroupPermission']['id'] = $this->data['CompanyGroup']['group_permission_id'];
            }else{
                $data['GroupPermission']['id'] = '';
            }
            $modelGroupPermission = ClassRegistry::init('GroupPermission');
            $modelGroupPermission->set($data);
            if ($modelGroupPermission->save()) {
                $this->data['CompanyGroup']['group_permission_id'] = $modelGroupPermission->id;
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

}
