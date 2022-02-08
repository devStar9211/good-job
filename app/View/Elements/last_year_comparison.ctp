<div class="list-raking">
    <?php
    if (empty($data)) {
        echo __("Empty data.");

    } else {
        foreach ($data as $key => $office):

            $class_rank = 'th';
            switch ((int) $office['Rate']['position']) {
                case 1:
                    $class_rank = '1st';
                    break;
                case 2:
                    $class_rank = '2nd';
                    break;
                case 3:
                    $class_rank = '3rd';
                    break;
                default:
                    $class_rank = 'th';
                    break;
            }

//            $manager_avatar = DEFAULT_AVATAR;
			$manager_avatar = !empty($office['Employee']['employee_avatar']) ? $office['Employee']['employee_avatar'] : DEFAULT_AVATAR;
            ?>
            <div class="ranking-group clearfix">
                <div class="input-group fluid">
				<span class="input-group-addon no-border col-xxs-12 rank-top-xxs-sm">
					<div class="rank rank-<?php echo $class_rank ?>"><?php echo $office['Rate']['position'] ?></div>
				</span>
                    <div class="col-xxs-12 no-padding">
                        <div class="input-group fluid">
						<span class="input-group-addon no-border no-padding">
							<div class="avatar avatar-rounded avatar-sm">
								<?php echo $this->Html->image('/'. AVATAR_PATH . $manager_avatar, array('class' => 'img img-responsive')) ?>
							</div>
						</span>
                            <div class="content-wrap h50">
                                <p class="bold fs2r" title="<?php echo $office['Office']['office_name'] ?>">
                                    <?php echo $office['Office']['office_name'] ?>
                                </p>
                                <p class="light-fade fs1rh"
                                   title="<?php //echo $office['TempBudgetSale']['company_name'];
                                   ?>">
                                    <?php echo $office['Company']['company_name'] ?>
                                </p>
                            </div>
                            <span class="input-group-addon no-border">
							<div class="clearfix">

								<div class="fs3r no-padding text-right">
									<?php echo round($office['Rate']['rate_sales_revenues'], 2).'%'; ?> <span
                                            class="fs1rh light-fade text-right b-xs">
										<?php echo __('前回%d位',$office['LastRate']['position']); ?></span>
								</div>
							</div>
						</span>


                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;
    }
    ?>
</div>
