<?php
	$this->start('css');
	echo $this->Html->css([
		'/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
		'/assets/components/morris/morris.css',
		'/assets/components/morris/prettify.min.css',
		'/assets/css/g_css.css'
	]);
	$this->end();

	$this->start('script');
	echo $this->Html->script([
		'/assets/components/bootstrap-datetimepicker/moment-with-locales.min.js',
        '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
		'/assets/components/morris/raphael-min.js',
		'/assets/components/morris/prettify.min.js',
		'/assets/components/morris/morris.min.js',
		'/assets/js/g_script/g_script.js',
		'/assets/js/g_script/g_front.js',
		'/assets/js/g_script/morris-chart.js'
	]); 
	$this->end();

	$data = isset($data) ? $data : array();
?>
<?php $this->start('script') ?>
	<script type="text/javascript">
		$(document).ready(function(e) {
			var data = [
				{
					y: '<?php echo __('あなた') ?>',
					a: <?php echo !empty($data['personal']['a']) ? $data['personal']['a'] : 0 ?>,
					b: <?php echo !empty($data['personal']['a']) ? $data['personal']['b'] : 0 ?>,
					c: <?php echo !empty($data['personal']['a']) ? $data['personal']['c'] : 0 ?>
				},{
					y: '<?php echo __('平均') ?>',
					a: <?php echo !empty($data['average']['a']) ? $data['average']['a'] : 0 ?>,
					b: <?php echo !empty($data['average']['b']) ? $data['average']['b'] : 0 ?>,
					c: <?php echo !empty($data['average']['c']) ? $data['average']['c'] : 0 ?>
				}
			]
			init_morris("<?php echo $this->Html->url(array('controller' => 'UserPage', 'action' => 'get_data_prize')) ?>", data);
		});
	</script>
<?php $this->end() ?>
<div class="my-container container">
	<div class="container-header">
		<h4 class="no-margin bold">
			<?php echo __('あなたの年間報奨金予定') ?>
			<span class="small">
				<?php echo date('Y') . __('年') . date('m') . __('月') . date('d') . __('日') .' '. __('現在') ?>
			</span>
		</h4>
		<hr class="no-margin">
		<h1 class="no-margin"><?php echo number_format((!empty($data) ? $data['personal']['a'] + $data['personal']['b'] + $data['personal']['c'] : 0),0,'.',',') ?><i class="fa"><?php echo __('pt') ?></i></h1>
		<p>
			<?php echo __('みなの平均') ?>:
			<span><?php echo number_format((!empty($data) ? $data['average']['a'] + $data['average']['b'] + $data['average']['c'] : 0),0,'.',',') ?><i class="fa"><?php echo __('pt') ?></i></span>
		</p>
	</div>
	<div class="container-body">
		<div class="chart-wrap">
			<h4><?php echo __('年間獲得推移') ?></h4>
			<div id="prize-chart">
				<div id="stacked"></div>
			</div>
		</div>
		<div class="row mb10">
			<div class="col-xs-12">
				<?php echo __('報奨金明細') ?>
				<hr class="no-margin">
			</div>
			<div class="col-xs-12">
				<?php echo date('Y/m') .' '. __('予算達成賞') ?>
				<?php echo !empty($data) ? number_format($data['personal']['third_prize'],2,'.',',') : 0 ?>
                <i class="fa">pt</i>
			</div>
			<div class="col-xs-12">
				<?php echo date('Y/m') .' '. __('昨年対比達成賞') ?>
				<?php echo !empty($data) ? number_format($data['personal']['second_prize'],2,'.',',') : 0 ?>
                <i class="fa">pt</i>
			</div>
			<div class="col-xs-12">
				<?php echo date('Y/m') .' '. __('人材紹介報酬') ?>
				<?php echo !empty($data) ? number_format($data['personal']['first_prize'],2,'.',',') : 0 ?>
                <i class="fa">pt</i>
			</div>
		</div>
		<div class="clearfix">
			<i class="fa">&#x203B;</i>
			<?php echo __('予測は現在までの獲得金額から今後獲得出来るであろう金額を予測したものです。確定の金額ではありません。') ?>
		</div>
	</div>
</div>