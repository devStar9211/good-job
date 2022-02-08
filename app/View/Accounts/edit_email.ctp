<?php $this->start('css') ?>
<?php echo $this->Html->css('/assets/css/l_css'); ?>
<?php $this->end() ?>
<div class="container">
    <div class="">
        <div id="edit_profile">
            <?php if (!empty($user['Employee']['id'])): ?>
                <?php
                $this->start('css');
                echo $this->Html->css([
                    '/assets/components/bootstrap-formHelper/bootstrap-formhelpers.min.css',
                    '/assets/components/croppie/croppie.css',
                    '/assets/css/g_css.css',
                    // Select2
                    '/assets/components/select2/select2.min.css',
                ]);
                $this->end();

                $this->start('modal');
                echo $this->element('avatar_cropie_modal');
                $this->end();

                $data = isset($data) ? $data : array();
                ?>
                <?php $this->start('script') ?>
                <?php
                echo $this->Html->script([
                    '/assets/components/jquery-mask/jquery.mask.min.js',
                    '/assets/components/bootstrap-formHelper/bootstrap-formhelpers.min.js',
                    '/assets/components/croppie/croppie.min.js',
                    '/assets/components/ajaxzip3/ajaxzip3.js',
                    '/assets/js/g_script/g_script.js',
                    '/assets/js/g_script/g_validate.js',
                    '/assets/js/g_script/g_avatar_croppie.js',
                    '/assets/js/g_script/g_back.js',
                    '/assets/components/bootstrap-datetimepicker/moment-with-locales.min.js',
                    '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
                    // // Select2
                    '/assets/components/select2/select2.full.min.js',
                ]);
                ?>
                <script type="text/javascript">
                    $(document).ready(function (e) {
                        //Initialize Select2 Elements
                        $(".select2").select2();
                    });
                </script>
                <script type="text/javascript">
                    var _validate = _validate | [];
                    _validate = [
                        {
                            role: 'email',
                            rule: [
                                {
                                    type: 'regex',
                                    regExp: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                                    message: '<?php echo __("メールが正しくありません。") ?>'
                                }
                            ]
                        }
                    ];
                </script>
            <?php $this->end() ?>
                <div class="employee-add-container">
                    <?php
                    echo $this->Form->create('Employee', array(
                        'method' => 'post',
                        'inputDefaults' => array(
                            'div' => true,
                            'label' => false
                        ),
                        'autocomplete' => 'off',
                        'data-validate' => '1',
                    ));
                    ?>
                    <div class="row">
                        <!-- box -->
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header with-border">
                                </div>
                                <?php echo $this->Form->create('Account', array('id' => 'account-validate')); ?>
                                <div class="box-body">
                                    <?php echo $this->element('flash-message'); ?>
                                    <?php
                                    echo $this->Form->input('email', array('label' => array('text' => __('Email'), 'class' => 'control-label col-xs-12 col-sm-2'), 'value' => $data['Account']['email'], 'required'=>true));
                                    ?>
                                    <div class="form-group clear">
                                        <label for="AccountEmail" class="control-label col-xs-12 col-sm-2"></label>
                                        <div class="col-sm-10 ">
                                            <div class="checkbox icheck">
                                                <?php
                                                echo $this->Html->link(
                                                    '利用規約',
                                                    '/terms.html',
                                                    array()
                                                );
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-footer clearfix">
                                    <?php echo $this->Form->submit(__('利用規約に同意して保存'), array('after' => '<input type="reset" class="btn btn-default" value="キャンセル" style="margin-left: 10px;" onclick=\'location.href = "/"\'>')); ?>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                        <!-- ./box -->
                    </div>
                    <?php echo $this->Form->end() ?>
                </div>
            <?php else: ?>
            <?php $this->start('css') ?>
            <?php echo $this->Html->css('/assets/components/croppie/croppie'); ?>
            <?php $this->end() ?>
                <div class="box">
                    <div class="box-header with-border">
                    </div>
                    <?php echo $this->Form->create('Account', array('id' => 'account-validate')); ?>
                    <div class="box-body">
                        <?php echo $this->element('flash-message'); ?>
                        <?php
                        echo $this->Form->input('email', array('label' => array('text' => __('Email'), 'class' => 'control-label col-xs-12 col-sm-2'), 'value' => $data['Account']['email']));
                        ?>
                        <div class="form-group clear">
                            <label for="AccountEmail" class="control-label col-xs-12 col-sm-2"></label>
                            <div class="col-sm-10 ">
                                <div class="checkbox icheck">
                                    <?php
                                    echo $this->Html->link(
                                        '利用規約',
                                        '/terms.html',
                                        array()
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->submit(__('利用規約に同意して保存')); ?>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>