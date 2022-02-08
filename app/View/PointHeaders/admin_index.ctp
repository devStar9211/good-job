<?php
$this->start('css');
echo $this->Html->css([
    '/assets/css/g_css.css'
]);
$this->end();

$this->start('script');
echo $this->Html->script([
    '/assets/components/responsive-table/responsive-tables.js',
    '/assets/js/g_script/g_validate.js',
    '/assets/js/g_script/g_script.js',
    '/assets/js/g_script/g_back.js',
    '/assets/js/v_script/point.js',
    '/assets/js/format_number.js'

]);
$this->end();
?>
<div class="revenue-budget-container">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $title_for_layout ?></h3>
        </div>
        <div class="box-body">
            <?php
            echo $this->Form->create('Employee', array(
                'url' => array(
                    'controller' => 'Employees',
                    'action' => 'admin_index'
                ),
                'method' => 'post',
                'inputDefaults' => array(
                    'div' => false,
                    'label' => false
                ),
                'autocomplete' => 'off',
                'data-validate' => '1',
                'id' => 'employee-list-form',
                'data-source' => $this->Html->url(array('controller' => 'point_headers', 'action' => 'admin_ajax_list_employee'))
            ));
            ?>
            <?php echo $this->element('flash-message') ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo $this->Form->select('company', $companies, array('empty' => __('会社'), 'default' => (isset($data['company_id']) ? $data['company_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'generateListOffices(this)', 'data-office-node' => '#office_id', 'data-source' => $this->Html->url(array('controller' => 'Offices', 'action' => 'admin_generate_list_offices')), 'role' => 'company_id')) ?>
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
            <?php echo $this->Form->end() ?>
            <div class="table-wrap dataTables_wrapper form-inline dt-bootstrap">
                <div class="loader" style="display:none;"><div class="text-loader">Loading</div></div>
                <div class="mh55" id="budget_revenue_tb" role="tb">
                    <?php echo $this->element('point_details_list_employee_table', array('data' => $data)) ?>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="point_detail_view" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
