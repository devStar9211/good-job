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
		'id' => 'csv_import_employee',
		'controller' => 'point_details',
		'action' => 'admin_import_point_from_csv',
		'multiple' => true
	));
	$this->end();
?>
<div class="budget-csv-import-container">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo __('CSVアップロード') ?></h3>
		</div>
		<div class="box-body">
			<?php
				echo $this->Form->create('ImportSales', array(
					'url' => array(
						'controller' => 'Employee',
						'action' => 'admin_csv_import'
					),
					'method' => 'post',
					'inputDefaults' => array(
						'div' => false,
						'label' => false
					),
					'autocomplete' => 'off',
					'data-validate' => '1',
					'id' => 'employee-csv-form'
				));
			?>
			<?php echo $this->element('flash-message') ?>
			<div class="msg-report"></div>
			<div class="row row-5">
				<div class="col-sm-6 group-btn mt10">
					<div>
						<?php
							echo $this->Html->link('<i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i>&nbsp;'. __('Click here for CSV format for upload'), array('controller' => 'PointDetails', 'action' => 'admin_export_sample', 'point_details'), array('escape' => false));
						?>
					</div>
					<div class="mt10">
						<?php
							echo $this->Form->button(__('Upload'), array('type' => 'button', 'class' => 'btn btn-primary', 'onclick' => 'popup_csv_import(\'#employee-csv-form\',\'#csv_import_employee\')'));
						?>
					</div>
				</div>

			</div>
			<?php echo $this->Form->end() ?>
		</div>
	</div>
</div>