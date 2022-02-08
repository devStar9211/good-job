<?php
App::uses('AppController', 'Controller');

/**
 * Companies Controller
 *
 * @property Company $Company
 * @property PaginatorComponent $Paginator
 */
class CompaniesController extends AppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');
    public $uses = array('Config', 'Company', 'CompanyGroup', 'EmailNotification');

    /**
     * index method
     *
     * @return void
     */
    public function admin_index()
    {
        $this->Company->recursive = 0;
        $this->set('title_for_layout', __('Companies'));
        $search = $this->request->query;
        $conditions = array();
        if (isset($search['name']))
            $conditions = array_merge($conditions, array('Company.name LIKE' => "%" . $search['name'] . "%"));

        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions' => $conditions,
            'paramType' => 'querystring',
            'order' => array(
                'id' => 'desc'
            ),
        );
        try {
            $companies = $this->Paginator->paginate('Company');
        } catch (NotFoundException $e) {
            //Do something here like redirecting to first or last page.
            //$this->request->params['paging'] will give you required info.
            $this->request->query['page'] = 1;
            $this->redirect(array('action' => 'index', '?' => $this->request->query, 'admin' => true));
        }
        $this->set('companies', $companies);
        $this->set('is_search', isset($search['search']) ? 1 : 0);
        $this->set('search', $search);

    }

    /**
     * add method
     *
     * @return void
     */
    public function admin_add()
    {
        $this->set('title_for_layout', __('Add company'));

        $config_js_validate = array(
            'form_id' => 'Company-validate',
            'fields' => array(
                'data[Company][name]' => array(
                    'required' => array('true', __(' Trường này là bắt buộc')),
                ),
                'data[Company][company_group_id]' => array(
                    'required' => array('true', __(' Trường này là bắt buộc')),

                ),
            ),
            'addMethod' => array(),
        );
        $this->set('config_js_validate', $config_js_validate);

        if ($this->request->is('post')) {
            $this->Company->create();
            $this->request->data['Company']['created'] = date('Y-m-d H:i:s');
            if ($this->Company->save($this->request->data)) {
                $this->Session->setFlash('アイテムが保存されました.', 'flashmessage', array('type' => 'success'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
            }
        }
        $companyGroups = $this->Company->CompanyGroup->find('list');

        $this->set(compact('companyGroups'));
    }


    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null)
    {
        $config_js_validate = array(
            'form_id' => 'Company-validate',
            'fields' => array(
                'data[Company][name]' => array(
                    'required' => array('true', __(' Trường này là bắt buộc')),
                ),
                'data[Company][company_group_id]' => array(
                    'required' => array('true', __(' Trường này là bắt buộc')),

                ),
            ),
            'addMethod' => array(),
        );
        $this->set('config_js_validate', $config_js_validate);

        $this->set('title_for_layout', __('Edit companies'));
        if (!$this->Company->exists($id)) {
            throw new NotFoundException('無効な投稿');
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Company']['id'] = $id;
            if ($this->Company->save($this->request->data)) {
                $this->Session->setFlash('アイテムが保存されました.', 'flashmessage', array('type' => 'success'), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
            }
        } else {
            $options = array('conditions' => array('Company.' . $this->Company->primaryKey => $id));
            $this->request->data = $this->Company->find('first', $options);
        }
        $companyGroups = $this->Company->CompanyGroup->find('list');
        $this->set(compact('companyGroups'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null)
    {
        $this->Company->id = $id;
        if (!$this->Company->exists()) {
            throw new NotFoundException('無効な投稿');
            $this->Session->setFlash('アイテムが保存されました.', 'flashmessage', array('type' => 'success'), 'success');
        }
        try {
            $this->Company->delete();
        } catch (Exception $e) {
            $this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function admin_config()
    {
        $gridConfig = $this->Config->find('first', array(
            'conditions' => array('Config.key' => 'grid')
        ));
        $gridConfig = !empty($gridConfig) ? unserialize($gridConfig['Config']['value']) : '';
        $this->set('gridConfig', $gridConfig);
        if ($this->request->is(array('post', 'put'))) {
            $this->Config->create();
            $this->Config->set(
                array(
                    'key' => 'grid',
                    'value' => serialize($this->request->data['Config'])
                )
            );

            if ($this->Config->save()) {
                $this->Session->setFlash(__('アイテムが保存されました.'), 'flashmessage', array('type' => 'success'), 'success');
                return $this->redirect(array('action' => 'admin_config'));
            } else {
                $this->Session->setFlash(__('アイテムを保存できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
            }
        }
        $this->set('title_for_layout', __('日次決算表示項目設定'));
        $companieGroups = $this->CompanyGroup->find('list');

        $this->set('companieGroups', $companieGroups);
        $this->set('pageId', 'config');
    }

    public function admin_daily_settlement_color_setting()
    {
        $config_key = 'daily_settlement_grid_color';
        $this->set('title_for_layout', __('Daily settlement chart group color setting'));
        $gridConfig = $this->Config->find('first', array(
            'conditions' => array('Config.key' => $config_key)
        ));
        $gridConfig = !empty($gridConfig) ? unserialize($gridConfig['Config']['value']) : '';
        $this->set('gridConfig', $gridConfig);

        $companies = $this->Company->find('list', array(
             'fields'=>array('id', 'name')
        ));
        $this->set('companies', $companies);

        if ($this->request->is(array('post', 'put'))) {
            $this->Config->create();
            $this->Config->set(
                array(
                    'key' => $config_key,
                    'value' => serialize($this->request->data)
                )
            );

            if ($this->Config->save()) {
                $this->Session->setFlash(__('アイテムが保存されました.'), 'flashmessage', array('type' => 'success'), 'success');
                $this->redirect(Controller::referer());
            } else {
                $this->Session->setFlash(__('アイテムを保存できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
            }
        }

        $companieGroups = $this->CompanyGroup->find('list');

        $this->set('companieGroups', $companieGroups);
        $this->set('pageId', 'config');
    }

    public function admin_company_notification()
    {
        $this->Company->recursive = 0;
        $this->set('title_for_layout', __('従業員登録通知設定'));
        $search = $this->request->query;
        $conditions = array();
        if (isset($search['name']))
            $conditions = array_merge($conditions, array('Company.name LIKE' => "%" . $search['name'] . "%"));

        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions' => $conditions,
            'paramType' => 'querystring',
            'order' => array(
                'id' => 'desc'
            ),
        );
        try {
            $companies = $this->Paginator->paginate('Company');
        } catch (NotFoundException $e) {
            //Do something here like redirecting to first or last page.
            //$this->request->params['paging'] will give you required info.
            $this->request->query['page'] = 1;
            $this->redirect(array('action' => 'index', '?' => $this->request->query, 'admin' => true));
        }
        $this->set('companies', $companies);
        $this->set('is_search', isset($search['search']) ? 1 : 0);
        $this->set('search', $search);
    }

    public function admin_email_list($id = null) {


        $model = 'EmailNotification';
        $this->uses[] = $model;
        if(!empty($this->data)){
            $response = false;
            $isError = false;
            $datasource = $this->EmailNotification->getDataSource();
            $datasource->begin();
            try {
                $postdata = $this->data;
                if (!empty($model) && !empty($postdata)) {
                    $dataSave = null;
                    if ($postdata['EditData']['id'] > 0) {
                        $dataSave['id'] = $postdata['EditData']['id'];
                        $dataSave['company_id'] = $postdata['company_id'];
                        $dataSave['name'] = trim($postdata['FormData']['name'][$postdata['EditData']['id']]);

                    } else {
                        $dataSave['id'] = '';
                        $dataSave['name'] = trim($postdata['EditData']['name']);
                        $dataSave['company_id'] = $postdata['company_id'];
                    }
                    $this->EmailNotification->create();
                    $saved = $this->EmailNotification->save($dataSave);
                    if ($saved) {
                        $response['data'] = $saved;
                        $isError = false;
                        $response['flash']['message'] = __('Item saved');
                        $response['flash']['type'] = 'success';
                    } else {

                        $isError = true;
                        $errorMessage = array();
                        $validationErrors = $this->EmailNotification->validationErrors;
                        foreach ($validationErrors as $key => $value) {
                            $errorMessage[] = $value[0];
                        }
                        $errorMessage = implode(' ', $errorMessage);
                        $response['flash']['message'] = $errorMessage;
                        $response['flash']['type'] = 'error';
                    }
                }
            } catch (Exception $e) {
                $response['flash']['message'] = __('The item could not be saved. Please try again.');
                $response['flash']['type'] = 'error';
            }
            if ($isError) {
                $datasource->rollback();
            } else {
                $datasource->commit();
            }

            if(!empty($response['flash'])){
                $this->Session->setFlash($response['flash']['message'], 'flashmessage', array('type' => $response['flash']['type']), $response['flash']['type']);
                $this->redirect("/admin/companies/email_list/$id");
            }
        }
        $data = $this->EmailNotification->find("all", array(
            'conditions' => array(
                'EmailNotification.company_id' => $id
            )
        ));
        $dataCompany = $this->Company->find('first', array(
            'conditions' => array('Company.' . $this->Company->primaryKey => $id))
        );

        $this->set("title_for_layout",$dataCompany['Company']['name']);

        $this->set("collection",$data);
        $config_field = array(
            'model' => $model,
            'controller' => 'companies',
            'action' => 'save',
            'fields' => array(
                'id'=> 'id',
                'company_id' => 'company_id',
                'name' => 'name'
            ),
        );

        $this->set("config_field",$config_field);
        $this->set("id",$id);
    }

    public function admin_email_list_delete($id = null)
    {
        $response = false;
        $isError = false;
        $datasource = $this->EmailNotification->getDataSource();
        $datasource->begin();
        try {
            $data = $this->EmailNotification->read(null, $id);

            if (!empty($data)) {
                if ($this->EmailNotification->delete($id)) {
                    $isError = false;
                    $response['flash']['message'] = __('Item saved');
                    $response['flash']['type'] = 'success';
                } else {
                    $isError = true;
                    $response['flash']['message'] = __('Không thể xóa bản ghi này, dữ liệu đang được sử dụng ở nơi khác.');
                    $response['flash']['type'] = 'warning';
                }
            }

        } catch (Exception $e) {
            $isError = true;
            $response['flash']['message'] = __('Bản ghi này đang được sử dụng ở nơi khác.');
            $response['flash']['type'] = 'error';
        }
        if ($isError) {
            $datasource->rollback();
        } else {
            $datasource->commit();
        }
        $this->redirect(Controller::referer());
    }
}