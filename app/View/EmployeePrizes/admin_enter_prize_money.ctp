<?php
	$this->start('css');
	echo $this->Html->css([
		'/assets/css/g_css.css'
	]);
	$this->end();
?>
<?php $this->start('script') ?>
<?php
	echo $this->Html->script([
		'/assets/js/g_script/g_script.js',
		'/assets/js/g_script/g_validate.js',
		'/assets/js/g_script/g_back.js',
        '/assets/js/price.js'
	]);
?>
	<script type="text/javascript">
		$(document).ready(function(e) {
			<?php if(empty($_POST)): ?>
				generate_employee_prizes($('#prize_id')[0]);
			<?php endif ?>
		});
	</script>
<?php $this->end() ?>
<div class="revenue-budget-container">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo $title_for_layout ?></h3>
		</div>
		<div class="box-body">
			<?php
				echo $this->Form->create('EmployeePrize', array(
					'url' => array(
						'controller' => 'EmployeePrizes',
						'action' => 'admin_enter_prize_money'
					),
					'method' => 'post',
					'inputDefaults' => array(
						'div' => false,
						'label' => false
					),
					'autocomplete' => 'off',
					'data-validate' => '1',
					'data-source' => $this->Html->url(array('controller' => 'EmployeePrizes', 'action' => 'admin_generate_employee_prizes'))
				));
			?>
			<?php echo $this->Form->input('id', array('type' => 'hidden', 'hidden' => 'hidden', 'value' => '')) ?>
			<div class="msg-report"><?php echo $this->element('flash-message') ?></div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?php echo $this->Form->select('company', $companies, array('empty' => __('会社'), 'default' => (isset($data['company_id']) ? $data['company_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'generateListOffices(this);generate_employee_prizes(this);', 'data-office-node' => '#office_id', 'data-source' => $this->Html->url(array('controller' => 'Offices', 'action' => 'admin_generate_list_offices')), 'role' => 'company_id')) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?php echo $this->Form->select('office', $offices, array('empty' => __('事業所'), 'default' => (isset($data['office_id']) ? $data['office_id'] : ''), 'class' => 'form-control select2', 'id' => 'office_id', 'role' => 'office_id', 'onchange' => 'generate_employee_prizes(this)')) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<div class='input-group date'>
							<?php echo $this->Form->input('year-month', array('type' => 'text', 'value' => (!empty($data['year']) && !empty($data['month']) ? $data['year'].'-'.$data['month'] : ''), 'class' => 'form-control', 'placeholder' => __('年 - 月'), 'role' => 'year_month', 'id' => 'monthYearSelect3')) ?>
							<label for="monthYearSelect3" class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?php echo $this->Form->select('prize', $prizes, array('empty' => __('賞金'), 'default' => (isset($data['prize']) ? $data['prize'] : ''), 'class' => 'form-control select2', 'id' => 'prize_id', 'role' => 'prize_id', 'onchange' => 'generate_employee_prizes(this)')) ?>
					</div>
				</div>
			</div>
			<div class="table-wrap mh55">
				<div class="loader"><div class="text-loader">Loading</div></div>
				<div class="row">
					<div class="col-md-6" role="tb-wrap">
						<?php if(!empty($data['employees'])): ?>
							<?php foreach($data['employees'] as $employee): ?>
								<div class="form-group">
									<div class="col-xs-6 no-padding">
										<div class="h34 align-middle">
											<span>(#<?php echo $employee['id'] ?>)&nbsp;</span>
											<?php
												echo $employee['name'];
												echo $this->Form->input('EmployeePrize.employees.'. $employee['id'] .'.name', array('type' => 'hidden', 'hidden' => 'hidden', 'value' => $employee['name']));
											?>
										</div>
									</div>
									<div class="col-xs-6 no-padding">
										<?php
											echo $this->Form->input('EmployeePrize.employees.'. $employee['id'] .'.prize', array('type' => 'text', 'class' => 'form-control text-right price_format', 'onkeydown' => 'numberInput(event)', 'value' => $employee['prize']));
										?>
									</div>
								</div>
							<?php endforeach ?>
						<?php endif ?>
					</div>
				</div>
			</div>
			<div class="clearfix group-btn">
				<?php
					echo $this->Form->button(__('保存'), array('type' => 'submit', 'class' => 'btn btn-primary btn-md'));
					echo $this->Html->link(__('CSVアップロード'), array('controller' => 'EmployeePrizes', 'action' => 'admin_csv_import'), array('class' => 'btn btn-primary'));
				?>
			</div>
			<?php echo $this->Form->end() ?>
		</div>
	</div>
</div>
<div id="input-sample" class="hidden">
	<?php
		echo $this->Form->input('EmployeePrize.employees.id.prize', array('type' => 'text', 'class' => 'form-control text-right price_format', 'onkeydown' => 'numberInput(event)', 'data-role' => 'employee_prize_id_prize'));
		echo $this->Form->input('EmployeePrize.employees.id.name', array('type' => 'hidden', 'hidden' => 'hidden', 'data-role' => 'employee_prize_id_name'));
	?>
</div>

<script type="text/javascript">
    if (typeof format_price_all === 'function') {
        format_price_all();
    }
</script>
