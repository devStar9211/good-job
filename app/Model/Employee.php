<?php

class Employee extends AppModel
{
    public $useTable = 'employees';
    public $primaryKey = 'id';
    public $virtualFields = array(
        'point_total' => 'SELECT SUM(point_details.value) FROM point_details where point_details.employee_id = Employee.id',
    );
    public $hasOne = array(

    );
    public $belongsTo = array(
        'Office' => array(
            'className' => 'Office',
            'foreignKey' => 'office_id'
        ),
        'Position' => array(
            'className' => 'Position',
            'foreignKey' => 'position_id'
        ),
        'Account' => array(
            'className' => 'Account',
            'foreignKey' => 'account_id'
        )
    );

    public $hasMany = array(
        'EmployeePrize' => array(
            'className' => 'EmployeePrize',
            'foreignKey' => 'employee_id'
        ),
        'EmployeeAllowance' => array(
            'className' => 'EmployeeAllowance',
            'foreignKey' => 'employee_id'
        ),
        'EmployeeRelationship' => array(
            'className' => 'EmployeeRelationship',
            'foreignKey' => 'employee_id'
        ),
        'EmployeeLicense' => array(
            'className' => 'EmployeeLicense',
            'foreignKey' => 'employee_id'
        ),
        'EmployeeOccupation' => array(
            'className' => 'EmployeeOccupation',
            'foreignKey' => 'employee_id'
        ),

        'PointBonus' => array(
            'className' => 'PointBonus',
            'foreignKey' => 'employee_id',
            'dependent' => true
        ),
        'PointDetail' => array(
            'className' => 'PointDetail',
            'foreignKey' => 'employee_id',
            'dependent' => true
        ),
    );

    public $hasAndBelongsToMany = array(
        'Occupation' =>
            array(
                'className' => 'Occupation',
                'joinTable' => 'employee_occupations',
                'foreignKey' => 'employee_id',
                'associationForeignKey' => 'occupation_id',
                'unique' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'finderQuery' => '',
                'with' => 'employee_occupations'
            ),
    );

    public $validate = array(
        'name' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 100 characters)'
            )
        ),
        'kana_name' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 100 characters)'
            )
        ),
        'basic_salary' => array(),
        'hourly_wage' => array(),
        'daily_wage' => array(),
        'public_transportation' => array(),
        'vehicle_cost' => array(),
        'one_way_transportation' => array(),
        'round_trip_transportation' => array(),
        'commute_route' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 255 characters)'
            )
        ),
        'social_insurance' => array(),
        'employment_insurance' => array(),
        'join_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'please fill out this field'
            ),
        ),
        'postal_code' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 30),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 30 characters)'
            )
        ),
        'prefecture' => array(),
        'municipality' => array(),
        'municipal_town' => array(),
        'phone' => array(),
        'basis_pension_number' => array(),
        'sos_contact_person' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 100 characters)'
            )
        ),
        'sos_contact_person_kana' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 100 characters)'
            )
        ),
        'sos_phone' => array(),
        'sos_address' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 100 characters)'
            )
        ),
        'bank_name' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 100 characters)'
            )
        ),
        'branch_name' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 255),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 255 characters)'
            )
        ),
        'account_number' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 50),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 50 characters)'
            )
        ),
        'account_name' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'allowEmpty' => true,
                'message' => 'the text you provided is too long (the maximum length is 100 characters)'
            )
        ),
        'employee_number' => array(
            'unique' => array(
                'rule' => 'isUnique',
                'required' => 'create',
                'message' => 'Employee number is duplicated'
            ),
        ),
    );

    private function l_c($l = null, $c = null)
    {
        $ms = '';

        if ($l !== null) {
            $ms .= '&nbsp;<span style="text-decoration:underline;">' . __('line') . ':&nbsp;' . $l . '</span>';
        }
        if ($c !== null) {
            $ms .= '&nbsp;<span style="text-decoration:underline;">' . __('column') . ':&nbsp;' . $c . '</span>';
        }

        return $ms;
    }

    public function import_employee($chunk)
    {
        $response = array(
            'status' => false,
            'message' => array()
        );
        $today = date("Y-m-d H:i:s");

        $datasource = $this->getDataSource();
        $isError = false;

        try {
            $datasource->begin();

            $_accounts = ClassRegistry::init('Account');
            $_company_group = ClassRegistry::init('CompanyGroup');
            $_company = ClassRegistry::init('Company');
            $_office = ClassRegistry::init('Office');
            $_hiring_patterns = ClassRegistry::init('HiringPattern');
            $_positions = ClassRegistry::init('Position');
            $_allowances = ClassRegistry::init('Allowance');
            $_employee_allowances = ClassRegistry::init('EmployeeAllowance');
            $_licenses = ClassRegistry::init('License');
            $_employee_licenses = ClassRegistry::init('EmployeeLicense');
            $_occupations = ClassRegistry::init('Occupation');
            $_employee_occupations = ClassRegistry::init('EmployeeOccupation');
            $_employee_relationships = ClassRegistry::init('EmployeeRelationship');
        } catch (Exception $e) {
            $isError = true;
            $response['message'][] = __('an unknown error occurred');
        }

        if (!$isError) {
            foreach ($chunk as $ix => $data) {
                $ix = $ix + 3;
                $skip = false;

                try {
                    if ($data['office_id']['value'] !== '') {
                        $v_office = $_office->find('first', array(
                            'conditions' => array(
                                'Office.id' => $data['office_id']['value']
                            ),
                            'contain' => array(
                                'Company' => array(
                                    'CompanyGroup'
                                )
                            ),
                            'limit' => 1,
                        ));
                    } else {
                        $v_office = 1;
                    }

                    if ($data['position_id']['value'] !== '') {
                        $v_position = $_positions->find('count', array(
                            'conditions' => ['id' => $data['position_id']['value']],
                            'limit' => 1,
                            'recursive' => -1
                        ));
                    } else {
                        $v_position = 1;
                    }

                    if (empty($v_office)) {
                        $isError = $skip = true;

                        $response['message'][] = (
                            __('couldn\'t find office with id: %s', $data['office_id']['value'])
                            . $this->l_c($data['office_id']['position']['line'], $data['office_id']['position']['col'])
                        );
                    }

                    if ($data['hiring_pattern_id']['value'] !== '') {
                        $v_hiring_pattern = $_hiring_patterns->find('count', array(
                            'conditions' => ['id' => $data['hiring_pattern_id']['value']],
                            'limit' => 1,
                            'recursive' => -1
                        ));
                    } else {
                        $v_hiring_pattern = 1;
                    }

                    if (empty($v_hiring_pattern)) {
                        $isError = $skip = true;
                        $response['message'][] = (
                            __('couldn\'t find hiring pattern with id: %s', $data['hiring_pattern_id']['value'])
                            . $this->l_c($data['hiring_pattern_id']['position']['line'], $data['hiring_pattern_id']['position']['col'])

                        );
                    }

                    if (empty($v_position)) {
                        $isError = $skip = true;
                        $response['message'][] = (
                            __('couldn\'t find position with id: %s', $data['position_id']['value'])
                            . $this->l_c($data['position_id']['position']['line'], $data['position_id']['position']['col'])

                        );
                    }

                    if (!empty($data['allowance_id']['value'])) {
                        foreach ($data['allowance_id']['value'] as $allowance) {
                            if (!empty($allowance)) {
                                $v_allowances = $_allowances->find('count', array(
                                    'conditions' => ['id' => $allowance],
                                    'recursive' => -1
                                ));

                                if (empty($v_allowances)) {
                                    $isError = $skip = true;
                                    $response['message'][] = (
                                        __('couldn\'t find allowance with id: %s', $allowance)
                                        . $this->l_c($data['allowance_id']['position']['line'], $data['allowance_id']['position']['col'])
                                    );
                                }
                            }
                        }
                    }

                    if (!empty($data['license_id']['value'])) {
                        foreach ($data['license_id']['value'] as $license) {
                            if (!empty($license)) {
                                $v_licenses = $_licenses->find('count', array(
                                    'conditions' => ['id' => intval($license)],
                                    'recursive' => -1
                                ));

                                if (empty($v_licenses)) {
                                    $isError = $skip = true;
                                    $response['message'][] = (
                                        __('couldn\'t find license with id: %s', $license)
                                        . $this->l_c($data['license_id']['position']['line'], $data['license_id']['position']['col'])
                                    );
                                }
                            }
                        }
                    }

                    if (!empty($data['occupation_id']['value'])) {
                        foreach ($data['occupation_id']['value'] as $occupation) {
                            if (!empty($occupation)) {
                                $v_occupations = $_occupations->find('count', array(
                                    'conditions' => ['id' => intval($occupation)],
                                    'recursive' => -1
                                ));

                                if (empty($v_occupations)) {
                                    $isError = $skip = true;
                                    $response['message'][] = (
                                        __('couldn\'t find occupation (%s)', $occupation)
                                        . $this->l_c($data['occupation_id']['position']['line'], $data['occupation_id']['position']['col'])
                                    );
                                }
                            }
                        }
                    }

                    if (!$skip) {
                        $company_id = null;
                        $company_group_id = null;
                        $group_permission_id = null;

                        if (!empty($data['office_id']['value'])) {
                            $company_id = $v_office['Company']['id'];
                            $company_group_id = $v_office['Company']['company_group_id'];
                            $group_permission_id = $v_office['Company']['CompanyGroup']['group_permission_id'];
                        } else {
                            if (class_exists('AuthComponent')) {
                                $user = AuthComponent::user();
                                if (!empty($user['Admin']['data_access_level'])) {
                                    $company_id = $user['Admin']['data_access_level'];
                                    $company_group = $_company->find('first', array(
                                        'conditions' => array('Company.id' => $company_id),
                                        'contain' => array(
                                            'CompanyGroup'
                                        )
                                    ));

                                    $company_group_id = $company_group['CompanyGroup']['id'];
                                    $group_permission_id = $company_group['CompanyGroup']['group_permission_id'];
                                }
                            }
                        }

                        if ($data['employee_register_only']['value'] == 1 && $data['have_sale_permission']['value'] == 1) {
                            $group_permission_id = EMPLOYEE_AND_SALE_GROUP;
                        } else if ($data['employee_register_only']['value'] == 1 && $data['have_sale_permission']['value'] == 0) {
                            $group_permission_id = EMPLOYEE_REGISTER_ONLY_GROUP;
                        } else if ($data['employee_register_only']['value'] == 0 && $data['have_sale_permission']['value'] == 1) {
                            $group_permission_id = SALE_GROUP;
                        }

                        $_accounts->create();
                        $account = $_accounts->save(array(
                            'email' => trim($data['email']['value']),
                            'username' => trim($data['username']['value']),
                            'password' => trim($data['password']['value']),
                            'group_permission_id' => $group_permission_id
                        ));

                        if ($account) {
                            $start_month = date('Y-m-01', strtotime($data['join_date']['value']));
                            $end_month = date('Y-m-t', strtotime($data['join_date']['value']));

//                            if (!empty($data['office_id']['value'])) {
                                $num_of_e_in_m = $this->find('count', array(
                                    'conditions' => array(
//                                        'office_id' => $data['office_id']['value'],
                                        'join_date BETWEEN ? AND ?' => array($start_month, $end_month)
                                    ),
                                    'order' => array(
                                        'join_date' => 'desc'
                                    ),
                                    'recursive' => -1
                                ));
//                            } else {
//                                $num_of_e_in_m = -1;
//                            }

                            $employee_number = (
                                date('ym', strtotime($data['join_date']['value']))
                                . str_pad(intval($num_of_e_in_m + 1), 2, '0', STR_PAD_LEFT)
                            );

                            $employee_number = trim($data['employee_number']['value']) !== '' ? trim($data['employee_number']['value']) : $employee_number;

                            $this->create();
                            $saved = $this->save(array(
                                'account_id' => $account['Account']['id'],
                                'company_id' => $company_id,
                                'office_id' => !empty($data['office_id']['value']) ? $data['office_id']['value'] : null,
                                'hiring_pattern_id' => !empty($data['hiring_pattern_id']['value']) ? $data['hiring_pattern_id']['value'] : null,
                                'position_id' => !empty($data['position_id']['value']) ? $data['position_id']['value'] : null,
                                'company_group_id' => $company_group_id,
                                'name' => trim($data['name']['value']),
                                'gender' => $data['gender']['value'],
                                'kana_name' => trim($data['kana_name']['value']),
                                'in_office' => $data['in_office']['value'],
                                'basic_salary' => $data['basic_salary']['value'] !== '' ? (double)$data['basic_salary']['value'] : null,
                                'daily_wage' => $data['daily_wage']['value'] !== '' ? (double)$data['daily_wage']['value'] : null,
                                'hourly_wage' => $data['hourly_wage']['value'] !== '' ? (double)$data['hourly_wage']['value'] : null,
                                'traffic_type' => implode(',', $data['traffic_type']['value']),
                                'public_transportation' => $data['public_transportation']['value'] !== '' ? (double)$data['public_transportation']['value'] : null,
                                'vehicle_cost' => $data['vehicle_cost']['value'] !== '' ? (double)$data['vehicle_cost']['value'] : null,
                                'one_way_transportation' => $data['one_way_transportation']['value'] !== '' ? (double)$data['one_way_transportation']['value'] : null,
                                'round_trip_transportation' => $data['round_trip_transportation']['value'] !== '' ? (double)$data['round_trip_transportation']['value'] : null,
                                'commute_route' => trim($data['commute_route']['value']),
                                'social_insurance' => $data['social_insurance']['value'] !== '' ? (double)$data['social_insurance']['value'] : null,
                                'employment_insurance' => $data['employment_insurance']['value'] !== '' ? (double)$data['employment_insurance']['value'] : null,
                                'join_date' => date('Y-m-d', strtotime($data['join_date']['value'])),
                                'postal_code' => trim($data['postal_code']['value']),
                                'prefecture' => trim($data['prefecture']['value']),
                                'municipality' => trim($data['municipality']['value']),
                                'municipal_town' => trim($data['municipal_town']['value']),
                                'phone' => trim($data['phone']['value']),
                                'basis_pension_number' => $data['basis_pension_number']['value'] !== '' ? (double)$data['basis_pension_number']['value'] : null,
                                'dob' => $data['dob']['value'] !== '' ? date('Y-m-d', strtotime($data['dob']['value'])) : null,
                                'avatar' => DEFAULT_AVATAR,
                                'avatar_original' => '',
                                'profile' => '',
                                'sos_contact_person' => trim($data['sos_contact_person']['value']),
                                'sos_contact_person_kana' => trim($data['sos_contact_person_kana']['value']),
                                'sos_phone' => trim($data['sos_phone']['value']),
                                'sos_address' => trim($data['sos_address']['value']),
                                'bank_name' => trim($data['bank_name']['value']),
                                'branch_name' => trim($data['branch_name']['value']),
                                'account_number' => trim($data['account_number']['value']),
                                'account_name' => trim($data['account_name']['value']),
                                'employee_number' => $employee_number,
                            ));

                            if ($saved) {
                                if (!empty($data['allowance_id']['value'])) {
                                    foreach ($data['allowance_id']['value'] as $allowance) {
                                        if (!empty($allowance)) {
                                            $_employee_allowances->create();
                                            if (
                                            !$_employee_allowances->save(array(
                                                'employee_id' => $saved['Employee']['id'],
                                                'allowance_id' => $allowance
                                            ))
                                            ) {
                                                $isError = true;
                                                break;
                                            }
                                        }
                                    }
                                }

                                if (!empty($data['license_id']['value'])) {
                                    foreach ($data['license_id']['value'] as $license) {
                                        if (!empty($license)) {
                                            $_employee_licenses->create();
                                            if (
                                            !$_employee_licenses->save(array(
                                                'employee_id' => $saved['Employee']['id'],
                                                'license_id' => intval($license)
                                            ))
                                            ) {
                                                $isError = true;
                                                break;
                                            }
                                        }
                                    }
                                }

                                if (!empty($data['occupation_id']['value'])) {
                                    foreach ($data['occupation_id']['value'] as $occupation) {
                                        if (!empty($occupation)) {
                                            $_employee_occupations->create();
                                            if (
                                            !$_employee_occupations->save(array(
                                                'employee_id' => $saved['Employee']['id'],
                                                'occupation_id' => intval($occupation)
                                            ))
                                            ) {
                                                $isError = true;
                                                break;
                                            }
                                        }
                                    }
                                }

                                foreach ($data['relationships'] as $relationship) {
                                    if (
                                        $relationship['name']['value'] !== ''
                                        && $relationship['name_kana']['value'] !== ''
                                        && $relationship['dob']['value'] !== ''
                                        && $relationship['relation']['value'] !== ''
                                        && $relationship['job']['value'] !== ''
                                    ) {
                                        $this->EmployeeRelationship->create();
                                        if (
                                        !$_employee_relationships->save(array(
                                            'employee_id' => $saved['Employee']['id'],
                                            'type' => 0,
                                            'postal_code' => '',
                                            'address' => '',
                                            'phone_number' => '',
                                            'name' => trim($relationship['name']['value']),
                                            'kana_name' => trim($relationship['name_kana']['value']),
                                            'dob' => date('Y-m-d', strtotime($relationship['dob']['value'])),
                                            'relationship' => trim($relationship['relation']['value']),
                                            'occupation' => trim($relationship['job']['value'])
                                        ))
                                        ) {
                                            $isError = true;
                                            break;
                                        }
                                    }
                                }
                            } else {
                                $isError = true;
                                if (!empty($this->validationErrors)) {
                                    foreach ($this->validationErrors as $alias => $msg) {
                                        $response['message'][] = (
                                            current($msg)
                                            . (
                                            isset($data[$alias])
                                                ? $this->l_c($data[$alias]['position']['line'], $data[$alias]['position']['col'])
                                                : $this->l_c($ix)
                                            )
                                        );
                                    }
                                }
                            }
                        } else {
                            $isError = true;
                            if (!empty($_accounts->validationErrors)) {
                                foreach ($_accounts->validationErrors as $alias => $msg) {
                                    $ms = (
                                        current($msg)
                                        . (
                                        isset($data[$alias])
                                            ? $this->l_c($data[$alias]['position']['line'], $data[$alias]['position']['col'])
                                            : $this->l_c($ix)
                                        )
                                    );

                                    if (!in_array($ms, $response['message'])) {
                                        $response['message'][] = $ms;
                                    }
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                    $isError = true;
                    $response['message'][] = __('an unknown error occurred') . $this->l_c($ix);
                }
            }
        }

        try {
            if ($isError) {
                $datasource->rollback();
                $response['status'] = false;
            } else {
                $datasource->commit();
                $response['status'] = true;
            }
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'][] = __('an unknown error occurred');
            $datasource->rollback();
        }

        return $response;
    }

    public function update_employee($chunk)
    {
        $response = array(
            'status' => false,
            'message' => array()
        );
        $today = date("Y-m-d H:i:s");

        $datasource = $this->getDataSource();
        $isError = false;

        try {
            $datasource->begin();

            $_accounts = ClassRegistry::init('Account');
            $_company_group = ClassRegistry::init('CompanyGroup');
            $_company = ClassRegistry::init('Company');
            $_office = ClassRegistry::init('Office');
            $_hiring_patterns = ClassRegistry::init('HiringPattern');
            $_positions = ClassRegistry::init('Position');
            $_allowances = ClassRegistry::init('Allowance');
            $_employee_allowances = ClassRegistry::init('EmployeeAllowance');
            $_licenses = ClassRegistry::init('License');
            $_employee_licenses = ClassRegistry::init('EmployeeLicense');
            $_occupations = ClassRegistry::init('Occupation');
            $_employee_occupations = ClassRegistry::init('EmployeeOccupation');
            $_employee_relationships = ClassRegistry::init('EmployeeRelationship');
        } catch (Exception $e) {
            $isError = true;
            $response['message'][] = __('an unknown error occurred');
        }

        if (!$isError) {
            foreach ($chunk as $ix => $data) {
                $ix = $ix + 3;
                $skip = false;

                try {
                    $employee = $this->find('list', array(
                        'fields' => array('account_id'),
                        'conditions' => array(
                            'id' => $data['id']['value']
                        ),
                        'limit' => 1,
                        'recursive' => -1
                    ));
                    if (empty($employee)) {
                        $isError = $skip = true;
                        $response['message'][] = (
                            __('couldn\'t find employee with id: %s', $data['id']['value'])
                            . $this->l_c($data['id']['position']['line'], $data['id']['position']['col'])

                        );
                    }

                    if ($data['office_id']['value'] !== '') {
                        $v_office = $_office->find('first', array(
                            'conditions' => array(
                                'Office.id' => $data['office_id']['value']
                            ),
                            'contain' => array(
                                'Company' => array(
                                    'CompanyGroup'
                                )
                            ),
                            'limit' => 1,
                        ));
                    } else {
                        $v_office = 1;
                    }

                    if ($data['position_id']['value'] !== '') {
                        $v_position = $_positions->find('count', array(
                            'conditions' => ['id' => $data['position_id']['value']],
                            'limit' => 1,
                            'recursive' => -1
                        ));
                    } else {
                        $v_position = 1;
                    }

                    if ($data['hiring_pattern_id']['value'] !== '') {
                        $v_hiring_pattern = $_hiring_patterns->find('count', array(
                            'conditions' => ['id' => $data['hiring_pattern_id']['value']],
                            'limit' => 1,
                            'recursive' => -1
                        ));
                    } else {
                        $v_hiring_pattern = 1;
                    }

                    if (empty($v_office)) {
                        $isError = $skip = true;

                        $response['message'][] = (
                            __('couldn\'t find office with id: %s', $data['office_id']['value'])
                            . $this->l_c($data['office_id']['position']['line'], $data['office_id']['position']['col'])
                        );
                    }

                    if (empty($v_hiring_pattern)) {
                        $isError = $skip = true;
                        $response['message'][] = (
                            __('couldn\'t find hiring pattern with id: %s', $data['hiring_pattern_id']['value'])
                            . $this->l_c($data['hiring_pattern_id']['position']['line'], $data['hiring_pattern_id']['position']['col'])

                        );
                    }

                    if (empty($v_position)) {
                        $isError = $skip = true;
                        $response['message'][] = (
                            __('couldn\'t find position with id: %s', $data['position_id']['value'])
                            . $this->l_c($data['position_id']['position']['line'], $data['position_id']['position']['col'])

                        );
                    }

                    if (!empty($data['allowance_id']['value'])) {
                        foreach ($data['allowance_id']['value'] as $allowance) {
                            if (!empty($allowance)) {
                                $v_allowances = $_allowances->find('count', array(
                                    'conditions' => ['id' => $allowance],
                                    'recursive' => -1
                                ));

                                if (empty($v_allowances)) {
                                    $isError = $skip = true;
                                    $response['message'][] = (
                                        __('couldn\'t find allowance with id: %s', $allowance)
                                        . $this->l_c($data['allowance_id']['position']['line'], $data['allowance_id']['position']['col'])
                                    );
                                }
                            }
                        }
                    }

                    if (!empty($data['license_id']['value'])) {
                        foreach ($data['license_id']['value'] as $license) {
                            if (!empty($license)) {
                                $v_licenses = $_licenses->find('count', array(
                                    'conditions' => ['id' => intval($license)],
                                    'recursive' => -1
                                ));

                                if (empty($v_licenses)) {
                                    $isError = $skip = true;
                                    $response['message'][] = (
                                        __('couldn\'t find license with id: %s', $license)
                                        . $this->l_c($data['license_id']['position']['line'], $data['license_id']['position']['col'])
                                    );
                                }
                            }
                        }
                    }

                    if (!empty($data['occupation_id']['value'])) {
                        foreach ($data['occupation_id']['value'] as $occupation) {
                            if (!empty($occupation)) {
                                $v_occupations = $_occupations->find('count', array(
                                    'conditions' => ['id' => intval($occupation)],
                                    'recursive' => -1
                                ));

                                if (empty($v_occupations)) {
                                    $isError = $skip = true;
                                    $response['message'][] = (
                                        __('couldn\'t find occupation (%s)', $occupation)
                                        . $this->l_c($data['occupation_id']['position']['line'], $data['occupation_id']['position']['col'])
                                    );
                                }
                            }
                        }
                    }

                    if (!$skip) {
                        $company_id = null;
                        $company_group_id = null;
                        $group_permission_id = null;

                        if (!empty($data['office_id']['value'])) {
                            $company_id = $v_office['Company']['id'];
                            $company_group_id = $v_office['Company']['company_group_id'];
                            $group_permission_id = $v_office['Company']['CompanyGroup']['group_permission_id'];
                        } else {
                            if (class_exists('AuthComponent')) {
                                $user = AuthComponent::user();
                                if (!empty($user['Admin']['data_access_level'])) {
                                    $company_id = $user['Admin']['data_access_level'];
                                    $company_group = $_company->find('first', array(
                                        'conditions' => array('Company.id' => $company_id),
                                        'contain' => array(
                                            'CompanyGroup'
                                        )
                                    ));

                                    $company_group_id = $company_group['CompanyGroup']['id'];
                                    $group_permission_id = $company_group['CompanyGroup']['group_permission_id'];
                                }
                            }
                        }

                        if ($data['employee_register_only']['value'] == 1 && $data['have_sale_permission']['value'] == 1) {
                            $group_permission_id = EMPLOYEE_AND_SALE_GROUP;
                        } else if ($data['employee_register_only']['value'] == 1 && $data['have_sale_permission']['value'] == 0) {
                            $group_permission_id = EMPLOYEE_REGISTER_ONLY_GROUP;
                        } else if ($data['employee_register_only']['value'] == 0 && $data['have_sale_permission']['value'] == 1) {
                            $group_permission_id = SALE_GROUP;
                        }

                        $account = $_accounts->find('first', array(
                            'conditions' => array(
                                'id' => current($employee)
                            ),
                            'recursive' => -1
                        ));

                        $update_account = array();
                        if ($account['Account']['group_permission_id'] != $group_permission_id) {
                            $update_account['group_permission_id'] = $group_permission_id;
                        }
                        if ($data['email']['value'] != $account['Account']['email']) {
                            $update_account['email'] = $data['email']['value'];
                        }
                        if ($data['username']['value'] != $account['Account']['username']) {
                            $update_account['username'] = $data['username']['value'];
                        }
                        if (!empty($data['password']['value'])) {
                            $update_account['password'] = $data['password']['value'];
                        }

                        if (
                        !empty($update_account)
                        ) {
                            $_accounts->set(array('id' => $account['Account']['id']));
                            if (
                            !$_accounts->save(array('Account' => $update_account))
                            ) {
                                $isError = true;
                                if (!empty($_accounts->validationErrors)) {
                                    foreach ($_accounts->validationErrors as $alias => $msg) {
                                        $ms = (
                                            current($msg)
                                            . (
                                            isset($data[$alias])
                                                ? $this->l_c($data[$alias]['position']['line'], $data[$alias]['position']['col'])
                                                : $this->l_c($ix)
                                            )
                                        );

                                        if (!in_array($ms, $response['message'])) {
                                            $response['message'][] = $ms;
                                        }
                                    }
                                }
                            }
                        }

                        $this->id = $data['id']['value'];
                        $saved = $this->save(array(
                            'Employee' => array(
                                'company_id' => $company_id,
                                'office_id' => !empty($data['office_id']['value']) ? $data['office_id']['value'] : null,
                                'position_id' => !empty($data['position_id']['value']) ? $data['position_id']['value'] : null,
                                'hiring_pattern_id' => !empty($data['hiring_pattern_id']['value']) ? $data['hiring_pattern_id']['value'] : null,
                                'company_group_id' => $company_group_id,
                                'name' => trim($data['name']['value']),
                                'gender' => $data['gender']['value'],
                                'kana_name' => trim($data['kana_name']['value']),
                                'in_office' => $data['in_office']['value'],
                                'basic_salary' => $data['basic_salary']['value'] !== '' ? (double)$data['basic_salary']['value'] : null,
                                'daily_wage' => $data['daily_wage']['value'] !== '' ? (double)$data['daily_wage']['value'] : null,
                                'hourly_wage' => $data['hourly_wage']['value'] !== '' ? (double)$data['hourly_wage']['value'] : null,
                                'traffic_type' => implode(',', $data['traffic_type']['value']),
                                'public_transportation' => $data['public_transportation']['value'] !== '' ? (double)$data['public_transportation']['value'] : null,
                                'vehicle_cost' => $data['vehicle_cost']['value'] !== '' ? (double)$data['vehicle_cost']['value'] : null,
                                'one_way_transportation' => $data['one_way_transportation']['value'] !== '' ? (double)$data['one_way_transportation']['value'] : null,
                                'round_trip_transportation' => $data['round_trip_transportation']['value'] !== '' ? (double)$data['round_trip_transportation']['value'] : null,
                                'commute_route' => trim($data['commute_route']['value']),
                                'social_insurance' => $data['social_insurance']['value'] !== '' ? (double)$data['social_insurance']['value'] : null,
                                'employment_insurance' => $data['employment_insurance']['value'] !== '' ? (double)$data['employment_insurance']['value'] : null,
                                'join_date' => date('Y-m-d', strtotime($data['join_date']['value'])),
                                'postal_code' => trim($data['postal_code']['value']),
                                'prefecture' => trim($data['prefecture']['value']),
                                'municipality' => trim($data['municipality']['value']),
                                'municipal_town' => trim($data['municipal_town']['value']),
                                'phone' => trim($data['phone']['value']),
                                'basis_pension_number' => $data['basis_pension_number']['value'] !== '' ? (double)$data['basis_pension_number']['value'] : null,
                                'dob' => $data['dob']['value'] !== '' ? date('Y-m-d', strtotime($data['dob']['value'])) : null,
                                'sos_contact_person' => trim($data['sos_contact_person']['value']),
                                'sos_contact_person_kana' => trim($data['sos_contact_person_kana']['value']),
                                'sos_phone' => trim($data['sos_phone']['value']),
                                'sos_address' => trim($data['sos_address']['value']),
                                'bank_name' => trim($data['bank_name']['value']),
                                'branch_name' => trim($data['branch_name']['value']),
                                'account_number' => trim($data['account_number']['value']),
                                'account_name' => trim($data['account_name']['value']),
                                'employee_number' => trim($data['employee_number']['value']),
                            )
                        ));

                        if (!$saved) {
                            $isError = true;
                            if (!empty($this->validationErrors)) {
                                foreach ($this->validationErrors as $alias => $msg) {
                                    $response['message'][] = (
                                        current($msg)
                                        . (
                                        isset($data[$alias])
                                            ? $this->l_c($data[$alias]['position']['line'], $data[$alias]['position']['col'])
                                            : $this->l_c($ix)
                                        )
                                    );
                                }
                            }
                        }

                        $_employee_allowances->unbindModel(array('belongsTo' => array('Employee', 'Allowance')));
                        if (
                        $_employee_allowances->deleteAll(array(
                            'employee_id' => $data['id']['value']
                        ))
                        ) {
                            if (!empty($data['allowance_id']['value'])) {
                                foreach ($data['allowance_id']['value'] as $allowance) {
                                    if (!empty($allowance)) {
                                        $_employee_allowances->create();
                                        if (
                                        !$_employee_allowances->save(array(
                                            'employee_id' => $data['id']['value'],
                                            'allowance_id' => $allowance
                                        ))
                                        ) {
                                            $isError = true;
                                            $response['message'][] = (
                                                __('couldn\'t update allowances')
                                                . $this->l_c($data['allowance_id']['position']['line'], $data['allowance_id']['position']['col'])
                                            );
                                            break;
                                        }
                                    }
                                }
                            }
                        } else {
                            $isError = true;
                            $response['message'][] = (
                                __('couldn\'t update allowances')
                                . $this->l_c($data['allowance_id']['position']['line'], $data['allowance_id']['position']['col'])
                            );
                        }

                        $_employee_licenses->unbindModel(array('belongsTo' => array('Employee', 'License')));
                        if (
                        $_employee_licenses->deleteAll(array(
                            'employee_id' => $data['id']['value']
                        ))
                        ) {
                            if (!empty($data['license_id']['value'])) {
                                foreach ($data['license_id']['value'] as $license) {
                                    if (!empty($license)) {
                                        $_employee_licenses->create();
                                        if (
                                        !$_employee_licenses->save(array(
                                            'employee_id' => $data['id']['value'],
                                            'license_id' => intval($license)
                                        ))
                                        ) {
                                            $isError = true;
                                            $response['message'][] = (
                                                __('couldn\'t update licenses')
                                                . $this->l_c($data['license_id']['position']['line'], $data['license_id']['position']['col'])
                                            );
                                            break;
                                        }
                                    }
                                }
                            }
                        } else {
                            $isError = true;
                            $response['message'][] = (
                                __('couldn\'t update licenses')
                                . $this->l_c($data['license_id']['position']['line'], $data['license_id']['position']['col'])
                            );
                        }

                        $_employee_occupations->unbindModel(array('belongsTo' => array('Employee', 'Occupation')));
                        if (
                        $_employee_occupations->deleteAll(array(
                            'employee_id' => $data['id']['value']
                        ))
                        ) {
                            if (!empty($data['occupation_id']['value'])) {
                                foreach ($data['occupation_id']['value'] as $occupation) {
                                    if (!empty($occupation)) {
                                        $_employee_occupations->create();
                                        if (
                                        !$_employee_occupations->save(array(
                                            'employee_id' => $data['id']['value'],
                                            'occupation_id' => intval($occupation)
                                        ))
                                        ) {
                                            $isError = true;
                                            $response['message'][] = (
                                                __('couldn\'t update occupations')
                                                . $this->l_c($data['occupation_id']['position']['line'], $data['occupation_id']['position']['col'])
                                            );
                                            break;
                                        }
                                    }
                                }
                            }
                        } else {
                            $isError = true;
                            $response['message'][] = (
                                __('couldn\'t update occupations')
                                . $this->l_c($data['occupation_id']['position']['line'], $data['occupation_id']['position']['col'])
                            );
                        }

                        $_employee_relationships->unbindModel(array('belongsTo' => array('Employee')));
                        if (
                        $_employee_relationships->deleteAll(array(
                            'employee_id' => $data['id']['value']
                        ))
                        ) {
                            foreach ($data['relationships'] as $relationship) {
                                if (
                                    $relationship['name']['value'] !== ''
                                    && $relationship['name_kana']['value'] !== ''
                                    && $relationship['dob']['value'] !== ''
                                    && $relationship['relation']['value'] !== ''
                                    && $relationship['job']['value'] !== ''
                                ) {
                                    $this->EmployeeRelationship->create();
                                    if (
                                    !$_employee_relationships->save(array(
                                        'employee_id' => $data['id']['value'],
                                        'type' => 0,
                                        'postal_code' => '',
                                        'address' => '',
                                        'phone_number' => '',
                                        'name' => trim($relationship['name']['value']),
                                        'kana_name' => trim($relationship['name_kana']['value']),
                                        'dob' => date('Y-m-d', strtotime($relationship['dob']['value'])),
                                        'relationship' => trim($relationship['relation']['value']),
                                        'occupation' => trim($relationship['job']['value'])
                                    ))
                                    ) {
                                        $isError = true;
                                        break;
                                    }
                                }
                            }
                        } else {
                            $isError = true;
                        }
                    }
                } catch (Exception $e) {
                    $isError = true;
                    $response['message'][] = __('an unknown error occurred') . $this->l_c($ix);
                }
            }
        }

        try {
            if ($isError) {
                $datasource->rollback();
                $response['status'] = false;
            } else {
                $datasource->commit();
                $response['status'] = true;
            }
        } catch (Exception $e) {
            $response['status'] = false;
            $response['message'][] = __('an unknown error occurred');
            $datasource->rollback();
        }

        return $response;
    }

    public function get_current_rank($employee_id, $e_working_time)
    {
        $PointRank = ClassRegistry::init('PointRank');

        $employee_point = $this->find('first', array(
            'contain'=>array(
                'Occupation',
            ),
            'conditions'=>array(
                'id'=>$employee_id
            )
        ));

        if(empty($employee_point)){
            return false;
        }
        if($e_working_time == null) $e_working_time= $employee_point['Employee']['working_time_id'];

        $employee_occupations = array();
        if (!empty($employee_point['Occupation'])) {
            foreach ($employee_point['Occupation'] as $_occupation) {
                $employee_occupations[] = $_occupation['id'];
            }
        }

        $point_detail = !empty($employee_point['Employee']['point_total']) ? $employee_point['Employee']['point_total'] : '';

        $ranks = $PointRank->find('all', array(
            'conditions' => array(
                'PointRank.company_group_id' => $employee_point['Employee']['company_group_id']
            ),
            'contain' => array(
                'Occupation',
                'Stage'
            ),
            'order' => array(
                'PointRank.level' => 'desc'
            )
        ));
        $point = $point_detail;

        $i = 0;
        $color = '';
        $rank_card = '';
        $stage_name = '';
        $necessary_point = '';
        $subsidize_rate = '';
        $rank_name = '';
        $previous_level = '';
        $previous_necessary_point = '';
        $necessary_point_next = '';
        $necessary_point_next_rate = '';

        foreach ($ranks as $key => $_rank) {
            $i++;

            $rank_occupations = array();
            foreach ($_rank['Occupation'] as $_occupation) {
                $rank_occupations[] = $_occupation['id'];
            }
            $matching = array_intersect($rank_occupations, $employee_occupations);

            $subsidize_rate = $_rank['PointRank']['subsidize_rate'];
            $rank_name = $_rank['PointRank']['rank_name'];
            $necessary_point = $_rank['PointRank']['necessary_point'];
            $rank_card = $_rank['PointRank']['rank_card'];
            $stage_name = $_rank['Stage']['name'];
            $color = $_rank['PointRank']['color'];

            if ($previous_level != '') {
                $necessary_point_next = $previous_necessary_point - $point;
                $necessary_point_next_rate = round(($point / $previous_necessary_point) * 100, 0);
            }
            if (!empty($matching) || ($_rank['PointRank']['working_time_id'] == $e_working_time && $e_working_time > 0)) {
                break;
            }
            if ($_rank['PointRank']['necessary_point'] <= $point) {
                if (
                    $_rank['PointRank']['rank_name'] == 'A'
                    ||
                    (
                        $_rank['PointRank']['working_time_id'] > 0
                        && $_rank['PointRank']['working_time_id'] != $e_working_time
                    )
                ) {
                    continue;
                } else {
                    break;
                }
            }
            $previous_level = $_rank['PointRank']['level'];
            $previous_necessary_point = $_rank['PointRank']['necessary_point'];
        }
        $response = array(
            'subsidize_rate' => $subsidize_rate,
            'rank_name' => $rank_name,
            'necessary_point' => $necessary_point,
            'rank_card' => $rank_card,
            'stage_name' => $stage_name,
            'necessary_point_next' => $necessary_point_next,
            'color' => $color,
            'necessary_point_next_rate' => $necessary_point_next_rate
        );
        return $response;
    }

}