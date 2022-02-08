<?php
	$this->start('css');
	echo $this->Html->css([
		'/assets/css/g_css.css'
	]);
	$this->end();

	$this->start('script');
	echo $this->Html->script([
		'/assets/js/g_script/g_script.js',
		'/assets/js/g_script/g_validate.js',
		'/assets/js/g_script/g_csv_modal.js',
		'/assets/js/g_script/g_back.js'
	]);
	$this->end();

	$this->start('modal');
	echo $this->element('csv_modal_import', array(
		'id' => 'csv_import_employee_prize',
		'controller' => 'EmployeePrizes',
		'action' => 'admin_import_from_csv',
		'multiple' => true,
	));
	$this->end();
?>
<div class="prize-csv-import-container">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo __('賞金入力') ?></h3>
		</div>
		<div class="box-body">
			<?php if (isset($string_erro)): ?>
				<div class="alert alert-error" style="">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<?=$string_erro?>
				</div>
			<?php endif ?>
			<?php echo $this->element('flash-message'); ?>
			<?php
				echo $this->Form->create('EmployeePrize', array(
					'url' => array(
						'controller' => 'EmployeePrizes',
						'action' => 'admin_employee_prizes_csv_import'
					),
					'method' => 'post',
					'inputDefaults' => array(
						'div' => false,
						'label' => false
					),
					'autocomplete' => 'off',
					'data-validate' => '1',
					'id' => 'employee-prizes-csv-form',
					'enctype'=>"multipart/form-data"
				));
			?>
			<div class="msg-report"></div>
			<div class="row mt10">
				<div class="col-md-6">
					<div>
						<?php
							echo $this->Html->link('<i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i>&nbsp;'. __('download sample csv file'), array('controller' => 'EmployeePrizes', 'action' => 'admin_export_sample'), array('escape' => false));
						?>
					</div>
					<div class="mt10">
						<?php
							echo $this->Form->button(__('CSVアップロード'), array('type' => 'button', 'class' => 'btn btn-primary', 'onclick' => 'popup_csv_import(\'#employee-prizes-csv-form\',\'#csv_import_employee_prize\')'));
						?>
					</div>
				</div>
			</div>
			<?php echo $this->Form->end() ?>
		</div>
	</div>
</div>