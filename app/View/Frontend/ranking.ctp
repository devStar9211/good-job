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
    '/assets/js/jquery-ui.js',
    '/assets/components/bootstrap-datetimepicker/moment-with-locales.min.js',
    '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
    '/assets/js/g_script/g_script.js',
    '/assets/js/g_script/g_front.js',
    '/assets/js/format_number.js',
    '/assets/js/jquery.table.hpaging.js'
]);
?>
<script type="text/javascript">
    $(document).ready(function (e) {
//        $(function () {
//            $("#budget_ranking").hpaging({"limit": 20});
//        });
    });
</script>
<?php $this->end() ?>
<div class="ranking-container my-container container pd-5">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1" data-toggle="tab">
                    予算ランキング
                </a>
            </li>
            <li>
                <a href="#tab_2" data-toggle="tab">
                    四半期ランキング
                </a>
            </li>
        </ul>
        <div class=" tab-content">
            <div class="ranking-monthly container-fluid tab-pane active" id="tab_1">
                <?php echo __('budget ranking') . ' (' . date('m') . '/' . date('d') . __('更新') . ')' ?>
                <br>
                <div class="row">
                    <div class='col-md-6 input-group date query-date'>
                        <?php echo $this->Form->input('year-month', array('div' => false, 'label' => false, 'type' => 'text', 'id' => 'monthYearSelect5', 'class' => 'form-control', 'role' => 'ranking-time', 'data-source' => $this->Html->url(array('controller' => 'Frontend', 'action' => 'get_budget_ranking_data')), 'placeholder' => __('年 - 月'), 'value' => (!empty($year) && !empty($month) ? $year . '-' . $month : ''))) ?>
                        <label for="monthYearSelect5" class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </label>
                    </div>
                </div>
                <br>
                <div class="row tb-wrap mh55">
                    <div class='col-md-12 no-padding-mobile'>
                        <div class="loader">
                            <div class="text-loader">Loading</div>
                        </div>
                        <div class="list-ranking mt10" role="data-wrap-monthly">
                            <?php echo $this->element('budget_ranking_data', array('data' => $data)) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ranking-quarter container-fluid tab-pane " id="tab_2">
                <?php echo __('quarterly ranking') . '（' . $year . '年' . $month . '月更新）'; ?>
                <br/>
                <div class="row">
                    <div class='col-md-6 input-group date query-date'>
                        <?php
                        echo $this->Form->select('quarter', $quarter_choice, array('empty' => __(''), 'default' => (!empty($data['office_id']) ? $data['office_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'generateRankingQuarter(1, true);', 'role' => 'quarter-ranking-time', 'data-source' => $this->Html->url(array('controller' => 'Frontend', 'action' => 'get_quarter_budget_ranking_data')), 'default' => $date_quarter_selected['year_quarter'] . '-Q' . $date_quarter_selected['quarter']));
                        ?>
                    </div>
                </div>
                <br>
                <div class="row tb-wrap mh55">
                    <div class='col-md-12 no-padding-mobile'>
                        <div class="loader">
                            <div class="text-loader">Loading</div>
                        </div>
                        <div class="list-ranking mt10" role="data-wrap-quarter">
                            <?php echo $this->element('budget_ranking_quarter_data', array('data' => $data)) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>