<?php
	$this->start('css');
	echo $this->Html->css([
		'/assets/css/g_css.css',
		'/assets/components/bootstrap-datepicker/datepicker.min.css',

	]);
	$this->end();

	$this->start('script');

	echo $this->Html->script([
        '/assets/components/bootstrap-datepicker/bootstrap-datepicker.js',
		'/assets/js/g_script/g_script.js',
		'/assets/js/g_script/g_validate.js',
		'/assets/js/g_script/g_csv_modal.js',
		'/assets/js/g_script/g_back.js',


	]);?>
        <script type="text/javascript">
            $.fn.datepicker.dates['qtrs'] = {
                days: ["Sunday", "Moonday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                daysShort: ["Sun", "Moon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                months: ["年01〜03月", "年04〜06月", "年07〜09月", "年10〜12月", "", "", "", "", "", "", "", ""],
                monthsShort: ["1月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3月", "4月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6月", "7月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9月", "10月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;11月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12月", "", "", "", "", "", "", "", ""],
                today: "Today",
                clear: "Clear",
                format: "mm/dd/yyyy",
                titleFormat: "MM yyyy",
                /* Leverages same syntax as 'format' */
                weekStart: 0
            };
            // page download csv point history quarterly
            $('#dateQuarterDownload').datepicker({
                format: "yyyy MM",
                minViewMode: 1,
                autoclose: true,
                language: "qtrs",
                forceParse: false
            }).on("show", function(event) {

                $(".month").each(function(index, element) {
                    if (index > 3) $(element).hide();
                });

            });
        </script>
    <?php
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
<style>
    .datepicker table tr td span {
        width: 100% !important;
    }
</style>
<div class="quarterly-csv-download">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo $title_for_layout ?></h3>
		</div>
		<div class="box-body">
			<?php
				echo $this->Form->create('data', array(
					'url' => array(
						'controller' => 'point_bonus',
						'action' => 'admin_quarterly_csv_download'
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
					<div class="row">
                        <div class="col-md-5">
                            <div class='input-group date'>
                                <?php echo $this->Form->input('date', array('type' => 'text', 'value' => (isset($data['date']) ? $data['date'] : ''), 'class' => 'form-control', 'id' => 'dateQuarterDownload', 'placeholder' => __('年度'), 'role' => 'date', 'required'=>true)) ?>
                                <label for="dateQuarterDownload" class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </label>
                            </div>
                        </div>
					</div>
					<div class="mt10">
						<?php
							echo $this->Form->button(__('CSVダウンロード'), array('type' => 'submit', 'class' => 'btn btn-primary'));
						?>
					</div>
				</div>
			</div>
			<?php echo $this->Form->end() ?>
		</div>
	</div>
</div>