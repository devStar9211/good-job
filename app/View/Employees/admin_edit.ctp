<?php
$this->start('css');
echo $this->Html->css([
    '/assets/components/bootstrap-formHelper/bootstrap-formhelpers.min.css',
    '/assets/components/croppie/croppie.css',
    '/assets/css/g_css.css',
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
    '/assets/js/g_script/g_back.js'
]);
?>
<script type="text/javascript">
    var _validate = _validate | [];
    _validate = [
        {
            role: 'email',
            rule: [
                {
                    type: 'regex',
                    regExp: /^((([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,})))*$/,
                    message: '<?php echo __("メールが正しくありません。") ?>'
                }
            ]
        }, {
            role: '_phone_number',
            rule: [
                {
                    type: 'regex',
                    regExp: /^(\([0-9]{3}\)\s*[0-9]{3}\-[0-9]{4})*$/,
                    message: '<?php echo __("この電話番号は使用できません。") ?>'
                }
            ]
        }, {
            role: 'username',
            rule: [
                {
                    type: 'notEmpty',
                    message: '<?php echo __("このフィルドに記入して下さい。") ?>'
                }, {
                    type: 'minLength',
                    min: 6,
                    message: '<?php echo __("ユーザは6文字以上です。") ?>'
                }, {
                    type: 'maxLength',
                    max: 50,
                    message: '<?php echo __("ユーザ名が長すぎます。最長は50文字以内です。") ?>'
                }, {
                    type: 'regex',
                    regExp: /^(((([a-zA-Z0-9][a-zA-Z0-9_\.]+[a-zA-Z0-9])|[a-zA-Z0-9]*)|(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))))*$/i,
                    message: '<?php echo __("ユーザは無効な文字を含んでいます。") ?>'
                }
            ]
        }, {
            role: 'password',
            rule: [
                {
                    type: 'notEmpty',
                    message: '<?php echo __("このフィルドに記入して下さい。") ?>'
                }, {
                    type: 'minLength',
                    min: 6,
                    message: '<?php echo __("パスワードは6文字以上です。") ?>'
                }, {
                    type: 'maxLength',
                    max: 32,
                    message: '<?php echo __("パスワードが長すぎます。最長は32文字以内です。") ?>'
                }, {
                    type: 'regex',
                    regExp: /^[a-zA-Z0-9_]*$/i,
                    message: '<?php echo __("パスワードは無効な文字を含んでいます。") ?>'
                }
            ]
        }
    ];

    <?php if(isset($user) && !empty($user['Admin']['data_access_level'])): ?>

    _validate.push({
        role: 'company_id',
        rule: [
            {
                type: 'notEmpty',
                message: '<?php echo __("このフィルドに記入して下さい。") ?>'
            }
        ]
    });

    <?php endif ?>

    $(document).ready(function (e) {
        // $('.input-phone').mask('(000) 000-0000', {placeholder: '(000) 000-0000'});
        $('.postal-code').mask('000-0000', {placeholder: '000-0000'});

        var box_color = ['warning', 'primary', 'danger', 'default', 'success', 'info'];
        var $boxes = $('.box');
        var ix = Math.round((Math.random() * (box_color.length - 1)));
        $boxes.each(function (index, el) {
            $(el).addClass('box-' + box_color[ix]);

            if (ix >= box_color.length - 1) {
                ix = 0;
            }
            else {
                ix++;
            }
        });
    });

    $('.joinDateSelect').datetimepicker({
        locale: 'ja',
        format: 'YYYY-MM-DD',
    }).on('dp.change', function (e) {
//        get_employee_number(this);
        //xxxx
    });

    function get_employee_number(node) {
        var self = node,
            $field_target = $($(self).data('field-node')),
            source = $(self).data('source'),
            join_date = $(self).val();
        if (join_date !== '' && source != undefined) {
            $.ajax({
                url: source,
                type: 'POST',
                dataType: 'json',
                data: {join_date: join_date},
            })
                .done(function (res, status, xhr) {
                    console.log(res);
                    if (res.status) {
                        var employee_number = res.employee_number;
                        $field_target.val(employee_number);
                    }
                });
        } else {
            $field_target.val();
        }
    }

    // disable enter submit form
    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

</script>
<?php $this->end() ?>
<div class="employee-add-container">
    <?php
    echo $this->Form->create('Employee', array(
        'url' => array(
            'controller' => 'Employees',
            'action' => 'admin_edit',
            $data['id']
        ),
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
                    <h3 class="box-title"><?php echo __('employee registration') ?></h3>
                </div>
                <div class="box-body">
                    <?php echo $this->element('flash-message') ?>
                    <div class="row no-margin">
                        <div class="col-sm-6 no-padding">
                            <div class="input-group avatar-group">
								<span class="input-group-addon avatar-wrap text-center">
									<figure>
										<div class="avatar-select" onclick="popup_cropie_modal()">
											<div class="avatar-select-shadow align-middle"><i
                                                        class="fa fa-fw fa-image"></i></div>
                                            <?php
                                            echo $this->Html->image('/' . AVATAR_PATH . $data['avatar'], array('width' => '132', 'height' => '132', 'id' => 'avatar-preview', 'data-default' => $this->webroot . AVATAR_PATH . DEFAULT_AVATAR));
                                            ?>
										</div>
										<figcaption><?php echo __('avatar') ?></figcaption>
									</figure>
                                    <?php
                                    echo $this->Form->input('Employee.avatar.input', array('type' => 'hidden', 'hidden' => 'hidden', 'id' => 'avatar-input', 'data-source' => ($this->webroot . AVATAR_PATH . $data['avatar'])));
                                    echo $this->Form->input('Employee.avatar.original', array('type' => 'hidden', 'hidden' => 'hidden', 'id' => 'avatar-input-original', 'data-source' => (!empty($data['avatar_original']) ? $this->webroot . AVATAR_PATH . $data['avatar_original'] : $this->webroot . AVATAR_PATH . DEFAULT_AVATAR)));
                                    echo $this->Form->input('Employee.avatar.points', array('type' => 'hidden', 'hidden' => 'hidden', 'id' => 'avatar-points', 'data-source' => (!empty($data['avatar_points']) ? $data['avatar_points'] : '')));
                                    ?>
								</span>
                                <div class="control-wrap">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('name', array('type' => 'text', 'class' => 'form-control', 'placeholder' => __('name'), 'role' => 'name', 'value' => $data['name'])) ?>
                                    </div>
                                    <div class="form-group w100">
                                        <div class='input-group date' id='dob'>
                                            <?php echo $this->Form->input('dob', array('div' => false, 'type' => 'text', 'class' => 'form-control yearMonthDaySelect', 'placeholder' => __('birthday'), 'role' => 'dob', 'value' => $data['dob'])) ?>
                                            <label for="EmployeeDob" class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="register-switch">
                                            <input type="radio" name="data[Employee][gender]" value="0" id="sex_m"
                                                   class="register-switch-input" <?php echo $data['gender'] != 0 ? '' : 'checked' ?>>
                                            <label for="sex_m"
                                                   class="register-switch-label"><?php echo __('male') ?></label>
                                            <input type="radio" name="data[Employee][gender]" value="1" id="sex_f"
                                                   class="register-switch-input" <?php echo $data['gender'] == 1 ? 'checked' : '' ?>>
                                            <label for="sex_f"
                                                   class="register-switch-label"><?php echo __('female') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 padding-left-sm">
                            <div class="form-group">
                                <?php echo $this->Form->input('kana_name', array('type' => 'text', 'class' => 'form-control', 'role' => 'name', 'placeholder' => __('tên furigana'), 'value' => $data['kana_name'])) ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('Account.email', array('type' => 'email', 'class' => 'form-control', 'role' => 'email', 'placeholder' => __('mail adress'), 'value' => $data['account']['email'])) ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('phone', array('type' => 'text', 'class' => 'form-control input-phone', 'role' => 'phone', 'placeholder' => __('phone number'), 'value' => $data['phone'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./box -->
    </div>
    <div class="row row-5">
        <div class="col-sm-6">
            <!-- box -->
            <div class="clearfix">
                <div class="box">
                    <div class="box-body">
                        <div class="row no-margin">
                            <div class="col-xs-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('Account.username', array('type' => 'text', 'class' => 'form-control', 'role' => 'username', 'label' => __('username'), 'placeholder' => __('username'), 'value' => $data['account']['username'])) ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $this->Form->input('Account.password', array('type' => 'password', 'class' => 'form-control', 'role' => 'edit_password', 'label' => __('password'), 'placeholder' => __('* * * * * * * *'), 'required' => false)) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./box -->
            <!-- box -->
            <div class="clearfix">
                <div class="box">
                    <div class="box-body">
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group <?php if (isset($user) && !empty($user['Admin']['data_access_level'])) {
                                    echo 'required';
                                } ?>">
                                    <label for="EmployeeCompanyId"><?php echo __('company') ?></label>
                                    <?php
                                    echo $this->Form->select('company_id', $companies, array('empty' => __('会社'), 'default' => (!empty($data['company_id']) ? $data['company_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'generateListOffices(this)', 'data-office-node' => '#EmployeeOfficeId', 'data-source' => $this->Html->url(array('controller' => 'Offices', 'action' => 'admin_generate_list_offices')), 'role' => 'company_id'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <label for="EmployeeOfficeId"><?php echo __('office') ?></label>
                                    <?php
                                    echo $this->Form->select('office_id', $offices, array('empty' => __('事業所'), 'default' => (!empty($data['office_id']) ? $data['office_id'] : ''), 'class' => 'form-control select2', 'role' => 'office_id'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <label for="EmployeeHiringPatternId"><?php echo __('hiring pattern') ?></label>
                                    <?php
                                    echo $this->Form->select('hiring_pattern_id', $hiring_patterns, array('empty' => __('選択して下さい。'), 'default' => (!empty($data['hiring_pattern_id']) ? $data['hiring_pattern_id'] : ''), 'class' => 'form-control select2', 'role' => 'hiring_pattern_id'));
                                    ?>
                                </div>
                            </div>

                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <label for="EmployeePositionId"><?php echo __('position') ?></label>
                                    <?php
                                    echo $this->Form->select('position_id', $positions, array('empty' => __('役職'), 'default' => (!empty($data['position_id']) ? $data['position_id'] : ''), 'class' => 'form-control select2', 'role' => 'position'));
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group required">
                                    <label for="EmployeeJoinDate"><?php echo __('join date') ?></label>
                                    <div class='input-group date'>
                                        <?php echo $this->Form->input('join_date', array('div' => false, 'type' => 'text', 'value' => '', 'class' => 'form-control joinDateSelect', 'placeholder' => __('join date'), 'role' => 'join_date', 'value' => (!empty($data['join_date']) ? $data['join_date'] : ''), 'data-field-node' => '#EmployeeEmployeeNumber', 'data-source' => $this->Html->url(array('controller' => 'employees', 'action' => 'admin_get_employee_number')))) ?>
                                        <label for="EmployeeJoinDate" class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-12 no-padding">
                                <div class="form-group required">
                                    <label for="EmployeeEmployeeNumber"><?php echo __('employee number') ?></label>
                                    <?php echo $this->Form->input('employee_number', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => __('employee number'), 'value' => $data['employee_number'])) ?>
                                </div>
                            </div>


                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group no-margin">
                                    <label class="align-middle h34 checkbox-wrap">
                                        <?php
                                        echo $this->Form->input('in_office', array('div' => false, 'type' => 'checkbox', 'class' => 'larger', 'checked' => ($data['in_office'] ? 'checked' : '')));
                                        echo __('in office');
                                        ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./box -->
            <!-- box -->
            <div class="clearfix">
                <div class="box">
                    <div class="box-body">
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <label for="EmployeeLicenses"><?php echo __('license') ?></label>
                                    <?php echo $this->Form->select('licenses', $licenses, array('type' => 'text', 'class' => 'form-control select2', 'multiple' => 'multiple', 'default' => !empty($data['licenses']) ? $data['licenses'] : '')) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <label for="EmployeeOccupation"><?php echo __('occupation') ?></label>
                                    <?php echo $this->Form->select('occupations', $occupations, array('type' => 'text', 'class' => 'form-control select2', 'multiple' => 'multiple', 'default' => !empty($data['occupations']) ? $data['occupations'] : '')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./box -->
            <!-- box -->
            <div class="clearfix">
                <div class="box">
                    <div class="box-body">
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php
                                    echo $this->Form->input('postal_code', array('type' => 'text', 'class' => 'form-control postal-code', 'label' => __('postal code'), 'placeholder' => __('postal code'), 'role' => 'postal_code', 'onkeyup' => 'AjaxZip3.zip2addr(this,"","data[Employee][prefecture]","data[Employee][municipality]","data[Employee][municipal_town]","",false)', 'value' => $data['postal_code']))
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <label for="EmployeePrefecture"><?php echo __('Prefecture') ?></label>
                                    <?php
                                    echo $this->Form->select('prefecture', $this->Data->prefectures(), array('empty' => __('Prefecture'), 'class' => 'form-control', 'default' => (!empty($data['prefecture']) ? $data['prefecture'] : ''), 'role' => 'address'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php
                                    echo $this->Form->input('municipality', array('type' => 'text', 'class' => 'form-control', 'role' => 'address', 'label' => __('municipality'), 'value' => $data['municipality']))
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php
                                    echo $this->Form->input('municipal_town', array('type' => 'text', 'class' => 'form-control', 'role' => 'address', 'label' => __('municipal town'), 'value' => $data['municipal_town']))
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./box -->
            <!-- box -->
            <div class="clearfix">
                <div class="box">
                    <div class="box-body">
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('bank_name', array('type' => 'text', 'class' => 'form-control', 'label' => __('bank name'), 'placeholder' => __('bank name'), 'role' => 'name', 'value' => $data['bank_name'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('branch_name', array('type' => 'text', 'class' => 'form-control', 'label' => __('branch name'), 'placeholder' => __('branch name'), 'role' => 'name', 'value' => $data['branch_name'])) ?>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('account_number', array('type' => 'text', 'class' => 'form-control', 'label' => __('account number'), 'placeholder' => __('account number'), 'role' => 'name', 'value' => $data['account_number'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('account_name', array('type' => 'text', 'class' => 'form-control', 'label' => __('account name'), 'placeholder' => __('account name'), 'role' => 'name', 'value' => $data['account_name'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./box -->
        </div>
        <div class="col-sm-6">
            <!-- box -->
            <div class="clearfix">
                <div class="box">
                    <div class="box-body">
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('basic_salary', array('type' => 'text', 'class' => 'form-control', 'label' => __('basic salary'), 'placeholder' => __('basic salary'), 'role' => 'money', 'onkeydown' => 'numberInput(event)', 'value' => $data['basic_salary'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('daily_wage', array('type' => 'text', 'class' => 'form-control', 'label' => __('lương ngày'), 'placeholder' => __('lương ngày'), 'role' => 'money', 'onkeydown' => 'numberInput(event)', 'value' => $data['daily_wage'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('hourly_wage', array('type' => 'text', 'class' => 'form-control', 'label' => __('lương giờ'), 'placeholder' => __('lương giờ'), 'role' => 'money', 'onkeydown' => 'numberInput(event)', 'value' => $data['hourly_wage'])) ?>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('social_insurance', array('type' => 'text', 'class' => 'form-control', 'label' => __('social insurance'), 'placeholder' => __('social insurance'), 'role' => 'money', 'onkeydown' => 'numberInput(event)', 'value' => $data['social_insurance'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('employment_insurance', array('type' => 'text', 'class' => 'form-control', 'label' => __('employment insurance'), 'placeholder' => __('employment insurance'), 'role' => 'money', 'onkeydown' => 'numberInput(event)', 'value' => $data['employment_insurance'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('basis_pension_number', array('type' => 'text', 'class' => 'form-control', 'label' => __('basis pension number'), 'placeholder' => __('basis pension number'), 'role' => 'money', 'onkeydown' => 'numberInput(event)', 'value' => $data['basis_pension_number'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./box -->
            <!-- box -->
            <div class="clearfix">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="EmployeeAllowances"><?php echo __('allowances') ?></label>
                                    <?php echo $this->Form->select('allowances', $allowances, array('type' => 'text', 'class' => 'form-control select2', 'multiple' => 'multiple', 'default' => !empty($data['allowances']) ? $data['allowances'] : '')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./box -->
            <!-- box -->
            <div class="clearfix">
                <div class="box">
                    <div class="box-body">
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <div>
                                        <label><?php echo __('traffic type') ?></label>
                                    </div>
                                    <div class="clearfix">
                                        <?php
                                        echo $this->Data->transportation('traffic_type.', !empty($data['traffic_type']) ? $data['traffic_type'] : '');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('public_transportation', array('type' => 'text', 'class' => 'form-control', 'label' => __('public transportation (regular monthly fee)'), 'placeholder' => __('public transportation'), 'role' => 'money', 'onkeydown' => 'numberInput(event)', 'value' => $data['public_transportation'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('vehicle_cost', array('type' => 'text', 'class' => 'form-control', 'label' => __('vehicle cost (amount per attendance)'), 'placeholder' => __('vehicle cost'), 'role' => 'money', 'onkeydown' => 'numberInput(event)', 'value' => $data['vehicle_cost'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('one_way_transportation', array('type' => 'text', 'class' => 'form-control', 'label' => __('one way transportation'), 'placeholder' => __('one way transportation'), 'role' => 'money', 'onkeydown' => 'numberInput(event)', 'value' => $data['one_way_transportation'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('round_trip_transportation', array('type' => 'text', 'class' => 'form-control', 'label' => __('round trip transportation'), 'placeholder' => __('round trip transportation'), 'role' => 'money', 'onkeydown' => 'numberInput(event)', 'value' => $data['round_trip_transportation'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <label for="EmployeeCommuteRoute"><?php echo __('commute route') ?></label>
                                    <?php echo $this->Form->textarea('commute_route', array('type' => 'text', 'class' => 'form-control', 'placeholder' => __('commute route'), 'role' => 'address', 'value' => $data['commute_route'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./box -->
            <!-- box -->
            <div class="clearfix">
                <div class="box">
                    <div class="box-body">
                        <div class="row no-margin">
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('sos_contact_person', array('type' => 'text', 'class' => 'form-control', 'label' => __('tên người liên lạc lúc cần'), 'placeholder' => __('tên người liên lạc lúc cần'), 'role' => 'name', 'value' => $data['sos_contact_person'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('sos_contact_person_kana', array('type' => 'text', 'class' => 'form-control', 'label' => __('tên Kana của người liên lạc lúc cần'), 'placeholder' => __('tên Kana của người liên lạc lúc cần'), 'role' => 'name', 'value' => $data['sos_contact_person_kana'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('sos_phone', array('type' => 'text', 'class' => 'form-control input-phone', 'label' => __('sos phone'), 'placeholder' => __('sos phone'), 'role' => 'phone', 'value' => $data['sos_phone'])) ?>
                                </div>
                            </div>
                            <div class="col-sm-12 no-padding">
                                <div class="form-group">
                                    <?php echo $this->Form->input('sos_address', array('type' => 'text', 'class' => 'form-control', 'label' => __('địa chỉ liên lạc lúc cần'), 'placeholder' => __('địa chỉ liên lạc lúc cần'), 'role' => 'address', 'value' => $data['sos_address'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./box -->

        </div>
    </div>
    <div class="row row-5" role="container">
        <!-- box -->
        <div class="col-sm-6" role="sample" hidden="hidden">
            <div class="box">
                <div class="remove_sample_btn_25" onclick="remove_sample(this)"><i class="fa fa-close"></i></div>
                <div class="box-header with-border">
                    <h5 class="no-margin bold" data-type="key-title"
                        data-title="<?php echo __('phụ dưỡng gia đình') . ' key' ?>"></h5>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <?php echo $this->Form->input('Relation.key.name', array('div' => 'required', 'type' => 'text', 'class' => 'form-control', 'role' => 'name', 'label' => __('name'), 'placeholder' => __('name'), 'data-type' => 'key-name', 'data-name' => 'data[Relation][key][name]', 'id' => 'RelationName_0')) ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('Relation.key.kana_name', array('div' => 'required', 'type' => 'text', 'class' => 'form-control', 'role' => 'name', 'label' => __('kana name'), 'placeholder' => __('kana name'), 'data-type' => 'key-name', 'data-name' => 'data[Relation][key][kana_name]', 'id' => 'RelationKanaName_0')) ?>
                    </div>
                    <div class="form-group">
                        <label for="RelationDob_0"><?php echo __('birthday') ?></label>
                        <div class='input-group date'>
                            <?php echo $this->Form->input('Relation.key.dob', array('div' => false, 'type' => 'text', 'value' => '', 'class' => 'form-control yearMonthDaySelect', 'placeholder' => __('birthday'), 'role' => 'dob', 'data-type' => 'key-name', 'data-name' => 'data[Relation][key][dob]', 'id' => 'RelationDob_0')) ?>
                            <label for="RelationDob_0" class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('Relation.key.relationship', array('div' => array('class' => 'required'), 'type' => 'text', 'class' => 'form-control', 'role' => 'other', 'label' => __('quan hệ'), 'placeholder' => __('quan hệ'), 'data-type' => 'key-name', 'data-name' => 'data[Relation][key][relationship]', 'id' => 'RelationRelationship_0')) ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('Relation.key.occupation', array('div' => array('class' => 'required'), 'type' => 'text', 'class' => 'form-control', 'role' => 'other', 'label' => __('công việc'), 'placeholder' => __('công việc'), 'data-type' => 'key-name', 'data-name' => 'data[Relation][key][occupation]', 'id' => 'RelationOccupation_0')) ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./box -->
        <?php $i = 0; ?>
        <?php if (!empty($data['relation'])): ?>
            <?php foreach ($data['relation'] as $relation): ?>
                <?php $i++; ?>
                <!-- box -->
                <div class="col-sm-6">
                    <div class="box">
                        <div class="remove_sample_btn_25" onclick="remove_sample(this)"><i class="fa fa-close"></i>
                        </div>
                        <div class="box-header with-border">
                            <h5 class="no-margin bold" data-type="key-title"
                                data-title="<?php echo __('phụ dưỡng gia đình') . ' key' ?>">
                                <?php echo __('phụ dưỡng gia đình') . ' ' . $i ?>
                            </h5>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <?php echo $this->Form->input('Relation.' . $i . '.name', array('div' => 'required', 'type' => 'text', 'class' => 'form-control', 'role' => 'name', 'label' => __('name'), 'placeholder' => __('name'), 'data-type' => 'key-name', 'data-name' => 'data[Relation][key][name]', 'id' => 'RelationName_' . $i, 'value' => $relation['name'])) ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('Relation.' . $i . '.kana_name', array('div' => 'required', 'type' => 'text', 'class' => 'form-control', 'role' => 'name', 'label' => __('kana name'), 'placeholder' => __('kana name'), 'data-type' => 'key-name', 'data-name' => 'data[Relation][key][kana_name]', 'id' => 'RelationKanaName_' . $i, 'value' => $relation['kana_name'])) ?>
                            </div>
                            <div class="form-group">
                                <label for="RelationDob_<?php echo $i ?>"><?php echo __('birthday') ?></label>
                                <div class='input-group date'>
                                    <?php echo $this->Form->input('Relation.' . $i . '.dob', array('div' => false, 'type' => 'text', 'value' => $relation['dob'], 'class' => 'form-control yearMonthDaySelect', 'placeholder' => __('birthday'), 'role' => 'dob', 'data-type' => 'key-name', 'data-name' => 'data[Relation][key][dob]', 'id' => 'RelationDob_' . $i)) ?>
                                    <label for="RelationDob_<?php echo $i ?>" class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('Relation.' . $i . '.relationship', array('div' => array('class' => 'required'), 'type' => 'text', 'class' => 'form-control', 'role' => 'other', 'label' => __('quan hệ'), 'placeholder' => __('quan hệ'), 'data-type' => 'key-name', 'data-name' => 'data[Relation][key][relationship]', 'id' => 'RelationRelationship_' . $i, 'value' => $relation['relationship'])) ?>
                            </div>
                            <div class="form-group">
                                <?php echo $this->Form->input('Relation.' . $i . '.occupation', array('div' => array('class' => 'required'), 'type' => 'text', 'class' => 'form-control', 'role' => 'other', 'label' => __('công việc'), 'placeholder' => __('công việc'), 'data-type' => 'key-name', 'data-name' => 'data[Relation][key][occupation]', 'id' => 'RelationOccupation_' . $i, 'value' => $relation['occupation'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./box -->
            <?php endforeach ?>
        <?php endif ?>
        <!-- ./box -->
        <div class="col-sm-6" role="add">
            <div class="form-group">
                <button type="button" class="btn btn-success bold fluid" onclick="add_sample(this, 4)"><span
                            class="glyphicon glyphicon-plus"></span>&nbsp;<?php echo __('phụ dưỡng gia đình') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="row row-5" role="container">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-body">
                    <div class="form-group">
                        <?php echo $this->Form->input('employee_register_only', array('type' => 'checkbox', 'class' => 'form-control', 'label' => __('Employee registration authority only'), 'checked'=> $data['employee_register_only'] == 1 ? 'true' : '')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('have_sale_permission', array('type' => 'checkbox', 'class' => 'form-control', 'label' => __('sales registration right'), 'checked'=> $data['have_sale_permission'] == 1 ? 'true' : '')); ?>
                    </div>
                    <br>
                    <p>シフト権限 （１つ選択）</p>
                    <div class="form-group group-radio">
                        <?php
                        $radio_options = array(
                            'shift_authority_view'=>'シフト閲覧',
                            'shift_authority_edit'=>'シフト閲覧・編集',
                            'shift_authority_all'=>'シフト閲覧・編集・人件費閲覧',
                        );
                        echo $this->Form->radio("shift_authority", $radio_options, array('legend'=>false,'class' => 'radio-option', 'default' => $data['shift_authority'] != null ? $data['shift_authority'] : 'shift_authority_view'));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- box -->
        <div class="col-sm-12">
            <div class="box">
                <div class="box-body">
                    <div class="row no-margin">
                        <div class="form-group">
                            <label for="EmployeeProfile"><?php echo __('profile') ?></label>
                            <?php
                            echo $this->Form->textarea('profile', array('class' => 'form-control', 'role' => 'profile', 'value' => $data['profile']));
                            ?>
                        </div>
                    </div>
                    <div class="row no-margin mt20">
                        <?php echo $this->Form->button(__('save'), array('type' => 'submit', 'class' => 'btn btn-primary')) ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./box -->
    </div>
    <?php echo $this->Form->end() ?>
</div>