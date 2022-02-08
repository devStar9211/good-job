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
<div class="ranking-quarter ranking-container my-container container">
    <div class="container-header">
        <div class="row">
            <div class="col-xs-12">
                <h4 class="no-margin"><?php echo __('quarterly ranking').'（'.$month.'月'.$year.'日更新）'; ?></h4>
                <hr class="no-margin">
            </div>
            <div class="col-sm-6 mt10">
                <?php
                echo $this->Form->create('Frontend', array(
                    'url' => array(
                        'controller' => 'Frontends',
                        'action' => 'quarter_budget_ranking'
                    ),
                    'method' => 'post',
                    'inputDefaults' => array(
                        'div' => false,
                        'label' => false
                    ),
                    'autocomplete' => 'off',
                    'data-validate' => '1',
                    'id' => 'quarterly-ranking',
                    'data-source' => $this->Html->url(array('controller' => 'frontend', 'action' => 'quarter_budget_ranking'))
                ));
                ?>
                <?php
                echo $this->Form->select('quarter', $quarter_choice, array('empty' => __(''), 'default' => (!empty($data['office_id']) ? $data['office_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'generateRankingQuarter(1, true);', 'role'=>'quarter-ranking-time', 'data-source' => $this->Html->url(array('controller' => 'Frontend', 'action' => 'get_quarter_budget_ranking_data')), 'default'=> $date_quarter_selected['year_quarter'].'-Q'.$date_quarter_selected['quarter']));

                ?>
                <?php echo $this->Form->end() ?>
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
                                style="width: 108px;"><?php echo __('quarter cumulative achievement rate'); ?></td>
                            <td class="col-value " style="width: 108px;"><?php echo __('quarterly cumulative excess profit') ?></td>
                        </tr>
                        </tbody>
                    </table>

                </h2>
            </div>
            <div class="clearfix tb-wrap mh55">
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