<?php $this->start('action_bar');?>
<div class="btn-group pull-right">
	<?php echo $this->Html->link('ユーザー一覧',array('action' => 'index', 'admin' => true),array('escape'=>false, 'class' => 'btn btn-primary'));
	?>
	<?php echo $this->Form->postLink(
		'削除する',
		array('action' => 'delete', $account['User']['id']),
		array('escape'=>false, 'class' => 'btn btn-danger'),
		__('%sを本当に削除しますか。', $account['User']['id'])

	);
	?>
</div>
<?php $this->end(); ?>
<div class="row accounts form">
	<div class="col-xs-12">
		<?php echo $this->Form->create('User');?>
		<?php
			echo $this->Form->input('username', array('label' => array('text' => 'ユーザー名','class'=>'control-label col-xs-12 col-sm-2'), 'disabled'=> true));
			echo $this->Form->input('full_name', array('label' => array('text' => '名前','class'=>'control-label col-xs-12 col-sm-2')));
			echo $this->Form->input('status', array(
				'options' => array('非アクティブ', 'アクティブ'),
				'default' => $this->data['User']['status'],
				'label' => array('text' => 'ステータス','class'=>'control-label col-xs-12 col-sm-2'),

			));
			echo $this->Form->input('email', array('label' => array('text' => 'メール','class'=>'control-label col-xs-12 col-sm-2')));
		?>
		<?php
			echo $this->Form->submit('変更する');
		?>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
