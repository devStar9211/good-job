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
        $(function () {
            $("#budget_ranking").hpaging({"limit": 20});
        });

    });
</script>
<?php $this->end() ?>
<div class="ranking-monthly ranking-container my-container container">
    <div class="container-header">
        <div class="row">
            <div class="col-xs-12">
                <h4 class="no-margin"><?php echo __('budget ranking') . ' (' . date('m') . '/' . date('d') . __('更新') . ')' ?></h4>
                <hr class="no-margin">
            </div>
            <div class="col-sm-6 mt10">
                <div class='input-group date'>
                    <?php echo $this->Form->input('year-month', array('div' => false, 'label' => false, 'type' => 'text', 'id' => 'monthYearSelect5', 'class' => 'form-control', 'role' => 'ranking-time', 'data-source' => $this->Html->url(array('controller' => 'Frontend', 'action' => 'get_budget_ranking_data')), 'placeholder' => __('年 - 月'), 'value' => (!empty($year) && !empty($month) ? $year . '-' . $month : ''))) ?>
                    <label for="monthYearSelect5" class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="container-body">
        <div class="clearfix">
            <br/>
            <br/>
            <div class="clearfix tb-wrap mh55">
                <h2 class="section-title">
                    <table class="bg_rk_group_value">
                        <tbody>
                        <tr class="no-border ">
                            <td class="col-value text-left"><?php echo __('Office name'); ?></td>

                            <td class="col-value"
                                style="width: 108px;"><?php echo __('operating income achievement rate'); ?></td>
                            <td class="col-value " style="width: 108px;"><?php echo __('previous ranking'); ?></td>
                            <td class="col-value " style="width: 108px;"><?php echo __('excess profit') ?></td>
                        </tr>
                        </tbody>
                    </table>

                </h2>
            </div>
            <div class="clearfix tb-wrap mh55">
                <div class="loader">
                    <div class="text-loader">Loading</div>
                </div>
                <div class="list-ranking mt10" role="data-wrap-monthly">
                    <?php echo $this->element('budget_ranking_data', array('data' => $data)) ?>
                </div>
            </div>

        </div>
    </div>
</div>