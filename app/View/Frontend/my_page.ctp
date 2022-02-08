<?php
$this->start('css');
echo $this->Html->css([
    '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
    '/assets/components/morris/morris.css',
    '/assets/components/morris/prettify.min.css',
    '/assets/css/g_css.css',
    '/assets/css/n_style.css',

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
    '/assets/js/g_script/morris-chart.js',
    '/assets/js/format_number.js'

]);
$this->end();
$data = isset($data) ? $data : array();
?>
<?php $this->start('script') ?>
<script type="text/javascript">
    $(document).ready(function (e) {
        //DONUT CHART
        <?php if(isset($earned_points['group']['color']) && isset($earned_points['group']['value'])){ ?>
        var donut = new Morris.Donut({
            element: 'sales-chart',
            resize: true,
            colors: ["<?php echo implode('","', $earned_points['group']['color']) ?>"],
            data: [<?php echo implode(',', $earned_points['group']['value']) ?>],
            hideHover: 'auto'
        });
        <?php } ?>
        // earned point earch quarter
        <?php if(!empty($earned_points_earch_quarter['group']['value'])){ ?>
        var data = [
            {
                y: '',
                <?php echo implode(',', $earned_points_earch_quarter['group']['value']) ?>
            }
        ]
        config = {
            xkey: 'y',
            ykeys: ['<?php echo implode("','", $earned_points_earch_quarter['group']['key']) ?>'],
            labels: ['<?php echo implode("','", $earned_points_earch_quarter['group']['label']) ?>'],
            fillOpacity: 0.6,
            hideHover: 'auto',
            behaveLikeLine: true,
            resize: true,
            stacked: true,
            barColors: ["<?php echo implode('","', $earned_points_earch_quarter['group']['color']) ?>"],
            element: 'prize-chart'
        };
        var chart = Morris.Bar(config);
        chart.setData(data);
        <?php } ?>
    });
</script>
<?php $this->end() ?>
<div class="my-container container">
    <div class="container-header">
        <div class="row">
            <div class="col-md-4">
                <?php
                echo $this->Form->select('quarter', $quarter_choice, array('empty' => __(''), 'default' => (!empty($data['office_id']) ? $data['office_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'selectQuarter(this);', 'role' => 'quarter-ranking-time', 'data-source' => $this->Html->url(array('controller' => 'Frontend', 'action' => 'get_quarter_budget_ranking_data')), 'default' => $year_select . '-Q' . $quarter_select));
                ?>
            </div>
        </div>
        <br>
        <h4 class="list-header has-border bold">
            <?php echo __('あなたの第' . $quarter_select . '四半期ボーナス予想') ?>
            <span class="small">
				<?php echo date('Y') . __('年') . date('m') . __('月') . date('d') . __('日') . ' ' . __('現在') ?>
			</span>
        </h4>
        <h2><?php echo number_format((!empty($earned_points_total) ? $earned_points_total['total_point_with_bonus'] : 0), 0, '.', ',') ?><?php echo __('円') ?></h2>
        </p>
    </div>
    <div class="container-body">
        <div class="row mb10">
            <div class="col-xs-6 ">
                <h4 class="text-center"><?php echo __('Total earned points'); ?></h4>
                <h4 class="text-center">
                    <?php echo !empty($earned_points) ? '<span class="">' . number_format($earned_points['total'], 0, '.', ',') . '</span>' : '0' ?>
                    pt</h4>
                <?php
                if ($earned_points['total'] > 0) {
                    ?>
                    <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
                <?php } ?>
            </div>
            <div class="col-xs-6 ">
                <h4 class="text-center"><?php echo __('Quarterly points earned'); ?></h4>
                <h4 class="text-center"><?php echo number_format((!empty($earned_points_earch_quarter['total']) ? $earned_points_earch_quarter['total'] : 0), 0, '.', ',') ?>
                    <i class="fa">pt</i></h4>
                <?php
                if ($earned_points_earch_quarter['total'] > 0) {
                    ?>
                    <div id="prize-chart">
                        <div id="stacked"></div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="clear current-rank">
                    <div class="block-stage-label">
                        <img style=""
                             src="<?php echo !empty($current_rank['rank_card']) ? '/img/rankcard/' . $current_rank['rank_card'] : ''; ?>"/>
                    </div>
                    <div class="block-stage-text">
                        <p><?php echo __('Current rank'); ?></p>
                        <p><?php echo !empty($current_rank['stage_name']) ? $current_rank['stage_name'] : ''; ?></p>
                    </div>

                </div>
                <div class="point-progress">
                    <p class="point-only">
                        <?php echo !empty($current_rank['necessary_point_next']) ? __('còn %s point nữa thì đến rank tiếp theo', number_format(($current_rank['necessary_point_next']), 0, '.', ',')) : ''; ?>
                    </p>
                    <div class="progress">
                        <?php echo '<div class="progress-bar " role="progressbar" aria-valuenow="' . $current_rank['necessary_point_next_rate'] . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $current_rank['necessary_point_next_rate'] . '%; background-color: ' . $current_rank['color'] . '"></div>'; ?>

                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="contain">
                    <div class=" list-header clear">
                        <div class=" header-left">
                            <b><?php echo __('Earned points') ?></b>
                        </div>
                        <div class=" header-right text-right">
                            <?php echo $this->Html->link(__('もっと見る'), '/all_point'); ?>
                        </div>
                    </div>
                    <div class="row mb10">
                        <?php
                        if (!empty($earned_points['list_point_detail'])) {
                            foreach ($earned_points['list_point_detail'] as $_point_detail) {
                                ?>
                                <div class="col-xs-12">
                                    <?php echo $_point_detail; ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="contain">
                    <div class=" list-header clear">
                        <div class=" header-left">
                            <b><?php echo __('Cumulative Earned Basic Bonus') ?></b>
                        </div>
                        <div class=" header-right text-right">
                            <?php echo $this->Html->link(__('もっと見る'), '/point_bonus'); ?>
                        </div>
                    </div>
                    <div class="row mb10">
                        <?php
                        if (!empty($earned_points['list_point_bonus'])) {
                            foreach ($earned_points['list_point_bonus'] as $_point_detail) {
                                ?>
                                <div class="col-xs-12">
                                    <?php echo $_point_detail; ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>