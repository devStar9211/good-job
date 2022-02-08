<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?=$title_for_layout?></h3>
	</div>
	<?php echo $this->Form->create('Category', array('id' => 'category-validate')); ?>
	<div class="box-body">
		<?php echo $this->element('flash-message'); ?>
		<?php
			echo $this->Form->input( 'parent_id',array('required' => false, 'options' => $parentCategories, 'empty' => true, 'default' => $this->request->data['Category']['parent_id'], 'label' => array('text' => __('Parent'),'class'=>'control-label col-xs-12 col-sm-2')));
			echo $this->Form->input('name',  array('label' => array('text' => __('Category name'),'class'=>'control-label col-xs-12 col-sm-2')));
		?>
	</div>
	<div class="box-footer clearfix">
		<?php echo $this->Form->submit(__('save'));?>
	</div>
	<?php echo $this->Form->end(); ?>
</div>