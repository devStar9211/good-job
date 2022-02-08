<?php
$this->start('css');
echo $this->Html->css([

    '/assets/css/v_css.css',
    '/assets/css/g_css.css',
]);
$this->end();

?>
<div class="container-fluid">
    <div class="row">
        <div class="box box-default">
            <div class="box-header">
                <legend class="no-margin">
                    <h3 class="box-title"><?php echo $title_for_layout; ?></h3>
                </legend>
            </div>
            <div class="box-body">
                <div class="msg-report"><?php echo $this->element('flash-message') ?></div>
                <?php
                echo $this->Form->create($config_field['model'], array(
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
                <?php foreach ($collection as $_data) { ?>
                    <div class="form-group row">
                        <div class="cat_col_name col-md-6  col-sm-6 col-xs-6">
                            <span class="col_id"><?php echo '(#' . $_data[$config_field['model']]['id'] . ')'; ?></span>

                            <?php
                            echo $this->Form->input('FormData.' . $config_field['fields']['name'] . '.' . $_data[$config_field['model']]['id'], array('type' => 'text', 'label' => false, 'style' => 'width: 100%; ', 'value' => $_data[$config_field['model']]['name'], 'class' => 'form-control ', 'maxlength' => '100'));
                            ?>

                        </div>

                        <div class="col-md-6  col-sm-6 col-xs-6">
                            <div class="row" style="width: 250px;">
                                <div class="col-md-6  col-sm-6 col-xs-6">
                                    <label class="btn btn-block btn-info"
                                           for="custom_submit_<?php echo $_data[$config_field['model']]['id'] ?>"><?php echo __('Update') ?></label>
                                    <input style="display: none;" onClick="$(this).closest('form').submit();"
                                           type="radio" name="EditData[id]"
                                           value="<?php echo $_data[$config_field['model']]['id'] ?>"
                                           id="custom_submit_<?php echo $_data[$config_field['model']]['id'] ?>"/>
                                </div>

                                <div class="col-md-6  col-sm-6 col-xs-6">
                                    <a class="btn btn-block btn-danger"
                                       onclick="check_delete('<?php echo $this->webroot . 'admin/' . $config_field['controller'] . '/index/' . $_data[$config_field['model']]['id'] ?>', event)"
                                       href=""><?php echo __('Delete') ?></a>
                                </div>


                            </div>

                        </div>
                    </div>
                <?php } ?>
                <div class="form-group row">
                    <div class="cat_col_name col-md-6 col-sm-6 col-xs-6">

                        <?php
                        echo $this->Form->input('EditData.name', array('label' => false, 'style' => 'width: 100%; ', 'value' => '', 'class' => 'form-control'));
                        ?>

                    </div>

                    <div class="col-md-6  col-sm-6 col-xs-6">
                        <div class="row" style="width: 250px;">
                            <div class="col-md-12">
                                <label class="btn btn-block btn-default" style="width: 95px"
                                       for="add_new"><?php echo __('Add new') ?></label>
                                <input style="display: none;" onClick="$(this).closest('form').submit();" type="radio"
                                       name="EditData[id]" value="" id="add_new"/>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>


<?php $this->start('script') ?>
<?php echo $this->Html->script('/assets/js/v_script/v_script.js'); ?>
<?php $this->end() ?>


