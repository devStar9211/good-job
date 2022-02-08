<?php

/**
 * Condition Point Controller
 *
 */
class ConditionPointsController extends AppController
{
    public $uses = array('PointRank', 'CompanyGroup', 'Occupation', 'Stage', 'PointRankOccupation','WorkingTime');

    public function admin_index($id = null)
    {
        $this->set('title_for_layout', __('Achievement point condition'));
        $working_time = array(1 => __('Fulltime'), 2 => __('Parttime'));
        if ($this->request->is('post')) {
            $data = $this->request->data;
        }
        $conditions = array();
        $listOccupations = $this->Occupation->find('list', array(
            'fields' => array('id', 'name')
        ));
        $listStages = $this->Stage->find('list', array(
            'fields' => array('id', 'name')
        ));
        $working_times = $this->WorkingTime->find('list', array(
            'fields' => array('id', 'name')
        ));
        $companyGroups = $this->CompanyGroup->find('list');
        if (!empty($_GET['company_group']) && in_array($_GET['company_group'], array_keys($companyGroups))) {
            $conditions['PointRank.company_group_id'] = $data['company_group'] = $_GET['company_group'];
        } else {
            $conditions['PointRank.company_group_id'] = $data['company_group'] = key($companyGroups);
        }
//        pr($conditions);die;
        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'contain' => array(
                'Occupation' => array(
                    'fields' => array(
                        'Occupation.id',
                        'Occupation.name',
                    ),
                ),
                'Stage'
            ),
            'conditions' => $conditions,
            'paramType' => 'querystring',
            'order' => array(
                'rank_name' => 'asc'
            ),
            'recursive' => -1
        );
        $data['ranks'] = $this->Paginator->paginate('PointRank');

//        pr($data['ranks']);die;

        // Save data
        if ($this->request->is('post')) {
            $req = $this->request->data;
//            pr($req);die;

            $isError = false;
            $datasource = $this->PointRank->getDataSource();
            $datasource->begin();
            try {
                foreach ($req['necessary_point'] as $key => $_item) {
                    $dataSave = array(
                        'id' => $key,
                        'company_group_id' => $req['company_group_id'],
                        'necessary_point' => $req['necessary_point'][$key],
                        'subsidize_rate' => $req['subsidize_rate'][$key],
                        'working_time_id' => $req['working_time'][$key],
                    );

                    $this->PointRank->set($dataSave);
                    $savedP = $this->PointRank->save();
                    if ($savedP) {
                        $isError = false;
                    } else {
                        $isError = true;
                        break;
                    }
                    $this->PointRankOccupation->deleteAll(array('point_rank_id'=>$savedP['PointRank']['id']));
                    if (!$isError && !empty($req['occupation'][$key])) {
//                        pr($req['occupation'][$key]);die;

                        foreach ($req['occupation'][$key] as $_occupation) {
                            $dataSave = array(
                                'id'=>'',
                                'point_rank_id' => $savedP['PointRank']['id'],
                                'occupation_id' => $_occupation,
                            );
                            $this->PointRankOccupation->set($dataSave);
                            $savedO = $this->PointRankOccupation->save();
                            if ($savedO) {
                                $isError = false;
                            } else {
                                $isError = true;
                                break;
                            }
                        }
                    }

                    if ($isError) {
                        break;
                    }
                }

            } catch (Exception $e) {
                $isError = true;
            }
            if ($isError) {
                $datasource->rollback();

            } else {
                $datasource->commit();
            }
            if (!$isError) {
                $this->Session->setFlash(__('Item saved'), 'flashmessage', array('type' => 'success'), 'success');
                $this->redirect(Controller::referer());
            } else {
                $this->Session->setFlash(__('The item could not be saved. Please try again.'), 'flashmessage', array('type' => 'error'), 'success');
            }

        }
        $this->set(compact('working_time', 'data', 'listOccupations', 'listStages', 'companyGroups', 'working_times'));
    }


}


