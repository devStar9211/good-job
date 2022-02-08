<div class="groupPermissions view">
<h2><?php echo __('Group Permission'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($groupPermission['GroupPermission']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($groupPermission['GroupPermission']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($groupPermission['GroupPermission']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated'); ?></dt>
		<dd>
			<?php echo h($groupPermission['GroupPermission']['updated']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Group Permission'), array('action' => 'edit', $groupPermission['GroupPermission']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Group Permission'), array('action' => 'delete', $groupPermission['GroupPermission']['id']), array(), __('Are you sure you want to delete # %s?', $groupPermission['GroupPermission']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Group Permissions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group Permission'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts'), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account'), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Company Groups'), array('controller' => 'company_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Company Group'), array('controller' => 'company_groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Accounts'); ?></h3>
	<?php if (!empty($groupPermission['Account'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Username'); ?></th>
		<th><?php echo __('Password'); ?></th>
		<th><?php echo __('Group Permission Id'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Updated'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($groupPermission['Account'] as $account): ?>
		<tr>
			<td><?php echo $account['id']; ?></td>
			<td><?php echo $account['email']; ?></td>
			<td><?php echo $account['username']; ?></td>
			<td><?php echo $account['password']; ?></td>
			<td><?php echo $account['group_permission_id']; ?></td>
			<td><?php echo $account['type']; ?></td>
			<td><?php echo $account['created']; ?></td>
			<td><?php echo $account['updated']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'accounts', 'action' => 'view', $account['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'accounts', 'action' => 'edit', $account['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'accounts', 'action' => 'delete', $account['id']), array(), __('Are you sure you want to delete # %s?', $account['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Account'), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Company Groups'); ?></h3>
	<?php if (!empty($groupPermission['CompanyGroup'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Group Permission Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Start Month'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Updated'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($groupPermission['CompanyGroup'] as $companyGroup): ?>
		<tr>
			<td><?php echo $companyGroup['id']; ?></td>
			<td><?php echo $companyGroup['group_permission_id']; ?></td>
			<td><?php echo $companyGroup['name']; ?></td>
			<td><?php echo $companyGroup['start_month']; ?></td>
			<td><?php echo $companyGroup['created']; ?></td>
			<td><?php echo $companyGroup['updated']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'company_groups', 'action' => 'view', $companyGroup['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'company_groups', 'action' => 'edit', $companyGroup['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'company_groups', 'action' => 'delete', $companyGroup['id']), array(), __('Are you sure you want to delete # %s?', $companyGroup['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Company Group'), array('controller' => 'company_groups', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
