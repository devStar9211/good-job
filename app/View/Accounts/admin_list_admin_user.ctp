<?php $this->start('css')?>
	<?php echo $this->Html->css('/assets/css/l_css');?>
<?php $this->end()?>
<div class="box" id="list-admin-user">
	<div class="box-header with-border">
		<h3 class="box-title"><?=$title_for_layout?></h3>
		<div class="box-tools">
		   <div id="btn-box">
		  	<?php echo $this->Html->link(__('Đăng ký admin user'), array('action' => 'register_admin_user'), array('escape' => false, "class"=>"btn btn-primary btn-sm")); ?>
		   </div>
	    </div>
	</div>
	
	<div class="box-body" id="table-list">
		<?php echo $this->element('flash-message'); ?>
		<?php
			$option = array();
			$option['title'] = __('list admin user');
			$option['col'] =array(
			  0 => array('key_tab' =>'id','title_tab' => '#','option_tab' => 'sort'),
			  1 => array('key_tab' =>'Company.name','title_tab' => __('Company name'),'option_tab' => 'sort'),
			  2 => array('key_tab' =>'username','title_tab' => __('Username'),'option_tab' => 'sort'),
			  3 => array('key_tab' =>'Admin.name','title_tab' => __('Name'),'option_tab' => 'sort'),
			  4 => array('key_tab' =>'email','title_tab' => __('Email'),'option_tab' => 'sort'),
			  5 => array('key_tab' =>'','title_tab' => __('Action'),'option_tab' => ''),
			);
			echo $this->grid->create($accounts,null,$option);
		?>

		<?php foreach ($accounts as $key => $account): ?>
			<tr>
		        <td><?php echo $account['Account']['id'] ?>&nbsp;</td>
		        <?php if (empty($account['Company']['name'])): ?>
		        	<td><?= __('All')?></td>
		        <?php else: ?>
		        	<td><?php echo $account['Company']['name']; ?>&nbsp;</td>
		        <?php endif ?>

				<td>
					<?=$this->Html->image('../'.AVATAR_PATH.$account['Admin']['avatar'], array('alt' => 'Avatar'));?>
					<?php echo $account['Account']['username']; ?>&nbsp;
				</td>

				<td><?php echo $account['Admin']['name']; ?>&nbsp;</td>
				<td><?php echo $account['Account']['email']; ?>&nbsp;</td>

				<td class="actions">
		            <?php echo $this->Html->link(
		                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white')),
		                array('action' => 'change_admin_user', $account['Account']['id']),
		                array('escape'=>false, 'class' => 'btn btn-success btn-sm', 'title' => '編集する')
		            );
		            ?>
		            
		            <a href="/admin/accounts/delete_admin_user/<?= $account['Account']['id']?>" id="post-delete" class="btn btn-danger btn-sm btn-cat-cancel" title="削除する" onclick="check_delete('/admin/accounts/delete_admin_user/'+<?= $account['Account']['id']?>, event)"><i class="glyphicon glyphicon-remove icon-white"></i></a>
				</td>
			</tr>
		<?php endforeach; 
			echo $this->grid->end_table($accounts,null,$option);
		?>
	</div>
</div>

<?php $this->start('script')?>
<?php echo $this->Html->script('/assets/js/l_script.js');?>
<?php $this->end()?>