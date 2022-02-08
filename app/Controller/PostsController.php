<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
/**
 * Posts Controller
 *
 * @property Post $Post
 * @property PaginatorComponent $Paginator
 */
class PostsController extends AppController {
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

/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->set('title_for_layout', __('article list'));
        $conditions = array();
		$joins = array();

        $conditions = array_merge($conditions, array(
            'Post.type' => "post",
        ));
        $search = $this->request->query;
        if (isset($search['search_param']) && !empty($search['search_param'])) {
        	$conditions = array_merge($conditions, array(
	        	'Post.title LIKE' => "%" . $search['search_param'] . "%"
	        ));
        }
        if (isset($search['filter-date']) && $search['filter-date'] != 'all') {
        	$conditions = array_merge($conditions, array(
	        	'YEAR(Post.created)' => date('Y', strtotime($search['filter-date'])),
            	'MONTH(Post.created)' => date('m', strtotime($search['filter-date']))
	        ));
        }

        if (isset($search['category']) && $search['category'] != 'all') {
            $conditions = array_merge($conditions, array(
                'Category.id' => $search['category'],
            ));
            $joins = array_merge($joins, array(
                array( 
                    'table'=> 'post_categories',
                    'alias' => 'PostCategory',
                    'type' => 'LEFT',
                    'conditions' =>array( 
                        'PostCategory.post_id = Post.id'
                    )
                ),
                array( 
                    'table'=> 'categories',
                    'alias' => 'Category',
                    'type' => 'LEFT',
                    'conditions' =>array( 
                        'Category.id = PostCategory.category_id'
                    )
                )
            ));
        }

        if (isset($search['subsubsub']) && $search['subsubsub'] != 'all') {
        	$conditions = array_merge($conditions, array(
	        	'Post.status' => $search['subsubsub'],
	        ));
        }

		$this->Paginator->settings = array(
            'limit' => Configure::read('Paging.size'),
            // 'limit' => 1,
            'fields' => array(
                'DISTINCT(Post.id)',
            	'Post.id', 
            	'Post.account_id', 
            	'Post.type', 
            	'Post.title', 
            	'Post.short_description', 
            	'Post.avatar', 
            	'Post.status', 
            	'Post.created',
            	'Account.id', 
            	'Account.username', 
                // 'Category.id',
                // 'Category.name',
            	),
            // 'paramType' => 'querystring',
            'order' => array(
                'created' => 'desc'
            ),
            'contain' => array(
            	'Category'=>array(
                    'fields' => array('id', 'name'),
                ),
            	'Account'=>array(
            		'fields' => array('id'),
            	),
            	'PostCategory'=>array(
            		'fields' => array('id', 'post_id', 'category_id'),
            	),
            ),
            'joins'=>$joins,
            'conditions' => $conditions,
        );
        // $this->Post->recursive = -1;
        // pr($this->Paginator->paginate());die;
        $total = $this->Post->find('count', array('conditions' => array('Post.type' => 'post',)));
        $total_publish = $this->Post->find('count', array('conditions' => array('Post.type' => 'post', 'Post.status' => 'Publish')));
        $total_trash = $this->Post->find('count', array('conditions' => array('Post.type' => 'post', 'Post.status' => 'Draft')));
		$this->set(array(
            'posts' => $this->Paginator->paginate(),
            'search' => $search,
            'total' => $total,
            'total_publish' => $total_publish,
            'total_trash' => $total_trash
        ));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_view($id = null){
        $this->set('title_for_layout', __('Preview'));
        if (!$this->Post->exists($id)) {
            throw new NotFoundException(__('Invalid post'));
        }
        $options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
        $post = $this->Post->find('first', $options);
        // debug($post);die;
        $this->set('title_for_layout', $post['Post']['title']);
        $this->set('post', $post);
    }

/**
 * add method
 *
 * @return void
 */
	public function admin_add() {
		$this->set('title_for_layout', __('New post'));
        $config_js_validate = array(
            'form_id' => 'post-validate',
            'fields' => array(
                'data[Post][title]'=>array(
                    'required' => array('true',__(' Trường này là bắt buộc')),
                ),
                'data[Post][short_description]'=>array(
                    'required' => array('true',__(' Trường này là bắt buộc')),

                ),
            ),
            'addMethod' => array(),
        );
        $this->set('config_js_validate', $config_js_validate);

		if ($this->request->is('post')) {
			// $year = date('Y');
			// $month = date('m');
			// $fileName = $this->request->data['Post']['avatar']['name'];
			// $new_folder = new Folder(WWW_ROOT . 'uploads' . DS . $year . DS . $month, true);
			// $uploadPath = '/uploads/' . $year . '/' . $month . '/';
	  //       $uploadFile = $uploadPath.$fileName;

	  //       if(move_uploaded_file($this->request->data['Post']['avatar']['tmp_name'], WWW_ROOT.$uploadFile)){
	        	$this->Post->create();
	        	$data = array();
				$data['Post']['account_id'] = $this->Auth->user()['id'];
				$data['Post']['title'] = $this->request->data['Post']['title'];
				$data['Post']['avatar'] = $this->request->data['Post']['avatar'];
				$data['Post']['type'] = 'post';
                $data['Post']['description'] = $this->request->data['Post']['description'];
				$data['Post']['public_date'] = date('Y-m-d');
                $data['Post']['short_description'] = $this->request->data['Post']['short_description'];
				$data['Post']['created'] = date('Y-m-d H:i:s');
				if ($this->request->data['status'] == "公開") {
					$data['Post']['status'] = 'Publish';
				}else{
					$data['Post']['status'] = 'Draft';
				}
				if ($post = $this->Post->save($data)) {
                    if (!empty($this->request->data['Post']['category'])) {
                        foreach ($this->request->data['Post']['category'] as $value) {
                            $this->PostCategory->create();
                            $dataPC = array();
                            $dataPC['PostCategory']['post_id'] = $post['Post']['id'];
                            $dataPC['PostCategory']['category_id'] = $value;

                            $this->PostCategory->save($dataPC);
                        }
                    }
					$this->Session->setFlash(__('アイテムが保存されました.'), 'flashmessage', array('type' => 'success'), 'success');
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('アイテムを保存できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
				}
	        // }else{
	        // 	$this->Session->setFlash(__('The post could not be saved. Please, try again.'));
	        // }
		}
		// $employees = $this->Post->Employee->find('list');
		// $this->set(compact('employees'));
	}


/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
        $this->set('title_for_layout', __('Edit post'));

        $config_js_validate = array(
            'form_id' => 'post-validate',
            'fields' => array(
                'data[Post][title]'=>array(
                    'required' => array('true',__(' Trường này là bắt buộc')),
                ),
                'data[Post][short_description]'=>array(
                    'required' => array('true',__(' Trường này là bắt buộc')),

                ),
            ),
            'addMethod' => array(),
        );
        $this->set('config_js_validate', $config_js_validate);

		if (!$this->Post->exists($id)) {
			throw new NotFoundException(__('アカウントは無効です'));
		}
		if ($this->request->is(array('post', 'put'))) {
            $data = array();
            $data['Post']['id'] = $id;
            $data['Post']['title'] = $this->request->data['Post']['title'];
            $data['Post']['avatar'] = $this->request->data['Post']['avatar'];
            $data['Post']['description'] = $this->request->data['Post']['description'];
            $data['Post']['short_description'] = $this->request->data['Post']['short_description'];
            if ($this->request->data['status'] == "公開") {
                $data['Post']['status'] = 'Publish';
            }else{
                $data['Post']['status'] = 'Draft';
            }

			if ($this->Post->save($data)) {
                $this->PostCategory->deleteAll([       
                    'PostCategory.post_id'=>$id,
                ]);
                if (!empty($this->request->data['Post']['category'] )) {
                    foreach ($this->request->data['Post']['category'] as $value) {
                        $this->PostCategory->create();
                        $dataPC = array();
                        $dataPC['PostCategory']['post_id'] = $id;
                        $dataPC['PostCategory']['category_id'] = $value;

                        $this->PostCategory->save($dataPC);
                    }
                }
                
				$this->Session->setFlash(__('アイテムが保存されました.'), 'flashmessage', array('type' => 'success'), 'success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('アイテムを保存できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
			}
		} else {
			$options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
			$this->request->data = $this->Post->find('first', $options);
		}
        // debug($this->request->data, $showHtml = null, $showFrom = true);die;
		// $employees = $this->Post->Employee->find('list');

		// $this->set(compact('employees'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Post->id = $id;
		if (!$this->Post->exists()) {
			throw new NotFoundException(__('無効な投稿'));
		}
        $this->PostCategory->deleteAll([       
            'PostCategory.post_id'=>$id,
        ]);

		if ($this->Post->delete()) {
			$this->Session->setFlash(__('アイテムが保存されました.'), 'flashmessage', array('type' => 'success'), 'success'); 
		} else {
			$this->Session->setFlash(__('アイテムを保存できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
		}
		return $this->redirect(array('action' => 'index'));
	}

    public function admin_deleteAjax()
    {
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $array_id = $this->request->query['array_id'];
                if(isset($this->request->query['array_image'])){
                    $array_image = $this->request->query['array_image'];
                    foreach ($array_id as  $id) {
                        $this->Post->id = $id;
                        $this->Post->delete();
                    }
                    foreach ($array_image as $value) {
                        unlink(WWW_ROOT.$value);
                    }
                    echo "success";
                }else{
                    foreach ($array_id as $key => $id) {
                        $this->PostCategory->deleteAll([       
                            'PostCategory.post_id'=>$id,
                        ]);
                        $this->Post->id = $id;
                        $this->Post->delete();
                    }
                    echo "success";
                } 
        }
        die;
    }

    public function admin_addmediaAjax(){
        $this->layout = 'ajax';

        if (isset($_FILES["img_name"])) {
            $ret = array();

            $error = $_FILES["img_name"]["error"];
            {

                if (!is_array($_FILES["img_name"]['name'])) //upload 1 anh
                {
                    $RandomNum = uniqid();

                    $ImageName = str_replace(' ', '-', strtolower($_FILES['img_name']['name']));
                    $ImageType = $_FILES['img_name']['type']; //"image/png", image/jpeg etc.

                    $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
                    $ImageExt = str_replace('.', '', $ImageExt);
                    $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
                    $NewImageName = $ImageName . '-' . $RandomNum . '.' . $ImageExt;

                    $year = date('Y');
                    $month = date('m');
                    $new_folder = new Folder(WWW_ROOT . 'uploads' . DS . $year . DS . $month, true);

                    $uploadPath = '/uploads/' . $year . '/' . $month . '/' .$NewImageName;

                    if(move_uploaded_file($_FILES["img_name"]["tmp_name"], WWW_ROOT . $uploadPath)){
                        $this->Post->create();
                        $data = array();
                        $data['Post']['account_id'] = $this->Auth->user()['id'];
                        $data['Post']['title'] = $ImageName;
                        $data['Post']['short_description'] = $uploadPath;
                        $data['Post']['description'] = '';
                        $data['Post']['type'] = 'media';
                        $data['Post']['status'] = '1';
                        $data['Post']['avatar'] = '';
                        $data['Post']['public_date'] = date('Y-m-d');
                        $data['Post']['created'] = date('Y-m-d H:i:s');
                        if ($post = $this->Post->save($data)) {
                            $id = $post['Post']['id'];
                            $avatar = $uploadPath;

                            $array = array(
                                "id" => $id,
                                "avatar" => $avatar
                            );
             
                            echo json_encode($array);
                        }else {
                            echo "error";
                        }
                    } else {
                        echo "error";
                    }
                }
            }
        }
        // if($this->request->is('ajax')) {
            // $year =  date('Y');
        // $year = date('Y');
        // $month = date('m');
        // $fileName = basename($_FILES['uploadfile']['name']);
        // $new_folder = new Folder(WWW_ROOT . 'uploads' . DS . $year . DS . $month, true);
        // $uploadPath = '/uploads/' . $year . '/' . $month . '/' .strtotime(date("Y-m-d H:i:s")). '_';
        // $uploadFile = $uploadPath.$fileName;
        // if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], WWW_ROOT.$uploadFile)){
        //     $this->Post->create();
        //     $data = array();
        //     $data['Post']['account_id'] = '2';
        //     $data['Post']['title'] = $fileName;
        //     $data['Post']['short_description'] = $uploadFile;
        //     $data['Post']['type'] = 'media';
        //     $data['Post']['status'] = '1';
        //     $data['Post']['created'] = date('Y-m-d H:i:s');
        //     if ($post = $this->Post->save($data)) {
        //         $id = $post['Post']['id'];
        //         $avatar = $uploadFile;

        //         $array = array(
        //             "id" => $id,
        //             "avatar" => $avatar
        //         );
 
        //         echo json_encode($array);
        //     }else {
        //         echo "error";
        //     }
        // } else {
        //     echo "error";
        // }
        // }
        die;
    }

    public function admin_addAvatar(){
        $this->layout = 'ajax';
        // if($this->request->is('ajax')) {
            // $year =  date('Y');
        $year = date('Y');
        $month = date('m');
        $fileName = basename($_FILES['uploadfile']['name']);
        $new_folder = new Folder(WWW_ROOT . 'uploads' . DS . $year . DS . $month, true);
        $uploadPath = '/uploads/' . $year . '/' . $month . '/' .strtotime(date("Y-m-d H:i:s")). '_';
        $uploadFile = $uploadPath.$fileName;
        if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], WWW_ROOT.$uploadFile)){
            $this->Post->create();
            $data = array();
            $data['Post']['account_id'] = $this->Auth->user()['id'];
            $data['Post']['title'] = $fileName;
            $data['Post']['short_description'] = $uploadFile;
            $data['Post']['description'] = '';
            $data['Post']['type'] = 'media';
            $data['Post']['status'] = '1';
            $data['Post']['avatar'] = '';
            $data['Post']['public_date'] = date('Y-m-d');
            $data['Post']['created'] = date('Y-m-d H:i:s');
            if ($post = $this->Post->save($data)) {
                $id = $post['Post']['id'];
                $avatar = $uploadFile;

                $array = array(
                    "id" => $id,
                    "avatar" => $avatar
                );
 
                echo json_encode($array);
            }else {
                echo "error";
            }
        } else {
            echo "error";
        }
        // }
        die;
    }
    //lay danh sach media
    public function admin_getListMedia(){
        $medias = $this->Post->find('all' ,array(
                'order' => array('Post.created' => 'DESC'),
                'conditions' => array('Post.type'=>'media'),
                'contain' => false
            )
        );
        $this->response->body($medias);
        return $this->response;
        // return $categorys;
    }

    //tìm kiem media
    public function admin_searchMedia(){
        $this->layout = 'ajax';
        if($this->request->is('ajax')) {
            $key = $this->request->query['key'];
            $filter_date = $this->request->query['filter_date'];
            $type = $this->request->query['type'];
            $conditions = array();
             $conditions = array_merge($conditions, array(
                'Post.type' => "media",
            ));
            if (!empty($key)) {
               $conditions = array_merge($conditions, array('Post.title LIKE' => "%" . $key . "%")); 
            }
            if($filter_date != 'all'){
                $conditions = array_merge($conditions,array(
                    'YEAR(Post.created)' => date('Y', strtotime($filter_date)),
                    'MONTH(Post.created)' => date('m', strtotime($filter_date))
                ));
            }

            $medias = $this->Post->find('all' ,array(
                'order' => array('Post.created' => 'DESC'),
                'conditions' => $conditions,
                'contain' => false
            ));

            if (empty($medias)) {
                echo '<div class="media-empty" style="font-weight: bold;text-align: center; margin-top: 40px;"> どのフォルダーも見つかりません。 </div>';
            }else{
                if ($type == 'get') {
                    echo "<ul>";
                    foreach ($medias as $media) {
                        echo '<li id="li-get-'.$media['Post']['id'].'">
                                    <input type="hidden" name="value-id-get" id ="value-id-get" value="'.$media['Post']['id'].'">
                                    <input type="checkbox" id="cbget'.$media['Post']['id'].'" name="check_image_get[]" value="'.$media["Post"]["short_description"].'"/>
                                    <label for="cbget'.$media['Post']['id'].'"><img src="'.$media["Post"]["short_description"].'" /></label>
                              </li>';
                    }
                    echo "</ul>";
                }else{
                    echo "<ul>";
                    foreach ($medias as $media) {
                        echo ' <li id="li-'.$media['Post']['id'].'">
                                    <input type="hidden" name="value-id" id ="value-id" value="'.$media['Post']['id'].'">
                                    <input type="checkbox" id="cb'.$media['Post']['id'].'" name="check_image[]" value="'.$media["Post"]["short_description"].'"/>
                                    <label for="cb'.$media['Post']['id'].'"><img src="'.$media["Post"]["short_description"].'" /></label>
                              </li>';
                    }
                    echo "</ul>";
                }
            }
        }
        die;
    }

    public function admin_config()
    {
        $this->loadModel('Config');
        $postNumberConfig = $this->Config->find('first', array(
            'conditions' => array('Config.key' => 'home_post_number')
        ));
        if ($this->request->is(array('post', 'put'))) {
            $this->Config->create();
            $this->Config->set(
                array(
                    'key' => 'home_post_number',
                    'value' => $this->request->data['Config']['value']
                )
            );
            if ($this->Config->save()) {
                $this->Session->setFlash(__('アイテムが保存されました.'), 'flashmessage', array('type' => 'success'), 'success');
                return $this->redirect(array('action' => 'admin_config'));
            } else {
                $this->Session->setFlash(__('アイテムを保存できませんでした。もう一度試してください。'), 'flashmessage', array('type' => 'error'), 'error');
            }
        }else{
            $this->request->data = $postNumberConfig;
        }
        $this->set('title_for_layout', __('Blog article display count'));
        $this->set('pageId', 'config');
    }
}
