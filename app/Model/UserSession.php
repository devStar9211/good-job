<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');


class UserSession extends AppModel {
	public $useDbConfig = 'default';
	public $useTable = 'cake_sessions';
	public $primaryKey = 'id';

	//public $belongsTo = array('Account');

	public function beforeSave($options = [])
	{
		$this->data[$this->alias]['account_id'] = AuthComponent::user('id');

		return true;
	}
}