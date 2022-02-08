<?php

/**
 * Home Controller
 *
 */
class PointHeadersController extends AppController
{
    public $uses = array('Employee', 'Company', 'Office', 'PointHeader', 'PointDetail');

    public function admin_index($id = null)
    {
        $this->set("title_for_layout", __('Point history'));
        $data = $conditions = array();
        $data['date'] = date('Y-m');

        // switch for super admin or company admin
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));
        $data['company_id'] = key($companies);
        $data['office_id'] = null;
        if (!empty($_GET['company'])) {
            $data['company_id'] = $_GET['company'];
            $data['company_id'] = $_GET['company'];
            if (
            !empty($_GET['office'])
            ) {
                $data['office_id'] = $_GET['office'];
            }
        }
        $offices = $this->Office->find('list', array(
            'fields' => array('id', 'name'),
            'conditions' => array(
                'company_id' => $data['company_id']
            ),
            'order' => array(
                'created' => 'asc'
            )
        ));
        $data['office_id'] = empty($data['office_id']) ? key($offices) : $data['office_id'];
        $data = array_merge($data, $this->admin_generate_list_employee_data($data['company_id'], $data['office_id'], $data['date']));
        $this->set(compact('companies', 'offices', 'employees', 'data'));
    }

    public function admin_ajax_list_employee($id = null)
    {
        $this->autoRender = false;
        $response = array(
            'status' => 0,
            'message' => ''
        );
        if ($this->request->is('ajax')) {
            $req = $this->request->data;
            if (!empty($req['office']) && !empty($req['date'])) {
                // switch for super admin or company admin
                $data = $this->admin_generate_list_employee_data($req['company'], $req['office'], $req['date']);
                $view = new View($this, false);
                $table_data = $view->element('point_details_list_employee_table', array('data' => $data));
                $response['status'] = 1;
                $response['table_data'] = $table_data;
            }
        }
        return json_encode($response);
    }

    public function admin_generate_list_employee_data($company_id, $office_id, $date)
    {
        $conditionsEmployee = array();
        if (!empty($office_id)) {
            $conditionsEmployee['Employee.company_id'] = $company_id;
        }
        if (!empty($office_id)) {
            $conditionsEmployee['Employee.office_id'] = $office_id;
        }
        $data = array(
            'company' => $office_id,
            'date' => $date,
            'employees' => array(),

        );
        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions' => $conditionsEmployee,
            'contain' => array(
                'PointHeader',
            ),
            'paramType' => 'querystring',
            'recursive' => -1
        );
        $employees = $this->Paginator->paginate('Employee');
        $data['employees'] = $employees;
        return $data;

    }

    public function admin_view($id = null)
    {
        $this->set("title_for_layout", __('Point detail'));
        $conditions = array(
            'PointDetail.point_header_id' => $id
        );
        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions' => $conditions,
            'contain' => 'PointType',
            'paramType' => 'querystring',

        );
        $data = $this->Paginator->paginate('PointDetail');
        $this->set(compact('data'));
    }

    public function admin_ajax_point_detail_view($id = null)
    {
        $this->autoRender = false;
        $response = array(
            'status' => 0,
            'message' => ''
        );
        $date = '2017-04';
        if (!empty($date)) {
            $conditionsEmployee['PointDetail.point_header_id'] = $id;
        }
        $view = new View($this, false);
        if ($this->request->is('ajax') && $id != null) {

            $this->Paginator->settings = array(
                'limit' => Configure::read('Paging.size'),
                'conditions' => $conditionsEmployee,
                'contain' => 'PointType',
                'paramType' => 'querystring',

            );
            $data = $this->Paginator->paginate('PointDetail');
            $table_data = $view->element('ajax_point_detail_view_table', array('data' => $data));
        } else {
            $table_data = $view->element('ajax_point_detail_view_table', array('data' => ''));
        }
        return $table_data;
    }
}


