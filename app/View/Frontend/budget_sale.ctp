<?php
	$this->start('css');
	echo $this->Html->css([
        '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
		'/assets/css/g_css.css',
		'/assets/css/v_css.css'
	]);
	$this->end();

	$data = isset($data) ? $data : array();
?>
<?php $this->start('script') ?>
<?php
	echo $this->Html->script([
        '/assets/components/bootstrap-datetimepicker/moment-with-locales.min.js',
        '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
		'/assets/js/g_script/g_script.js',
		'/assets/js/v_script/v_front.js',
	]);
?>
	<script type="text/javascript">
		$(document).ready(function(e) {
			var paging = <?php echo json_encode($paging) ?>;

			paginate(paging, '#budget-sale-paginator', 'generateLastYearComprison');
		});
	</script>
<?php $this->end() ?>

<div class="ranking-container container">
	<div class="container-header container-fluid">
		<div class="row">
			<div class="col-sm-6 no-padding">
                <h4><?php echo __('Last year comparison') .' ('. date('m') .'/'. date('d')  . __('更新') .')' ?></h4>
				<div class='input-group date'>
					<?php echo $this->Form->input('year-month', array('div' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control', 'role' => 'ranking-time', 'data-source' => $this->Html->url(array('controller' => 'Frontend', 'action' => 'get_last_year_comparison_data')), 'id' => 'lastYearComprison', 'placeholder' => __('年 - 月'), 'value' => (!empty($year) && !empty($month) ? $year .'-'. $month : ''))) ?>
					<label for="lastYearComprison" class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class="container-body">
		<div class="clearfix">
			<div class="clearfix text-right mt10">
				<span role="page-count"><?php echo $paging['count'] ?></span><?php echo __('件中') ?><span role="page-start"><?php echo $paging['start'] ?></span>-<span role="page-end"><?php echo $paging['end'] ?></span><?php echo __('件表示') .'('. __('ページ') ?><span role="page-page"><?php echo $paging['page'] ?></span>/<span role="page-pages"><?php echo $paging['pages'] ?></span>)
			</div>
			<div class="clearfix tb-wrap mh55">
				<div class="loader"><div class="text-loader">Loading</div></div>
				<div class="list-ranking mt10" role="data-wrap">
					<?php echo $this->element('last_year_comparison', array('data' => $data)) ?>
				</div>
			</div>
			<div class="row mt10">
				<div class="col-sm-8">
					<ul class="col-sm-8 pagination no-margin" id="budget-sale-paginator">
						<!-- js paginator -->
					</ul>
				</div>
				<div class="col-sm-4 text-right hidden-xs">
					<span role="page-count"><?php echo $paging['count'] ?></span><?php echo __('件中') ?><span role="page-start"><?php echo $paging['start'] ?></span>-<span role="page-end"><?php echo $paging['end'] ?></span><?php echo __('件表示') .'('. __('ページ') ?><span role="page-page"><?php echo $paging['page'] ?></span>/<span role="page-pages"><?php echo $paging['pages'] ?></span>)
				</div>
			</div>
		</div>
	</div>
</div>