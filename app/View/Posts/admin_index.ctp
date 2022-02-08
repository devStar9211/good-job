<?php $this->start('css')?>
	<?php echo $this->Html->css('post');?>
	<?php echo $this->Html->css('/assets/css/l_css');?>
<?php $this->end()?>
<div class="list-post">
	<div class="box">
		<div class="box-header with-border">
		  <h3 class="box-title"><?=$title_for_layout?></h3>

		  <div class="box-tools">
			  <div id="btn-box">
			  	<?php echo $this->Html->link(__('Add new'), array('action' => 'add'), array('escape' => false, "class"=>"btn btn-primary btn-sm")); ?>
			  </div>
		  </div>
		</div>
		<div class="box-body">
			<?php echo $this->element('flash-message'); ?>
			<div class="row" id="table-list">
				<form id="posts-filter" method="get" class="clearfix">
					<div class="search-top">
						<div class=" col-xs-6 search-action">
							<div class="pull-left">
								<!-- <div class="action"> -->
									<!-- <div id="delete-all" class="btn btn-primary btn-sm" disabled='disabled'>Delete</div> -->
									
								<!-- </div> -->
								<div class="alignleft actions bulkactions">
									<select id="action">
										<option value="-1"><?=__('Bulk Actions')?></option>
										<!-- <option value="edit">Edit</option> -->
										<option value="delete"><?=__('Delete')?></option>
									</select>
									<div class="btn btn-default btn-sm" id="doaction"><?=__('Execute')?></div>
								</div>
							</div>
						</div>
						<div class=" col-xs-6  subsubsub-search">
							<div class="search-box">
								<div class="input-group">        
						            <input type="text" class="form-control search_param" name="search_param"" placeholder="" value="<?php echo isset($search['search_param']) ? $search['search_param'] : ''?>">
						            <span class="input-group-btn">
						                <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
						            </span>
						        </div>
							</div>
						</div>
					</div>
					
					<div class="clearfix"></div>

					<div class="search-bottom">
						<div class="col-ms-6 col-md-3 search-subsubsub">
							<div class="subsubsub">
								<select name="subsubsub" id="filter-subsubsub">
									<option value="all" selected="selected">全て<span class="count">(<?=$total?>)</span></option>
									<?php if (isset($search['subsubsub']) && $search['subsubsub'] == "publish"): ?>
						        		<option value="publish" selected="selected">公共 <span class="count">(<?=$total_publish?>)</span></option>
						        	<?php else: ?>
						        		<option value="publish">公共 <span class="count">(<?=$total_publish?>)</span></option>
							        <?php endif ?>
									<?php if (isset($search['subsubsub']) && $search['subsubsub'] == "draft"): ?>
						        		<option value="draft" selected="selected">下書き<span class="count">(<?=$total_trash?>)</span></option>
						        	<?php else: ?>
						        		<option value="draft">下書き<span class="count">(<?=$total_trash?>)</span></option>
							        <?php endif ?>
								</select>
							</div>
						</div>
						<div class="col-ms-6 col-md-3 search-filter">
							<div class="filter">
								<select name="filter-date" id="filter-by-date">
									<option value="all" selected="selected"><?=__('All Date')?></option>
									<?php
									$today =  date('Y-m-1');
									while (date('Y', strtotime($today)) >= 2017) {
										if (isset($search['filter-date']) && $search['filter-date'] == $today) {?>
											<option value="<?=$today?>" selected="selected"><?php echo date('F Y', strtotime($today));?></option>
										<?php }else{?>
									   <option value="<?=$today?>"><?php echo date('F Y', strtotime($today));?></option>
									<?php 
										}
									  $month_plus = date('Y-m-d', strtotime($today . " -1 month"));
									  $today = $month_plus;
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-ms-6 col-md-3 search-category">
							<div class="filter">
								<select name="category" id="cat" class="postform">
									<option value="all" selected="selected"><?=__('All Categories')?></option>
									 <?php $categories = $this->requestAction(['controller' => 'Categories', 'action' => 'categoryList']);?>
							        <?php foreach ($categories as $category): ?>
							        	<?php if (isset($search['category']) && $search['category'] == $category['Category']['id']): ?>
							        		<option  selected="selected" value="<?=$category['Category']['id']?>"><?=$category['Category']['name']?></option>
							        	<?php else: ?>
											<option value="<?=$category['Category']['id']?>"><?=$category['Category']['name']?></option>
							        	<?php endif ?>
							        <?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-ms-6 col-md-3 search-filter">
							<div class="filter">
								<input type="submit" name="filter_action" id="post-query-submit" class="btn btn-default btn-sm" value="<?=__('Filter')?>">
							</div>
						</div>
					</div>
				</form>
				<div class="col-xs-12"></div>
				<div class="col-sm-12">
					<div class="post-paginator">
						<?=$this->element('paginator')?>
					</div>
				</div>
				<div class="col-sm-12 list-content">
					<div class="table-responsive">
						<table class="table table-striped table-hover  table-bordered  dataTable ">
							<thead>
							<tr>
								<td>
									<input type="checkbox" id="checkDeleteAll"/>
									<label for="checkDeleteAll"></label>
								</td>
								<td><?php echo $this->Paginator->sort('title', __('Title')); ?></td>
								<td><?php echo $this->Paginator->sort('account_id', __('Author')); ?></td>
								<td><?php echo __('Categories'); ?></td>
								<td><?php echo $this->Paginator->sort('Date', __('Date')); ?></td>
								<td><?php echo __('Action'); ?></td>
							</tr>
							</thead>
							<tbody>
							<?php if (!empty($posts)): ?>
								<?php foreach ($posts as $post): ?>
								<tr>
									<td>
										<input type="checkbox" name="checkbox[]" data-id="checkbox" value="<?=$post['Post']['id']?>" id="<?=$post['Post']['id']?>" />
										<label for="<?=$post['Post']['id']?>"></label>
									</td>
									<td class="title">
										<div class="media">
							              <div class="media-left">
							                <a href="#">
							                	<img src="<?php echo h($post['Post']['avatar']); ?>" alt="">
							                </a>
							              </div>
							              <div class="media-body">
							                <h4 class="media-heading">
							                	<p><?php echo $this->Html->link(__($post['Post']['title']), array('action' => 'edit', $post['Post']['id'])); ?></p>
							                </h4>
							                <div class="action">
							                	<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $post['Post']['id'])); ?>
												<?php //echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $post['Post']['id']), array(), __('本当に削除します %s?', $post['Post']['id'])); ?><span> | </span>
												<a href="/admin/posts/delete/<?=$post['Post']['id']?>" onclick="check_delete('/admin/posts/delete/'+<?=$post['Post']['id']?>,event)"><?=__('Delete')?></a>
												<span> | </span>
												<?php echo $this->Html->link(__('Preview'), array('action' => 'view', $post['Post']['id'])); ?>
											</div>
							              </div>
							            </div>
									</td>
									<td>
										<?php echo $this->Html->link($post['Account']['username'], array('controller' => 'accounts', 'action' => 'view', $post['Account']['id'])); ?>
									</td>
									<td>
										<?php foreach ($post['Category'] as $key => $category): ?>
											<?php if ($key == 0): ?>
												<?= $category['name']?>
											<?php else: ?>
												<?php echo ', '.$category['name']?>
											<?php endif ?>
										<?php endforeach ?>
									</td>
									<td class="date">
										<p>
											<?php if ($post['Post']['status'] == "Publish"): ?>
												公共
											<?php else: ?>
												下書きとして保存
											<?php endif ?>
										</p>
										<p><?php echo h($post['Post']['created']); ?></p>
									</td>
									<td>
										<?php echo $this->Html->link(
			                                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white')),
			                                array('action' => 'edit', $post['Post']['id']),
			                                array('escape'=>false, 'class' => 'btn btn-success btn-sm', 'title' => '編集する')

			                            );
			                            ?>
			                            <?php echo $this->Html->link(
			                                $this->Html->tag('i', '', array('class' => 'fa fa-eye')),
			                                array('action' => 'view', $post['Post']['id']),
			                                array('escape'=>false, 'class' => 'btn btn-warning btn-sm', 'title' => 'パスワード変更')

			                            );
			                            ?>
			                            <?php //echo $this->Form->postLink(
			                                //$this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-remove icon-white')),
			                                //array('action' => 'delete', $post['Post']['id']),
			                               /// array('escape'=>false, 'id' => 'post-delete', 'class' => 'btn btn-danger btn-sm btn-cat-cancel', 'title' => '削除する')
			                            //);
			                            ?>
			                            <a href="/admin/posts/delete/<?=$post['Post']['id']?>" id="post-delete" class="btn btn-danger btn-sm btn-cat-cancel" title="削除する" onclick="check_delete('/admin/posts/delete/'+<?=$post['Post']['id']?>,event)"><i class="glyphicon glyphicon-remove icon-white"></i></a>
									</td>	
								</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr class="empty-data">
									<td colspan="6" style="text-align: center;">
										<strong><?php echo __('Không có bài viết nào')?></strong>
									</td>
									
								</tr>
							<?php endif ?>
							
							</tbody>
							<thead>
								<tr>
									<td>
										<input type="checkbox" id="checkDeleteAllBottom"/>
										<label for="checkDeleteAllBottom"></label>
									</td>
									<td><?php echo $this->Paginator->sort('title', __('Title')); ?></td>
									<td><?php echo $this->Paginator->sort('employee_id', __('Author')); ?></td>
									<td><?php echo __('Categories'); ?></td>
									<td><?php echo $this->Paginator->sort('Date', __('Date')); ?></td>
									<td><?php echo __('Action'); ?></td>
								</tr>
							</thead>
						</table>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="post-paginator">
						<?=$this->element('paginator')?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->start('script')?>
<?php echo $this->Html->script('/assets/js/l_script.js');?>
<?php $this->end()?>