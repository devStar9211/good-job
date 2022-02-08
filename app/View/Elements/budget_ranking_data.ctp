<table id="budget_ranking" class="list-ranking budget_ranking">
    <tbody class="">
    <?php foreach ($data['office'] as $k => $office): ?>
        <?php
        $ranking = $office['ranking'];
        $class_rank = 'th';
        switch ($office['ranking']) {
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
        $manager_avatar = !empty($office['employee']) ? $office['employee']['avatar'] : DEFAULT_AVATAR;
        ?>
        <tr class="ranking-group">
            <td class="a-center col-avatar">
                <div class="rank rank-<?php echo $class_rank ?>"><?php echo $ranking ?></div>
                <div class="avatar avatar-rounded avatar-sm">
                    <?php echo $this->Html->image('/' . AVATAR_PATH . $manager_avatar, array('class' => 'img img-responsive')) ?>
                </div>
            </td>
            <td class="col-name">
                <div class="content-wrap h50 office-name">
                    <p class="bold " title="<?php echo $office['office']['name'] ?>"><?php echo $office['office']['name'] ?></p>
                    <p class="light-fade fs1rh" title="<?php echo $office['company']['name'] ?>">
                        <?php echo $office['company']['name'] ?>
                    </p>
                </div>
            </td>
            <td class="col-xxs-12 no-padding col-group-value">
                <table class="bg_rk_group_value">
                    <tr class="no-border ">
                        <td class="fs3r col-value val-ratio" style=""><?php echo $office['profit']['last-month']['rates'] > 0 ? number_format($office['profit']['last-month']['rates'], 1, '.', ',') . '%' : '-' ?></td>
                        <td class="fs1rh light-fade text-right b-xs col-value val-rank" style="">
                            <?php
                            echo(
                            !empty($office['previous_ranking'])
                                ? __('前回' . $office['previous_ranking'] . '位')
                                : '. . .'
                            );
                            ?>
                        </td>
                        <td class="fs3r col-value val-profit" style="">
                            <?php echo '<span class="format_number_text">'.number_format($office['profit']['last-month']['excess_profit'], 0, '.', ',').'</span>円'  ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>