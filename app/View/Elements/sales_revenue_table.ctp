<div class="clearfix">
	<div class="table-responsive" role="tb-wrap">
		<table id="tb-sales" class="table table-bordered table-striped dataTable responsive" data-fixed="150px">
			<thead>
				<th>&#65279;</th>
				<?php if(!empty($data['offices'])): ?>
					<?php foreach($data['offices'] as $id => $name): ?>
						<th class="text-center">
							<?php echo $name; ?>
							<?php echo $this->Form->input('ExpenseSale.offices.'. $id, array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => $name)); ?>
						</th>
					<?php endforeach ?>
				<?php else: ?>
					<th>&#65279;</th>
				<?php endif ?>
			</thead>
			<tbody id="expense-sales">

				<!-- revenues honobono-->
                <?php $revenues_key = 0 ?>
                <?php if(!empty($data['revenues_honobono'])): ?>
                    <?php foreach($data['revenues_honobono'] as $name => $value): ?>
                        <?php $revenues_key++ ?>
                        <tr <?php if($revenues_key > 1) { echo 'class="revenue_'. $revenues_key .'"'; } ?>>
                            <td>
                                <?php
                                echo $this->Form->input('ExpenseSale.e_revenue.'. $revenues_key .'.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid ', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('売上'), 'value' => $value['name'], 'disabled'=>true));
                                ?>
                            </td>
                            </td>
                            <?php foreach($data['offices'] as $id => $name): ?>
                                <td>
                                    <?php
                                    echo $this->Form->input('ExpenseSale.e_revenue.'. $revenues_key .'.'. $id .'.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => (isset($value[$id]) ? $value[$id]['value'] : ''),'disabled'=>true));

                                    ?>
                                </td>
                            <?php endforeach ?>
                        </tr>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>

				<!-- revenues -->
				<?php $revenues_key = 0 ?>
				<?php if(!empty($data['revenues'])): ?>
					<?php foreach($data['revenues'] as $name => $value): ?>
						<?php $revenues_key++ ?>
						<tr <?php if($revenues_key > 1) { echo 'class="revenue_'. $revenues_key .'"'; } ?>>
							<td>
								<?php
									echo $this->Form->input('ExpenseSale.e_revenue.'. $revenues_key .'.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid ', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('売上'), 'value' => $value['name']));
								?>
							</td>
							</td>
								<?php foreach($data['offices'] as $id => $name): ?>
									<td>
										<?php
											echo $this->Form->input('ExpenseSale.e_revenue.'. $revenues_key .'.'. $id .'.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => (isset($value[$id]) ? $value[$id]['value'] : ''), 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'role' => 'revenue-input', 'data-month' => $id));
											echo $this->Form->input('ExpenseSale.e_revenue.'. $revenues_key .'.'. $id .'.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => (isset($value[$id]) ? $value[$id]['id'] : '')));
										?>
									</td>
								<?php endforeach ?>
							</tr>
						</tr>
					<?php endforeach ?>
				<?php else: ?>
					<?php $revenues_key = 1 ?>
					<tr>
						<td>
							<?php
								echo $this->Form->input('ExpenseSale.e_revenue.'. $revenues_key .'.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid ', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('売上'), 'value' => __('売上')));
								echo $this->Form->input('ExpenseSale.e_revenue.'. $revenues_key .'.name', array('div' => false, 'label' => false, 'type' => 'hidden', 'value' => __('売上'), 'readonly' => 'readonly'));
							?>
						</td>
						<?php foreach($data['offices'] as $id => $name): ?>
							<td>
								<?php
									echo $this->Form->input('ExpenseSale.e_revenue.'. $revenues_key .'.'. $id .'.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'role' => 'revenue-input', 'data-month' => $id));
									echo $this->Form->input('ExpenseSale.e_revenue.'. $revenues_key .'.'. $id .'.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => ''));
								?>
							</td>
						<?php endforeach ?>
					</tr>
				<?php endif ?>
				<tr role="revenue-row" class="hidden">
					<td>
						<?php
							echo $this->Form->input('ExpenseSale.e_revenue.key.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid ', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('売上')));
							echo $this->Form->input('ExpenseSale.e_revenue.key.', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'role' => 'revenue-v-key', 'data-key' => $revenues_key));
						?>
					</td>
					<?php foreach($data['offices'] as $id => $name): ?>
						<td>
							<?php
								echo $this->Form->input('ExpenseSale.e_revenue.key.'. $id .'.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'maxlength' => '18', 'onfocus' => 'update_budget_profit()', 'onkeyup' => 'update_budget_profit()', 'role' => 'revenue-input', 'data-month' => $id));
								echo $this->Form->input('ExpenseSale.e_revenue.key.'. $id .'.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => ''));
							?>
						</td>
					<?php endforeach ?>
				</tr>
				<tr role="revenue-add">
					<td><?php echo $this->Form->button('<i class="fa fa-plus primary"></i>', array('type' => 'button', 'class' => 'btn btn-default table-input format_number', 'onclick' => 'add_another_row(\'#expense-sales\', this, \'revenue\')')) ?></td>
					<?php foreach($data['offices'] as $id => $name): ?>
						<td><div class="h34"></div></td>
					<?php endforeach ?>
				</tr>
				<?php if(!empty($data['revenues'])): ?>
					<?php for($i = 2; $i <= count($data['revenues']); $i++): ?>
						<div class="remove_row_btn" data-position=".revenue_<?php echo $i ?>" onclick="remove_row('.revenue_<?php echo $i ?>', this, true)">
							<i class="fa fa-close"></i>
						</div>
					<?php endfor ?>
				<?php endif ?>
				<!-- ./revenues -->
				<!-- labor cost -->
				<tr>
					<td><div class="h34 align-middle"><?php echo __('人件費') ?></div></td>
					<?php if(!empty($data['offices'])): ?>
						<?php foreach($data['offices'] as $id => $name): ?>
							<td>
								<?php
									echo $this->Form->input('ExpenseSale.e_labor_cost.'. $id, array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => (isset($data['labor_cost'][$id]) ? $data['labor_cost'][$id] : ''), 'maxlength' => '18'));
								?>
							</td>
						<?php endforeach ?>
					<?php else: ?>
						<td><div class="h34">&#65279;</div></td>
					<?php endif ?>
				</tr>
				<!-- ./labor cost -->
				<!-- overtime cost -->
				<tr>
					<td><div class="h34 align-middle"><?php echo __('残業費') ?></div></td>
					<?php if(!empty($data['offices'])): ?>
						<?php foreach($data['offices'] as $id => $name): ?>
							<td>
								<?php
									echo $this->Form->input('ExpenseSale.e_overtime_cost.'. $id, array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => (isset($data['overtime_cost'][$id]) ? $data['overtime_cost'][$id] : ''), 'maxlength' => '18'));
								?>
							</td>
						<?php endforeach ?>
					<?php else: ?>
						<td><div class="h34">&#65279;</div></td>
					<?php endif ?>
				</tr>
				<!-- ./overtime cost -->
				<!-- expenses -->
				<?php $key = 0; ?>
				<?php if(!empty($data['expenses'])): ?>
					<?php foreach($data['expenses'] as $expense): ?>
						<?php $key++; ?>
						<tr <?php if($key > 1) { echo 'class="expense_'. $key .'"'; } ?>>
							<td>
								<?php
									echo $this->Form->input('ExpenseSale.e_expense.'. $key .'.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid ', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('経費'), 'value' => $expense['name']));
								?>
							</td>
							<?php foreach($data['offices'] as $id => $name): ?>
								<td>
									<?php
										echo $this->Form->input('ExpenseSale.e_expense.'. $key .'.'. $id .'.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'value' => (isset($expense[$id]) ? $expense[$id]['value'] : ''), 'maxlength' => '18'));
									echo $this->Form->input('ExpenseSale.e_expense.'. $key .'.'. $id .'.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'value' => (isset($expense[$id]) ? $expense[$id]['id'] : '')));
									?>
								</td>
							<?php endforeach ?>
						</tr>
					<?php endforeach ?>
				<?php else: ?>
					<?php $key = 1; ?>
					<tr>
						<td>
							<?php
								echo $this->Form->input('ExpenseSale.e_expense.'. $key .'.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid ', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('経費'), 'value' => __('経費')));
							?>
						</td>
						<?php if(!empty($data['offices'])): ?>
							<?php foreach($data['offices'] as $id => $name): ?>
								<td>
									<?php
										echo $this->Form->input('ExpenseSale.e_expense.'. $key .'.'. $id .'.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'maxlength' => '18'));
										echo $this->Form->input('ExpenseSale.e_expense.'. $key .'.'. $id .'.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden'));
									?>
								</td>
							<?php endforeach ?>
						<?php else: ?>
							<td><div class="h34">&#65279;</div></td>
						<?php endif ?>
					</tr>
				<?php endif ?>
				<tr role="expense-row" class="hidden">
					<td>
						<?php
							echo $this->Form->input('ExpenseSale.e_expense.key.name', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control table-input input-fluid ', 'role' => 'v_name', 'onchange' => 'pass_v_e_val(this)', 'data-base-name' => __('経費')));
							echo $this->Form->input('ExpenseSale.e_expense.key.', array('div' => false, 'label' => false, 'type' => 'hidden', 'hidden' => 'hidden', 'role' => 'expense-v-key', 'data-key' => $key));
						?>
					</td>
					<?php if(!empty($data['offices'])): ?>
						<?php foreach($data['offices'] as $id => $name): ?>
							<td>
								<?php
									echo $this->Form->input('ExpenseSale.e_expense.key.'. $id .'.value', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control text-right table-input format_number', 'maxlength' => '18', 'role' => 'expense-input'));
									echo $this->Form->input('ExpenseSale.e_expense.key.'. $id .'.id', array('div' => false, 'label' => false, 'type' => 'hidden', 'value' => '', 'hidden' => 'hidden'));
								?>
							</td>
						<?php endforeach ?>
					<?php else: ?>
						<td><div class="h34">&#65279;</div></td>
					<?php endif ?>
				</tr>
				<tr role="expense-add">
					<td><?php echo $this->Form->button('<i class="fa fa-plus primary"></i>', array('type' => 'button', 'class' => 'btn btn-default table-input format_number', 'onclick' => 'add_another_row(\'#expense-sales\', this, \'expense\')')) ?></td>
					<?php if(!empty($data['offices'])): ?>
						<?php foreach($data['offices'] as $id => $name): ?>
							<td><div class="h34">&#65279;</div></td>
						<?php endforeach ?>
					<?php else: ?>
						<td><div class="h34">&#65279;</div></td>
					<?php endif ?>
				</tr>
				<?php if(!empty($data['expenses'])): ?>
					<?php for($i = 2; $i <= count($data['expenses']); $i++): ?>
						<div class="remove_row_btn" data-position=".expense_<?php echo $i ?>" onclick="remove_row('.expense_<?php echo $i ?>', this, true)">
							<i class="fa fa-close"></i>
						</div>
					<?php endfor ?>
				<?php endif ?>
				<!-- ./expenses -->
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
    // format number
    $(function(){
        $('input.format_number').number( true, 0 );
    });

</script>