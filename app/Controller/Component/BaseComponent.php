<?php

App::uses('Component', 'Controller');

class BaseComponent extends Component
{
    public function isEmployeeRegisterOnly($user)
    {
        return isset($user['Employee']['employee_register_only']) && $user['Employee']['employee_register_only'] == true ? true : false;
    }

    public function haveSalePermission($user)
    {
        return isset($user['Admin']['have_sale_permission']) && $user['Admin']['have_sale_permission'] == true ? true : false;
    }

    public function getGridConfig($user)
    {
        $grid = Configure::read('Grid');
        // get grid config data
        $gcModel = ClassRegistry::init('Config');
        $gridConfig = $gcModel->find('first', array(
            'conditions' => array('Config.key' => 'grid')
        ));
        $gridConfig = !empty($gridConfig) ? unserialize($gridConfig['Config']['value']) : '';

        if ($gridConfig != '') {
            if (

                (empty($user['Employee']['id']) && $user['Admin']['data_access_level'] == 0) ||
                (empty($user['Employee']['id']) && $user['Admin']['data_access_level'] != 0)
            ) {
                // Admin User
                // Company Admin User
//                $gridConfig = array(COL_1, COL_2, COL_3, COL_4, COL_5, COL_6, COL_7, COL_8, COL_9, COL_10, COL_11, COL_12, COL_13, COL_14, COL_15, COL_20);
                $gridConfig = array_keys($grid);
            } else {
                // Employee User
                $gridConfig = isset($gridConfig[$user['Employee']['company_group_id']]) ? $gridConfig[$user['Employee']['company_group_id']] : array();
            }
        }
        return $gridConfig;
    }

    public function rewriteAuthSession($accountId)
    {

        $accountModel = ClassRegistry::init('Account');
        $sessionModel = ClassRegistry::init('UserSession');

        $accountModel->unbindModel(
            array('hasMany' => array('UserSession'))
        );

        $session = $sessionModel->find('first', array(
            'conditions' => array('UserSession.account_id' => $accountId)
        ));

        if (!empty($session)) {
            $sData = $session['UserSession']['data'];
            $sData = explode('|', $sData);
            unset($sData[3]);

            $newData = $accountModel->find('first', array(
                'conditions' => array('Account.id' => $accountId)
            ));
            $newAccount = array_shift($newData);
            $newAccount = array_merge($newAccount, $newData);
            $newAccount['Account'] = $newAccount;

            $sData[3] = serialize($newAccount);
            $sData = implode('|', $sData);
            $sessionModel->query("UPDATE `cake_sessions` SET `data` ='{$sData}' WHERE `account_id` = '{$accountId}'");

        }
    }
	
	public function get_start_month($office_id)
    {
        $CompanyGroup = ClassRegistry::init('CompanyGroup');

        $companyGroup = $CompanyGroup->find('first', array(
            'conditions' => array(
                'CompanyGroup.id' => 1
            ),
            'recursive' => -1
        ));
        $start_month = $companyGroup['CompanyGroup']['start_month'];
        return $start_month;
    }

    public function get_quarter($start_month, $model = null)
    {
        $model = $model != null ? $model.'.' : '';
        $yearNow = date('Y');
        $monthNow = date('m')+0;
        $end_month = $start_month-1;
        $yearQuarter = $monthNow < $start_month ? $yearNow-1 : $yearNow;

        $date_quarters = array(
            'quarters'=>'',
            'current_quarter'=>'',
        );
        $q = $quarter = $current_quarter = 0;
        $n_month = 12+$end_month;

        for($i=$start_month; $i <= $n_month; $i++ ){
            $q++;
            if($q%3 == 0 || $i == $start_month){
                $quarter ++;
                $q =0;
                $date_quarters['quarters'][$quarter]['quarter'] = $quarter;
            }
            $month = $i > 12 ? $i - 12 : $i;
            $year= ($i > 12) ? $yearQuarter+1 : $yearQuarter;
            $date_quarters['quarters'][$quarter]['date'][$i][$model.'year'] = $year;
            $date_quarters['quarters'][$quarter]['date'][$i][$model.'month'] = $month;
            $date_quarters['quarters'][$quarter]['year_quarter'] = $yearQuarter;
            if($date_quarters['quarters'][$quarter]['date'][$i][$model.'month'] == $monthNow){
                $date_quarters['current_quarter'] = $date_quarters['quarters'][$quarter];
            }
        }
        $date_quarters['current_quarter']['year_quarter'] = $yearQuarter;
         
        return $date_quarters;
    }


    public function get_quarter_by_date($start_month, $model = null, $quarter = null, $year = null)
    {
        $str_model = $model != null ? $model . '.' : '';
        $end_month = $start_month - 1;

        $date_quarters = array(
            'quarters' => '',
        );
        $q = $_quarter = $current_quarter = 0;
        $n_month = 12 + $end_month;

        for ($i = $start_month; $i <= $n_month; $i++) {
            $q++;
            $_month = $i > 12 ? $i - 12 : $i;
            $_year = ($i > 12) ? $year + 1 : $year;
            if ($q % 3 == 0 || $i == $start_month) {
                $_quarter++;
                $q = 0;
                $date_quarters['quarters'][$_quarter]['quarter'] = $_quarter;
                $date_quarters['quarters'][$_quarter]['year_quarter'] = $year;
            }

            $date_quarters['quarters'][$_quarter]['date'][$i][$str_model . 'year'] = $_year;
            $date_quarters['quarters'][$_quarter]['date'][$i][$str_model . 'month'] = $_month;

        }
        if($quarter != null && isset($date_quarters['quarters'][$quarter])) {
            $date_quarters['quarter_select'] = $date_quarters['quarters'][$quarter];
            $date_quarters['quarter_select']['year_quarter'] = $year;
        }
        $get_quarter = $this->get_quarter($start_month, $model);
        $date_quarters['current_quarter'] = $get_quarter['current_quarter'];
        return $date_quarters;
    }
//User.email
    public  function requiredEmail($data){
//        pr($data);die;
        $ret = array('require' => 0, 'mess' => '', 'btn_submit' => '登録する');
        if(!empty(
            isset($data['User']['email'])
            && $data['User']['email'] != 1
            && $data['Auth']['Account']['type'])
            && $data['Auth']['Account']['type'] == 'employee'
        ){
            if ($data['Auth']['Account']['email'] != '' && empty($data['Auth']['Account']['active'])){
                $ret['mess'] = 'アカウントの有効化を行ってください';
                $ret['btn_submit'] = '有効化する';
                $ret['require'] = 1;
            }else if($data['Auth']['Account']['email'] == ''){
                $ret['mess'] = 'メールアドレスが未記入です。';
                $ret['require'] = 1;
            }else{
                $ret['require'] = 0;
            }
        }else{
            $ret['require'] = 0;
        }
        return $ret;
    }
}