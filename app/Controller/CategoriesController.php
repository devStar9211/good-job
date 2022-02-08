<?php
App::uses('AppController', 'Controller');
/**
 * Categories Controller
 *
 * @property Category $Category
 * @property PaginatorComponent $Paginator
 */
class CategoriesController extends AppController {
	public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('PostCategory');
        // $this->Auth->allow('admin_add');
    }
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function admin_categoryList(){
		$categories = $this->Category->find('all' ,array(
            'order' => array('Category.name' => 'ASC'))
        );
        $this->response->body($categories);
        return $this->response;
	}
	public function admin_addAjax(){
		$this->layout = 'ajax';
    	if($this->request->is('ajax')) {
    		$newcategory = $this->request->query['newcategory'];
    		$parent_id = $this->request->query['parent_id'];

			$this->Category->create();
        	$data = array();
        	$data['Category']['name'] = $newcategory;
        	if ($parent_id != '-1') {
        		$data['Category']['parent_id'] = $parent_id;
        	}
        	if ($cas = $this->Category->save($data)) {
        		echo $cas['Category']['id']; 
        	}else {
				echo "error";
			}
		}
		die;
	}
/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->set('title_for_layout', __('Categories'));
        $this->Category->recursive = 0;
        $search = $this->request->query;
        $conditions = array();
        if(isset($search['name']))
            $conditions = array_merge($conditions, array('Category.name LIKE' => "%" . $search['name'] . "%"));

        $this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            'conditions' => $conditions,
            'paramType' => 'querystring',
            'order' => array(
                'updated' => 'desc'
            ),
        );
        try {
            $categories = $this->Paginator->paginate('Category');
        } catch (NotFoundException $e) {
            //Do something here like redirecting to first or last page.
            //$this->request->params['paging'] will give you required info.
            $this->request->query['page'] = 1;
            $this->redirect(array('action' => 'index', '?' => $this->request->query,'admin' => true));
        }
        $this->set('categories', $categories);
        $this->set('is_search', isset($search['search']) ? 1 : 0);
        $this->set('search', $search);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Category->exists($id)) {
			throw new NotFoundException(__('Invalid category'));
		}
		$options = array('conditions' => array('Category.' . $this->Category->primaryKey => $id));
		$this->set('category', $this->Category->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add() {
		$config_js_validate = array(
            'form_id' => 'category-validate',
            'fields' => array(
                'data[Category][name]'=>array(
                    'required' => array('true',__(' Trường này là bắt buộc')),
                ),
            ),
            'addMethod' => array(),
        );
        $this->set('config_js_validate', $config_js_validate);

		$this->set('title_for_layout', __('Add Categories'));
		if ($this->request->is('post')) {
			$this->Category->create();
			if ($this->Category->save($this->request->data)) {
				$this->Session->setFlash('アイテムが保存されました.', 'flashmessage', array('type' => 'success'), 'success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
			}
		}
		$parentCategories = $this->Category->ParentCategory->find('list');
		$this->set(compact('parentCategories'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$config_js_validate = array(
            'form_id' => 'category-validate',
            'fields' => array(
                'data[Category][name]'=>array(
                    'required' => array('true',__(' Trường này là bắt buộc')),
                ),
            ),
            'addMethod' => array(),
        );
        $this->set('config_js_validate', $config_js_validate);
		$this->set('title_for_layout', __('Edit Categories'));
		if (!$this->Category->exists($id)) {
			throw new NotFoundException('無効な投稿');
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['Category']['id'] = $id;
			if ($this->Category->save($this->request->data)) {
				$this->Session->setFlash('アイテムが保存されました.', 'flashmessage', array('type' => 'success'), 'success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
			}
		} else {
			$options = array('conditions' => array('Category.' . $this->Category->primaryKey => $id));
			$this->request->data = $this->Category->find('first', $options);
		}
		$parentCategories = $this->Category->ParentCategory->find('list');
		$this->set(compact('parentCategories'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException('無効な投稿');
		}
		// $this->PostCategory->deleteAll([       
  //           'PostCategory.category_id'=>$id,
  //       ]);
        try {
        	if ($this->Category->delete()) {
				$this->Session->setFlash('アイテムが保存されました.', 'flashmessage', array('type' => 'success'), 'success'); 
			} else {
				$this->Session->setFlash('アイテムを保存できませんでした。もう一度試してください。', 'flashmessage', array('type' => 'error'), 'error');
			}
        } catch (Exception $e) {
        	$this->Session->setFlash(__('Hiện đang có một số bài viết thuộc thể loại này. Để xóa thể loại bạn phải xóa những bài viết thuộc thể loại này trước.'), 'flashmessage', array('type' => 'error'), 'error');
        }
		return $this->redirect(array('action' => 'index'));
	}
}
