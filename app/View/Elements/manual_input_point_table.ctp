<div class="clearfix">
    <div class="table-responsive" role="tb-wrap">
        <table id="tb-budget" class="table table-bordered table-striped dataTable "  data-fixed="120px"
               style=" width: 300px; ">

            <tbody id="">
            <?php
            $i = 0;
            $collum = 6;
            $size_table = sizeof($data['employees']);
            foreach ($data['employees'] as $employee) {
                $i++;
                if ($i == 0 || ($i - 1) % $collum == 0) {
                    echo "<tr>";
                }
                $value = ((isset($employee['points']['value'])) ? $employee['points']['value'] : '');
                $id = ((isset($employee['points']['id'])) ? $employee['points']['id'] : '');
                $employee_id = ((isset($employee['points']['employee_id'])) ? $employee['points']['employee_id'] : '');
                ?>
                <td style="min-width: 150px">
                    <div class="h34 align-middle"><?php echo '(#'.$employee['info']['id'].') '. h($employee['info']['name']) ?></div>
                    <?php
                    echo $this->Form->input('CampaignPoint.point.' . $employee['info']['id'] . '.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => $value, 'maxlength' => '18', "onfocus"=>"update_budget_profit()", "onkeyup"=>"update_budget_profit()"));
                    echo $this->Form->hidden('CampaignPoint.point.' . $employee['info']['id'] . '.id', array('value' => $id));
                    echo $this->Form->hidden('CampaignPoint.point.' . $employee['info']['id'] . '.employee_id', array('value' => $employee_id));
                    ?>
                </td>

                <?php
                if ($i == $size_table || $i % $collum == 0) {
                    echo "</tr>";
                }

            } ?>

            </tbody>
        </table>
    </div>
</div>