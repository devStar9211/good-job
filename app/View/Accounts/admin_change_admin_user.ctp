<?php $this->start('css')?>
	<?php echo $this->Html->css('/assets/components/croppie/croppie');?>
	<?php echo $this->Html->css('/assets/css/l_css');?>
<?php $this->end()?>

<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title"><?=$title_for_layout?></h3>
	</div>
	<?php echo $this->Form->create('Account', array('id' => 'account-validate'));?>
	<div class="box-body">
		<?php echo $this->element('flash-message'); ?>
		<div id="admin-avatar">
			<figure>
				<div class="avatar-select" onclick="popup_cropie_modal()">
					<div class="avatar-select-shadow align-middle"><i class="fa fa-fw fa-image"></i></div>
					<?php
						echo $this->Html->image('/'. AVATAR_PATH . $this->request->data['Admin']['avatar'], array('width' => '132', 'height' => '132', 'id' => 'avatar-preview', 'data-default' => $this->webroot . AVATAR_PATH . DEFAULT_AVATAR));
					?>
				</div>
				<figcaption><label class="label-avatar"><?php echo __('avatar') ?></label></figcaption>
			</figure>
			<?php

				echo $this->Form->input('Account.avatar.input', array('type' => 'hidden', 'hidden' => 'hidden', 'id' => 'avatar-input', 'data-source' => ($this->webroot . AVATAR_PATH . $this->request->data['Admin']['avatar'])));
				echo $this->Form->input('Account.avatar.original', array('type' => 'hidden', 'hidden' => 'hidden', 'id' => 'avatar-input-original', 'data-source' => (!empty($this->request->data['Admin']['avatar_original']) ? $this->webroot . AVATAR_PATH . $this->request->data['Admin']['avatar_original'] : $this->webroot . AVATAR_PATH . DEFAULT_AVATAR)));
				echo $this->Form->input('Account.avatar.points', array('type' => 'hidden', 'hidden' => 'hidden', 'id' => 'avatar-points', 'data-source' => (!empty($this->request->data['Admin']['avatar_points']) ? $this->request->data['Admin']['avatar_points'] : '')));
			?>
		</div>
		<div class="clearfix"></div>
		<?php
			echo $this->Form->input('company_id',array('options' => $companies, 'empty' => array('0' => __('All')), 'default' =>$this->request->data['Admin']['data_access_level'] , 'label' => array('text' => __('Select company'),'class'=>'control-label col-xs-12 col-sm-3')));
			echo $this->Form->input('name', array('label' => array('text' => __('Name'),'class'=>'control-label col-xs-12 col-sm-3'), 'value' => $this->request->data['Admin']['name']));

			echo $this->Form->input('email', array('label' => array('text' => __('Email'),'class'=>'control-label col-xs-12 col-sm-3'), 'value' => $this->request->data['Account']['email']));
			echo $this->Form->input('username', array('label' => array('text' => __('Username'),'class'=>'control-label col-xs-12 col-sm-3')));
			echo $this->Form->input('password', array('label' => array('text' => __('Password'),'class'=>'control-label col-xs-12 col-sm-3'), 'type' => 'password', 'value' => '', 'required' => false));

			echo $this->Form->input('confirm_password', array('label' => array('text' => __('Confirm password'),'class'=>'control-label col-xs-12 col-sm-3'), 'type' => 'password', 'required' => false));

			echo $this->Form->input('username_olr', array('type' => 'hidden', 'value' => $this->request->data['Account']['username']));
			echo $this->Form->input('profile', array('value' => $this->request->data['Admin']['profile'], 'type' => 'textarea', 'label' => array('text' => __('profile'), 'class'=>'control-label col-xs-12 col-sm-3')));

            echo $this->Form->input('headquarter', array('type'=>'checkbox', 'class'=>'', 'label' => array('text' => __('Headquarter'),'class'=>'control-label col-xs-12 col-sm-3 no-padding-top'), 'checked'=> $this->request->data['Admin']['headquarter'] == 1 ? 'true' : ''  ));
		?>
	</div>
	<div class="box-footer clearfix">
		<?php echo $this->Form->submit(__('登録'));?>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<?php 
	$this->start('modal');
	echo $this->element('avatar_cropie_modal');
	$this->end();
?>
<?php $this->start('script')?>
	<?php echo $this->Html->script([
		'/assets/components/bootstrap-datetimepicker/moment-with-locales.min.js',
		'/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js',
		'/assets/components/croppie/croppie.min.js',
		'/assets/js/g_script/g_script.js',
		'/assets/js/g_script/g_avatar_croppie.js',
		'/assets/js/g_script/g_back.js'
	]);?>

<?php $this->end()?>