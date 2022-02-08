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

<div class="container-fluid">
    <div class="row">
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
        <div class="box box-default">
            <div class="box-header">
                <legend class="no-margin">
                    <h3 class="box-title"><?php echo $title_for_layout; ?></h3>
                </legend>
            </div>
            <div class="box-body">
                <div class="msg-report"><?php echo $this->element('flash-message') ?></div>

                <table class="table table-bordered" style="min-width: 300px;width: 50%;">
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
                                    echo $this->Form->input('Collumn.' . $key, array('type' => 'text', 'label' => false, 'style' => 'width: 100%; ', 'value' => !empty($gridConfig) ? $gridConfig[$key] : '', 'class' => 'form-control ', 'maxlength' => '100', 'placeholder' => __('Color'), 'div' => ''));
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
            <div class="box-footer clearfix">
                <?php echo $this->Form->submit(__('Submit')); ?>
            </div>
        </div>
        <?php echo $this->Form->end() ?>
    </div>
</div>





