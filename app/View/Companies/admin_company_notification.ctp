<?php $this->start('css')?>
	<?php echo $this->Html->css('/assets/css/l_css');?>
<?php $this->end()?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?=$title_for_layout?></h3>
		<div class="box-tools">
		   <div id="btn-box">
		  	<?php echo $this->Html->link(__('Add company'), array('action' => 'add'), array('escape' => false, "class"=>"btn btn-primary btn-sm")); ?>
		   </div>
	    </div>
	</div>
	
	<div class="box-body" id="table-list">
		<?php echo $this->element('flash-message'); ?>
			<?php
			$option = array();
			$option['title'] = '管理者アカウント管理 ';
			$option['col'] =array(
			  0 => array('key_tab' =>'id','title_tab' => '#','option_tab' => 'sort','style' => 'width: 40px;'),
			  1 => array('key_tab' =>'name','title_tab' => '氏名','option_tab' => 'sort'),
			  2 => array('key_tab' =>'company_group','title_tab' => __('Company group'),'option_tab' => 'sort'),
			  3 => array('key_tab' =>'','title_tab' => '操作','option_tab' => ''),
			);
			echo $this->grid->create($companies,null,$option);
		?>

		<?php
//        pr($companies);die;
        foreach ($companies as $key => $company): ?>
			<tr>
		        <td><?php echo $company['Company']['id']; ?>&nbsp;</td>
		        <td><?php echo h($company['Company']['name']); ?>&nbsp;</td>
		        <td><?php echo h($company['CompanyGroup']['name']); ?>&nbsp;</td>
                <td class="actions">
		            <?php echo $this->Html->link(
		                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit icon-white')),
		                array('action' => 'email_list', $company['Company']['id']),
		                array('escape'=>false, 'class' => 'btn btn-success btn-sm', 'title' => '編集する')
		            );
		            ?>
				</td>
			</tr>
		<?php endforeach; 
			echo $this->grid->end_table($companies,null,$option);
		?>
	</div>
</div>

<?php $this->start('script')?>
<?php echo $this->Html->script('/assets/js/l_script.js');?>
<?php $this->end()?>