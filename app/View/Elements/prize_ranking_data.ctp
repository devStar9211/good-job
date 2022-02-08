<div class="list-raking">
	<?php foreach($data as $employee): ?>
		<?php
			$class_rank = 'th';
			switch($employee['ranking']['rank']) {
				case 1: $class_rank = '1st'; break;
				case 2: $class_rank = '2nd'; break;
				case 3: $class_rank = '3rd'; break;
				default: $class_rank = 'th'; break;
			}

			$employee_avatar = !empty($employee['Employee']) ? $employee['Employee']['avatar'] : DEFAULT_AVATAR;
		?>
		<div class="ranking-group clearfix">
			<div class="input-group fluid">
				<span class="input-group-addon no-border col-xxs-12 rank-top-xxs-sm">
					<div class="rank rank-<?php echo $class_rank ?>"><?php echo $employee['ranking']['rank'] ?></div>
				</span>
				<div class="col-xxs-12 no-padding">
					<div class="input-group fluid">
						<span class="input-group-addon no-border no-padding">
							<div class="avatar avatar-rounded avatar-sm">
								<?php echo $this->Html->image('/'. AVATAR_PATH . $employee_avatar, array('class' => 'img img-responsive')) ?>
							</div>
						</span>
						<div class="content-wrap h50">
							<p class="bold fs2r" title="<?php echo $employee['Employee']['name'] ?>">
								<?php echo $employee['Employee']['name'] ?>
							</p>
							<p class="light-fade fs1rh" title="<?php echo $employee['Company']['name'] ?>">
								<?php echo $employee['Company']['name'] ?>
							</p>
						</div>
						<span class="input-group-addon no-border clearfix no-padding">
							<div class="col-xs-12 fs3r">
								<i class="fa">&#x00A5;</i>
								<?php echo number_format($employee['ranking']['prize'],0,'.',',') ?>
							</div>
						</span>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</div>