<?php
	$this->start('css');
	echo $this->Html->css([
		'/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
		'/assets/css/g_css.css'
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
		'/assets/js/g_script/g_front.js'
	]);
?>
	<script type="text/javascript">
		$(document).ready(function(e) {
			var paging = <?php echo json_encode($paging) ?>;

			paginate(paging, '#budget-rank-paginator', 'generateRanking');
		});
	</script>
<?php $this->end() ?>
<div class="prize-ranking-container my-container container">
	<div class="container-header">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="no-margin"><?php echo __('budget ranking') .' ('. date('m') .'/'. date('d')  . __('更新') .')' ?></h4>
				<hr class="no-margin">
			</div>
			<div class="col-sm-6 mt10">
				<div class='input-group date'>
					<?php echo $this->Form->input('year-month', array('div' => false, 'label' => false, 'type' => 'text', 'id' => 'monthYearSelect6', 'class' => 'form-control', 'role' => 'ranking-time', 'data-source' => $this->Html->url(array('controller' => 'PrizeRanking', 'action' => 'get_prize_ranking_data')), 'placeholder' => __('年 - 月'), 'value' => (!empty($year) && !empty($month) ? $year .'-'. $month : ''))) ?>
					<label for="monthYearSelect6" class="input-group-addon">
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
					<?php echo $this->element('prize_ranking_data', array('data' => $data)) ?>
				</div>
			</div>
			<div class="row mt10">
				<div class="col-sm-8">
					<ul class="col-sm-8 pagination no-margin" id="budget-rank-paginator">
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