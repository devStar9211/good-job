<?php $this->start('css')?>
	<?php echo $this->Html->css('/assets/css/l_css');?>
<?php $this->end()?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?=$title_for_layout?></h3>
		<div class="box-tools">
		   <div id="btn-box">
		  	<?php echo $this->Html->link(__('Add Category'), array('action' => 'add'), array('escape' => false, "class"=>"btn btn-primary btn-sm")); ?>
		   </div>
	    </div>
	</div>
	
	<div class="box-body" id="table-list">
		<?php echo $this->element('flash-message'); ?>
		<?php
			$option = array();
			$option['title'] = __('list Categories');
			$option['col'] =array(
			  0 => array('key_tab' =>'id','title_tab' => '#','option_tab' => 'sort'),
			  1 => array('key_tab' =>'name','title_tab' => '記事タイトル','option_tab' => 'sort'),
			  2 => array('key_tab' =>'updated','title_tab' => '最終更新','option_tab' => 'sort'),
			  3 => array('key_tab' =>'','title_tab' => '操作','option_tab' => ''),
			);
			echo $this->grid->create($categories,null,$option);
		?>

		<?php foreach ($categories as $key => $category): ?>
			<tr>
		        <td><?php echo ($key+1)?>&nbsp;</td>
		        <td><?php echo h($category['Category']['name']); ?>&nbsp;</td>

		        <td><?php echo h(date('Y-m-d H:i:s',strtotime($category['Category']['updated']))); ?>&nbsp;</td>
				<td class="actions">
		            <?php echo $this->Html->link(
		                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white')),
		                array('action' => 'edit', $category['Category']['id']),
		                array('escape'=>false, 'class' => 'btn btn-success btn-sm', 'title' => '編集する')
		            );
		            ?>
		            
		            <a href="/admin/categories/delete/<?= $category['Category']['id']?>" id="post-delete" class="btn btn-danger btn-sm btn-cat-cancel" title="削除する" onclick="check_delete('/admin/categories/delete/'+<?= $category['Category']['id']?>,event)"><i class="glyphicon glyphicon-remove icon-white"></i></a>
				</td>
			</tr>
		<?php endforeach; 
			echo $this->grid->end_table($categories,null,$option);
		?>
	</div>
</div>

<?php $this->start('script')?>
<?php echo $this->Html->script('/assets/js/l_script.js');?>
<?php $this->end()?>