<?php
$this->start('css');
echo $this->Html->css([
    '/assets/css/g_css.css'
]);
echo $this->Html->css([
    '/assets/components/responsive-table/responsive-tables.css',
    '/assets/css/g_css.css'
]);
$this->end();

$this->start('script');
echo $this->Html->script([
    '/assets/components/responsive-table/responsive-tables.js',
    '/assets/js/g_script/g_validate.js',
    '/assets/js/g_script/g_script.js',
    '/assets/js/g_script/g_back.js',
    '/assets/js/point.js',
    '/assets/js/autoNumeric.js',
	'/assets/js/format_number.js'
]);

?>
<script type="text/javascript">
    // format number
    $(function(){
        $('input.format_number').autoNumeric('init',{mDec:0});
    });
	$(document).ready(function($) {

    // for manual input point
    $('#date').datetimepicker({
        locale: 'ja',
        viewMode: 'years',
        format: 'YYYY',
        minDate: moment('2000-01-01'),
        maxDate: moment('2050-12-31')
    }).on('dp.change', function(e) {
        isValidForm($(this).parents('form[data-validate="1"]'));
        points_filter_employee(this)
    });

    

});
</script>
<?php
$this->end();
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $title_for_layout ?></h3>
        <div class="pull-right box-tools">
            <div class="btn-group">
                <?php
                echo $this->Html->link(__('CSVアップロード'), array('controller' => 'point_bonus', 'action' => 'admin_csv_import'), array('class' => 'btn btn-primary'));
                echo $this->Html->link(__('CSVダウンロード'), array('controller' => 'point_bonus', 'action' => 'export'), array('class' => 'btn btn-primary'));
                ?>
            </div>
        </div>
    </div>
    <div class="box-body">
        <?php
        echo $this->Form->create('PointBonus', array(
            'url' => array(
                'controller' => 'point_bonus',
                'action' => 'input'
            ),
            'method' => 'post',
            'inputDefaults' => array(
                'div' => false,
                'label' => false
            ),
            'autocomplete' => 'off',
            
        ));
        ?>
        <?php echo $this->element('flash-message') ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo $this->Form->select('company', $companies, array('empty' => __('会社'), 'default' => (isset($data['company_id']) ? $data['company_id'] : ''), 'class' => 'form-control select2','id' => 'company_id', 'onchange' => 'generateListOffices(this);', 'data-office-node' => '#office_id', 'data-source' => $this->Html->url(array('controller' => 'Offices', 'action' => 'admin_generate_list_offices')), 'role' => 'company_id')) ?>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo $this->Form->select('office', $offices, array('empty' => __('事業所'), 'default' => (isset($data['office_id']) ? $data['office_id'] : ''), 'class' => 'form-control select2', 'id' => 'office_id', 'role' => 'office_id', 'onchange' => 'points_filter_employee(this)')) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class='input-group date'>
                        <?php echo $this->Form->input('date', array('type' => 'text', 'value' => (isset($data['date']) ? $data['date'] : ''), 'class' => 'form-control', 'id' => 'date', 'placeholder' => __('年度'), 'role' => 'date', 'onchange' => 'points_filter_employee(this)')) ?>
                        <label for="date" class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>


        <div class="table-wrap dataTables_wrapper form-inline dt-bootstrap">
            <div class="loader" style="display:none;"><div class="text-loader">Loading</div></div>
            <div class="mh55" id="budget_revenue_tb" role="tb">
                <?php echo $this->element('point_bonus_table', array('data' => $data)) ?>
            </div>
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
