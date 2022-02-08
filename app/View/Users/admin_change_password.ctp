<section class="content">
	<div class="row accounts form">
		<div class="col-xs-12">
			<?php echo $this->Form->create('User');?>
			<?php
				if(isset($current_login)){
					echo $this->Form->input('old_password',array('label'=>array('text'=>'現在のパスワード', 'class'=>'control-label col-xs-12 col-sm-2'), 'type'=>'password'));
				}
				echo $this->Form->input('password',array('label'=>array('text'=>'新しいパスワード', 'class'=>'control-label col-xs-12 col-sm-2'), 'type'=>'password'));
				echo $this->Form->input('confirm_password',array('label'=>array('text'=>'パスワード再入力', 'class'=>'control-label col-xs-12 col-sm-2'),'type'=>'password'));
				echo $this->Form->submit('パスワード変更');
			?>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</section>

