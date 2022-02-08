<?php
App::uses('AppController', 'Controller');

/**
 * Accounts Controller
 *
 * @property Account $Account
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class SettingApiController extends AppController
{
    public $uses = array('Office', 'Company', 'OfficeRemoteLabel', 'OfficeRemote');

    public function admin_index()
    {
        $this->set('title_for_layout', __('Quản lý kết nối API'));

        $companies = $offices = $data = array();
        $data = array(
            'company_id' => null,
            'offices' => array(),
        );
        // get list company
        $companies = $this->Company->find('list', array(
            'fields' => array('id', 'name'),
            'order' => array(
                'created' => 'asc'
            )
        ));
        $data['company_id'] = key($companies);
        if (
        !empty($_GET['company'])
        ) {
            $data['company_id'] = $_GET['company'];

        }
        $offices = $this->Office->find('all', array(
            'fields' => array('id', 'name'),
            'conditions' => array(
                'company_id' => $data['company_id']
            ),
            'order' => array(
                'created' => 'asc'
            ),
            'recursive' => -1
        ));
        if (!empty($offices)) {
            $data['offices'] = $offices;
        }

        $office_remote_labels = $this->OfficeRemoteLabel->find('all', array(
            'order' => array(
                'id' => 'asc'
            ),
        ));

        // Save data
        if ($this->request->is('post')) {
            $reqOfficeRemote = $this->request->data['OfficeRemote'];
            $reqOfficeRemoteLabel = $this->request->data['OfficeRemoteLabel'];
            if ($this->OfficeRemote->saveData($reqOfficeRemote) && $this->OfficeRemoteLabel->saveData($reqOfficeRemoteLabel)) {
                $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');

            } else {
                $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'error');
            }
            $this->redirect(Controller::referer());

        } else {
            $data = array_merge($data, $this->admin_generate_data($data['company_id']));

        }

        $this->set(compact('companies', 'data', 'office_remote_labels'));
    }

    private function admin_generate_data($company_id)
    {
        $data = array(
            'offices' => null
        );
        $offices = $this->Office->find('all', array(
            'fields' => array('id', 'name'),
            'conditions' => array(
                'company_id' => $company_id
            ),
            'order' => array(
                'created' => 'asc'
            ),
            'recursive' => -1
        ));

        foreach ($offices as $_office) {

            $office_remote_labels = $this->OfficeRemoteLabel->find('all', array(
                'fields' => array(
                    'OfficeRemoteLabel.*',
                    'OfficeRemote.*',
                ),
                'joins' => array(
                    array(
                        'table' => 'office_remotes',
                        'alias' => 'OfficeRemote',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'OfficeRemote.office_remote_label_id = OfficeRemoteLabel.id',
                            'OfficeRemote.office_id' => $_office['Office']['id']
                        )
                    ),
                ),
                'order' => array(
                    'OfficeRemoteLabel.id' => 'asc'
                ),
                'group'=>array(
                    'OfficeRemoteLabel.id'
                )
            ));

//            pr($office_remote_labels);die;

            $data['offices'][$_office['Office']['id']]['office_remotes'] = $office_remote_labels;
            $data['offices'][$_office['Office']['id']]['office'] = $_office;
        }

        return $data;
    }
}
