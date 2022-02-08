<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	public $actsAs = array('Containable');
	public $recursive = 1;

	public function beforeFind($queryData) {

		if (class_exists('AuthComponent')) {
			$user = AuthComponent::user();

			if(!empty($user) && !empty($user['Employee']['id'])){
				switch($this->alias) {
					case 'Company': {
							$_company_group = ClassRegistry::init('CompanyGroup');
							$company_group = $_company_group->find('list', array(
								'fields' => array('id', 'name'),
								'conditions' => array(
									'id' => $user['Employee']['company_group_id']
								),
								'recursive' => -1
							));

							if(
								!empty($company_group)
								&& current($company_group) == 'グループ外企業'
							) {
								$queryData['conditions'][$this->alias.'.company_group_id'] = key($company_group);
							}
						} break;

					case 'Office': {
							$_company_group = ClassRegistry::init('CompanyGroup');
							$company_group = $_company_group->find('list', array(
								'fields' => array('id', 'name'),
								'conditions' => array(
									'id' => $user['Employee']['company_group_id']
								),
								'recursive' => -1
							));

							if(
								!empty($company_group)
								&& current($company_group) == 'グループ外企業'
							) {
								$queryData['conditions'][$this->alias.'.company_group_id'] = key($company_group);
							}
						} break;
				}
			}

			if(!empty($user) && !empty($user['Admin']['data_access_level'])){
				switch($this->alias) {
					case 'Company': {
							$queryData['conditions'][$this->alias.'.id'] = $user['Admin']['data_access_level'];
						} break;
					case 'Office':
					case 'Employee': {
							$queryData['conditions'][$this->alias.'.company_id'] = $user['Admin']['data_access_level'];
						} break;
					case 'CompanyGroup': {
							$_company = ClassRegistry::init('Company');
							$company_group_id = $_company->find('list', array(
								'fields' => array('company_group_id'),
								'conditions' => array('id' => $user['Admin']['data_access_level'])
							));
							$queryData['conditions'][$this->alias.'.id'] = current($company_group_id);
						} break;
				}
			}
		}
		
		return $queryData;
	}

	public function beforeSave($options = array()) {
		if (class_exists('AuthComponent')) {
			foreach($this->data as $alias => $data) {
				$model = ClassRegistry::init($alias);
				if(empty($model->id)) {
					$this->data[$alias]['created'] = date('Y-m-d H:i:s');
				}

				$user = AuthComponent::user();
				if(!empty($user) && !empty($user['Admin']['data_access_level'])){
					switch($alias) {
						case 'GroupPermission' : {
								return false;
							} break;
						case 'CompanyGroup': {
								return false;
							} break;
						case 'Company': {
								if(
									!empty($this->id)
									&& $this->id != $user['Admin']['data_access_level']
								) {
									return false;
								} else if(
									empty($this->id)
								) {
									return false;
								}
							} break;
						case 'Office': {
								$_company = ClassRegistry::init('Company');
								$company_group_id = $_company->find('list', array(
									'fields' => array('company_group_id'),
									'conditions' => array('id' => $user['Admin']['data_access_level'])
								));

								if(
									isset($data['company_id'])
									&& ($data['company_id'] != $user['Admin']['data_access_level']
									|| $data['company_group_id'] != current($company_group_id))
								) { return false; }

							} break;
						case 'Employee': {
								$_office = ClassRegistry::init('Office');
								$company_id = $_office->find('list', array(
									'fields' => array('company_id'),
									'conditions' => array('id' => $data['office_id'])
								));

								if(
									(
										$data['office_id'] !== null
										&& $data['company_id'] != current($company_id)
									) || $data['company_id'] != $user['Admin']['data_access_level']
								) { return false; }
							} break;
					}
				}
			}
		}
		return true;
	}

	public function beforeDelete($cascade = true) {
		if (class_exists('AuthComponent')) {
			$user = AuthComponent::user();
			if(!empty($user) && !empty($user['Admin']['data_access_level'])){
				switch($this->alias) {
					case 'Company': {
							if(
								$this->id != $user['Admin']['data_access_level']
							) { return false; }
						} break;
					case 'Office':
					case 'Employee': {
							$company_id = $this->find('list', array(
								'fields' => array('company_id'),
								'conditions' => array('id' => $this->id)
							));

							if(
								current($company_id) != $user['Admin']['data_access_level']
							) { return false; }
						} break;
				}
			}
		}
		return true;
	}

    public function get_flag_update_sale()
    {
        $Config = ClassRegistry::init('Config');

        $config = $Config->find('first', array(
            'conditions' => array('Config.key' => 'flag_update_sale')
        ));
        $flag_update_sale = !empty($config) ? unserialize($config['Config']['value']) : false;
        return $flag_update_sale;
    }

    function getLastQuery()
    {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
    }

}
