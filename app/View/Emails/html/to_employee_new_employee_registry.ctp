<h3>
	<?php echo __('Wellcom to Caregiver Japan 様日次決算システム') ?>
</h3>
<hr>
<p>
	<span><?php echo __('Hello') ?></span>
	<strong><?php echo $employee['name'] ?></strong>
</p>
<p>
	<h4><?php echo __('Here\'s your account information') ?>:</h4>
	<table>
		<tbody>
			<tr>
				<td style="padding: 0 10px 0 0;">- <?php echo __('username') ?>:</td>
				<td><?php echo $employee['account']['username'] ?></td>
			</tr>
			<tr>
				<td style="padding: 0 10px 0 0;">- <?php echo __('password') ?>:</td>
				<td><?php echo $employee['account']['password'] ?></td>
			</tr>
		</tbody>
	</table>
</p>
<p>
	<?php echo __('please goes %s to access our system.', $this->Html->link(__('here'), array('controller' => 'accounts', 'action' => 'login', 'full_base' => true, 'admin' => false, 'plugin' => false))) ?>
</p>