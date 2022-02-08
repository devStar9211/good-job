<?php $this->start('css') ?>
<?php
echo $this->Html->css('/assets/css/l_css');
echo $this->Html->css([
    '/assets/components/responsive-table/responsive-tables.css',
    '/assets/css/g_css.css'
]);
?>
<?php $this->end() ?>
<?php $this->start('script') ?>
<?php
echo $this->Html->script([
    '/assets/components/responsive-table/responsive-tables.js',
    '/assets/js/g_script/g_script.js',
    '/assets/js/g_script/g_validate.js',
    '/assets/js/g_script/g_back.js',
    '/assets/js/jquery.number.js'
]);
?>
<script type="text/javascript">


    function select_company_group(node) {

        var company_group_id = $(node).val();

        var filter = [];
        var path_filter = '';
        if (company_group_id) {
            filter.push('company_group=' + company_group_id);
        }

        filter.forEach(function (element, index) {
            path_filter += (element + (filter.length - 1 == index ? '' : '&'));
        });

        search = window.location.search;
        search = (
            search
                .replace(/^\?company_group=[^\&]*\&/ig, '\?').replace(/\&company_group=[^\&]*\&/ig, '\&').replace(/\&?company_group=[^\&]*$/ig, '')

        );

        if (search.replace(/^\?/, '').length && path_filter.length) {
            path_filter = '\&' + path_filter;
        }

        window.location.search = search + path_filter;
    }

    //
    //    $('.modalOccupation').on('hide.bs.modal', function (e) {
    //        console.log('xxxx');
    //    });


</script>
<?php $this->end() ?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $title_for_layout ?></h3>
        <div class="box-tools" style="width: 200px;">
            <div id="btn-box" class="row">
                <?php
                echo $this->Form->input("company_group_id", array('name' => 'company_group_id', 'div' => 'pull-right col-lg-12 col-md-12', "type" => "select", 'class' => 'form-control select2', "role" => "company_group_id", "options" => $companyGroups, 'required' => false, 'label' => false, 'default' => $data['company_group'], 'onchange' => 'select_company_group(this);',));
                ?>

            </div>
        </div>
    </div>

    <div class="box-body" id="table-list">
        <?php
        echo $this->Form->create('ConditionPoints', array(
            'url' => array(
                'controller' => 'condition_points',
                'action' => 'index'
            ),
            'method' => 'post',
            'inputDefaults' => array(
                'div' => false,
                'label' => false
            ),
            'autocomplete' => 'off',
            'data-source' => $this->Html->url(array('controller' => 'condition_points', 'action' => 'admin_index'))
        ));
        echo $this->Form->hidden('company_group_id', array('name' => 'company_group_id', 'value' => $data['company_group']));
        ?>
        <?php echo $this->element('flash-message'); ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable table-hover ">
                <thead>
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;"><a href="#">階級</a></th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">ステージ名称</th>
                    <th colspan="2" style="text-align: center; vertical-align: middle;border-bottom-width: 1px;">
                        必要ポイント
                    </th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">付与率</th>
                </tr>
                <tr>
                    <th style="text-align: center; vertical-align: middle;">必要条件</th>
                    <th style="text-align: center; vertical-align: middle;">役職</th>
                </tr>
                </thead>
                <?php foreach ($data['ranks'] as $key => $value): ?>
                    <tr>
                        <td><?php echo $value['PointRank']['rank_name']; ?>&nbsp;</td>
                        <td class="text-center" style="width: 250px">
                            <?php
                            echo $value['Stage']['name']
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($value['PointRank']['use_necessary_point'] == 1) {
                                echo $this->Form->input('necessary_point.' . $value['PointRank']['id'], array('div' => false, 'label' => false, 'type' => 'text', 'class' => ' text-right table-input', 'value' => !empty($value['PointRank']['necessary_point']) ? $value['PointRank']['necessary_point'] : '', 'style' => 'width: 100px !important; min-width: initial;')) . ' ポイント以上';
                            } else {
                                echo $this->Form->input('necessary_point.' . $value['PointRank']['id'], array('div' => false, 'label' => false, 'type' => 'text', 'class' => ' text-right table-input hide', 'value' => !empty($value['PointRank']['necessary_point']) ? $value['PointRank']['necessary_point'] : '', 'style' => 'width: 100px !important; min-width: initial;'));
                            }
                            ?>
                        </td>
                        <td style="width: 350px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    $positions = array();
                                    if (!empty($value['Occupation'])) {
                                        foreach ($value['Occupation'] as $_occupation) {
                                            $positions[$_occupation['id']] = $_occupation['id'];
                                        }
                                    }
                                    ?>
                                    <?php
                                    echo $this->Form->input('occupation.' . $value['PointRank']['id'], array('div' => '', "type" => "select", 'multiple' => 'multiple', 'class' => 'form-control select2', "options" => $listOccupations, 'label' => __('occupation'), 'default' => $positions));
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    echo $this->Form->input('working_time.' . $value['PointRank']['id'], array('div' => '', "type" => "select", 'class' => 'form-control select2', "options" => $working_times, 'label' => __('working time'), 'default' => $value['PointRank']['working_time_id'] != '' ? $value['PointRank']['working_time_id'] : '', 'empty' => ''));
                                    ?>
                                </div>
                            </div>
                        </td>
                        <td class="text-center" style="width: 250px">
                            <?php
                            echo $this->Form->input('subsidize_rate.' . $value['PointRank']['id'], array('div' => false, 'label' => false, 'type' => 'text', 'class' => ' text-right table-input', 'value' => $value['PointRank']['subsidize_rate'], 'maxlength' => '10', 'style' => 'width: 50px !important; min-width: initial;'));
                            ?>&nbsp;%
                        </td>
                    </tr>
                <?php endforeach;
                ?>
            </table>
        </div>
        <div class="clearfix group-btn">
            <?php
            echo $this->Form->button(__('保存'), array('type' => 'submit', 'class' => 'btn btn-primary btn-md'));
            echo $this->Form->button(__('入力キャンセル'), array('type' => 'button', 'class' => 'btn btn-primary btn-md', 'onclick' => 'need_confirm(\'points_filter_employee\',\'warning\',\'' . __('保存していない項目は削除されます。よろしいですか。') . '\',this)'));
            ?>
        </div>
        <?php echo $this->Form->end() ?>
    </div>
</div>

