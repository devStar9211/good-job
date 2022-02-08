<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class AccountsController extends AppController
{
    public $uses = array('Post', 'Account', 'Admin', 'Company', 'Aro', 'GroupPermission', 'Employee', 'Position', 'Office', 'EmployeeRelationship', 'Allowance', 'CompanyGroup', 'EmployeeAllowance', 'EmployeeOccupation', 'EmployeeLicense', 'Occupation', 'License', 'UserSession','Session');
    public $components = array('Email');
    public $secure = false;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('login', 'change_password', 'edit_profile', 'logout', 'initDB', 'forgot_password', 'edit_profile', 'edit_email');

        // set cookie options
        $this->Cookie->httpOnly = true;
        $cookie = $this->Cookie->read('remember_cookie');
//        debug($cookie);
        if (!$this->Auth->loggedIn() && $cookie) {
            $user = $this->Account->find('first', array(
                'conditions' => array(
                    'Account.username' => $cookie['username'],
                    'Account.password' => $cookie['password']
                )
            ));
            if ($user && !$this->Auth->login($user['Account'])) {
                return $this->redirect(array('action' => 'logout')); // destroy session & cookie
            }
        }

    }

    public function initDB()
    {
        // add aro
        $aro = $this->Acl->Aro;
        $groups = $this->GroupPermission->find('all', array('contain' => false));

        foreach ($groups as $key => $group) {
            $aro->create();
            $aro->save(array(
                'model' => 'GroupPermission',
                'foreign_key' => $group['GroupPermission']['id'],
            ));
        }

        $accounts = $this->GroupPermission->Account->find('all');
        foreach ($accounts as $key => $account) {
            $aro->create();
            $data = array(
                'parent_id' => $account['Account']['group_permission_id'],
                'alias' => $account['Account']['username'],
                'model' => 'Account',
                'foreign_key' => $account['Account']['id'],
            );
            $aro->save($data);
            // pr($data);
            // debug($aro->save($data));
        }
        // $log = $this->Aro->getDataSource()->getLog(false, false);
        $group = $this->Account->GroupPermission;

        // Allow admins to everything
        $group->id = 1;
        $this->Acl->allow($group, 'controllers');

        // allow users to only add and edit on posts and widgets
        $group->id = 2;
        $this->Acl->allow($group, 'controllers');
        $this->Acl->deny($group, 'controllers/Accounts/admin_register_admin_user');
        $this->Acl->deny($group, 'controllers/Accounts/admin_list_admin_user');
        $this->Acl->deny($group, 'controllers/Accounts/admin_change_admin_user');
        $this->Acl->deny($group, 'controllers/Accounts/admin_delete_admin_user');
        echo "all done";
        exit;
    }

    public function initDB13() {
        $group = $this->Account->GroupPermission;
        $group->id = 13;
        $this->Acl->allow($group, 'controllers/Employees/admin_add');
        $this->Acl->allow($group, 'controllers/Dashboard/admin_index');
        $this->Acl->allow($group, 'controllers/Frontend');
        echo "all done";
        exit;
    }

    public function login()
    {
        $this->set('title_for_layout', __('Login'));
        if ($this->Auth->user()) return $this->redirect($this->Auth->redirectUrl());

        $this->layout = 'alogin';
        if(!isset($this->request->data['Account'])) {
            $this->request->data['Account'] = $this->Cookie->check('remember') ? unserialize($this->Cookie->read('remember')) : array('remember' => 0, 'username' => '', 'password' => '');
        }
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $rflag = isset($this->request->data['Account']['remember']) ? 1 : 0;
                if($rflag) {
                    $this->Cookie->write('remember', serialize(
                        array('remember' => $rflag, 'username' => $this->request->data['Account']['username'], 'password' => $this->request->data['Account']['password'])
                    ), true, '10 Years');
                } else {
                    if($this->Cookie->check('remember')) $this->Cookie->delete('remember');
                }

                // build login link cookie for shift system
                $accountId = $this->Auth->user('id');
                $email = $this->Auth->user('email');
                $active = $this->Auth->user('active');
                if(!empty($email) && $active == 1){
                    $this->Session->write('User.email', 1);    
                } else {
                    $this->Session->write('User.email', 0);    
                }
                $key = "E!EZ54L%9o2V(R3v9H%)y_%a6#z1Qykc";
                $expire = 0;
                $hashKey = 'ty6+J&~7$0!TbJb28Zl)VuB5ZY!43&rq';
                $hash = hash_hmac('sha256', (string)$accountId, $hashKey);
                $encryptData = openssl_encrypt((string)$accountId . '|' . $hash, 'AES-256-ECB', $key);
                setcookie('login_link', $encryptData, $expire, '', '.good-job.online', $this->secure, true);
                if (isset($this->request->query['redirect_url'])) {
                    $redirect = $this->request->query['redirect_url'];
                } else {
                    $redirect = $this->Auth->redirectUrl($this->Auth->loginRedirect);
                }
                return $this->redirect($redirect);
            }
            $this->Session->setFlash(__('ユーザー名かパスワードが無効です。'), 'flashmessage', array('type' => 'error'), 'error');
        }
        $this->set('account', $this->request->data['Account']);

    }

    public function logout()
    {
        $redirect = $this->Auth->logout();
        if (isset($_SERVER["HTTP_HOST"]) && isset($_SERVER["HTTP_REFERER"])) {
            $domainSystem = $_SERVER["HTTP_HOST"];
            $urlReferer = $_SERVER["HTTP_REFERER"];
            $domainReferer = parse_url($urlReferer, PHP_URL_HOST);

            if ($domainSystem != $domainReferer) {
                $redirect = $urlReferer;
            }
        }
        $this->Cookie->delete('remember_cookie');
        setcookie('login_link', '', time() - 60, '', '.good-job.online', $this->secure, true);
        $this->Session->delete('User.email');
        return $this->redirect($redirect);
    }

    public function edit_email(){
        $this->layout = 'custom_layout';
        $user = $this->Auth->user();
        $id = $user['id'];
        if (!empty($user)) {
            $this->set('title_for_layout', __('Edit email'));
            if (!$this->Account->exists($id)) {
                throw new NotFoundException('無効な投稿');
            }


            $account_id = $this->Auth->user('id');
            $account = $this->Account->find('first',array(
                'conditions' => array('Account.id' => $account_id),
                'recursive' => -1
            ));

            $this->set('data', $account);
            if (empty($account)) {
                $this->redirect(array('action' => 'admin_index'));
            }
            if ($this->request->is('post')) {
                $req_account = $this->request->data['Account'];
                if (!empty($req_account)) {
//                    if (
//                    !empty($req_account['email'])
//                    && $account['Account']['email'] != $req_account['email']
//                    ) {
                        $update['email'] = $req_account['email'];
                        $update['active'] = 0;
//                    }
//                        pr($update);die;
                    if (!empty($update)) {
                        $update['id'] = $account_id;
//                            pr($update);die;
                        $account = $this->Account->save($update);
                        if(!empty($account)) {
                            if (
                                (empty($account['Account']['active']) && !empty($req_account['email']))
                                || (!empty($req_account['email']) && $req_account['email'] != $account['Account']['email'])
                            ) {
                                $code = md5($account['Account']['id']);
                                $url = 'http://' . $_SERVER['SERVER_NAME'] . '/active/?code=' . $code;
                                $this->Account->saveAll(array(
                                    'id' => $account['Account']['id'],
                                    'code' => $code,
                                ));
                                $this->Email->send($url, $req_account['email']);
                            }
                            $this->Session->setFlash(__('Email xác nhận đã được gửi. Kích hoạt tài khoản của bạn như được mô tả trong email.'), 'flashmessage', array('type' => 'success'), 'success');
                        }else{

                            $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
                        }
                    }
                    $this->set('data', $this->request->data);
//                        $this->redirect(Controller::referer());
                }
            }


        } else {
            return $this->redirect(array('action' => 'login'));
        }
    }

    public function edit_profile()
    {
        $user = $this->Auth->user();
        $id = $user['id'];
        if (!empty($user)) {
            $this->set('title_for_layout', __('Edit profile'));
            if (!$this->Account->exists($id)) {
                throw new NotFoundException('無効な投稿');
            }
            $this->layout = "default";
            $account = $this->Auth->user();
            $frontend['index'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/index');
            $frontend['daily_settlement'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/daily_settlement');
            $frontend['budget_ranking'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/budget_ranking');
            $frontend['budget_sale'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/budget_sale');
            $frontend['user_page'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/user_page');
            $frontend['list_post'] = $this->Acl->check(array('Account' => $account), 'controllers/Frontend/list_post');
            $this->set(compact('frontend'));

            if (!empty($user['Employee']['id'])) {
                $employee_id = $user['Employee']['id'];
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
                        
                        $account_id = $this->Auth->user('id');
                        $account = $this->Account->find('first',array(
                            'conditions' => array('Account.id' => $account_id),
                            'recursive' => -1
                            ));
                        $updated = $this->admin_updateEmployee($req, $req_account, $req_relation);
                        if ($updated) {

                            $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');

                            $_SESSION['Auth']['Account']['Employee']['avatar'] = $updated['Employee']['avatar'];
                            $_SESSION['Auth']['Account']['Employee']['avatar_original'] = $updated['Employee']['avatar_original'];
                            $_SESSION['Auth']['Account']['email'] = $updated['Employee']['account']['email'];
                            $_SESSION['Auth']['Account']['Employee']['name'] = $updated['Employee']['name'];

                            if(
                                (empty($account['Account']['active']) && !empty($req_account['email']))
                                || (!empty($req_account['email']) && $req_account['email'] != $account['Account']['email'])
                            ){
                                $code = md5($account['Account']['id']);
                                $url = 'http://'.$_SERVER['SERVER_NAME'].'/active/?code='.$code;
                                $this->Account->saveAll(array(
                                    'id' => $account['Account']['id'],
                                    'code' => $code,
                                ));
                                $this->Email->send($url,$req_account['email']);
                            }

                            $this->redirect($this->referer());
                        } else {
                            $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
                            $this->redirect($this->referer());
                        }
                    }
                }

                $positions = $this->Position->find('list', array(
                    'fields' => array('id', 'name'),
                    'order' => array(
                        'created' => 'asc'
                    )
                ));

                $offices = $this->Office->find('list', array(
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

                if ($employee['Employee']['avatar'] != DEFAULT_AVATAR) {
                    $points = explode('_', preg_replace('/\.[^\.]+$/', '', $employee['Employee']['avatar']));
                    if (count($points) == 6) {
                        unset($points[0]);
                        $employee['Employee']['avatar_points'] = implode('_', $points);
                    }
                }

                $this->set('data', $employee['Employee']);
                $this->set(compact('companies', 'offices', 'positions', 'licenses', 'occupations', 'allowances'));
            } else {
                $config_js_validate = array(
                    'form_id' => 'account-validate',
                    'fields' => array(
                        'data[Account][name]' => array(
                            'required' => array('true', __(' please fill out this field.')),
                        ),
                        'data[Account][username]' => array(
                            'required' => array('true', __(' please fill out this field.')),
                            'minlength' => array(5, __(' password must be at least 5 characters long.')),
                            'maxlength' => array(50, __(' password is too long. ')),
                        ),
                        'data[Account][company_id]' => array(
                            'required' => array('true', __(' please fill out this field.')),
                        ),
                    ),
                    'addMethod' => array(),
                );
                $this->set('config_js_validate', $config_js_validate);
                if ($this->request->is(array('post', 'put'))) {
                    $data = $this->request->data;
                    $accoutn_data = array();
                    $accoutn_data['Account']['id'] = $id;
                    $accoutn_data['Account']['email'] = $data['Account']['email'];

                    if (!empty($data['Account']['password'])) {
                        $accoutn_data['Account']['password'] = $data['Account']['password'];
                        $accoutn_data['Account']['confirm_password'] = $data['Account']['confirm_password'];
                    }

                    // pr($accoutn_data);
                    // die;
                    if ($this->Account->save($accoutn_data)) {
                        if (!empty($data['Account']['avatar']['input']) && !empty($data['Account']['avatar']['points']) && !empty($data['Account']['avatar']['original']) && $data['Account']['avatar']['original'] != 'default') {
                            $avatar_name = $this->admin_create_upload_avatar_name($data['Account']['avatar']['points']);
                        } else if (!empty($data['Account']['avatar']['input']) && !empty($data['Account']['avatar']['points']) && (empty($data['Account']['avatar']['original']) || $data['Account']['avatar']['original'] == 'default')) {
                            $avatar_name = $this->admin_create_upload_avatar_name($data['Account']['avatar']['points'], empty($data['Account']['avatar']['original']) ? (
                            !empty($user['Admin']['avatar_original']) ? preg_replace('/\.[^\.]+$/', '', $user['Admin']['avatar_original']) : 'default') : 'default');
                        } else {
                            $avatar_name = array(
                                'ava' => $user['Admin']['avatar'],
                                'ori' => $user['Admin']['avatar_original']
                            );
                        }

                        $admin = $this->Admin->find('first', array(
                            'conditions' => array('Admin.account_id' => $id)
                        ));
                        $admin_data = array();
                        if (!empty($admin)) {
                            $admin_data['Admin']['id'] = $admin['Admin']['id'];
                        }
                        $admin_data['Admin']['account_id'] = $id;
                        $admin_data['Admin']['profile'] = $data['Account']['profile'];
                        $admin_data['Admin']['name'] = $data['Account']['name'];
                        $admin_data['Admin']['avatar'] = $avatar_name['ava'];
                        $admin_data['Admin']['avatar_original'] = $avatar_name['ori'];
                        $admin_data['Admin']['headquarter'] = $data['Account']['headquarter'];

                        if (empty($admin)) {
                            $admin_data['Admin']['created'] = date('Y-m-d H:i:s');
                        }
                        if ($admin = $this->Admin->save($admin_data)) {
                            if (!empty($avatar_name['ori'])) {
                                if ($this->admin_upload_avatar($data['Account']['avatar']['original'], $data['Account']['avatar']['input'], $admin['Admin']['avatar_original'], $admin['Admin']['avatar'])) {
                                }
                            }
                            $this->Session->setFlash(__('success saved'), 'flashmessage', array('type' => 'success'), 'success');
                            $_SESSION['Auth']['Account']['Admin']['id'] = $admin['Admin']['id'];
                            $_SESSION['Auth']['Account']['Admin']['avatar'] = $admin['Admin']['avatar'];
                            $_SESSION['Auth']['Account']['Admin']['avatar_original'] = $admin['Admin']['avatar_original'];
                            $_SESSION['Auth']['Account']['email'] = $data['Account']['email'];
                            $_SESSION['Auth']['Account']['Admin']['name'] = $data['Account']['name'];

                            return $this->redirect(array('controller' => 'accounts', 'action' => 'edit_profile'));
                        }
                    }
                }
                $options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
                $this->request->data = $this->Account->find('first', $options);

                if ($this->request->data['Admin']['avatar'] != DEFAULT_AVATAR) {
                    $points = explode('_', preg_replace('/\.[^\.]+$/', '', $this->request->data['Admin']['avatar']));
                    if (count($points) == 6) {
                        unset($points[0]);
                        $this->request->data['Admin']['avatar_points'] = implode('_', $points);
                    }
                }

                $companies = $this->Company->find('list');
                $this->set(compact('companies'));
            }
        } else {
            return $this->redirect(array('action' => 'login'));
        }
    }

    public function forgot_password()
    {
        $this->set('title_for_layout', __('Forgot password'));
        $this->layout = 'default';

        $config_js_validate = array(
            'form_id' => 'account-validate',
            'fields' => array(
                'data[Account][email]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                    'email' => array('true', __(' Please enter a valid email address.'))
                ),
            ),
            'addMethod' => array(),
        );
        $this->set('config_js_validate', $config_js_validate);
        if ($this->request->is(array('post', 'put'))) {
            $email = $this->request->data['Account']['email'];
            $account = $this->Account->find('first', array(
                'conditions' => array(
                    'Account.email' => $email
                )
            ));
            if (!empty($account)) {
                $pass_new = $this->randomPassword();
                $accoutn_data = array();
                $accoutn_data['Account']['id'] = $account['Account']['id'];
                $accoutn_data['Account']['password'] = $pass_new;
                if ($this->Account->save($accoutn_data)) {
                    try {


                        $Email = new CakeEmail('forgot_password');
                        $Email->template('forgot_password', 'default');
                        $Email->emailFormat('both');
                        $Email->viewVars(array('pass_new' => $pass_new, 'account' => $account));
                        $Email->from(array('noreply@good-job.online' => 'Caregiver Japan'));
                        $Email->to($email);
                        $Email->subject(__('forgot password'));
                        $Email->send();
                    } catch (Exception $e) {
                        var_dump($e->getMessage());
                        die;
                    }
                    $this->redirect(array(
                            'controller' => 'accounts',
                            'action' => 'forgot_password',
                            '?' => array(
                                'success' => 'success',
                                'email' => $email
                            ),
                            '#' => 'top')
                    );
                }
            } else {
                $error = __('Email không tồn tại trong hệ thống.');
                $this->set('error', $error);
            }
        }
    }

    private function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function admin_list_admin_user()
    {
        $this->set('title_for_layout', __('List admin user'));
        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'fields' => array('id', 'email', 'username', 'updated', 'Company.id', 'Company.name'),
            'conditions' => array(
                'Account.type' => 'admin'
            ),
            // 'paramType' => 'querystring',
            'order' => array(
                'Acvoutn.updated' => 'desc'
            ),
            'joins' => array(
                array(
                    'table' => 'companies',
                    'alias' => 'Company',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Admin.data_access_level = Company.id'
                    )
                ),
            ),
            'contain' => array(
                'Employee' => array(),
                'GroupPermission' => array(
                    'fields' => array('id', 'name'),
                ),
                'Admin' => array(
                    'fields' => array('id', 'account_id', 'data_access_level', 'name', 'avatar'),
                ),
                // 'Company' => array(
                //     'fields' => array('id', 'name'),
                // ),
            ),
        );
        $accounts = $this->Paginator->paginate('Account');
        $this->set(compact('accounts'));

        // pr($accounts);
        // die;
    }

    public function admin_register_admin_user()
    {
        $this->set('title_for_layout', __('Đăng ký admin user'));

        $config_js_validate = array(
            'form_id' => 'account-validate',
            'fields' => array(
                'data[Account][name]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                ),
                'data[Account][username]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                    'minlength' => array(5, __(' Username must be at least 5 characters long.')),
                    'maxlength' => array(50, __(' Username is too long. ')),
                ),
                'data[Account][email]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                    'email' => array('true', __(' Please enter a valid email address.')),
                ),
                'data[Account][password]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                    'minlength' => array(5, __(' password must be at least 5 characters long.')),
                    'maxlength' => array(100, __(' password is too long. ')),
                ),
                'data[Account][confirm_password]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                    'equalTo' => array("'#AccountPassword'", __('  mật khẩu này không khớp. Thử lại?'))
                ),
                'data[Account][company_id]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                ),
                'data[Account][profile]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                ),
            ),
            'addMethod' => array(),
        );
        $this->set('config_js_validate', $config_js_validate);

        if ($this->request->is('post')) {
            // $datasource = $this->Account->getDataSource();
            $isError = false;
            try {
                // $datasource->begin();

                $data = $this->request->data;
                $this->Account->create();
                $accoutn_data = array();
                $accoutn_data['Account']['username'] = $data['Account']['username'];
                $accoutn_data['Account']['password'] = $data['Account']['password'];

//                if($data['Account']['employee_register_only'] == true && $data['Account']['have_sale_permission'] == true) {
//                    $accoutn_data['Account']['group_permission_id'] = EMPLOYEE_AND_SALE_GROUP;
//                } else if($data['Account']['employee_register_only'] == true && $data['Account']['have_sale_permission'] == false) {
//                    $accoutn_data['Account']['group_permission_id'] = EMPLOYEE_REGISTER_ONLY_GROUP;
//                } else if($data['Account']['employee_register_only'] == false && $data['Account']['have_sale_permission'] == true) {
//                    $accoutn_data['Account']['group_permission_id'] = SALE_GROUP;
//                } else

                if ($data['Account']['company_id'] == 0) {
                    $accoutn_data['Account']['group_permission_id'] = SUPER_ADMIN_GROUP;
                } else {
                    $accoutn_data['Account']['group_permission_id'] = COMPANY_ADMIN_GROUP;
                }
                $accoutn_data['Account']['email'] = $data['Account']['email'];
                $accoutn_data['Account']['type'] = 'admin';
                $accoutn_data['Account']['created'] = date('Y-m-d H:i:s');

                if ($account = $this->Account->save($accoutn_data)) {
                    $avatar_name = $this->admin_create_upload_avatar_name($data['Account']['avatar']['points'], empty($data['Account']['avatar']['original']) ? 'default' : null);

                    $this->Admin->create();
                    $admin_data = array();
                    $admin_data['Admin']['account_id'] = $account['Account']['id'];
                    $admin_data['Admin']['data_access_level'] = $data['Account']['company_id'];
                    $admin_data['Admin']['name'] = $data['Account']['name'];
                    $admin_data['Admin']['avatar'] = $avatar_name['ava'];
                    $admin_data['Admin']['avatar_original'] = $avatar_name['ori'];
                    $admin_data['Admin']['profile'] = $data['Account']['profile'];
                    $admin_data['Admin']['created'] = date('Y-m-d H:i:s');
                    $admin_data['Admin']['headquarter'] = $data['Account']['headquarter'];
//                    $admin_data['Admin']['employee_register_only'] = $data['Account']['employee_register_only'];
//                    $admin_data['Admin']['have_sale_permission'] = $data['Account']['have_sale_permission'];

//                    $admin_data['Admin']['shift_authority_view'] = $data['Account']['shift_authority_view'];
//                    $admin_data['Admin']['shift_authority_edit'] = $data['Account']['shift_authority_edit'];
//                    $admin_data['Admin']['shift_authority_all'] = $data['Account']['shift_authority_all'];

                    if ($admin = $this->Admin->save($admin_data)) {
                        if (!(empty($data['Account']['avatar']['original']) && empty($data['Account']['avatar']['input']))) {
                            if ($this->admin_upload_avatar($data['Account']['avatar']['original'], $data['Account']['avatar']['input'], $admin['Admin']['avatar_original'], $admin['Admin']['avatar'])) {
                            } else {
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
                    // $datasource->rollback();
                    $this->Session->setFlash(__('アイテムを保存できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
                } else {
                    // $datasource->commit();
                    return $this->redirect(array('action' => 'list_admin_user'));
                    $this->Session->setFlash(__('アイテムが保存されました.'), 'flashmessage', array('type' => 'success'), 'success');
                }
            } catch (Exception $e) {
                $isError = true;
                // $datasource->rollback();
                $this->Session->setFlash(__('アイテムを保存できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
            }
        }

        $companies = $this->Company->find('list');

        $this->set(compact('companies'));
    }

    public function admin_change_admin_user($id)
    {
        $this->set('title_for_layout', __('Change admin user'));
        $config_js_validate = array(
            'form_id' => 'account-validate',
            'fields' => array(
                'data[Account][name]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                ),
                'data[Account][username]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                    'minlength' => array(5, __(' Username must be at least 6 characters long.')),
                    'maxlength' => array(50, __(' Username is too long. ')),
                ),
                'data[Account][company_id]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                ),
                'data[Account][email]' => array(
                    'required' => array('true', __(' please fill out this field.')),
                    'email' => array('true', __(' Please enter a valid email address.')),
                ),
            ),
            'addMethod' => array(),
        );
        $this->set('config_js_validate', $config_js_validate);

        if (!$this->Account->exists($id)) {
            throw new NotFoundException('無効な投稿');
        }

        if ($this->request->is(array('post', 'put'))) {
            $data = $this->request->data;
            $accoutn_data = array();
            $accoutn_data['Account']['id'] = $id;
            $accoutn_data['Account']['email'] = $data['Account']['email'];

            if ($data['Account']['username'] != $data['Account']['username_olr']) {
                $accoutn_data['Account']['username'] = $data['Account']['username'];
            }

            if (!empty($data['Account']['password'])) {
                $accoutn_data['Account']['password'] = $data['Account']['password'];
                $accoutn_data['Account']['confirm_password'] = $data['Account']['confirm_password'];
            }

//            if($data['Account']['employee_register_only'] == true && $data['Account']['have_sale_permission'] == true) {
//                $accoutn_data['Account']['group_permission_id'] = EMPLOYEE_AND_SALE_GROUP;
//            } else if($data['Account']['employee_register_only'] == true && $data['Account']['have_sale_permission'] == false) {
//                $accoutn_data['Account']['group_permission_id'] = EMPLOYEE_REGISTER_ONLY_GROUP;
//            } else if($data['Account']['employee_register_only'] == false && $data['Account']['have_sale_permission'] == true) {
//                $accoutn_data['Account']['group_permission_id'] = SALE_GROUP;
//            } else

            if ($data['Account']['company_id'] == 0) {
                $accoutn_data['Account']['group_permission_id'] = SUPER_ADMIN_GROUP;
            } else {
                $accoutn_data['Account']['group_permission_id'] = COMPANY_ADMIN_GROUP;
            }



            if ($this->Account->save($accoutn_data)) {
                //xoa avatar cu
                $avatar_name = $this->admin_create_upload_avatar_name($data['Account']['avatar']['points'], empty($data['Account']['avatar']['original']) ? 'default' : null);
                $admin = $this->Admin->find('first', array(
                    'conditions' => array('Admin.account_id' => $id)
                ));
                $admin_data = array();
                if (!empty($admin)) {
                    $admin_data['Admin']['id'] = $admin['Admin']['id'];
                }
                $admin_data['Admin']['account_id'] = $id;
                $admin_data['Admin']['data_access_level'] = $data['Account']['company_id'];
                $admin_data['Admin']['name'] = $data['Account']['name'];
                $admin_data['Admin']['profile'] = $data['Account']['profile'];
                $admin_data['Admin']['headquarter'] = $data['Account']['headquarter'];
//                $admin_data['Admin']['employee_register_only'] = $data['Account']['employee_register_only'];
//                $admin_data['Admin']['have_sale_permission'] = $data['Account']['have_sale_permission'];

//                $admin_data['Admin']['shift_authority_view'] = $data['Account']['shift_authority_view'];
//                $admin_data['Admin']['shift_authority_edit'] = $data['Account']['shift_authority_edit'];
//                $admin_data['Admin']['shift_authority_all'] = $data['Account']['shift_authority_all'];

                if (empty($admin)) {
                    $admin_data['Admin']['created'] = date('Y-m-d H:i:s');
                }
                if (!empty($avatar_name['ori'])) {
                    if (!empty($admin)) {
                        if ($admin['Admin']['avatar'] != DEFAULT_AVATAR) {
                            if (@fopen(AVATAR_PATH . $admin['Admin']['avatar'], "r")) {
                                unlink(AVATAR_PATH . $admin['Admin']['avatar']);
                            }
                            if (@fopen(AVATAR_PATH . $admin['Admin']['avatar_original'], "r")) {
                                unlink(AVATAR_PATH . $admin['Admin']['avatar_original']);
                            }
                        }
                    }

                    $admin_data['Admin']['avatar'] = $avatar_name['ava'];
                    $admin_data['Admin']['avatar_original'] = $avatar_name['ori'];
                } else {
                    if ($admin['Admin']['avatar'] != DEFAULT_AVATAR && $avatar_name['ava'] == DEFAULT_AVATAR) {
                        $admin_data['Admin']['avatar'] = $admin['Admin']['avatar'];
                        $admin_data['Admin']['avatar_original'] = $admin['Admin']['avatar_original'];
                    } else {
                        if ($admin['Admin']['avatar'] != DEFAULT_AVATAR) {
                            if (@fopen(AVATAR_PATH . $admin['Admin']['avatar'], "r")) {
                                unlink(AVATAR_PATH . $admin['Admin']['avatar']);
                            }
                        }
                        $admin_data['Admin']['avatar'] = $avatar_name['ava'];
                        $admin_data['Admin']['avatar_original'] = $admin['Admin']['avatar_original'];
                    }
                }
                if ($admin = $this->Admin->save($admin_data)) {
                    if (!empty($avatar_name['ori'])) {
                        if ($this->admin_upload_avatar($data['Account']['avatar']['original'], $data['Account']['avatar']['input'], $admin['Admin']['avatar_original'], $admin['Admin']['avatar'])) {
                            $this->Session->setFlash('アイテムが保存されました.', 'flashmessage', array('type' => 'success'), 'success');
                        } else {
                            $this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
                        }
                    } else {
                        if ($admin['Admin']['avatar'] != DEFAULT_AVATAR && $avatar_name['ava'] != DEFAULT_AVATAR) {
                            if ($this->admin_upload_avatar($data['Account']['avatar']['original'], $data['Account']['avatar']['input'], $admin['Admin']['avatar_original'], $admin['Admin']['avatar'])) {
                                $this->Session->setFlash('アイテムが保存されました.', 'flashmessage', array('type' => 'success'), 'success');
                            } else {
                                $this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
                            }
                        } else {
                            $this->Session->setFlash('アイテムが保存されました.', 'flashmessage', array('type' => 'success'), 'success');
                        }
                    }

                    // rewrite auth session
                    $this->Base->rewriteAuthSession( $admin_data['Admin']['account_id'] );

                    $this->redirect(Controller::referer());
                } else {
                    $this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
                }
            } else {
                $this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
            }
        }

        $options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
        $this->request->data = $this->Account->find('first', $options);

        if ($this->request->data['Admin']['avatar'] != DEFAULT_AVATAR) {
            $points = explode('_', preg_replace('/\.[^\.]+$/', '', $this->request->data['Admin']['avatar']));
            if (count($points) == 6) {
                unset($points[0]);
                $this->request->data['Admin']['avatar_points'] = implode('_', $points);
            }
        }

        $companies = $this->Company->find('list');
        $this->set(compact('companies'));

        // pr( $this->request->data );die;
        // $this->set(compact('parentCategories'));
    }

    public function admin_delete_admin_user($id)
    {
        $this->Account->id = $id;
        if (!$this->Account->exists()) {
            throw new NotFoundException('無効な投稿');
        }
        $admin = $this->Admin->find('first', array(
            'conditions' => array('Admin.account_id' => $id)
        ));
        if ($admin['Admin']['avatar'] != DEFAULT_AVATAR) {
            if (@fopen(AVATAR_PATH . $admin['Admin']['avatar'], "r")) {
                unlink(AVATAR_PATH . $admin['Admin']['avatar']);
            }
            if (@fopen(AVATAR_PATH . $admin['Admin']['avatar'], "r")) {
                unlink(AVATAR_PATH . $admin['Admin']['avatar_original']);
            }
        }
        $this->Admin->deleteAll([
            'Admin.account_id' => $id,
        ]);
        $this->Post->deleteAll([
            'Post.account_id' => $id,
        ]);
        if ($this->Account->delete()) {
            $this->UserSession->deleteAll(array(
                'UserSession.account_id' => $id
            ));
            $this->Session->setFlash('アイテムが保存されました.', 'flashmessage', array('type' => 'success'), 'success');
        } else {
            $this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
        }
        return $this->redirect(array('action' => 'list_admin_user'));
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

    private function admin_updateEmployee($data, $data_account, $data_relation)
    {
        $response = false;
        $saved = null;

        $datasource = $this->Employee->getDataSource();
        $isError = false;
        // try {
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
            if (1) {
                $office = $this->Office->find('first', array(
                    'conditions' => array(
                        'id' => $data['office_id'],
                        'company_id' => $data['company_id']
                    ),
                    'recursive' => -1
                ));
            } else {

            }
            if (!empty($office)) {
                $group_permission_id = $this->CompanyGroup->find('first', array(
                    'fields' => array('group_permission_id'),
                    'conditions' => array(
                        'id' => $office['Office']['company_group_id']
                    ),
                    'recursive' => -1
                ));

                $update = array();
                /* if (
                    !empty($group_permission_id)
                    && $group_permission_id['CompanyGroup']['group_permission_id'] != $employee['Account']['group_permission_id']
                ) {
                    $update['group_permission_id'] = $group_permission_id['CompanyGroup']['group_permission_id'];
                } */
                if (
                    !empty($data_account['username'])
                    && $data_account['username'] != $employee['Account']['username']
                ) {
                    $update['username'] = $data_account['username'];
                }

//                if (
//                    !empty($data_account['email'])
//                    && $data_account['email'] != $employee['Account']['email']
//                ) {
                $update['email'] = $data_account['email'];
//                }
                if (
                    empty($data_account['email'])
                ) {
                $update['active'] = 0;
                }

                if (!empty($data_account['password'])) {
                    $update['password'] = trim($data_account['password']);
                }
                if (!empty($update)) {
//                    $this->Account->id = $employee['Account']['id'];
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
                        'company_id' => $office['Office']['company_id'],
                        'office_id' => $office['Office']['id'],
                        'position_id' => $data['position_id'],
                        'company_group_id' => $office['Office']['company_group_id'],
                        'name' => trim($data['name']),
                        'gender' => $data['gender'],
                        'kana_name' => trim($data['kana_name']),
                        'in_office' => $data['in_office'],
                        'basic_salary' => (double)$data['basic_salary'],
                        'daily_wage' => (double)$data['daily_wage'],
                        'hourly_wage' => (double)$data['hourly_wage'],
                        'traffic_type' => !empty($data['traffic_type']) ? implode(',', $data['traffic_type']) : '',
                        'public_transportation' => (double)$data['public_transportation'],
                        'vehicle_cost' => (double)$data['vehicle_cost'],
                        'one_way_transportation' => (double)$data['one_way_transportation'],
                        'round_trip_transportation' => (double)$data['round_trip_transportation'],
                        'commute_route' => trim($data['commute_route']),
                        'social_insurance' => (double)$data['social_insurance'],
                        'employment_insurance' => (double)$data['employment_insurance'],
                        'join_date' => date('Y-m-d', strtotime($data['join_date'])),
                        'postal_code' => trim($data['postal_code']),
                        'prefecture' => trim($data['prefecture']),
                        'municipality' => trim($data['municipality']),
                        'municipal_town' => trim($data['municipal_town']),
                        'phone' => trim($data['phone']),
                        'basis_pension_number' => trim($data['basis_pension_number']),
                        'dob' => date('Y-m-d', strtotime($data['dob'])),
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
                    );

                    $this->Employee->id = $employee['Employee']['id'];
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
        // } catch(Exception $e) {
        //     $isError = true;
        //     $datasource->rollback();
        //     $response = false;
        // }

        return $response;
    }

}