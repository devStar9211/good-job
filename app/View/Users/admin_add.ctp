<?php $this->start('action_bar');?>
<div class="btn-group pull-right">
	<?php echo $this->Html->link('ユーザー一覧',array('action' => 'index', 'admin' => true),array('escape'=>false, 'class' => 'btn btn-primary'));
	?>
</div>
<?php $this->end(); ?>
<div class="row accounts form">
	<div class="col-xs-12">
		<?php echo $this->Form->create('User');?>
		<?php
			echo $this->Form->input('username', array('label' => array('text' => 'ユーザー名','class'=>'control-label col-xs-12 col-sm-2')));
			echo $this->Form->input('password', array('label' => array('text' => 'パスワード','class'=>'control-label col-xs-12 col-sm-2')));
			echo $this->Form->input('confirm_password', array('label' => array('text' => 'パスワード再入力','class'=>'control-label col-xs-12 col-sm-2'), 'type' => 'password'));
			echo $this->Form->input('full_name', array('label' => array('text' => '名前','class'=>'control-label col-xs-12 col-sm-2')));
			echo $this->Form->input('status', array(
				'options' => array('非アクティブ', 'アクティブ'),
				'label' => array('text' => 'ステータス','class'=>'control-label col-xs-12 col-sm-2'),
			));
			echo $this->Form->input('email', array('label' => array('text' => 'メール','class'=>'control-label col-xs-12 col-sm-2')));
		?>
		<?php
			echo $this->Form->submit('登録');
		?>
		<?php echo $this->Form->end(); ?>
	</div>
</div>

