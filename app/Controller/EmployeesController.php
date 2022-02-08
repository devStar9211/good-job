<?php

App::uses('CakeEmail', 'Network/Email');
App::uses('CsvContentChunk', 'Controller/Component');

class EmployeesController extends AppController
{
    public $uses = array('Employee', 'Company', 'Office', 'Position', 'CompanyGroup', 'Account', 'Allowance', 'EmployeeRelationship', 'License', 'Occupation', 'EmployeeLicense', 'EmployeeOccupation', 'EmployeeAllowance', 'EmployeePrize', 'HiringPattern', 'UserSession', 'PointDetail', 'EmailNotification');

    public $components = array('CsvContentChunk');

    public function beforeFilter()
    {
        parent::beforeFilter();

        if (class_exists('AuthComponent')) {
            $user = AuthComponent::user();
            if (!empty($user) && !empty($user['Admin']['data_access_level'])) {
                $this->Employee->validate['company_id'] = array(
                    'notEmpty' => array(
                        'rule' => 'notEmpty',
                        'message' => 'please fill out this field'
                    ),
                );
            }
        }
    }

    private $csv_data_format = array(
        'id' => ['ID', '従業員ID'],
        'created' => ['Created', '登録日'],
        'updated' => ['Updated', '変更日'],
        'username' => ['Username', 'ユーザ名'],
        'password' => ['Password', 'パスワード', 'パスワード（古いパスワードのための空白）'],
        'in_office' => ['In Office', '在職フラグ'],
        'office_id' => ['Office', '事業所（ID）'],
        'hiring_pattern_id' => ['Hiring Pattern', '雇用形態（ID）'],
        'employee_number' => ['Employee Number', '従業員番号'],
        'name' => ['Name', '氏名'],
        'kana_name' => ['Kana Name', '氏名かな'],
        'position_id' => ['Position', '役職（ID）'],
        'license_id' => ['Licenses', '資格（ID）'],
        'occupation_id' => ['Occupations', '職種（職種ID）'],
        'basic_salary' => ['Basic Salary', '基本給'],
        'hourly_wage' => ['Hourly Wage', '時給'],
        'daily_wage' => ['Daily Wage', '日給'],
        'allowance_id' => ['Allowances', '手当（ID）'],
        'traffic_type' => ['Traffic Type', '移動手段'],
        'public_transportation' => ['Public Transportation', '公共交通機関（定期月額）'],
        'vehicle_cost' => ['Vehicle Cost', '車両費（出勤毎の金額）'],
        'one_way_transportation' => ['One Way Transportation', '片道交通費'],
        'round_trip_transportation' => ['Round Trip Transportation', '往復交通費'],
        'commute_route' => ['Commute Route', '通勤ルート'],
        'social_insurance' => ['Social Insurance', '社会保険料金額'],
        'employment_insurance' => ['Employment Insurance', '労働保険料金'],
        'join_date' => ['Join Date', '入社年月日'],
        'postal_code' => ['Postal Code', '郵便番号'],
        'prefecture' => ['Prefecture', '都道府県'],
        'municipality' => ['Municipality', '市区町村'],
        'municipal_town' => ['Municipal Town', '市区町村以降'],
        'dob' => ['Date of Birth', '生年月日'],
        'gender' => ['Gender', '性別'],
        'email' => ['Email', 'Eメールアドレス'],
        'phone' => ['Phone', '電話番号'],
        'basis_pension_number' => ['Basis Pension Number', '基礎年金番号'],
        'bank_name' => ['Bank Name', '銀行名'],
        'branch_name' => ['Branch Name', '支店名'],
        'account_number' => ['Bccount Number', '口座番号'],
        'account_name' => ['Bccount Name', '口座名義'],
        'sos_contact_person' => ['Emergency Contact Person', '緊急連絡先氏名'],
        'sos_contact_person_kana' => ['Emergency Contact Person Kana', '緊急連絡先かな'],
        'sos_phone' => ['Emergency Phone', '緊急連絡先電話番号'],
        'sos_address' => ['Emergency Address', '緊急連絡先住所'],
        'employee_relationships1_name' => ['Name (Relationships-1)', '扶養1名前'],
        'employee_relationships1_name_kana' => ['Name Kana (Relationships-1)', '扶養1名前かな'],
        'employee_relationships1_dob' => ['Dob (Relationships-1)', '扶養1生年月日'],
        'employee_relationships1_relation' => ['Relation (Relationships-1)', '扶養1関係'],
        'employee_relationships1_job' => ['Occupation (Relationships-1)', '扶養1仕事'],
        'employee_relationships2_name' => ['Name (Relationships-2)', '扶養2名前'],
        'employee_relationships2_name_kana' => ['Name Kana (Relationships-2)', '扶養2名前かな'],
        'employee_relationships2_dob' => ['Dob (Relationships-2)', '扶養2生年月日'],
        'employee_relationships2_relation' => ['Relation (Relationships-2)', '扶養2関係'],
        'employee_relationships2_job' => ['Occupation (Relationships-2)', '扶養2仕事'],
        'employee_relationships3_name' => ['Name (Relationships-3)', '扶養3名前'],
        'employee_relationships3_name_kana' => ['Name Kana (Relationships-3)', '扶養3名前かな'],
        'employee_relationships3_dob' => ['Dob (Relationships-3)', '扶養3生年月日'],
        'employee_relationships3_relation' => ['Relation (Relationships-3)', '扶養3関係'],
        'employee_relationships3_job' => ['Occupation (Relationships-3)', '扶養3仕事'],
        'employee_relationships4_name' => ['Name (Relationships-4)', '扶養4名前'],
        'employee_relationships4_name_kana' => ['Name Kana (Relationships-4)', '扶養4名前かな'],
        'employee_relationships4_dob' => ['Dob (Relationships-4)', '扶養4生年月日'],
        'employee_relationships4_relation' => ['Relation (Relationships-4)', '扶養4関係'],
        'employee_relationships4_job' => ['Occupation (Relationships-4)', '扶養4仕事'],
        'employee_register_only' => ['Employee registration authority only', '従業員登録権限のみ'],
        'have_sale_permission' => ['Sales registration authority', '売上登録権限'],
    );

    private function csv_format_title()
    {
        $arr = array();
        foreach ($this->csv_data_format as $alias => $title) {
            $arr[$title[0]] = $title[1];
        }
        return $arr;
    }

    private function csv_format_alias()
    {
        $arr = array();
        foreach ($this->csv_data_format as $alias => $title) {
            $arr[$alias] = $title[0];
        }
        return $arr;
    }

    public function admin_index()
    {
        $this->set('title_for_layout', '従業員一覧');

        $companies = $offices = $data = $conditions = array();

        // switch for super admin or company admin
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));
        if (count($companies) == 1) {
            $offices = $this->Office->find('list', array(
                'fields' => array('id', 'name'),
                'conditions' => array(
                    'company_id' => key($companies)
                ),
                'recursive' => -1
            ));

            $data['company_id'] = key($companies);
        }

        if (!empty($_GET['company'])) {
            $conditions['Employee.company_id'] = $data['company_id'] = $_GET['company'];
            $offices = $this->Office->find('list', array(
                'fields' => array('id', 'name'),
                'conditions' => array(
                    'company_id' => $_GET['company']
                ),
                'recursive' => -1
            ));

            if (
                !empty($_GET['office'])
                && in_array($_GET['office'], array_keys($offices))
            ) {
                $conditions['Employee.office_id'] = $data['office_id'] = $_GET['office'];
            }
        }
        // filter by name
        $search_name_default = '';
        if (isset($_GET['name']) && $_GET['name'] != '') {
            $conditions['Office.name like'] = '%' . $_GET['name'] . '%';
            $conditions['Employee.name'] = $data['name'] = $_GET['name'];
        } else {
            $data['name'] = '';
        }


        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions' => $conditions,
            'contain' => array('Account'),
            'paramType' => 'querystring',
            'order' => array(
                'Employee.id' => 'desc'
            ),
        );

        $employees = $this->Paginator->paginate('Employee');

        $company_ids = $office_ids = array();
        foreach ($employees as $key => $employee) {
            $company_ids[$key] = $employee['Employee']['company_id'];
            $office_ids[$key] = $employee['Employee']['office_id'];
        }

        $e_company = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'conditions' => array(
                'id' => $company_ids
            )
        ));

        foreach ($e_company as $id => $name) {
            foreach ($company_ids as $ix => $company) {
                if ($company == $id) {
                    $company_ids[$ix] = array(
                        'id' => $id,
                        'name' => $name
                    );
                }
            }
        }

        $e_office = $this->Office->find('list', array(
            'fields' => array('id', 'name'),
            'conditions' => array(
                'id' => $office_ids
            )
        ));

        foreach ($e_office as $id => $name) {
            foreach ($office_ids as $ix => $office) {
                if ($office == $id) {
                    $office_ids[$ix] = array(
                        'id' => $id,
                        'name' => $name
                    );
                }
            }
        }

        foreach ($employees as $ix => $employee) {
            $employees[$ix]['Company'] = $company_ids[$ix];
            $employees[$ix]['Office'] = $office_ids[$ix];
        }

        $this->set(compact('companies', 'offices', 'employees', 'data'));
    }

    public function admin_generate_list_employee()
    {
        $this->autoRender = false;

        $response = array(
            'status' => 0,
            'message' => ''
        );

        if ($this->request->is('ajax')) {
            $req = $this->request->data;

            if (!empty($req)) {
                $data = array();

                // switch for super admin or company admin

                $conditions = array();

                if (!empty($req['office'])) {
                    $conditions['office_id'] = $req['office'];
                }

                $employees = $this->Employee->find('list', array(
                    'fields' => array('id', 'name'),
                    'conditions' => $conditions,
                    'order' => array(
                        'Employee.name' => 'asc'
                    )
                ));

                foreach ($employees as $id => $name) {
                    $data[] = array(
                        'name' => $name,
                        'url' => Router::url(array('controller' => 'Employees', 'action' => 'admin_edit', $id))
                    );
                }

                $response['status'] = 1;
                $response['data'] = $data;
            }
        }

        return json_encode($response);
    }

    public function admin_add($office_id = null)
    {
        $this->set('title_for_layout', '従業員登録');

        $companies = $offices = $positions = $licenses = $occupation = $allowances = array();

        $data = array();

        if ($office_id != null) {
            $office = $this->Office->find('first', array(
                'conditions' => array(
                    'id' => $office_id
                ),
                'order' => array(
                    'created' => 'asc'
                ),
                'recursive' => -1
            ));

            if (!empty($office)) {
                $offices = $this->Office->find('list', array(
                    'fields' => array('id', 'name'),
                    'conditions' => array(
                        'company_id' => $office['Office']['company_id']
                    ),
                    'recursive' => -1
                ));

                $data = array(
                    'office_id' => $office['Office']['id'],
                    'company_id' => $office['Office']['company_id']
                );
            }
        }

        // switch for super admin or company admin
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));
        if (count($companies) == 1) {
            $offices = $this->Office->find('list', array(
                'fields' => array('id', 'name'),
                'conditions' => array(
                    'company_id' => key($companies)
                ),
                'recursive' => -1
            ));

            $data['company_id'] = key($companies);
        }

        $positions = $this->Position->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        $licenses = $this->License->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        $occupations = $this->Occupation->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        $allowances = $this->Allowance->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        $hiring_patterns = $this->HiringPattern->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        if ($this->request->is('post')) {
            $req = $this->request->data['Employee'];
            $req_account = $this->request->data['Account'];
            $req_relation = isset($this->request->data['Relation']) ? $this->request->data['Relation'] : array();

            if (!empty($req_relation)) {
                unset($req_relation['key']);
            }

            if (!empty($req) && !empty($req_account)) {
                $saved = $this->admin_saveEmployee($req, $req_account, $req_relation);

                if ($saved) {
                    $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');

                    $company_admin = $this->Account->find('all', array(
                        'conditions' => array(
                            'Admin.data_access_level' => $saved['Employee']['company_id']
                        ),
                        'contain' => 'Admin'
                    ));

                    $super_admin_email = $this->Auth->user('email');
                    $company_admin_email = array();
                    foreach ($company_admin as $ix => $ca) {
                        $company_admin_email[$ix] = $ca['Account']['email'];
                    }
                    $employee_email = $saved['Employee']['account']['email'];

                    $Email = new CakeEmail('forgot_password');
                    $Email->from(array($Email->config()['username'] => 'Caregiver Japan'));
                    $Email->subject('Caregiver Japan 様日次決算システム');
                    $Email->emailFormat('both');

                    $warning = __('couldn\'t send email to') . ' ';
                    $isWarning = false;

                    // to employee
                     try {
                     	$Email->to($employee_email);
                     	$Email->template('to_employee_new_employee_registry', 'default');
                     	$Email->viewVars(array('employee' => $saved['Employee']));
                     	$Email->send();
                     } catch(Exception $e) {
                     	$warning .= __('%s(employee)', $employee_email) .', ';
                     	$isWarning = true;
                     }

                    // to company admin
                    /*foreach ($company_admin_email as $ix => $cae) {
                        if (!empty($cae)) {
                            try {
                                $Email->to($cae);
                                $Email->template('to_admin_new_employee_registry', 'default');
                                $Email->viewVars(array('employee' => $saved['Employee'], 'admin' => $company_admin[$ix]['Admin']));
                                $Email->send();
                            } catch (Exception $e) {
                                $warning .= __('%s(company manager)', $cae) . ', ';
                                $isWarning = true;
                            }
                        }
                    }*/

                    // to super admin
                    /*if (
                        !in_array($super_admin_email, $company_admin_email)
                        && !empty($super_admin_email)
                    ) {
                        try {
                            $Email->to($super_admin_email);
                            $Email->template('to_admin_new_employee_registry', 'default');
                            $Email->viewVars(array('employee' => $saved['Employee'], 'admin' => $this->Auth->user('Admin')));
                            $Email->send();
                        } catch (Exception $e) {
                            $warning .= __('%s(admin)', $super_admin_email) . ', ';
                            $isWarning = true;
                        }
                    }*/

                    // to employee registration notification setting

                    $dataEmail = $this->EmailNotification->find("all", array(
                        'conditions' => array(
                            'EmailNotification.company_id' => $saved['Employee']['company_id']
                        )
                    ));

                    if (!empty($dataEmail)) {
                        $email_setting = array();
                        foreach ($dataEmail as $ix => $ca) {
                            $email_setting[$ix] = $ca['EmailNotification']['name'];
                        }

                        try {
                            $Email->to($email_setting);
                            $Email->template('to_admin_new_employee_registry', 'default');
                            $Email->viewVars(array('employee' => $saved['Employee'], 'admin' => $this->Auth->user('Admin')));
                            $Email->send();
                        } catch (Exception $e) {
                            $warning .= __('%s(admin)', $email_setting) . ', ';
                            $isWarning = true;
                        }
                    }

                    if ($isWarning) {
                        $warning = rtrim($warning, ', ');
                        $this->Session->setFlash($warning, 'flashmessage', array('type' => 'warning'), 'warning');
                    }

                    if ($this->Base->isEmployeeRegisterOnly($this->Auth->user())) {
                        $this->redirect('/admin/Employees/add');
                    } else {
                        $this->redirect(array('action' => 'admin_edit', $saved['Employee']['id']));
                    }
                } else {
                    $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');

                    $data = $req;
                    $data['account'] = $req_account;
                    $data['relation'] = $req_relation;
                    $data['traffic_type'] = !empty($req['traffic_type']) ? implode(',', $req['traffic_type']) : '';

                    if (
                        !empty($req['company_id'])
                        && empty($offices)
                    ) {
                        $offices = $this->Office->find('list', array(
                            'fields' => array('id', 'name'),
                            'conditions' => array('company_id' => $req['company_id'])
                        ));
                    }
                }
            }
        }

        $this->set('data', $data);
        $this->set(compact('companies', 'offices', 'positions', 'licenses', 'occupations', 'allowances', 'hiring_patterns'));
    }

    public function admin_edit($employee_id = null)
    {
        $this->set('title_for_layout', '従業員編集');

        $employee = $this->Employee->find('first', array(
            'conditions' => array(
                'Employee.id' => $employee_id
            ),
            'contain' => array(
                'Account' => array(
                    'fields' => array('email', 'username', 'password')
                ),

                'EmployeeRelationship' => array(
                    'fields' => array('id', 'name', 'kana_name', 'dob', 'relationship', 'occupation'),
                    'conditions' => array(
                        'type' => 0
                    )
                ),
                'EmployeeLicense',
                'EmployeeOccupation',
                'EmployeeAllowance'
            )
        ));

        if (empty($employee)) {
            $this->redirect(array('action' => 'admin_index'));
        }

        $employee['Employee']['account'] = $employee['Account'];
        $employee['Employee']['relation'] = $employee['EmployeeRelationship'];
        $employee['Employee']['licenses'] = array();
        $employee['Employee']['occupations'] = array();
        $employee['Employee']['allowances'] = array();

        foreach ($employee['EmployeeLicense'] as $li) {
            $employee['Employee']['licenses'][] = $li['license_id'];
        }
        foreach ($employee['EmployeeOccupation'] as $oc) {
            $employee['Employee']['occupations'][] = $oc['occupation_id'];
        }
        foreach ($employee['EmployeeAllowance'] as $al) {
            $employee['Employee']['allowances'][] = $al['allowance_id'];
        }

        $companies = $offices = $positions = $licenses = $occupations = $allowances = array();

        // switch for super admin or company admin
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        if (count($companies) == 1) {
            $offices = $this->Office->find('list', array(
                'fields' => array('id', 'name'),
                'conditions' => array(
                    'company_id' => key($companies)
                ),
                'recursive' => -1
            ));

            $data['company_id'] = key($companies);
        } else {
            $offices = $this->Office->find('list', array(
                'fields' => array('id', 'name'),
                'conditions' => array(
                    'company_id' => $employee['Employee']['company_id']
                ),
                'recursive' => -1
            ));
        }

        if ($this->request->is('post')) {
            $req = $this->request->data['Employee'];
            $req_account = $this->request->data['Account'];
            $req_relation = isset($this->request->data['Relation']) ? $this->request->data['Relation'] : array();

            if (!empty($req_relation)) {
                unset($req_relation['key']);
            }

            if (!empty($req) && !empty($req_account)) {
                $req['id'] = $employee_id;
                $updated = $this->admin_updateEmployee($req, $req_account, $req_relation);
                if ($updated) {
                    // rewrite auth session
                    $this->Employee->id = $updated['Employee']['id'];
                    $this->Base->rewriteAuthSession($this->Employee->field('account_id'));

                    $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
                    $employee = $updated;
                } else {
                    $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
                    $req['avatars'] = $req['avatar'];
                    $req['avatar'] = $employee['Employee']['avatar'];
                    $req['avatar_original'] = $employee['Employee']['avatar_original'];
                    $req['account'] = $req_account;
                    $req['traffic_type'] = !empty($req['traffic_type']) ? implode(',', $req['traffic_type']) : '';
                    $employee['Employee'] = $req;
                    $employee['Employee']['account'] = $req_account;
                    $employee['Employee']['relation'] = $req_relation;
                }
            }
        }

        $positions = $this->Position->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        $licenses = $this->License->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        $occupations = $this->Occupation->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        $allowances = $this->Allowance->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        $hiring_patterns = $this->HiringPattern->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        if ($employee['Employee']['avatar'] != DEFAULT_AVATAR) {
            $points = explode('_', preg_replace('/\.[^\.]+$/', '', $employee['Employee']['avatar']));
            if (count($points) == 6) {
                unset($points[0]);
                $employee['Employee']['avatar_points'] = implode('_', $points);
            }
        }

        $this->set('data', $employee['Employee']);
        $this->set(compact('companies', 'offices', 'positions', 'licenses', 'occupations', 'allowances', 'hiring_patterns'));
    }

    public function admin_delete($id = null)
    {
        $this->autoRender = false;
        if ($id) {
            $employee = $this->Employee->find('first', array(
                'conditions' => array(
                    'id' => $id
                ),
                'recursive' => -1
            ));

            if (!empty($employee)) {
                $datasource = $this->Employee->getDataSource();
                $isError = false;
                try {
                    $datasource->begin();

                    $this->EmployeeAllowance->unbindModel(array('belongsTo' => array('Employee', 'Allowance')));
                    if (
                    $this->EmployeeAllowance->deleteAll(array(
                        'employee_id' => $employee['Employee']['id']
                    ))
                    ) {
                        $this->EmployeeLicense->unbindModel(array('belongsTo' => array('Employee', 'License')));
                        if (
                        $this->EmployeeLicense->deleteAll(array(
                            'employee_id' => $employee['Employee']['id']
                        ))
                        ) {
                            $this->EmployeeOccupation->unbindModel(array('belongsTo' => array('Employee', 'Occupation')));
                            if (
                            $this->EmployeeOccupation->deleteAll(array(
                                'employee_id' => $employee['Employee']['id']
                            ))
                            ) {
                                $this->EmployeeRelationship->unbindModel(array('belongsTo' => array('Employee')));
                                if (
                                $this->EmployeeRelationship->deleteAll(array(
                                    'employee_id' => $employee['Employee']['id']
                                ))
                                ) {
                                    $this->EmployeePrize->unbindModel(array('belongsTo' => array('Employee', 'Prize')));
                                    if (
                                    $this->EmployeePrize->deleteAll(array(
                                        'employee_id' => $employee['Employee']['id']
                                    ))
                                    ) {
                                        if (
                                        $this->PointDetail->deleteAll(array(
                                            'employee_id' => $employee['Employee']['id']
                                        ))
                                        ) {
                                            if (
                                                $this->Employee->delete($employee['Employee']['id'])
                                                && $this->Account->delete($employee['Employee']['account_id'])
                                            ) {

                                                $this->UserSession->deleteAll(array(
                                                    'UserSession.account_id' => $employee['Employee']['account_id']
                                                ));
                                                if (!$this->remove_avatar($employee['Employee']['avatar'], $employee['Employee']['avatar_original'])) {
                                                    $isError = true;
                                                }
                                            } else {
                                                $isError = true;
                                            }
                                        } else {
                                            $isError = true;
                                        }
                                    } else {
                                        $isError = true;
                                    }
                                } else {
                                    $isError = true;
                                }
                            } else {
                                $isError = true;
                            }
                        } else {
                            $isError = true;
                        }
                    } else {
                        $isError = true;
                    }

                    if ($isError) {
                        $datasource->rollback();
                    } else {
                        $datasource->commit();
                    }
                } catch (Exception $e) {
                    $isError = true;
                    $datasource->rollback();
                }

                if ($isError) {
                    $this->Session->setFlash(__('có lỗi xảy ra, xóa employee thất bại'), 'flashmessage', array('type' => 'error'), 'error');
                } else {
                    $this->Session->setFlash(__('đã xóa employee'), 'flashmessage', array('type' => 'success'), 'success');
                }
            } else {
                $this->Session->setFlash(__('không tìm thấy employee'), 'flashmessage', array('type' => 'warning'), 'warning');
            }
        }

        $gets = array();

        if (isset($_GET['company']) && $_GET['company'] !== '') {
            $gets['company'] = $_GET['company'];
        }
        if (isset($_GET['office']) && $_GET['office'] !== '') {
            $gets['office'] = $_GET['office'];
        }
        if (isset($_GET['sort']) && $_GET['sort'] !== '') {
            $gets['sort'] = $_GET['sort'];
        }
        if (isset($_GET['direction']) && $_GET['direction'] !== '') {
            $gets['direction'] = $_GET['direction'];
        }
        if (isset($_GET['page']) && $_GET['page'] !== '') {
            $gets['page'] = $_GET['page'];
        }

        $this->redirect(array(
            'controller' => 'Employees',
            'action' => 'admin_index',
            '?' => $gets
        ));
    }

    private function admin_upload_avatar($original, $avatar, $ori_name, $ava_name, $old_ori_name = null, $old_ava_name = null)
    {
        $result = false;
        $ori = $original;
        $ava = $avatar;

        try {
            if ($ava_name != DEFAULT_AVATAR) {
                if (
                    !empty($ori)
                    && $ori != 'default'
                ) {
                    list($type, $ori) = explode(';', $ori);
                    list(, $ori) = explode(',', $ori);
                    $ori = base64_decode($ori);
                }

                if (!empty($ava)) {
                    list($type, $ava) = explode(';', $ava);
                    list(, $ava) = explode(',', $ava);
                    $ava = base64_decode($ava);
                }

                if (
                    !empty($ori)
                    && !empty($ori_name)
                    && $ori != 'default'
                    && $ori_name != DEFAULT_AVATAR
                    && $ori_name != $old_ava_name
                ) {
                    if (file_put_contents(AVATAR_PATH . $ori_name, $ori)) {
                        if (file_put_contents(AVATAR_PATH . $ava_name, $ava)) {
                            $result = true;
                            if (!empty($old_ava_name)) {
                                if (
                                    $old_ava_name != DEFAULT_AVATAR
                                    && file_exists(AVATAR_PATH . $old_ava_name)
                                    && unlink(AVATAR_PATH . $old_ava_name)
                                ) {
                                    if (
                                        $old_ori_name != DEFAULT_AVATAR
                                        && file_exists(AVATAR_PATH . $old_ori_name)
                                        && !empty($old_ori_name)
                                    ) {
                                        unlink(AVATAR_PATH . $old_ori_name);
                                    }
                                }
                            }
                        } else {
                            if (
                                $ori_name != DEFAULT_AVATAR
                                && file_exists(AVATAR_PATH . $ori_name)
                            ) {
                                unlink(AVATAR_PATH . $ori_name);
                            }
                        }
                    }
                } else if (
                    !empty($ava)
                    && !empty($ava_name)
                ) {
                    if ($ava_name != $old_ava_name) {
                        if (file_put_contents(AVATAR_PATH . $ava_name, $ava)) {
                            $result = true;
                            if (
                                $ori == 'default'
                                && !empty($old_ori_name)
                                && $old_ori_name != DEFAULT_AVATAR
                                && file_exists(AVATAR_PATH . $old_ori_name)
                            ) {
                                unlink(AVATAR_PATH . $old_ori_name);
                            }

                            if (
                                !empty($old_ava_name)
                                && $old_ava_name != DEFAULT_AVATAR
                                && file_exists(AVATAR_PATH . $old_ava_name)
                            ) {
                                unlink(AVATAR_PATH . $old_ava_name);
                            }
                        }
                    } else {
                        $result = true;
                    }
                }
            }
        } catch (Exception $e) {
        }

        return $result;
    }

    private function admin_create_upload_avatar_name($points, $original = null)
    {
        $poi = explode('_', $points);
        if (count($poi) == 5) {
            if (
                !empty($original)
                && $original != 'default'
            ) {
                $ori_name = $ava_name = $original;
            } else if ($original == 'default') {
                $ori_name = '';
                $ava_name = time();
            } else {
                $ori_name = $ava_name = time();
            }
            $result = array(
                'ori' => !empty($ori_name) ? $ori_name . '.jpg' : '',
                'ava' => $ava_name . '_' . $poi[0] . '_' . $poi[1] . '_' . $poi[2] . '_' . $poi[3] . '_' . $poi[4] . '.jpg'
            );
        } else {
            $result = array(
                'ori' => '',
                'ava' => DEFAULT_AVATAR
            );
        }

        return $result;
    }

    private function remove_avatar($avatar, $original)
    {
        $result = false;

        if (
            !empty($original)
            && $original != DEFAULT_AVATAR
            && file_exists(AVATAR_PATH . $original)
        ) {
            if (unlink(AVATAR_PATH . $original)) {
                if (
                    $avatar != DEFAULT_AVATAR
                    && file_exists(AVATAR_PATH . $avatar)
                ) {
                    $result = unlink(AVATAR_PATH . $avatar);
                }
            }
        } else if (
            !empty($avatar)
            && $avatar != DEFAULT_AVATAR
            && file_exists(AVATAR_PATH . $avatar)
        ) {
            $result = unlink(AVATAR_PATH . $avatar);
        } else {
            $result = true;
        }

        return $result;
    }

    private function admin_saveEmployee($data, $data_account, $data_relation)
    {
        $response = false;
        $saved = null;

        $datasource = $this->Employee->getDataSource();
        $isError = false;
//        try {
        $datasource->begin();

        // switch for super admin or company admin
        $office = $this->Office->find('first', array(
            'conditions' => array(
                'id' => $data['office_id'],
                'company_id' => $data['company_id']
            ),
            'recursive' => -1
        ));

        if (!empty($office)) {
            $office_id = $office['Office']['id'];
            $company_id = $data['company_id'];
            $company_group_id = $office['Office']['company_group_id'];

            $getCompanyGroup = $this->CompanyGroup->find('first', array(
                'fields' => array('group_permission_id'),
                'conditions' => array(
                    'id' => $office['Office']['company_group_id']
                ),
                'recursive' => -1
            ));
            $group_permission_id = !empty($getCompanyGroup) ? $getCompanyGroup['CompanyGroup']['group_permission_id'] : null;
        } else {
            $office_id = null;
            if (!empty($data['company_id'])) {
                $company = $this->Company->find('first', array(
                    'fields' => array('id', 'company_group_id'),
                    'conditions' => array('id' => $data['company_id']),
                    'recursive' => -1
                ));

                if (!empty($company)) {
                    $company_id = $company['Company']['id'];
                    $company_group_id = $company['Company']['company_group_id'];
                    $getCompanyGroup = $this->CompanyGroup->find('first', array(
                        'fields' => array('group_permission_id'),
                        'conditions' => array(
                            'id' => $company['Company']['company_group_id']
                        ),
                        'recursive' => -1
                    ));
                    $group_permission_id = !empty($getCompanyGroup) ? $getCompanyGroup['CompanyGroup']['group_permission_id'] : null;
                } else {
                    $isError = true;
                }
            } else {
                $company_id = null;
                $company_group_id = null;
                $group_permission_id = null;
            }
        }

        if ($data['employee_register_only'] == true && $data['have_sale_permission'] == true) {
            $group_permission_id = EMPLOYEE_AND_SALE_GROUP;
        } else if ($data['employee_register_only'] == true && $data['have_sale_permission'] == false) {
            $group_permission_id = EMPLOYEE_REGISTER_ONLY_GROUP;
        } else if ($data['employee_register_only'] == false && $data['have_sale_permission'] == true) {
            $group_permission_id = SALE_GROUP;
        }

        $dataAccountSave = array(
            'email' => trim($data_account['email']),
            'username' => trim($data_account['username']),
            'password' => trim($data_account['password']),
            'group_permission_id' => $group_permission_id,
        );

        $this->Account->create();
        $account = $this->Account->save($dataAccountSave);

        if (!empty($account)) {
            
            $account['Account']['password'] = trim($data_account['password']);

            $avatar_name = $this->admin_create_upload_avatar_name($data['avatar']['points'], empty($data['avatar']['original']) ? 'default' : null);

            /*$start_month = date('Y-m-01', strtotime($data['join_date']));
            $end_month = date('Y-m-t', strtotime($data['join_date']));
            if (!empty($office)) {
                $num_of_e_in_m = $this->Employee->find('count', array(
                    'conditions' => array(
                        'office_id' => $office['Office']['id'],
                        'join_date BETWEEN ? AND ?' => array($start_month, $end_month)
                    ),
                    'order' => array(
                        'join_date' => 'desc'
                    ),
                    'recursive' => -1
                ));
            } else {
                $num_of_e_in_m = -1;
            }

            $employee_number = (
                date('ym', strtotime($data['join_date']))
                . str_pad(intval($num_of_e_in_m + 1), 2, '0', STR_PAD_LEFT)
            );*/

            $this->Employee->create();
            $saved = $this->Employee->save(array(
                'account_id' => $account['Account']['id'],
                'company_id' => $company_id,
                'office_id' => $office_id,
                'position_id' => !empty($data['position_id']) ? $data['position_id'] : null,
                'company_group_id' => $company_group_id,
                'name' => trim($data['name']),
                'gender' => $data['gender'],
                'kana_name' => trim($data['kana_name']),
                'in_office' => $data['in_office'],
                'basic_salary' => $data['basic_salary'] !== '' ? (double)$data['basic_salary'] : null,
                'daily_wage' => $data['daily_wage'] !== '' ? (double)$data['daily_wage'] : null,
                'hourly_wage' => $data['hourly_wage'] !== '' ? (double)$data['hourly_wage'] : null,
                'traffic_type' => !empty($data['traffic_type']) ? implode(',', $data['traffic_type']) : '',
                'public_transportation' => $data['public_transportation'] !== '' ? (double)$data['public_transportation'] : null,
                'vehicle_cost' => $data['vehicle_cost'] !== '' ? (double)$data['vehicle_cost'] : null,
                'one_way_transportation' => $data['one_way_transportation'] !== '' ? (double)$data['one_way_transportation'] : null,
                'round_trip_transportation' => $data['round_trip_transportation'] !== '' ? (double)$data['round_trip_transportation'] : null,
                'commute_route' => trim($data['commute_route']),
                'social_insurance' => $data['social_insurance'] !== '' ? (double)$data['social_insurance'] : null,
                'employment_insurance' => $data['employment_insurance'] !== '' ? (double)$data['employment_insurance'] : null,
                'join_date' => date('Y-m-d', strtotime($data['join_date'])),
                'postal_code' => trim($data['postal_code']),
                'prefecture' => trim($data['prefecture']),
                'municipality' => trim($data['municipality']),
                'municipal_town' => trim($data['municipal_town']),
                'phone' => trim($data['phone']),
                'basis_pension_number' => trim($data['basis_pension_number']),
                'dob' => $data['dob'] !== '' ? date('Y-m-d', strtotime($data['dob'])) : null,
                'avatar' => $avatar_name['ava'],
                'avatar_original' => $avatar_name['ori'],
                'profile' => trim($data['profile']),
                'sos_contact_person' => trim($data['sos_contact_person']),
                'sos_contact_person_kana' => trim($data['sos_contact_person_kana']),
                'sos_phone' => trim($data['sos_phone']),
                'sos_address' => trim($data['sos_address']),
                'bank_name' => trim($data['bank_name']),
                'branch_name' => trim($data['branch_name']),
                'account_number' => trim($data['account_number']),
                'account_name' => trim($data['account_name']),
                'employee_number' => trim($data['employee_number']),
                'hiring_pattern_id' => $data['hiring_pattern_id'],
                'employee_register_only' => $data['employee_register_only'],
                'have_sale_permission' => $data['have_sale_permission'],
                'shift_authority' => $data['shift_authority'],
            ));

            if (!empty($saved)) {
                $saved['Employee']['account'] = $account['Account'];

                if (!empty($data['allowances'])) {
                    foreach ($data['allowances'] as $allowance) {
                        $this->EmployeeAllowance->create();
                        if (
                        $this->EmployeeAllowance->save(array(
                            'employee_id' => $saved['Employee']['id'],
                            'allowance_id' => $allowance
                        ))
                        ) {
                            $saved['Employee']['allowances'][] = $allowance;
                        } else {
                            $isError = true;
                            break;
                        }
                    }
                }

                if (
                !$isError
                ) {
                    $this->EmployeeLicense->unbindModel(array('belongsTo' => array('Employee', 'License')));
                    if (
                    $this->EmployeeLicense->deleteAll(array(
                        'employee_id' => $saved['Employee']['id']
                    ))
                    ) {
                        if (!empty($data['licenses'])) {
                            foreach ($data['licenses'] as $license) {
                                $this->EmployeeLicense->create();
                                if (
                                !$this->EmployeeLicense->save(array(
                                    'employee_id' => $saved['Employee']['id'],
                                    'license_id' => $license
                                ))
                                ) {
                                    $isError = true;
                                    break;
                                }
                            }
                        }
                    }
                }

                if (
                !$isError
                ) {
                    $this->EmployeeOccupation->unbindModel(array('belongsTo' => array('Employee', 'Occupation')));
                    if (
                    $this->EmployeeOccupation->deleteAll(array(
                        'employee_id' => $saved['Employee']['id']
                    ))
                    ) {
                        if (!empty($data['occupations'])) {
                            foreach ($data['occupations'] as $occupation) {
                                $this->EmployeeOccupation->create();
                                if (
                                !$this->EmployeeOccupation->save(array(
                                    'employee_id' => $saved['Employee']['id'],
                                    'occupation_id' => $occupation
                                ))
                                ) {
                                    $isError = true;
                                    break;
                                }
                            }
                        }
                    }
                }

                if (
                    !$isError
                    && !empty($data_relation)
                ) {
                    foreach ($data_relation as $relation) {
                        $this->EmployeeRelationship->create();
                        if (
                        !$this->EmployeeRelationship->save(array(
                            'employee_id' => $saved['Employee']['id'],
                            'type' => 0,
                            'postal_code' => '',
                            'address' => '',
                            'phone_number' => '',
                            'name' => trim($relation['name']),
                            'kana_name' => trim($relation['kana_name']),
                            'dob' => date('Y-m-d', strtotime($relation['dob'])),
                            'relationship' => trim($relation['relationship']),
                            'occupation' => trim($relation['occupation'])
                        ))
                        ) {
                            $isError = true;
                            break;
                        }
                    }
                }

                if (
                !(empty($data['avatar']['input']) && empty($data['avatar']['original']))
                ) {
                    if (!$this->admin_upload_avatar($data['avatar']['original'], $data['avatar']['input'], $saved['Employee']['avatar_original'], $saved['Employee']['avatar'])) {
                        $isError = true;
                    }
                }
            } else {
                $isError = true;
            }
        } else {
            $isError = true;
        }


        if ($isError) {
            $datasource->rollback();
            $response = false;
        } else {
            $datasource->commit();
            $response = $saved ? $saved : array();
        }
//        } catch (Exception $e) {
//            $isError = true;
//            $datasource->rollback();
//            $response = false;
//        }

        return $response;
    }

    public function admin_get_employee_number()
    {
        $this->autoRender = false;
        $response = array(
            'status' => 0,
            'message' => ''
        );

        if ($this->request->is('ajax')) {
            $req = $this->request->data;

            $start_month = date('Y-m-01', strtotime($req['join_date']));
            $end_month = date('Y-m-t', strtotime($req['join_date']));

            $num_of_e_in_m = $this->Employee->find('list', array(
                'fields' => array(
                    'Employee.id',
                    'Employee.employee_number',
                ),
                'conditions' => array(
//                                'office_id' => $office['Office']['id'],
                    'join_date BETWEEN ? AND ?' => array($start_month, $end_month)
                ),
                'order' => array(
                    'employee_number' => 'asc'
                ),
                'recursive' => -1
            ));

            $arr_number = array();
            foreach ($num_of_e_in_m as $num) {
                if (is_numeric($num)) {
                    $arr_number[] = substr($num, 4);
                }
            }
            arsort($arr_number, true);
            $employee_number = reset($arr_number);
            $employee_number = (
                date('ym', strtotime($req['join_date']))
                . str_pad(intval($employee_number + 1), 2, '0', STR_PAD_LEFT)
            );
            $response['status'] = 1;
            $response['employee_number'] = $employee_number;
        }
        return json_encode($response);
    }

    public function admin_ajax_check_duplicate_employee_number()
    {
        $this->autoRender = false;
        $response = array(
            'status' => 0,
            'message' => ''
        );

        if ($this->request->is('ajax')) {
            $req = $this->request->data;
            $is_duplicate = 0;
            $employee_number = $req['employee_number'];

            $num_of_e = $this->Employee->find('first', array(
                'fields' => array(
                    'Employee.id',
                    'Employee.employee_number',
                ),
                'conditions' => array(
                    'Employee.employee_number' => $employee_number
                ),
                'recursive' => -1
            ));

            if ($num_of_e != null) {
                $is_duplicate = 1;
            }

            $response['status'] = 1;
            $response['is_duplicate'] = $is_duplicate;
        }
        return json_encode($response);
    }

    private function admin_updateEmployee($data, $data_account, $data_relation)
    {
        $response = false;
        $saved = null;

        $datasource = $this->Employee->getDataSource();
        $isError = false;
        try {
            $datasource->begin();

            $employee = $this->Employee->find('first', array(
                'conditions' => array(
                    'Employee.id' => $data['id']
                ),
                'contain' => array(
                    'Account',
                    'EmployeeRelationship' => array(
                        'conditions' => array(
                            'type' => 0
                        )
                    )
                )
            ));

            if (!empty($employee)) {
                // switch for super admin or company admin
                $office = $this->Office->find('first', array(
                    'conditions' => array(
                        'id' => $data['office_id'],
                        'company_id' => $data['company_id']
                    ),
                    'recursive' => -1
                ));

                if (!empty($office)) {
                    $office_id = $office['Office']['id'];
                    $company_id = $data['company_id'];
                    $company_group_id = $office['Office']['company_group_id'];
                    $group_permission_id = $this->CompanyGroup->find('first', array(
                        'fields' => array('group_permission_id'),
                        'conditions' => array(
                            'id' => $office['Office']['company_group_id']
                        ),
                        'recursive' => -1
                    ));
                } else {
                    $office_id = null;
                    if (!empty($data['company_id'])) {
                        $company = $this->Company->find('first', array(
                            'fields' => array('id', 'company_group_id'),
                            'conditions' => array('id' => $data['company_id']),
                            'recursive' => -1
                        ));

                        if (!empty($company)) {
                            $company_id = $company['Company']['id'];
                            $company_group_id = $company['Company']['company_group_id'];
                            $group_permission_id = $this->CompanyGroup->find('first', array(
                                'fields' => array('group_permission_id'),
                                'conditions' => array(
                                    'id' => $company['Company']['company_group_id']
                                ),
                                'recursive' => -1
                            ));
                        } else {
                            $isError = true;
                        }
                    } else {
                        $company_id = null;
                        $company_group_id = null;
                        $group_permission_id = array('CompanyGroup' => array('group_permission_id' => null));
                    }
                }
                $update = array();
                if ($data['employee_register_only'] == true && $data['have_sale_permission'] == true) {
                    $update['group_permission_id'] = EMPLOYEE_AND_SALE_GROUP;
                } else if ($data['employee_register_only'] == true && $data['have_sale_permission'] == false) {
                    $update['group_permission_id'] = EMPLOYEE_REGISTER_ONLY_GROUP;
                } else if ($data['employee_register_only'] == false && $data['have_sale_permission'] == true) {
                    $update['group_permission_id'] = SALE_GROUP;
                } else if (
                    (!empty($group_permission_id) || $group_permission_id === null)
                    && $group_permission_id['CompanyGroup']['group_permission_id'] != $employee['Account']['group_permission_id']
                ) {
                    $update['group_permission_id'] = $group_permission_id['CompanyGroup']['group_permission_id'];
                }

                if (
                    !empty($data_account['username'])
                    && $data_account['username'] != $employee['Account']['username']
                ) {
                    $update['username'] = $data_account['username'];
                }

                if (
                    $data_account['email'] != $employee['Account']['email']
                ) {
                    $update['email'] = $data_account['email'];
                }

                if (!empty($data_account['password'])) {
                    $update['password'] = trim($data_account['password']);
                }
                if (!empty($update)) {
                    $update['id'] = $employee['Account']['id'];
                    $account = $this->Account->save($update);
                    if (!empty($account)) {
                        if (!empty($account['Account']['username'])) {
                            $employee['Account']['username'] = $account['Account']['username'];
                        }
                        if (!empty($account['Account']['password'])) {
                            $employee['Account']['password'] = $account['Account']['password'];
                        }
                    } else {
                        $isError = true;
                    }
                }

                if (!$isError) {
                    if (
                        !empty($data['avatar']['input'])
                        && !empty($data['avatar']['points'])
                        && !empty($data['avatar']['original'])
                        && $data['avatar']['original'] != 'default'
                    ) {
                        $avatar_name = $this->admin_create_upload_avatar_name($data['avatar']['points']);
                    } else if (
                        !empty($data['avatar']['input'])
                        && !empty($data['avatar']['points'])
                        && (
                            empty($data['avatar']['original'])
                            || $data['avatar']['original'] == 'default'
                        )
                    ) {
                        $avatar_name = $this->admin_create_upload_avatar_name(
                            $data['avatar']['points'],
                            empty($data['avatar']['original'])
                                ? (
                            !empty($employee['Employee']['avatar_original'])
                                ? preg_replace('/\.[^\.]+$/', '', $employee['Employee']['avatar_original'])
                                : 'default'
                            ) : 'default'
                        );
                    } else {
                        $avatar_name = array(
                            'ava' => $employee['Employee']['avatar'],
                            'ori' => $employee['Employee']['avatar_original']
                        );
                    }

                    $update = array(
                        'company_id' => $company_id,
                        'office_id' => $office_id,
                        'hiring_pattern_id' => !empty($data['hiring_pattern_id']) ? $data['hiring_pattern_id'] : null,
                        'position_id' => !empty($data['position_id']) ? $data['position_id'] : null,
                        'company_group_id' => $company_group_id,
                        'name' => trim($data['name']),
                        'gender' => $data['gender'],
                        'kana_name' => trim($data['kana_name']),
                        'in_office' => $data['in_office'],
                        'basic_salary' => $data['basic_salary'] !== '' ? (double)$data['basic_salary'] : null,
                        'daily_wage' => $data['daily_wage'] !== '' ? (double)$data['daily_wage'] : null,
                        'hourly_wage' => $data['hourly_wage'] !== '' ? (double)$data['hourly_wage'] : null,
                        'traffic_type' => !empty($data['traffic_type']) ? implode(',', $data['traffic_type']) : '',
                        'public_transportation' => $data['public_transportation'] !== '' ? (double)$data['public_transportation'] : null,
                        'vehicle_cost' => $data['vehicle_cost'] !== '' ? (double)$data['vehicle_cost'] : null,
                        'one_way_transportation' => $data['one_way_transportation'] !== '' ? (double)$data['one_way_transportation'] : null,
                        'round_trip_transportation' => $data['round_trip_transportation'] !== '' ? (double)$data['round_trip_transportation'] : null,
                        'commute_route' => trim($data['commute_route']),
                        'social_insurance' => $data['social_insurance'] !== '' ? (double)$data['social_insurance'] : null,
                        'employment_insurance' => $data['employment_insurance'] !== '' ? (double)$data['employment_insurance'] : null,
                        'join_date' => date('Y-m-d', strtotime($data['join_date'])),
                        'postal_code' => trim($data['postal_code']),
                        'prefecture' => trim($data['prefecture']),
                        'municipality' => trim($data['municipality']),
                        'municipal_town' => trim($data['municipal_town']),
                        'phone' => trim($data['phone']),
                        'basis_pension_number' => trim($data['basis_pension_number']),
                        'dob' => $data['dob'] !== '' ? date('Y-m-d', strtotime($data['dob'])) : null,
                        'avatar' => $avatar_name['ava'],
                        'avatar_original' => $avatar_name['ori'],
                        'profile' => trim($data['profile']),
                        'sos_contact_person' => trim($data['sos_contact_person']),
                        'sos_contact_person_kana' => trim($data['sos_contact_person_kana']),
                        'sos_phone' => trim($data['sos_phone']),
                        'sos_address' => trim($data['sos_address']),
                        'bank_name' => trim($data['bank_name']),
                        'branch_name' => trim($data['branch_name']),
                        'account_number' => trim($data['account_number']),
                        'account_name' => trim($data['account_name']),
                        'employee_number' => trim($data['employee_number']),
                        'hiring_pattern_id' => $data['hiring_pattern_id'],
                        'employee_register_only' => $data['employee_register_only'],
                        'have_sale_permission' => $data['have_sale_permission'],
                        'shift_authority' => $data['shift_authority'],
                    );
                    $update['id'] = $employee['Employee']['id'];
                    $saved = $this->Employee->save($update);

                    if (!empty($saved)) {
                        $saved['Employee']['account'] = isset($account) ? $account['Account'] : $employee['Account'];
                        if (
                        !isset($saved['Employee']['account']['username'])
                        ) {
                            $saved['Employee']['account']['username'] = $employee['Account']['username'];
                        }
                        if (
                        !isset($saved['Employee']['account']['email'])
                        ) {
                            $saved['Employee']['account']['email'] = $employee['Account']['email'];
                        }
                        if (
                        !isset($saved['Employee']['account']['password'])
                        ) {
                            $saved['Employee']['account']['password'] = $employee['Account']['password'];
                        }

                        if (
                        empty($saved['Employee']['id'])
                        ) {
                            $saved['Employee']['id'] = $employee['Employee']['id'];
                        }

                        if (
                        !$isError
                        ) {
                            $this->EmployeeLicense->unbindModel(array('belongsTo' => array('Employee', 'License')));
                            if (
                            $this->EmployeeLicense->deleteAll(array(
                                'employee_id' => $saved['Employee']['id']
                            ))
                            ) {
                                if (!empty($data['licenses'])) {
                                    foreach ($data['licenses'] as $license) {
                                        $this->EmployeeLicense->create();
                                        if (
                                        !$this->EmployeeLicense->save(array(
                                            'employee_id' => $saved['Employee']['id'],
                                            'license_id' => $license
                                        ))
                                        ) {
                                            $isError = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        if (
                        !$isError
                        ) {
                            $this->EmployeeOccupation->unbindModel(array('belongsTo' => array('Employee', 'Occupation')));
                            if (
                            $this->EmployeeOccupation->deleteAll(array(
                                'employee_id' => $saved['Employee']['id']
                            ))
                            ) {
                                if (!empty($data['occupations'])) {
                                    foreach ($data['occupations'] as $occupation) {
                                        $this->EmployeeOccupation->create();
                                        if (
                                        !$this->EmployeeOccupation->save(array(
                                            'employee_id' => $saved['Employee']['id'],
                                            'occupation_id' => $occupation
                                        ))
                                        ) {
                                            $isError = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        $saved['Employee']['allowances'] = array();
                        $this->EmployeeAllowance->unbindModel(array('belongsTo' => array('Employee', 'Allowance')));
                        if (
                        $this->EmployeeAllowance->deleteAll(array(
                            'employee_id' => $saved['Employee']['id']
                        ))
                        ) {
                            if (!empty($data['allowances'])) {
                                foreach ($data['allowances'] as $allowance) {
                                    $this->EmployeeAllowance->create();
                                    if (
                                    $this->EmployeeAllowance->save(array(
                                        'employee_id' => $saved['Employee']['id'],
                                        'allowance_id' => $allowance
                                    ))
                                    ) {
                                        $saved['Employee']['allowances'][] = $allowance;
                                    } else {
                                        $isError = true;
                                        break;
                                    }
                                }
                            }
                        } else {
                            $isError = true;
                        }

                        $saved['Employee']['relation'] = array();
                        $relation_id = array();
                        if (!empty($data_relation)) {
                            foreach ($data_relation as $value) {
                                $relation = null;

                                if (!empty($value['id'])) {
                                    foreach ($employee['EmployeeRelationship'] as $er) {
                                        if ($er['id'] == $value['id']) {
                                            $this->EmployeeRelationship->id = $er['id'];
                                            $relation = $this->EmployeeRelationship->save(array(
                                                'name' => $value['name'],
                                                'kana_name' => $value['kana_name'],
                                                'dob' => date('Y-m-d', strtotime($value['dob'])),
                                                'relationship' => $value['relationship'],
                                                'occupation' => $value['occupation']
                                            ));

                                            if ($relation) {
                                                $relation['EmployeeRelationship']['id'] = $er['id'];
                                            }

                                            break;
                                        }
                                    }
                                } else {
                                    if (
                                        $value['name'] !== ''
                                        && $value['kana_name'] !== ''
                                        && $value['dob'] !== ''
                                        && $value['relationship'] !== ''
                                        && $value['occupation'] !== ''
                                    ) {
                                        $this->EmployeeRelationship->create();
                                        $relation = $this->EmployeeRelationship->save(array(
                                            'employee_id' => $saved['Employee']['id'],
                                            'type' => 0,
                                            'postal_code' => '',
                                            'address' => '',
                                            'phone_number' => '',
                                            'name' => $value['name'],
                                            'kana_name' => $value['kana_name'],
                                            'dob' => date('Y-m-d', strtotime($value['dob'])),
                                            'relationship' => $value['relationship'],
                                            'occupation' => $value['occupation']
                                        ));
                                    }

                                }

                                if (!$relation) {
                                    $isError = true;
                                    break;
                                } else {
                                    $relation_id[] = $relation['EmployeeRelationship']['id'];
                                    $saved['Employee']['relation'][] = $relation['EmployeeRelationship'];
                                }
                            }
                        }

                        $this->EmployeeRelationship->unbindModel(array('belongsTo' => array('Employee')));
                        if (
                        !$this->EmployeeRelationship->deleteAll(array(
                            'employee_id' => $saved['Employee']['id'],
                            'id !=' => $relation_id
                        ))
                        ) {
                            $isError = true;
                        }

                        if (!empty($data['avatar']['input'])) {
                            if (
                                !empty($data['avatar']['input'])
                                && !empty($data['avatar']['points'])
                            ) {
                                $update_upload = $this->admin_upload_avatar(
                                    $data['avatar']['original'],
                                    $data['avatar']['input'],
                                    $saved['Employee']['avatar_original'],
                                    $saved['Employee']['avatar'],
                                    $employee['Employee']['avatar_original'],
                                    $employee['Employee']['avatar']);

                                if (!$update_upload) {
                                    $isError = true;
                                }
                            }
                        }
                    } else {
                        $isError = true;
                    }
                }
            } else {
                $isError = true;
            }

            if ($isError) {
                $datasource->rollback();
                $response = false;
            } else {
                $datasource->commit();
                $response = $saved ? $saved : array();
            }
        } catch (Exception $e) {
            $isError = true;
            $datasource->rollback();
            $response = false;
        }

        return $response;
    }

    public function admin_csv_import()
    {
        $this->set('title_for_layout', 'import employee');

        $companies = $offices = array();

        // switch for super admin or company admin
        // get list company
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));

        $this->set(compact('companies', 'offices'));
    }

    public function admin_import_employee_from_csv()
    {
        $this->autoRender = false;

        $response = array(
            'status' => 0,
            'success' => 0,
            'failure' => 0,
            'message' => array('Oops! Something went wrong.')
        );

        if ($this->request->is('ajax')) {
            $req = $this->request->data;
            if (
                !empty($req['CsvUpload'])
                && !empty($req['CsvUpload']['file'])
            ) {
                $csv_format = $this->csv_format_alias();
                unset($csv_format['id']);
//				unset($csv_format['employee_number']);

                $files = $req['CsvUpload']['file'];
                $method = 'get_content_file_c';
                $valid = $csv_format;
                $processer = $this->Employee;
                $procession = 'import_employee';

                $response = $this->CsvContentChunk->get_response($files, $method, $valid, $processer, $procession);
            }
        } else {
            $this->redirect('admin_index');
        }

        return json_encode($response);
    }

    public function admin_update_employee_from_csv()
    {
        $this->autoRender = false;

        $response = array(
            'status' => 0,
            'success' => 0,
            'failure' => 0,
            'message' => array('Oops! Something went wrong.')
        );

        if ($this->request->is('ajax')) {
            $req = $this->request->data;

            if (
                !empty($req['CsvUpload'])
                && !empty($req['CsvUpload']['file'])
            ) {
                $csv_format = $this->csv_format_alias();

                $files = $req['CsvUpload']['file'];
                $method = 'get_content_file_c';
                $valid = $csv_format;
                $processer = $this->Employee;
                $procession = 'update_employee';

                $response = $this->CsvContentChunk->get_response($files, $method, $valid, $processer, $procession);
            }
        } else {
            $this->redirect('admin_index');
        }

        return json_encode($response);
    }

    public function admin_export_sample($type = null)
    {
        $this->autoRender = false;

        if (in_array($type, ['import', 'update'])) {
            $s_key = 'Employee.Csv.Sample_' . $type;
            $watting_time = 30;
            $last_time = $this->Session->read($s_key);
            $last_time = $last_time ? $last_time : 0;
            $current_time = time();

            if ($current_time - $last_time < $watting_time) {
                $time_left = abs($watting_time - ($current_time - $last_time));
                $this->Session->setFlash(__('the next download will be available in %s seconds', $time_left), 'flashmessage', array('type' => 'warning'), 'warning');
                $this->redirect(array('action' => 'admin_csv_import', 'plugin' => null, 'admin' => true));
            } else {
                $this->Session->write($s_key, time());
            }

            $sample_alias = $this->csv_format_alias();
            $sample_format = $this->csv_format_title();

            $sample_name = 'sample.csv';
            if ($type == 'import') {
                $sample_name = '従業員登録' . '.' . $sample_name;

                unset($sample_format[$sample_alias['id']]);
//				unset($sample_format[$sample_alias['employee_number']]);
            } else {
                $sample_name = '従業員の編集' . '.' . $sample_name;
                $sample_format[$sample_alias['password']] = $this->csv_data_format['password'][2];
            }
            $sample_format[$sample_alias['occupation_id']] = '職種（ID）';

            $csv_data = array();
            $row = array();
            foreach ($sample_format as $key => $column) {
                $row[$key] = $column;
            }
            $csv_data[] = $row;

            $this->Export->exportCsv($csv_data, $sample_name);
        } else {
            $this->redirect(array('controller' => 'dashboard', 'action' => 'index', 'admin' => true));
        }
    }

    public function admin_export()
    {
        $this->autoRender = false;

        $watting_time = 30;
        $last_time = $this->Session->read('Employee.Csv.Download');
        $last_time = $last_time ? $last_time : 0;
        $current_time = time();

        if ($current_time - $last_time < $watting_time) {
            $time_left = abs($watting_time - ($current_time - $last_time));
            $this->Session->setFlash(__('the next download will be available in %s seconds', $time_left), 'flashmessage', array('type' => 'warning'), 'warning');
            $this->redirect(array('action' => 'admin_index', 'plugin' => null, 'admin' => true));
        } else {
            $this->Session->write('Employee.Csv.Download', time());
        }

        $this->Employee->belongsTo['Company'] = array(
            'className' => 'Company',
            'foreignKey' => 'company_id'
        );

        $data = $this->Employee->find('all', array(
            'fields' => array(
                'Employee.id',
                'Employee.created',
                'Employee.updated',
                // 'username',
                // 'password',
                'Employee.in_office',
                'Employee.office_id',
                'Employee.hiring_pattern_id',
                'Employee.employee_number',
                'Employee.name',
                'Employee.kana_name',
                'Employee.position_id',
                // 'licenses',
                // 'occupations',
                'Employee.basic_salary',
                'Employee.hourly_wage',
                'Employee.daily_wage',
                // 'allowances',
                'Employee.traffic_type',
                'Employee.public_transportation',
                'Employee.vehicle_cost',
                'Employee.one_way_transportation',
                'Employee.round_trip_transportation',
                'Employee.commute_route',
                'Employee.social_insurance',
                'Employee.employment_insurance',
                'Employee.join_date',
                'Employee.postal_code',
                'Employee.prefecture',
                'Employee.municipality',
                'Employee.municipal_town',
                'Employee.dob',
                'Employee.gender',
                // 'email',
                'Employee.phone',
                'Employee.basis_pension_number',
                'Employee.bank_name',
                'Employee.branch_name',
                'Employee.account_number',
                'Employee.account_name',
                'Employee.sos_contact_person',
                'Employee.sos_contact_person_kana',
                'Employee.sos_phone',
                'Employee.sos_address',
                // 'employee_relationships1_name',
                // 'employee_relationships1_name_kana',
                // 'employee_relationships1_dob',
                // 'employee_relationships1_relation',
                // 'employee_relationships1_job',
                // 'employee_relationships2_name',
                // 'employee_relationships2_name_kana',
                // 'employee_relationships2_dob',
                // 'employee_relationships2_relation',
                // 'employee_relationships2_job',
                // 'employee_relationships3_name',
                // 'employee_relationships3_name_kana',
                // 'employee_relationships3_dob',
                // 'employee_relationships3_relation',
                // 'employee_relationships3_job',
                // 'employee_relationships4_name',
                // 'employee_relationships4_name_kana',
                // 'employee_relationships4_dob',
                // 'employee_relationships4_relation',
                // 'employee_relationships4_job',
                 'employee_register_only',
                 'have_sale_permission',
            ),
            'contain' => array(
                'Account' => array(
                    'fields' => array('email', 'username')
                ),
                'EmployeeLicense' => array(
                    'fields' => array('license_id')
                ),
                'EmployeeOccupation' => array(
                    'fields' => array('occupation_id'),
                ),
                'EmployeeRelationship' => array(
                    'fields' => array('name', 'kana_name', 'dob', 'relationship', 'occupation')
                ),
                'EmployeeAllowance' => array(
                    'fields' => array('allowance_id')
                )
            ),
            'order' => array(
                'Employee.name' => 'asc'
            )
        ));

        $csv_alias = $this->csv_format_alias();
        $csv_format = $this->csv_format_title();
        $csv_data = array();
        $row = array();
        foreach ($csv_format as $key => $column) {
            $row[$key] = $column;
        }
        $csv_data[] = $row;

        foreach ($data as $employee) {
            $e = $employee['Employee'];

            $licenses = array();
            foreach ($employee['EmployeeLicense'] as $license) {
                $licenses[] = $license['license_id'];
            }
            $licenses = implode(',', $licenses);

            $occupations = array();
            foreach ($employee['EmployeeOccupation'] as $occupation) {
                $occupations[] = (
                $occupation['occupation_id']
                );
            }
            $occupations = implode(',', $occupations);

            $allowances = array();
            foreach ($employee['EmployeeAllowance'] as $allowance) {
                $allowances[] = $allowance['allowance_id'];
            }
            $allowances = implode(',', $allowances);

            $e['created'] = date('Y/m/d h:i:s', strtotime($e['created']));
            $e['updated'] = date('Y/m/d h:i:s', strtotime($e['updated']));
            $e['username'] = $employee['Account']['username'];
            $e['password'] = '';
            $e['dob'] = !empty($e['dob']) ? date('Y/m/d', strtotime($e['dob'])) : '';
            $e['join_date'] = date('Y/m/d', strtotime($e['join_date']));
            $e['license_id'] = $licenses;
            $e['occupation_id'] = $occupations;
            $e['allowance_id'] = $allowances;
            $e['email'] = $employee['Account']['email'];
            $e['gender'] = $e['gender'] ? 1 : 0;
            $e['employee_register_only'] = $e['employee_register_only'] ? 1 : 0;
            $e['have_sale_permission'] = $e['have_sale_permission'] ? 1 : 0;

            for ($i = 1; $i <= 4; $i++) {
                $e['employee_relationships' . $i . '_name'] = (
                isset($employee['EmployeeRelationship'][$i - 1])
                    ? $employee['EmployeeRelationship'][$i - 1]['name']
                    : ''
                );
                $e['employee_relationships' . $i . '_name_kana'] = (
                isset($employee['EmployeeRelationship'][$i - 1])
                    ? $employee['EmployeeRelationship'][$i - 1]['kana_name']
                    : ''
                );
                $e['employee_relationships' . $i . '_dob'] = (
                isset($employee['EmployeeRelationship'][$i - 1])
                    ? date('Y/m/d', strtotime($employee['EmployeeRelationship'][$i - 1]['dob']))
                    : ''
                );
                $e['employee_relationships' . $i . '_relation'] = (
                isset($employee['EmployeeRelationship'][$i - 1])
                    ? $employee['EmployeeRelationship'][$i - 1]['relationship']
                    : ''
                );
                $e['employee_relationships' . $i . '_job'] = (
                isset($employee['EmployeeRelationship'][$i - 1])
                    ? $employee['EmployeeRelationship'][$i - 1]['occupation']
                    : ''
                );
            }

            $row = array();
            foreach ($csv_format as $key => $column) {
                $ix = array_search($key, $csv_alias);

                if ($ix !== false) {
                    $row[$csv_alias[$ix]] = isset($e[$ix]) ? $e[$ix] : '';
                }
            }
            $csv_data[] = $row;
        }

        $this->Export->exportCsv($csv_data, '従業員一覧.csv');
    }
}