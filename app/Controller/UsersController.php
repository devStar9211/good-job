<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $uses = array('User');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('admin_logout', 'admin_change_password','admin_profile', 'admin_login');
    }
    
    /**
     * index method
     *
     * @return void
     */
    public function admin_index() {
        $this->set('title_for_layout', '管理者アカウント管理');
        $this->User->recursive = 0;
        $search = $this->request->query;
        $conditions = array();
        if(isset($search['username']))
            $conditions = array_merge($conditions, array('User.username LIKE' => "%" . $search['username'] . "%"));
        if(isset($search['email']))
            $conditions = array_merge($conditions, array('User.email LIKE' => "%" . $search['email'] . "%"));
        if(isset($search['status']) && is_numeric($search['status']))
            $conditions = array_merge($conditions, array('User.status' => $search['status']));

        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions' => $conditions,
            'paramType' => 'querystring',
            'order' => array(
                'updated' => 'desc'
            ),
        );
        try {
            $user_list = $this->Paginator->paginate('User');
        } catch (NotFoundException $e) {
            //Do something here like redirecting to first or last page.
            //$this->request->params['paging'] will give you required info.
            $this->request->query['page'] = 1;
            $this->redirect(array('action' => 'index', '?' => $this->request->query,'admin' => true));
        }
        $this->set('accounts', $user_list);
        $this->set('is_search', isset($search['search']) ? 1 : 0);
        $this->set('search', $search);
    }

    /**
     * add method
     *
     * @return void
     */
    public function admin_add() {
        $this->set('title_for_layout', 'ユーザー登録');
        $this->User->changeAccountValidation();
        if ($this->request->is('post')) {
            $this->User->create();
            $this->request->data['User']['profile_picture'] = '';
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('アイテムが保存されました'), 'flashmessage', array('type' => 'success'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('アイテムを保存できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        $this->set('title_for_layout', 'アドミンアカウント編集');
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('アカウントは無効です'));
        }
        $this->User->changeAccountValidation();
        $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
        $account = $this->User->find('first', $options);
        if ($this->request->is(array('post', 'put'))) {
            $data = array();
            $data['User']['id'] = $account['User']['id'];
            $data['User']['email'] = $this->request->data['User']['email'];
            $data['User']['full_name'] = $this->request->data['User']['full_name'];
            $data['User']['status'] = $this->request->data['User']['status'];
            if ($this->User->save($data)) {
                $this->Session->setFlash(__('アイテムが保存されました'), 'flashmessage', array('type' => 'success'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('アイテムを保存できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
            }
        } else {
            $this->request->data = $account;
        }
        $this->set('account', $account);
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('アカウントは無効です'));
        }
        if($this->User->has_managed_model($id)){
            $this->Session->setFlash(__('このマネジャーが管理されるモデルがあり、削除できません。'), 'flashmessage', array('type' => 'error'), 'error');
        } else if ($this->User->delete()) {
            $this->Session->setFlash(__('アイテムは削除されました。'), 'flashmessage', array('type' => 'success'), 'success');
        } else {
            $this->Session->setFlash(__('項目は削除できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function admin_login(){
        if($this->Auth->user()) return $this->redirect($this->Auth->redirectUrl());
        $this->layout = 'alogin';
        setcookie('remember',1, -1);
        if ($this->request->is('post')) {
            $year =  time() + 86400;
            if(!empty($_POST['data']['User']['remember'])) {
                setcookie('usr', $_POST['data']['User']['username'], $year);
                setcookie('pwd', $_POST['data']['User']['password'], $year);
                setcookie('remember', $_POST['data']['User']['remember'], $year);
            }else{
                unset($_COOKIE['usr']);
                unset($_COOKIE['pwd']);
                unset($_COOKIE['remember']);
                setcookie('usr', null, -1, '/');
                setcookie('pwd', null, -1, '/');
                setcookie('remember', null, -1, '/');
            }
            if ($this->Auth->login()) {
                $user = $this->Auth->user();
                $data = array('User' => array(
                   'id' => $user['id'],
                   'last_login' => date('Y-m-d H:i:s')
                ));
                $this->User->save($data);

                return $this->redirect($this->Auth->redirectUrl($this->Auth->loginRedirect));
            }
            
            $this->Session->setFlash(__('ユーザー名かパスワードが無効です。'), 'flashmessage', array('type' => 'error'), 'error');
        }
    }

    public function admin_logout() {
        return $this->redirect($this->Auth->logout());
    }
    
    public function admin_change_password($id = null){
        $this->User->changeAccountValidation();
        $current_login = FALSE;
        $user_login = $this->Auth->user();
        if($id === null || $id === $user_login['id']){
            $current_login = TRUE;
            $id = $user_login['id'];
            $this->set('current_login', 1);
            $this->set('title_for_layout', 'パスワード変更');
        }else{
            $this->set('title_for_layout', 'アドミンのパスワード変更');
        }
        
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('アカウントは無効です'));
        }

        $targetUser = $this->User->find('first', array(
            'conditions' => array('User.id' => $id)
        ));

        if($user_login['id'] != $targetUser['User']['id']){

            throw new UnauthorizedException('あなたがページにアクセスする権限がありません。');

        }
        if ($this->request->is(array('post', 'put'))) {
                $data = $this->request->data;
                $str_mess = '';

                if($current_login){
                    $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
                    $current_user = $this->User->find('first', $options);
                    $newHash = Security::hash($this->request->data['User']['old_password'], 'blowfish', $current_user['User']['password']);
                    $correct = $current_user['User']['password'] == $newHash;
                    if(!$correct){
                        $str_mess = "現在のパスワードは正しくない.";
                        $this->User->validationErrors['old_password'] = array("現在のパスワードは正しくない.");
                    }
                }
                $data_update = array();
                $data_update['User']['password'] = $data['User']['password'];
                $data_update['User']['confirm_password'] = $data['User']['confirm_password'];
                $data_update['User']['id'] = $id;
                if ($this->User->save($data_update)) {
                    
                    $this->Session->setFlash(__('パスワードが変更されました.'), 'flashmessage', array('type' => 'success'), 'success');
                    if($user_login['id'] == $targetUser['User']['id']){
                        return $this->redirect($this->request->here);
                    }
                    return $this->redirect(array('controller' => 'dashboard', 'action' => 'index', 'admin' => true));

                } else {
                    $this->Session->setFlash(__('パスワードが変更されなかった。'), 'flashmessage', array('type' => 'error'), 'error');
                }
        }
    }
}
