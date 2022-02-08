<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class Account extends AppModel {
	public $actsAs = array('Acl' => array('type' => 'requester'));
	public $useTable = 'accounts';
	public $primaryKey = 'id';

	public $belongsTo = array(
		'GroupPermission' => array(
			'className' => 'GroupPermission',
			'foreignKey' => 'group_permission_id'
		)
	);
	public $hasOne = array(
		'Admin' => array(
			'className' => 'Admin',
			'foreignKey' => 'account_id',
		),
		// 'Admin' => array(
		// 	'className' => 'Admin',
		// 	'conditions' => array('Account.id' => 'Admin.account_id')
		// ),
		'Employee'
	);

	public $hasMany = array('UserSession');

	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'please fill out this field.'
			),
		),
		'email' => array(
			'unique' => array(
	            'rule' => 'isUnique',
	            'allowEmpty' => true,
	            'message' => 'email đã được sử dụng. Hãy thử dùng một email khác.'
	        ),
	        'email' => array(
		        'rule' => 'email',
		        'allowEmpty' => true,
		        'message' => 'Please supply a valid email address.'
		    )
		),
		'username' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'please fill out this field.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 100),
				'message' => 'username is too long.'
			),
			'minLength' => array(
				'rule' => array('minLength', 6),
				'message' => 'username must be at least 6 characters long.'
			),
			'pattern' => array(
				'rule' => '/^((([a-zA-Z0-9][a-zA-Z0-9_\.]+[a-zA-Z0-9])|[a-zA-Z0-9]*)|(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,})))$/',
				'message' => 'username contains invalid characters.'
			),
			'unique' => array(
	            'rule' => 'isUnique',
	            'required' => 'create',
	            'message' => 'username đã được sự dụng. Hảy thử dùng một username khác.'
	        ),
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'please fill out this field.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 100),
				'message' => 'password is too long.'
			),
			'minLength' => array(
				'rule' => array('minLength', 6),
				'message' => 'password must be at least 6 characters long.'
			)
		),
		'confirm_password' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'please fill out this field.'
			),
			'compare' => array(
                'rule' =>  array('validate_passwords'),
                'message' => 'mật khẩu này không khớp. Thử lại?',
            )
		),
		'company_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'please fill out this field.'
			),
		)
	);

    public function beforeSave($options = array()) {
    	parent::beforeSave();
    	
		if (isset($this->data[$this->alias]['password'])) {
		$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['password']
			);
		}

		return true;
    }

    public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['Account']['group_permission_id'])) {
            $groupId = $this->data['Account']['group_permission_id'];
        } else {
            $groupId = $this->field('group_permission_id');
        }
        if (!$groupId) {
            return null;
        }
        return array('GroupPermission' => array('id' => $groupId));
    }

    public function bindNode($user)
    {
        return array('model' => 'GroupPermission', 'foreign_key' => $user['Account']['group_permission_id']);
    }
    public function validate_passwords() {
        return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
    }

    public function unique_email($check) {
    	$is_unique = true;
    	if(!empty($check)) {
    		$is_unique = ($this->find('count', array('conditions' => $check, 'recursive' => -1)) == 0);
    	}

    	return $is_unique;
    }
}