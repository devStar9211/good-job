<div class="groupPermissions index">
	<h2><?php echo __('Group Permissions'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('updated'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($groupPermissions as $groupPermission): ?>
	<tr>
		<td><?php echo h($groupPermission['GroupPermission']['id']); ?>&nbsp;</td>
		<td><?php echo h($groupPermission['GroupPermission']['name']); ?>&nbsp;</td>
		<td><?php echo h($groupPermission['GroupPermission']['created']); ?>&nbsp;</td>
		<td><?php echo h($groupPermission['GroupPermission']['updated']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $groupPermission['GroupPermission']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $groupPermission['GroupPermission']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $groupPermission['GroupPermission']['id']), array(), __('Are you sure you want to delete # %s?', $groupPermission['GroupPermission']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Group Permission'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Accounts'), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account'), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Company Groups'), array('controller' => 'company_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Company Group'), array('controller' => 'company_groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
