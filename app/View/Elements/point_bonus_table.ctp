<?php
$start_month = $data['start_month'];
$end_month = $data['start_month'] + 11;
?>

<div class="clearfix">
    <div class="table-responsive" role="tb-wrap">
        <table id="tb-budget" class="table table-bordered table-striped dataTable responsive" data-collumn='2'
               data-fixed="180px"
               style="">
            <thead>
            <th class="text-left" style="width: 30px">#</th>
            <th class="text-left" style="width: 150px"><?php echo __('name'); ?></th>

            <?php
            for ($i = $start_month; $i <= $end_month; $i++) :
                if ($i > 12) {
                    $month = $i - 12;
                } else {
                    $month = $i;
                }
                ?>
                <th  class="text-right"><?php echo __($month.'月'); ?></th>

            <?php endfor ?>

            </thead>
            <tbody id="">
            <?php
            if(isset($data['employees']) && $data['employees'] != null) {
                $i = 0;
                $collum = 6;
                $size_table = sizeof($data['employees']);
                foreach ($data['employees'] as $employee) {
                    echo "<tr>";
                    ?>
                    <td>
                        <div class="h34 align-middle"><?php echo $employee['info']['id']; ?></div>
                    </td>
                    <td>
                        <div class="h34 align-middle"><?php echo h($employee['info']['name']) ?></div>
                    </td>
                    <?php
                    for ($i = $start_month; $i <= $end_month; $i++) :
                        $id = ((isset($employee['points'][$i]['id'])) ? $employee['points'][$i]['id'] : '');
                        $bonus_yen = ((isset($employee['points'][$i]['bonus_yen'])) ? $employee['points'][$i]['bonus_yen'] : '');

                        ?>
                        <td>
                            <?php
                            echo $this->Form->input('points.' . $employee['info']['id'] . '.' . $i . '.bonus_yen', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => $bonus_yen, 'maxlength' => '18'));
                            echo $this->Form->hidden('points.' . $employee['info']['id'] . '.' . $i . '.id', array('value' => $id));
                            ?>
                        </td>


                    <?php endfor ?>

                    <?php
                    echo "</tr>";
                }
            }
            ?>

            </tbody>
        </table>
    </div>

</div>