<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?=$title_for_layout?></h3>
	</div>
	<?php echo $this->Form->create('Company', array('id' => 'Company-validate'));?>
	<div class="box-body">
		<?php echo $this->element('flash-message'); ?>
		<?php
			echo $this->Form->input('company_group_id',array('options' => $companyGroups, 'empty' => true, 'label' => array('text' => __('group company'),'class'=>'control-label col-xs-12 col-sm-2')));
			echo $this->Form->input('name', array('label' => array('text' => __('Company name'),'class'=>'control-label col-xs-12 col-sm-2')));
		?>
	</div>
	<div class="box-footer clearfix">
		<?php echo $this->Form->submit(__('Submit'));?>
	</div>
	<?php echo $this->Form->end(); ?>
</div>