<?php $this->start('css')?>
	<?php echo $this->Html->css('/assets/css/l_css');?>
<?php $this->end()?>
<div class="container">
	<div class="row">
		<div class="forgot_password">
			<?php if (isset($_GET['success'])): ?>
				<p><?=__('Mật khẩu mới của bạn đả được gửi đến email:')?> <b><?=$_GET['email']?></b></p>
				<p><?=__('Hảy kiểm tra email của bạn.')?><p>
				<?php
					echo $this->Html->link(__('click vào đây để tiếp tục đăng nhập vào hệ thống.'),'login');
				?>
			<?php else: ?>
				<div class="box">
					<?php if (isset($error)): ?>
						<div class="box-header" style="text-align: center;">
						<label style="color: #dd4b39 !important;" class="control-label" before="<i class=&quot;fa fa-times-circle-o&quot;></i> "><?= $error ?></label>
						</div>
					<?php endif ?>
					<?php echo $this->Form->create('Account', array('id' => 'account-validate'));?>
					<div class="box-body">
						<?php echo $this->element('flash-message'); ?>
						<div class="clearfix"></div>
						<?php
							echo $this->Form->input('email', array('label' => array('type' => 'email', 'text' => __('Email'),'class'=>'control-label col-xs-12 col-sm-2')));
						?>
					</div>
					<div class="box-footer clearfix">
						<?php echo $this->Form->submit(__('Resend Password'));?>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			<?php endif ?>
		</div>
	</div>
</div>