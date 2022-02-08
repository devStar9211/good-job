<?php $grid = Configure::read('Grid'); ?>
<?php
$this->start('css');
echo $this->Html->css([
    '/assets/css/v_css.css',
    '/assets/css/g_css.css',
]);
$this->end();

?>
<?php $this->start('script') ?>
<?php echo $this->Html->script('/assets/js/v_script/v_script.js'); ?>
<script type="text/javascript">
    $(".colorpicker1").colorpicker();

</script>
<?php $this->end() ?>
<?php
echo $this->Form->create('Company', array(
    'inputDefaults' => array(
        'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
        'div' => array('class' => 'form-group'),
        'label' => array('class' => 'control-label col-xs-12 col-sm-2'),
        'between' => '<div class="controls  ">',
        'after' => '</div><div class="clearfix"></div>',
        'error' => array('attributes' => array('wrap' => 'label', 'class' => 'control-label', 'before' => '<i class="fa fa-times-circle-o"></i> ')),
        'class' => 'form-control'
    )
));
?>
<div class="container-fluid">
    <div class="msg-report"><?php echo $this->element('flash-message') ?></div>
    <div class="row">

        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header">
                    <legend class="no-margin">
                        <h3 class="box-title"><?php echo __('Color for collumn'); ?></h3>
                    </legend>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 70%"><?php echo __('Collumn'); ?></th>
                            <th style="min-width: 130px; width: 30%;"><?php echo __('Color'); ?></th>
                        </tr>
                        <?php foreach ($grid as $key => $_col) {
                            ?>
                            <tr>
                                <td><?php echo $_col; ?></td>
                                <td>
                                    <div class="input-group colorpicker1">
                                        <?php
                                        echo $this->Form->input('collumn.' . $key, array('type' => 'text', 'label' => false, 'style' => 'width: 100%; ', 'value' => isset($gridConfig['collumn'][$key]) && !empty($gridConfig['collumn'][$key]) ? $gridConfig['collumn'][$key] : '', 'class' => 'form-control ', 'maxlength' => '100', 'placeholder' => __('Color'), 'div' => ''));
                                        ?>
                                        <div class="input-group-addon">
                                            <i></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header">
                    <legend class="no-margin">
                        <h3 class="box-title"><?php echo __('Color for company'); ?></h3>
                    </legend>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 70%"><?php echo __('Company name'); ?></th>
                            <th style="min-width: 130px; width: 30%;"><?php echo __('Color'); ?></th>
                        </tr>
                        <?php foreach ($companies as $key => $_item) {
                            ?>
                            <tr>
                                <td><?php echo $_item; ?></td>
                                <td>
                                    <div class="input-group colorpicker1">
                                        <?php
                                        echo $this->Form->input('company.' . $key, array('type' => 'text', 'label' => false, 'style' => 'width: 100%; ', 'value' => !empty($gridConfig['company']) ? $gridConfig['company'][$key] : '', 'class' => 'form-control ', 'maxlength' => '100', 'placeholder' => __('Color'), 'div' => ''));
                                        ?>
                                        <div class="input-group-addon">
                                            <i></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="box no-border">
        <div class="box-footer clearfix">
            <div class="row no-margin mt20">
                <?php echo $this->Form->button(__('save'), array('type' => 'submit', 'class' => 'btn btn-primary')) ?>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end() ?>




