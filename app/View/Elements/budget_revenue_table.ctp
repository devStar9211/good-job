<div class="clearfix">
    <div class="table-responsive" role="tb-wrap">
        <table id="tb-budget" class="table table-bordered table-striped dataTable responsive" data-fixed="150px">
            <thead>
            <?php
            $inputNumber = '';
            $start_month = $data['start_month'];
            $end_month = $data['start_month'] + 11;

            $col_table = array(
                array('&#65279;' => array()),
            );
            for ($i = $start_month; $i <= $end_month; $i++) {
                if ($i > 12) {
                    $month = $i - 12;
                } else {
                    $month = $i;
                }
                $col_table[] = array(__($month . '月') => array('class' => 'text-center'));
            }
            echo $this->Html->tableHeaders($col_table);

            ?>
            </thead>
            <tbody id="revenue-budget">
            <!-- revenues -->
            <?php $revenues_key = 0 ?>
            <?php if (!empty($data['revenues'])): ?>
                <?php foreach ($data['revenues'] as $name => $value): ?>
                    <?php $revenues_key++ ?>
                    <tr <?php if ($revenues_key > 1) {
                        echo 'class="revenue_' . $revenues_key . '"';
                    } ?>>
                        <td>
                            <?php
                            echo $this->Form->input('RevenueBudget.e_revenue.' . $revenues_key . '.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('売上'), 'value' => $name));
                            ?>
                        </td>
                        </td>
                        <?php
                        for ($i = $start_month; $i <= $end_month; $i++) :
                            ?>
                            <td>
                                <?php


                                echo $this->Form->input('RevenueBudget.e_revenue.' . $revenues_key . '.' . $i . '.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => (isset($value[$i]['value']) ? $value[$i]['value'] : ''), 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'role' => 'revenue-input', 'data-month' => $i));
                                echo $this->Form->input('RevenueBudget.e_revenue.' . $revenues_key . '.' . $i . '.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => (isset($value[$i]) ? $value[$i]['id'] : '')));
                                ?>
                            </td>
                        <?php endfor ?>
                    </tr>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <?php $revenues_key = 1 ?>
                <tr>
                    <td>
                        <?php
                        echo $this->Form->input('RevenueBudget.e_revenue.' . $revenues_key . '.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('売上'), 'value' => __('売上')));
                        echo $this->Form->input('RevenueBudget.e_revenue.' . $revenues_key . '.name', array('div' => false, 'label' => false, 'type' => 'hidden', 'value' => __('売上'), 'readonly' => 'readonly'));
                        ?>
                    </td>
                    <?php
                    for ($i = $start_month; $i <= $end_month; $i++):
                    ?>
                        <td>
                            <?php
                            echo $this->Form->input('RevenueBudget.e_revenue.' . $revenues_key . '.' . $i . '.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'role' => 'revenue-input', 'data-month' => $i));
                            echo $this->Form->input('RevenueBudget.e_revenue.' . $revenues_key . '.' . $i . '.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => ''));
                            ?>
                        </td>
                    <?php endfor ?>
                </tr>
            <?php endif ?>
            <tr role="revenue-row" class="hidden">
                <td>
                    <?php
                    echo $this->Form->input('RevenueBudget.e_revenue.key.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('売上')));
                    echo $this->Form->input('RevenueBudget.e_revenue.key.', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'role' => 'revenue-v-key', 'data-key' => $revenues_key));
                    ?>
                </td>
                <?php
                for ($i = $start_month; $i <= $end_month; $i++):
                ?>
                    <td>
                        <?php
                        echo $this->Form->input('RevenueBudget.e_revenue.key.' . $i . '.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'role' => 'revenue-input', 'data-month' => $i));
                        echo $this->Form->input('RevenueBudget.e_revenue.key.' . $i . '.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => ''));
                        ?>
                    </td>
                <?php endfor ?>
            </tr>
            <tr role="revenue-add">
                <td><?php echo $this->Form->button('<i class="fa fa-plus primary"></i>', array('type' => 'button', 'class' => 'btn btn-default table-input', 'onclick' => 'add_another_row(\'#revenue-budget\', this, \'revenue\')')) ?></td>
                <?php for ($i = $start_month; $i <= $end_month; $i++) : ?>
                    <td>
                        <div class="h34"></div>
                    </td>
                <?php endfor ?>
            </tr>
            <!-- ./revenues -->
            <!-- labor_cost -->
            <tr>
                <td>
                    <div class="h34 align-middle"><?php echo __('人件費') ?></div>
                </td>
                <?php
                for ($i = $start_month; $i <= $end_month; $i++):
                ?>
                    <td>
                        <?php
                        echo $this->Form->input('RevenueBudget.e_labor_cost.' . $i, array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => (isset($data['budget_labor_cost']) && isset($data['budget_labor_cost'][$i]) ? $data['budget_labor_cost'][$i] : ''), 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'data-month' => $i, 'role' => 'labor-cost-input'));
                        ?>
                    </td>
                <?php endfor ?>
            </tr>
            <!-- ./labor_cost -->
            <!-- overtime_cost -->
            <tr>
                <td>
                    <div class="h34 align-middle"><?php echo __('残業費') ?></div>
                </td>
                <?php
                for ($i = $start_month; $i <= $end_month; $i++):
                ?>
                    <td>
                        <?php
                        echo $this->Form->input('RevenueBudget.e_overtime.' . $i, array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => (isset($data['budget_overtime_cost']) && isset($data['budget_overtime_cost'][$i]) ? $data['budget_overtime_cost'][$i] : ''), 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'data-month' => $i, 'role' => 'overtime-cost-input'));
                        ?>
                    </td>
                <?php endfor ?>
            </tr>
            <!-- ./overtime_cost -->
            <!-- expenses -->
            <?php $expenses_key = 0 ?>
            <?php if (!empty($data['expenses'])): ?>
                <?php foreach ($data['expenses'] as $name => $value): ?>
                    <?php $expenses_key++ ?>
                    <tr <?php if ($expenses_key > 1) {
                        echo 'class="expense_' . $expenses_key . '"';
                    } ?>>
                        <td>
                            <?php
                            echo $this->Form->input('RevenueBudget.e_expense.' . $expenses_key . '.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('経費'), 'value' => $name));
                            echo $this->Form->input('RevenueBudget.e_expense.' . $expenses_key . '.name', array('div' => false, 'label' => false, 'type' => 'hidden', 'value' => $name, 'readonly' => 'readonly'));
                            ?>
                        </td>
                        <?php
                        for ($i = $start_month; $i <= $end_month; $i++):
                        ?>
                            <td>
                                <?php
                                echo $this->Form->input('RevenueBudget.e_expense.' . $expenses_key . '.' . $i . '.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => (isset($value[$i]['value']) ? $value[$i]['value'] : ''), 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'role' => 'expense-input', 'data-month' => $i));
                                echo $this->Form->input('RevenueBudget.e_expense.' . $expenses_key . '.' . $i . '.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'value' => '', 'hidden' => 'hidden', 'value' => (isset($value[$i]) ? $value[$i]['id'] : '')));
                                ?>
                            </td>
                        <?php endfor ?>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <?php $expenses_key = 1 ?>
                <tr>
                    <td>
                        <?php
                        echo $this->Form->input('RevenueBudget.e_expense.' . $expenses_key . '.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('経費'), 'value' => __('経費')));
                        echo $this->Form->input('RevenueBudget.e_expense.' . $expenses_key . '.name', array('div' => false, 'label' => false, 'type' => 'hidden', 'value' => __('経費'), 'readonly' => 'readonly'));
                        ?>
                    </td>
                    <?php
                    for ($i = $start_month; $i <= $end_month; $i++):
                    ?>
                        <td>
                            <?php
                            echo $this->Form->input('RevenueBudget.e_expense.' . $expenses_key . '.' . $i . '.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'role' => 'expense-input', 'data-month' => $i));
                            echo $this->Form->input('RevenueBudget.e_expense.' . $expenses_key . '.' . $i . '.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'value' => '', 'hidden' => 'hidden'));
                            ?>
                        </td>
                    <?php endfor ?>
                </tr>
            <?php endif ?>
            <tr role="expense-row" class="hidden">
                <td>
                    <?php
                    echo $this->Form->input('RevenueBudget.e_expense.key.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('経費')));
                    echo $this->Form->input('RevenueBudget.e_expense.key.', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'role' => 'expense-v-key', 'data-key' => $expenses_key));
                    ?>
                </td>
                <?php
                for ($i = $start_month; $i <= $end_month; $i++):
                ?>
                    <td>
                        <?php
                        echo $this->Form->input('RevenueBudget.e_expense.key.' . $i . '.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'role' => 'expense-input', 'data-month' => $i));
                        echo $this->Form->input('RevenueBudget.e_expense.key.' . $i . '.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'value' => '', 'hidden' => 'hidden'));
                        ?>
                    </td>
                <?php endfor ?>
            </tr>
            <tr role="expense-add">
                <td><?php echo $this->Form->button('<i class="fa fa-plus primary"></i>', array('type' => 'button', 'class' => 'btn btn-default table-input', 'onclick' => 'add_another_row(\'#revenue-budget\', this, \'expense\')')) ?></td>
                <?php for ($i = $start_month; $i <= $end_month; $i++) : ?>
                    <td>
                        <div class="h34"></div>
                    </td>
                <?php endfor ?>
            </tr>
            <!-- ./expenses -->
            <tr role="profit">
                <td>
                    <div class="h34 align-middle"><?php echo __('営業利益') ?></div>
                </td>
                <?php for ($i = $start_month; $i <= $end_month; $i++) : ?>
                    <td>
                        <div class="h34 text-right align-middle" style="font-size:14px;"
                             id="profit-<?php echo $i ?>"></div>
                    </td>
                <?php endfor ?>
            </tr>
            </tbody>
        </table>
        <?php if (!empty($data['revenues'])): ?>
            <?php for ($i = 2; $i <= count($data['revenues']); $i++): ?>
                <div class="remove_row_btn" data-position=".revenue_<?php echo $i ?>"
                     onclick="remove_row('.revenue_<?php echo $i ?>', this, true)">
                    <i class="fa fa-close"></i>
                </div>
            <?php endfor ?>
        <?php endif ?>
        <?php if (!empty($data['expenses'])): ?>
            <?php for ($i = 2; $i <= count($data['expenses']); $i++): ?>
                <div class="remove_row_btn" data-position=".expense_<?php echo $i ?>"
                     onclick="remove_row('.expense_<?php echo $i ?>', this, true)">
                    <i class="fa fa-close"></i>
                </div>
            <?php endfor ?>
        <?php endif ?>
    </div>
</div>
