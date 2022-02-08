<?php
App::uses('AppController', 'Controller');
/**
 * GroupPermissions Controller
 *
 * @property GroupPermission $GroupPermission
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class GroupPermissionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	/**
	 * beforeFitler
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->GroupPermission->recursive = 0;
		$this->set('groupPermissions', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->GroupPermission->exists($id)) {
			throw new NotFoundException(__('Invalid group permission'));
		}
		$options = array('conditions' => array('GroupPermission.' . $this->GroupPermission->primaryKey => $id));
		$this->set('groupPermission', $this->GroupPermission->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->GroupPermission->create();
			if ($this->GroupPermission->save($this->request->data)) {
				$this->Session->setFlash(__('The group permission has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group permission could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->GroupPermission->exists($id)) {
			throw new NotFoundException(__('Invalid group permission'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->GroupPermission->save($this->request->data)) {
				$this->Session->setFlash(__('The group permission has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group permission could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('GroupPermission.' . $this->GroupPermission->primaryKey => $id));
			$this->request->data = $this->GroupPermission->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->GroupPermission->id = $id;
		if (!$this->GroupPermission->exists()) {
			throw new NotFoundException(__('Invalid group permission'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->GroupPermission->delete()) {
			$this->Session->setFlash(__('The group permission has been deleted.'));
		} else {
			$this->Session->setFlash(__('The group permission could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
