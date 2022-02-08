<?php
	$this->start('css');
	echo $this->Html->css([
		'/assets/components/responsive-table/responsive-tables.css',
		'/assets/css/g_css.css'
	]);
	$this->end();

	$data = isset($data) ? $data : array();
?>
<?php $this->start('script') ?>
<?php
	echo $this->Html->script([
		'/assets/components/responsive-table/responsive-tables.js',
		'/assets/js/g_script/g_script.js',
		'/assets/js/g_script/g_validate.js',
		'/assets/js/g_script/g_back.js',
        '/assets/js/autoNumeric.js'
	]);
?>
	<script type="text/javascript">
		$(document).ready(function(e) {
			update_remove_btn_position();
			update_budget_profit();
			$('.loader').hide();
		});

        // format number
        $(function(){
            $('input.format_number').autoNumeric('init',{mDec:0});
        });

        function generateOfficesDayList(node) {
            var form = $(node).parents('form');
            var company_id = form.find('[role="company_id"]').val();
            var date= form.find('[role="date"]').val();
            var filter = [];
            var path_filter = '';
            if(company_id) {
                filter.push('company='+ company_id);
            }
            if(date) {
                filter.push('date='+ date);
            }
            filter.forEach( function(element, index) {
                path_filter += (element + (filter.length - 1 == index ? '' : '&'));
            });

            search = window.location.search;
            search = (
                search
                    .replace(/^\?company=[^\&]*\&/ig,'\?').replace(/\&company=[^\&]*\&/ig,'\&').replace(/\&?company=[^\&]*$/ig,'')
                    .replace(/^\?date=[^\&]*\&/ig,'\?').replace(/\&date=[^\&]*\&/ig,'\&').replace(/\&?date=[^\&]*$/ig,'')
            );

            if(search.replace(/^\?/,'').length && path_filter.length) { path_filter = '\&'+ path_filter; }

            window.location.search = search + path_filter;
        }
        // for revenue sales only
        $('#monthYearSelect2').datetimepicker({
            locale: 'ja',
            viewMode: 'months',
            format: 'YYYY-MM',
            minDate: moment('2000-01-01'),
            maxDate: moment('2050-12-31')
        }).on('dp.change', function(e) {
            isValidForm($(this).parents('form[data-validate="1"]'));
            generateOfficesDayList(this);
        });

	</script>
<?php $this->end() ?>
<div class="expense-sales-container">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo __('月次売上費用入力') ?></h3>
		</div>
		<div class="box-body">
			<?php
				echo $this->Form->create('ExpenseSale', array(
					'url' => array(
						'controller' => 'BudgetSales',
						'action' => 'admin_revenue_sales'
					),
					'method' => 'post',
					'inputDefaults' => array(
						'div' => false,
						'label' => false
					),
					'autocomplete' => 'off',
					'data-validate' => '1',
					'data-source' => $this->Html->url(array('controller' => 'BudgetSales', 'action' => 'admin_revenue_sales_monthly_data'))
				));
			?>
			<?php echo $this->Form->input('id', array('type' => 'hidden', 'hidden' => 'hidden', 'value' => '')) ?>
			<div class="msg-report"><?php echo $this->element('flash-message') ?></div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?php echo $this->Form->select('company', $companies, array('empty' => __('会社'), 'default' => (isset($data['company_id']) ? $data['company_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'generateOfficesDayList(this)', 'data-office-node' => '#office_id', 'role' => 'company_id')) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<div class='input-group date'>
							<?php echo $this->Form->input('year-month', array('type' => 'text', 'value' => (!empty($data['year']) && !empty($data['month']) ? $data['year'].'-'.$data['month'] : ''), 'class' => 'form-control', 'id' => 'monthYearSelect2', 'placeholder' => __('年 - 月'), 'role' => 'date')) ?>
							<label for="monthYearSelect2" class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?php echo $this->Html->link(__('CSVアップロード'), array('controller' => 'BudgetSales', 'action' => 'admin_sales_csv_import'), array('class' => 'btn btn-primary')) ?>
					</div>
				</div>
			</div>
			<div class="table-wrap dataTables_wrapper form-inline dt-bootstrap">
				<div class="loader" style="display:block;"><div class="text-loader">Loading</div></div>
				<div class="mh55" id="sales_expense_tb">
					<?php echo $this->element('sales_revenue_table', array('data' => $data)) ?>
				</div>
			</div>
			<div class="clearfix group-btn">
				<?php
					echo $this->Form->button(__('保存'), array('type' => 'submit', 'class' => 'btn btn-primary btn-md'));
					echo $this->Form->button(__('入力キャンセル'), array('type' => 'button', 'class' => 'btn btn-primary btn-md', 'onclick' => 'need_confirm(\'generateOfficesDayList\',\'warning\',\''.__('保存していない項目は削除されます。よろしいですか。').'\',this)'));
				?>
			</div>
			<?php echo $this->Form->end() ?>
		</div>
	</div>
</div>