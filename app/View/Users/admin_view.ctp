<div class="accounts view">
<h2><?php echo __('Account'); ?></h2>
	<dl>
		<dt><?php echo __('Username'); ?></dt>
		<dd>
			<?php echo h($account['User']['username']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($account['User']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Full Name'); ?></dt>
		<dd>
			<?php echo h($account['User']['full_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Account Type'); ?></dt>
		<dd>
			<?php echo h($account['User']['account_type']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Account'), array('action' => 'edit', $account['User']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Account'), array('action' => 'delete', $account['User']['id']), array(), __('Are you sure you want to delete # %s?', $account['User']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account'), array('action' => 'add')); ?> </li>
	</ul>
</div>
