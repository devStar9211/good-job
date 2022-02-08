<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

    public $name = "User";

    //check exit user
    public function checkExitUser($username) {
        if (!empty($username)) {
            $username_exist = $this->find('first', array(
                'conditions' => array('User.username' => $username)
            ));
            if ($username_exist) {
                return $username_exist;
            }
        }
        return FALSE;
    }

    //get latest user
    public function getLatestUser() {
        $user = $this->find('first', array('order' => array('User.id' => 'DESC')));
        if ($user) {
            $id = $user['User']['id'];
            return $id;
        }
        return FALSE;
    }

    public function insertAccount($data) {
        if (count($data)) {
            $id_instagram = $data->user->id;
            if (!($account = $this->checkInstagramExits($id_instagram))) {
                //Account not exits
                $data_user = array(
                    'username' => $data->user->username,
                    'email' => $data->user->email,
                    'full_name' => $data->user->full_name,
                    'description' => $data->user->bio,
                    'birthday' => date('Y/m/d', strtotime($data->user->birthday)),
                    'instagram_id' => $id_instagram,
                    'instagram_access_token' => $data->access_token,
                    'profile_picture' => $data->user->profile_picture
                );
                return $this->save($data_user);
            } else {
                //Account exits
                $token = $data->access_token;
                if (!$this->checkTokenExits($id_instagram, $token)) {
                    //Delete acount old
                    if ($this->deleteAll(array('User.instagram_id' => $id_instagram))) {
                        return $this->insertAccount($data);
                    }
                }
            }
        }
        return FALSE;
    }

    public function listUserByType($account_type = 0, $page = 1) {
        return $this->find('all', array(
                    'conditions' => array(
                        'User.account_type' => $account_type,
                        'User.instagram_id <>' => '',
                        'User.instagram_username <>' => '',
                        'User.instagram_access_token <>' => '',
                    ),
                    'limit' => NUM_MODELS_SHOW,
                    'page' => $page,
                    'order' => array('User.total_follows' => 'desc')
        ));
    }

    public function countUserByType($account_type = 0) {
        return $this->find('count', array(
                    'conditions' => array(
                        'User.account_type' => $account_type,
                        'User.instagram_id <>' => '',
                        'User.instagram_username <>' => '',
                        'User.instagram_access_token <>' => '',
                    )
        ));
    }


    public function changeFollow($id_instagram, $follow = 0) {
        if (!$id_instagram) {
            return FALSE;
        }
        if ($follow) {
            $follow = 'unfollow';
        } else {
            $follow = 'follow';
        }
        $result = $this->instagram->modifyRelationship($follow, $id_instagram);
        if ($result->meta->code == 200) {
            $rs = $this->find('first', array(
                'conditions' => array('User.instagram_id' => $id_instagram)
            ));
            $total_follows = $rs['User']['total_follows'];
            if ($follow === 'unfollow') {
                $total_follows--;
            } elseif ($follow === 'follow') {
                $total_follows++;
            }
            $this->save(array(
                'id' => $rs['User']['id'],
                'total_follows' => $total_follows
            ));
            return array(
                'status' => $follow,
                'total_follows' => $total_follows
            );
        }
        return FALSE;
    }

    public function getAccountByID($id = 0) {
        if ($id) {
            $account = $this->find('first', array(
                'conditions' => array('User.id' => $id)
            ));
            if ($account) {
                $data_account = $account['User'];
                return $data_account;
            }
        }
        return FALSE;
    }


    public function edit_info($account_id = 0, $email = NULL) {
        if (!empty($email) && $account_id) {
            $result = $this->save(array(
                'id' => $account_id,
                'email' => $email
            ));
            if (count($result)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function delete_account($account_id = 0) {
        if ($account_id) {
            $result = $this->deleteAll(array('id' => $account_id));
            return $result;
        }
        return FALSE;
    }

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                    $this->data[$this->alias]['password']
            );
        }

        if (isset($this->data[$this->alias]['show_page'])) {
            $this->data[$this->alias]['show_page'] = serialize($this->data[$this->alias]['show_page']);
        }

        return true;
    }

    public function afterFind($results, $primary = false)
    {
        if (isset($this->data[$this->alias]['show_page'])) {
            $this->data[$this->alias]['show_page'] = unserialize($this->data[$this->alias]['show_page']);
        }
        return parent::afterFind($results, $primary); // TODO: Change the autogenerated stub
    }

    public function changeAccountValidation() {
        $this->validator()->remove('email');
        $this->validator()->add('username', array(
            'unique' => array(
                'rule' => 'isUnique',
                'required' => 'create',
                'message' => 'ユーザ名が存在していている。'
            ),
//            'alphanumeric' => array(
//                'rule' => 'alphanumeric',
//                'message' => '文字と数字のみ'
//            )
        ));

        $this->validator()->add('full_name', array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'message' => 'Full name must not be empty'
            )
        ));

        $this->validator()->add('password', array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'message' => 'パスワードが空でないでなければなりません'
            ),
            'size' => array(
                'rule' => array('lengthBetween', 4, 100),
                'message' => 'パスワードは、少なくとも4文字長さであるべき'
            )
        ));

        $this->validator()->add('status', array(
            'allowedChoice' => array(
                'rule' => array('inList', Configure::read('User')),
                'message' => 'ステータスは無効です。'
            )
        ));

        $this->validator()->add('email', array(
            'email' => array(
                'rule' => 'email',
                'message' => 'メールは無効です。',
                'allowEmpty' => true
            )
        ));

//        $this->validator()->add('full_name', array(
//            'alphaNumeric' => array(
//                'rule' => array('custom', '/^[A-Za-z0-9ぁ-ん一-龠ァ-ヴー .-_]+$/u'),
//                'message' => '文字と数字のみ',
//                'allowEmpty' => false
//            )
//        ));

        $this->validator()->add('confirm_password', array(
            'compare' => array(
                'rule' => array('validate_passwords'),
                'message' => 'パスワードに同じ値が必要',
            ),
        ));

        $this->validator()->add('old_password', array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => false,
                'message' => 'パスワードが空でないでなければなりません'
            ),
        ));

        $this->validator()->add('order', array(
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please enter only numbers',
                'allowEmpty' => true,
                'required' => false,
            )
        ));
    }

    public function validate_dimenstion($check){
        $uploadData = array_shift($check);
        list($width, $height, $type, $attr) = getimagesize($uploadData['tmp_name']);
        if($width == 319 && $height == 78) {
            return true;
        }
        return false;
    }

    public function validate_upload_file($check){
        $uploadData = array_shift($check);

        if ( $uploadData['size'] == 0 || $uploadData['error'] !== 0) {
            return false;
        }

        $uploadFolder = WWW_ROOT . 'files';
        $extension = pathinfo($uploadData['name'], PATHINFO_EXTENSION);
        $filename = $this->data['User']['social_username'].'_logo.'.$extension;
        $uploadPath =  $uploadFolder . DS . $filename;

        if( !file_exists($uploadFolder) ){
            mkdir($uploadFolder);
        }

        if (move_uploaded_file($uploadData['tmp_name'], $uploadPath)) {
            $this->set('logo', $filename);
            return true;
        }

        return false;
    }

    public function validate_passwords() {
        return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
    }

    public function isManagerBy($model, $manager) {
        return $this->field('id', array('id' => $model, 'manager' => $manager)) !== false;
    }

    public function has_post($id) {
        $posts = $this->Post->find('first', array(
            'conditions' => array('Post.account_id' => $id)
        ));
        return !empty($posts);
    }

    public function has_managed_model($id) {
        $model = $this->find('first', array(
            'conditions' => array('User.manager' => $id)
        ));
        return !empty($model);
    }

    /**
     * Get list users registered for menu
     * @return object
     * ======================================================
     * Author : ndhung.dev@gmail.com - 18/03/2016
     */
    function getUsersForMenu()
    {
        $keyCache = MENU_USERS;
        if ($records = Cache::read($keyCache))
            return $records;
        $records = $this->find('all', array(
                'fields' => 'User.social_username, User.username',
                'conditions' => array(
                    'User.registered' => 1
                ),
                'order' => 'User.id desc',
                'limit' => 3
            )
        );
    }
    /* show list brand in menu */
    function getBrandForMenu() {
        $records = $this->find('all', array(
                'fields' => 'User.show_page, User.full_name, User.profile_picture, User.social_username, User.logo, User.order',
                'conditions' => array(
                    'User.user_type' => 'brand'
                ),
                'order' => 'User.order desc',
                'limit' => 15
            )
        );

        return Set::Map($records);
    }

}
