<?php
$this->start('css');
echo $this->Html->css([
    '/assets/css/g_css.css',

]);
$this->end();

$this->start('script');
echo $this->Html->script([
    '/assets/js/g_script/g_script.js',
    '/assets/js/g_script/g_validate.js',
    '/assets/js/g_script/g_csv_modal.js',
    '/assets/js/g_script/g_back.js',


]);
?>

<?php
$this->end();

$this->start('modal');
echo $this->element('csv_modal_import', array(
    'id' => 'csv_import_employee',
    'controller' => 'point_details',
    'action' => 'admin_import_point_from_csv',
    'multiple' => true
));
$this->end();
?>
<div class="budget-csv-import-container">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $title_for_layout ?></h3>
        </div>
        <div class="box-body">
            <?php
            echo $this->Form->create('data', array(
                'url' => array(
                    'controller' => 'point_details',
                    'action' => 'admin_history_csv_download'
                ),
                'method' => 'post',
                'inputDefaults' => array(
                    'div' => false,
                    'label' => false
                ),
                'autocomplete' => 'off',
                'data-validate' => '1',
                'id' => 'employee-csv-form'
            ));
            ?>
            <?php echo $this->element('flash-message') ?>
            <div class="msg-report"></div>
            <div class="row row-5">
                <div class="col-sm-6 group-btn mt10">
                    <div class="row">
                        <div class="col-md-5">
                            <div class='input-group date'>
                                <?php echo $this->Form->input('date_from', array('type' => 'text', 'value' => (isset($data['date']) ? $data['date'] : ''), 'class' => 'form-control', 'id' => 'dateSelectDownloadPointFrom', 'placeholder' => __('年度'), 'role' => 'date', 'required' => true)) ?>
                                <label for="dateSelectDownloadPointFrom" class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </label>
                            </div>
                        </div>

                        <div class='col-md-1'>
                            <p style="font-size: 28px; text-align: center;">~</p>
                        </div>
                        <div class='col-md-5'>
                            <div class='input-group date'>
                                <?php echo $this->Form->input('date_to', array('type' => 'text', 'value' => (isset($data['date']) ? $data['date'] : ''), 'class' => 'form-control', 'id' => 'dateSelectDownloadPointTo', 'placeholder' => __('年度'), 'role' => 'date', 'required' => true)) ?>
                                <label for="dateSelectDownloadPointTo" class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt10">
                        <?php
                        echo $this->Form->button(__('download'), array('type' => 'submit', 'class' => 'btn btn-primary'));
                        ?>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end() ?>
        </div>
    </div>
</div>