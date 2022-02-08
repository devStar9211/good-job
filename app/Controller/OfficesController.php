<?php

class OfficesController extends AppController
{
    public $uses = array('Office', 'OfficeManager');
    public $helpers = array('Session', 'CKForm', 'Html', 'Grid', 'Data');
    private $sortable = array(
        2 => '達成率順',
        3 => '小計内の最後列に表示',
        1 => '小計内の最前列に表示'
    );


    public function admin_index()
    {
        $this->set('title_for_layout', __('Danh sách Office'));
        $this->Office->recursive = 0;
        $search = $this->request->query;
        $conditions = array();

        // company
        $companies = $this->Office->Company->find('list', array(
            'fields' => array('Company.id', 'Company.name')
        ));
        $companies = array('' => __('Company')) + $companies;
        $this->set('companies', $companies);

        // filter by company_id
        $company_default = null;
        if (isset($search['company_id']) && $search['company_id'] != '') {
            $conditions['Office.company_id'] = $search['company_id'];
            $company_default = $search['company_id'];
            $this->set('company_default', $company_default);
        }
        $this->set('company_default', $company_default);

        // filter by name
        $search_name_default = '';
        if (isset($search['name']) && $search['name'] != '') {
            $conditions['Office.name like'] = '%' . $search['name'] . '%';
            $search_name_default = $search['name'];
        }
        $this->set('search_name_default', $search_name_default);


        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions' => $conditions,
            'paramType' => 'querystring',
            'order' => array(
                'id' => 'desc'
            ),
        );
        try {
            $user_list = $this->Paginator->paginate('Office');

        } catch (NotFoundException $e) {
            $this->request->query['page'] = 1;
            $this->redirect(array('action' => 'index', '?' => $this->request->query, 'admin' => true));
        }
        $this->set('accounts', $user_list);
        $this->set('is_search', isset($search['search']) ? 1 : 0);
        $this->set('search', $search);

    }

    public function admin_office_manager($id = null)
    {
        $response = false;
        $isError = false;

        $this->set('title_for_layout', __('Office manager'));
        $this->loadModel('Employee');
        $this->loadModel('Company');
        $this->loadModel('Office');
        $this->Employee->recursive = -1;
        $companies = $this->Company->find('list', array(
            'fields' => array('Company.id', 'Company.name')
        ));

        $conditions = null;
        if ($id != null) {
            $conditions = array(
                'Office.company_id' => $id
            );
        }

        $company_options = array('/admin/offices/office_manager/' => '全て');
        foreach ($companies as $key => $_name) {
            $company_options[$key]['value'] = '/admin/offices/office_manager/' . $key;
            $company_options[$key]['name'] = $_name;
        }
        $this->set('company_options', $company_options);
        $this->set('current_company', '/admin/offices/office_manager/' . $id);
        $offices = $this->Office->find('list', array(
            'conditions' => $conditions,
            'fields' => array('Office.id', 'Office.name'),
            'order'=>array(
                'Office.id'=>'asc'
            )
        ));
        $collection_offices = array();
        foreach ($offices as $key => $office_name) {
            $collection_offices[$key]['office_name'] = $office_name;
            $employees = $this->Employee->find('list', array(
                'conditions' => array(
//                    'Employee.in_office' => 1,
                    'Position.id'=>array(1,5)
                ),
                'contain'=>array(
                    'Position'
                ),
                'fields' => array('Employee.id', 'Employee.name',)
            ));
            $employee_manager = $this->OfficeManager->find('all', array(
                'conditions' => array(
                    'OfficeManager.office_id' => $key
                ),
                'fields' => array('OfficeManager.id', 'OfficeManager.employee_id', 'OfficeManager.date', 'OfficeManager.status'),
                'recursive' => -1,
                'order' => array(
                    'OfficeManager.date' => 'asc'
                ),
            ));
            $collection_offices[$key]['employee_manager'] = $employee_manager;
            $collection_offices[$key]['employees'] = $employees;
            $collection_offices[$key]['current_employee'] = (!empty($current_employee)) ? $current_employee['Employee']['id'] : null;
        }
        $this->set('collection_offices', $collection_offices);

        try {
            if (!empty($this->data)) {
                $datasource = $this->OfficeManager->getDataSource();
                $datasource->begin();
                $post_data = $this->data;
                foreach ($post_data['Office'] as $office_id => $item) {
                    $this->OfficeManager->deleteAll(array('OfficeManager.office_id' => $office_id));
                }
                foreach ($post_data['Office'] as $office_id => $item) {

                    $ids = $item['id'];
                    $employees = $item['employee_id'];
                    $dates = $item['date'];
                    $dataSave = array();
                    foreach ($employees as $k => $employee_id) {
                        if ($employee_id != '' && $dates[$k] != '') {
                            $dataSave[] = array(
                                'id' => $ids[$k],
                                'employee_id' => $employee_id,
                                'office_id' => $office_id,
                                'date' => date('Y-m-1', strtotime($dates[$k])),
                            );
                        }
                    }
                    if (!empty($dataSave)) {
                        $saved = $this->OfficeManager->saveAll($dataSave);
                        if ($saved) {
                            $isError = false;
                        } else {
                            $isError = true;
                            break;
                        }
                    }
                }
                if ($isError) {
                    $datasource->rollback();
                } else {
                    $datasource->commit();
                }
                $this->redirect(Controller::referer());
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function form_data_collection()
    {
        $this->loadModel('Company');
        $this->loadModel('CompanyGroup');
        $this->loadModel('Division');
        $this->loadModel('Office');
        $this->loadModel('Evaluation');
        $this->loadModel('OfficeGroup');

        $companies = $this->Company->find('list', array(
            'fields' => array('Company.id', 'Company.name')
        ));
        $companies = array('' => '') + $companies;
        $this->set('companies', $companies);

        $company_groups = $this->CompanyGroup->find('list', array(
            'fields' => array('CompanyGroup.id', 'CompanyGroup.name')
        ));
        $company_groups = array('' => '') + $company_groups;
        $this->set('company_groups', $company_groups);

        $divisions = $this->Division->find('list', array(
            'fields' => array('Division.id', 'Division.name')
        ));
        $divisions = array('' => '') + $divisions;
        $this->set('divisions', $divisions);

        // evaluations
        $evaluations = $this->Evaluation->find('list', array(
            'fields' => array('Evaluation.id', 'Evaluation.name')
        ));
        $evaluations = array('' => '') + $evaluations;
        $this->set('evaluations', $evaluations);

        // office_evaluations
        $office_evaluations = $this->Office->OfficeEvaluation->find('list', array(
            'fields' => array('Evaluation.id', 'Evaluation.name'),
            'recursive' => '1'
        ));
        $office_evaluations = array('' => '') + $office_evaluations;
        $this->set('office_evaluations', $office_evaluations);
        $this->set('office_evaluations_default', '');

        // business_categories
        $business_categories = $this->Office->BusinessCategory->find('list');
        $business_categories = array('' => '') + $business_categories;
        $this->set('business_categories', $business_categories);
        $this->set('business_category_default', '');

        //office groups
        $office_groups = $this->OfficeGroup->find('list', array(
            'fields' => array('OfficeGroup.id', 'OfficeGroup.name')
        ));
        $office_groups = array('' => '') + $office_groups;
        $this->set('office_groups', $office_groups);

        // addition_judgments
        $addition_judgments = $this->Office->OfficeAdditionJudgment->AdditionJudgment->find('list');
        $this->set('addition_judgments', $addition_judgments);
        $this->set('addition_judgment_default', '');


    }

    public function admin_add()
    {
        $this->set('title_for_layout', __('Thêm mới Office'));
        $this->form_data_collection();
        $companies = array('' => '');
        $this->set('companies', $companies);
        $this->set('sortable', $this->sortable);


        // Save data
        if ($this->request->is(array('post', 'put'))) {
            $request = $this->request->data;
            if (!empty($request)) {
                $saved = $this->saveOffice(null, $request);
                if ($saved) {

                    $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
                    $this->redirect(array('action' => 'edit', $saved['Office']['id']));
                } else {
                    $data = $request;
                    $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'success');
                }
            }
        }

        $this->render('/Offices/admin_edit');
    }

    public function admin_edit($id = null)
    {
        $this->set('title_for_layout', __('Chỉnh sửa Office'));
        $this->form_data_collection();
        $office_evaluations_default = $this->Office->OfficeEvaluation->find('list', array(
            'conditions' => array(
                'OfficeEvaluation.office_id' => $id
            ),
            'fields' => array('OfficeEvaluation.evaluation_id'),
            'recursive' => '-1'
        ));
        $this->set('office_evaluations_default', $office_evaluations_default);

        // business_category_default
        $business_category_default = $this->Office->OfficeBusinessCategory->find('list', array(
            'conditions' => array(
                'OfficeBusinessCategory.office_id' => $id
            ),
            'fields' => array('OfficeBusinessCategory.business_category_id'),
            'recursive' => '-1'
        ));
        $this->set('business_category_default', $business_category_default);

        // Save data
        if ($this->request->is(array('post', 'put'))) {
            $request = $this->request->data;
            if (!empty($request)) {
                $saved = $this->saveOffice($id, $request);
                if ($saved) {
                    $data = $saved;
                    $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
                    $this->redirect(Controller::referer());
                } else {
                    $data = $request;
                    $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'success');
                }
            }
        } else {
            $data = $this->Office->read(null, $id);
            if (empty($data)) {
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->data = $data;
        $this->set('sortable', $this->sortable);

    }

    private function saveOffice($id, $post_data = null)
    {
        $response = false;
        $isError = false;
        $datasource = $this->Office->getDataSource();
        $datasource->begin();

        if (!empty($post_data)) {
            try {

                $dataOffice = $post_data['Office'];
                $data_save_trimmed['Office'] = array_map('trim', $dataOffice);
                unset($this->Office->validate['api_shift_office_id']);
                $this->Office->create();

                $saved = $this->Office->save($data_save_trimmed);
                if ($saved) {
                    $response = $saved;
                    $office_id = $saved['Office']['id'];
                    $isError = false;
                } else {
                    $isError = true;
                }

                // save to table evaluations
                if (!$isError && !empty($post_data['Evaluation']['evaluation_id'])) {
                    $this->Office->OfficeEvaluation->deleteAll(array('OfficeEvaluation.office_id' => $id));
                    foreach ($post_data['Evaluation']['evaluation_id'] as $_evaluation_id) {
                        $dataOfficeEvaluation = array(
                            'OfficeEvaluation' => array(
                                'id' => '',
                                'office_id' => $office_id,
                                'evaluation_id' => $_evaluation_id,
                            )
                        );
                        $this->Office->OfficeEvaluation->set($dataOfficeEvaluation);
                        $saved = $this->Office->OfficeEvaluation->save();
                        if ($saved) {
                            $isError = false;
                        } else {

                            $isError = true;
                            break;
                        }
                    }
                }
                //save to table office_business_categories
                if (!$isError && !empty($post_data['BusinessCategory']['business_category_id'])) {

                    $this->Office->OfficeBusinessCategory->deleteAll(array('OfficeBusinessCategory.office_id' => $id));
                    foreach ($post_data['BusinessCategory']['business_category_id'] as $_business_category_id) {
                        $dataOfficeBusinessCategory = array(
                            'OfficeBusinessCategory' => array(
                                'id' => '',
                                'office_id' => $office_id,
                                'business_category_id' => $_business_category_id,
                            )
                        );
                        $this->Office->OfficeBusinessCategory->set($dataOfficeBusinessCategory);
                        $saved = $this->Office->OfficeBusinessCategory->save();
                        if ($saved) {
                            $isError = false;
                        } else {
                            $isError = true;
                            break;
                        }
                    }
                }

                // save to table office_addition_judgments
                $this->Office->OfficeAdditionJudgment->deleteAll(array('OfficeAdditionJudgment.office_id' => $id));
                if (!$isError && !empty($post_data['AdditionJudgments']['addition_judgment_id'])) {
                    foreach ($post_data['AdditionJudgments']['addition_judgment_id'] as $_addition_judgment_id) {
                        $dataOfficeAdditionJudgments = array(
                            'OfficeAdditionJudgment' => array(
                                'id' => '',
                                'office_id' => $office_id,
                                'addition_judgment_id' => $_addition_judgment_id,
                            )
                        );
                        $this->Office->OfficeAdditionJudgment->set($dataOfficeAdditionJudgments);
                        $saved = $this->Office->OfficeAdditionJudgment->save();
                        if ($saved) {
                            $isError = false;
                        } else {
                            $isError = true;
                            break;
                        }
                    }
                }

                // save to table office_self_paids
                $this->Office->OfficeSelfPaid->deleteAll(array('OfficeSelfPaid.office_id' => $id));
                if (!$isError && !empty($post_data['OfficeSelfPaid'])) {
                    foreach ($post_data['OfficeSelfPaid'] as $_item) {
                        if ($_item['name'] != '' && $_item['price']) {
                            $dataOfficeSelfPaid = array(
                                'OfficeSelfPaid' => array(
//                                'id' => $_item['id'],
                                    'office_id' => $office_id,
                                    'name' => $_item['name'],
                                    'price' => $_item['price'],
                                )
                            );
                            $this->Office->OfficeSelfPaid->create();
                            $saved = $this->Office->OfficeSelfPaid->save($dataOfficeSelfPaid);
                            if ($saved) {
                                $isError = false;
                            } else {
                                $isError = true;
                                break;
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $isError = true;
            }
            if ($isError) {
                $datasource->rollback();
                $response = false;
            } else {
                $datasource->commit();
            }
        }
        return $response;

    }

    function admin_delete($id = null)
    {
        $response = false;
        $isError = false;
        $datasource = $this->Office->getDataSource();
        $datasource->begin();
        try {
            if (isset($id) && is_numeric($id)) {
                $data = $this->Office->read(null, $id);
                if (!empty($data)) {
                    $isError = false;
                    $this->Office->delete($id);
                    $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
                } else {
                    $isError = true;
                }
            }
        } catch (Exception $e) {
            $isError = true;
            $this->Session->setFlash(__('Không thể xóa bản ghi này, dữ liệu đang được sử dụng ở nơi khác.'), 'flashmessage', array('type' => 'warning'), 'warning');
        }
        if ($isError) {
            $datasource->rollback();
        } else {
            $datasource->commit();
        }

        $this->redirect(Controller::referer());
    }

    // ajax generate companies by company group id
    public function admin_generate_list_companies()
    {
        $this->loadModel('Company');
        $this->autoRender = false;
        $response = array(
            'status' => 0,
            'message' => ''
        );
        if ($this->request->is('ajax') && !empty($this->request->data['company_group_id'])) {
            $companies = $this->Company->find('list', array(
                'fields' => array('id', 'name'),
                'conditions' => array(
                    'company_group_id' => $this->request->data('company_group_id')
                ),
                'order' => array(
                    'created' => 'asc'
                )
            ));
            $data = array();
            foreach ($companies as $key => $value) {
                $data[] = array($key, $value);
            }
            $response['status'] = 1;
            $response['data'] = $data;
        }
        return json_encode($response);
    }

    // ajax generate offices by company id
    public function admin_generate_list_offices()
    {
        $this->autoRender = false;
        $response = array(
            'status' => 0,
            'message' => ''
        );
        if ($this->request->is('ajax') && !empty($this->request->data['company_id'])) {
            $offices = $this->Office->find('list', array(
                'fields' => array('id', 'name'),
                'conditions' => array(
                    'company_id' => $this->request->data('company_id')
                ),
                'order' => array(
                    'created' => 'asc'
                )
            ));
            $data = array();
            foreach ($offices as $key => $value) {
                $data[] = array($key, $value);
            }
            $response['status'] = 1;
            $response['data'] = $data;
        }
        return json_encode($response);
    }
}


