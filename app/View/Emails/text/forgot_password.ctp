<p><?= __('Xin chào:')?> <strong>
	<?php if (empty($account['Admin']['id'])): ?>
		<?=$account['Employee']['name']?>
	<?php else: ?>
		<?=$account['Admin']['name']?>
	<?php endif ?>
</strong></p>
<p><?php echo __("Mật khẩu mới của bạn là ")?>: <b><?php echo $pass_new; ?></b></p>