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
		'/assets/js/g_script/g_validate.js',
		'/assets/js/g_script/g_script.js',
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


        function generateMonthList(node) {
            var form = $(node).parents('form');
            var company_id = form.find('[role="company_id"]').val();
            var office_id = form.find('[role="office_id"]').val();
            var date= form.find('[role="date"]').val();
            var filter = [];
            var path_filter = '';
            if(company_id) {
                filter.push('company='+ company_id);
                if(office_id) {
                    filter.push('office='+ office_id);
                }
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
                    .replace(/^\?office=[^\&]*\&/ig,'\?').replace(/\&office=[^\&]*\&/ig,'\&').replace(/\&?office=[^\&]*$/ig,'')
                    .replace(/^\?date=[^\&]*\&/ig,'\?').replace(/\&date=[^\&]*\&/ig,'\&').replace(/\&?date=[^\&]*$/ig,'')
            );

            if(search.replace(/^\?/,'').length && path_filter.length) { path_filter = '\&'+ path_filter; }

            window.location.search = search + path_filter;
        }
        // for revenue budget only
        $('#yearSelect').datetimepicker({
            locale: 'ja',
            viewMode: 'years',
            format: 'YYYY',
            minDate: moment('2000-01-01'),
            maxDate: moment('2050-12-31')
        }).on('dp.change', function(e) {
            isValidForm($(this).parents('form[data-validate="1"]'));
            generateMonthList(this);
        });

    </script>
<?php $this->end() ?>
<div class="revenue-budget-container">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo __('予算入力') ?></h3>
		</div>
		<div class="box-body">
			<?php
				echo $this->Form->create('RevenueBudget', array(
					'url' => array(
						'controller' => 'BudgetSales',
						'action' => 'admin_revenue_budget'
					),
					'method' => 'post',
					'inputDefaults' => array(
						'div' => false,
						'label' => false
					),
					'autocomplete' => 'off',
					'data-validate' => '1',
					'id' => 'RevenueBudget',
					'data-source' => $this->Html->url(array('controller' => 'BudgetSales', 'action' => 'admin_revenue_budget_monthly_data'))
				));
			?>
			<?php echo $this->Form->input('id', array('type' => 'hidden', 'hidden' => 'hidden', 'value' => '')) ?>
			<?php echo $this->Form->input('clear', array('type' => 'hidden', 'hidden' => 'hidden', 'value' => (isset($data['clear']) ? $data['clear'] : 0), 'id' => 'clear_old_data')) ?>
			<div class="msg-report"><?php echo $this->element('flash-message') ?></div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?php echo $this->Form->select('company', $companies, array('empty' => __('会社'), 'default' => (isset($data['company_id']) ? $data['company_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'generateListOffices(this);generateMonths(this);', 'data-office-node' => '#office_id', 'data-source' => $this->Html->url(array('controller' => 'Offices', 'action' => 'admin_generate_list_offices')), 'role' => 'company_id')) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?php echo $this->Form->select('office', $offices, array('empty' => __('事業所'), 'default' => (isset($data['office_id']) ? $data['office_id'] : ''), 'class' => 'form-control select2', 'id' => 'office_id', 'role' => 'office_id', 'onchange' => 'generateMonthList(this)')) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<div class='input-group date'>
							<?php echo $this->Form->input('year', array('type' => 'text', 'value' => (isset($data['year']) ? $data['year'] : ''), 'class' => 'form-control', 'id' => 'yearSelect', 'placeholder' => __('年度'), 'role' => 'date')) ?>
							<label for="yearSelect" class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?php echo $this->Html->link(__('CSVアップロード'), array('controller' => 'BudgetSales', 'action' => 'admin_budget_csv_import'), array('class' => 'btn btn-primary')) ?>
					</div>
				</div>
			</div>
			<div class="table-wrap dataTables_wrapper form-inline dt-bootstrap">
				<div class="loader" style="display:block;"><div class="text-loader">Loading</div></div>
				<div class="mh55" id="budget_revenue_tb" role="tb">
					<?php echo $this->element('budget_revenue_table', array('data' => $data)) ?>
				</div>
			</div>
			<div class="clearfix group-btn">
				<?php
					echo $this->Form->button(__('保存'), array('type' => 'submit', 'class' => 'btn btn-primary btn-md'));
					echo $this->Form->button(__('入力キャンセル'), array('type' => 'button', 'class' => 'btn btn-primary btn-md', 'onclick' => 'need_confirm(\'generateMonthList\',\'warning\',\''.__('保存していない項目は削除されます。よろしいですか。').'\',this)'));
				?>
			</div>
			<?php echo $this->Form->end() ?>
		</div>
	</div>
</div>