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
		'id' => 'csv_sales_import',
		'controller' => 'BudgetSales',
		'action' => 'admin_import_sales_from_csv',
		'multiple' => true,
	));
	$this->end();
?>
<div class="budget-csv-import-container">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo __('月次売上費用入力') ?></h3>
		</div>
		<div class="box-body">
			<?php
				echo $this->Form->create('ImportBudget', array(
					'url' => array(
						'controller' => 'BudgetSales',
						'action' => 'admin_sales_csv_import'
					),
					'method' => 'post',
					'inputDefaults' => array(
						'div' => false,
						'label' => false
					),
					'autocomplete' => 'off',
					'data-validate' => '1',
					'id' => 'ImportBudget'
				));
			?>
			<?php echo $this->element('flash-message') ?>
			<div class="msg-report"></div>
			<div class="row mt10">
				<div class="col-md-6">
					<div>
						<?php
							echo $this->Html->link('<i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i>&nbsp;'. __('download sample csv file'), array('controller' => 'BudgetSales', 'action' => 'admin_export_sample', 'sales'), array('escape' => false));
						?>
					</div>
					<div class="mt10">
						<?php
							echo $this->Form->button(__('CSVアップロード'), array('type' => 'button', 'class' => 'btn btn-primary', 'onclick' => 'need_confirm(\'popup_csv_import\',\'warning\',\''.__('dữ liệu hiện tại sẽ có thể bị ghi đè、よろしいですか。').'\',\'#ImportBudget\',\'#csv_sales_import\')'));
						?>
					</div>
				</div>
			</div>
			<?php echo $this->Form->end() ?>
		</div>
	</div>
</div>