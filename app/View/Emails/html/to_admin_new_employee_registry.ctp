<h3>
	<?php echo __('Wellcom to Caregiver Japan 様日次決算システム') ?>
</h3>
<hr>
<p>
	<span><?php echo __('Hello') ?></span>
	<strong><?php echo $admin['name'] ?></strong>
</p>
<p>
	<?php echo __('new employee %s is successfuly added.', '<b>'.$employee['name'].'</b>') ?>
</p>
<p>
	<?php echo __('please goes %s for more information.', $this->Html->link(__('here'), array('controller' => 'Employees', 'action' => 'admin_edit', $employee['id'], 'full_base' => true, 'admin' => true, 'plugin' => false))) ?>
</p>