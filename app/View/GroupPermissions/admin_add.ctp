<div class="groupPermissions form">
<?php echo $this->Form->create('GroupPermission'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Group Permission'); ?></legend>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Group Permissions'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Accounts'), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account'), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Company Groups'), array('controller' => 'company_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Company Group'), array('controller' => 'company_groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
