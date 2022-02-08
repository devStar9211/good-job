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

    function call_datetimepicker() {
        $('.dateSelect').datetimepicker({
            locale: 'ja',
            viewMode: 'months',
            format: 'YYYY-MM',
        }).on('dp.change', function (e) {
        });
    }
    call_datetimepicker();


    //    console.log(html);
    $('.btn-append').click(function () {
        var rand_str = Math.random().toString(36).substring(7);
        var html = jQuery(this).closest('.box-choice').find('.manager-list .office-row').eq(0).clone();

        html.find('input[type="hidden"]').val('');
        html.find('select option').eq(0).attr('selected','selected');
        html.find('.dateSelect').val('').attr('id','id_'+rand_str);
        html.find('label').attr('for','id_'+rand_str);

        console.log(rand_str);

        $(this).closest('.box-choice').find('.manager-list').append(html);

        var numItems = $(this).closest('.box-choice').find('.manager-list .row').length;


        call_datetimepicker();

//        $(this).closest('.box-choice').find('.manager-list').append(html);
        jQuery(this).closest('.box-choice').find('.btn-remove').removeAttr('disabled');
    });

    $('.manager-list').on('click', '.status', function () {
        index_num = $(this).closest('.office-row').index();
        $(this).val(index_num);


    });

    $('.box-choice').on('click', ' .btn-remove', function () {
        var numItems = $(this).closest('.box-choice').find('.manager-list .row').length;
        if (numItems > 1) {
            $(this).removeAttr('disabled');
            $(this).closest('.office-row').remove();
        }else {
//            $(this).attr('disabled','disabled');
            $(this).closest('.office-row').find('select option').eq(0).attr('selected', 'selected');
            $(this).closest('.office-row').find('input').val('');
        }
    });

    $('.box-choice ').on('change', '.office-row select', function () {
        if($(this).val() != ''){
            $(this).closest('.office-row').find('.dateSelect').attr('required', 'required');
        }else{
            $(this).closest('.office-row').find('.dateSelect').removeAttr('required');
        }
    });



</script>
<?php $this->end() ?>

<div class="container-fluid">
    <div class="row">
        <div class="box box-default">
            <div class="box-header">
                <legend class="no-margin">
                    <h3 class="box-title"><?php echo $title_for_layout; ?></h3>
                </legend>
            </div>
            <div class="box-body ">
                <div class="msg-report"><?php echo $this->element('flash-message') ?></div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3 col-xs-12 col-sm-4">
                            <?php echo __("Select company"); ?>
                        </div>
                        <div class="col-md-6 col-xs-12 col-sm-8">
                            <?php
                            echo $this->Form->input("Company.company_id", array("label" => false, "type" => "select", 'class' => 'form-control', "options" => $company_options, 'onchange' => "location = this.value;", 'default' => $current_company));
                            ?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="container-fluid">
                    <?php
                    echo $this->Form->create('Office');
                    ?>
                    <?php
                    foreach ($collection_offices as $key => $_data) {
                        ?>
                        <div class="form-group ">
                            <div class="row">
                                <div class="col-md-3 col-xs-12 col-sm-4">
                                    <?php echo $_data['office_name'] . '(#' . $key . ')'; ?>
                                </div>
                                <div class="box-choice col-md-6 col-xs-12 col-sm-8 ">

                                    <div class="manager-list">
                                        <?php
                                        if (!empty($_data['employee_manager'])) {
                                            $i=0;
                                            foreach ($_data['employee_manager'] as $_item) {
                                                ?>
                                                <div class="row office-row">
                                                    <div class="col-md-6 ">
                                                        <?php
                                                        echo $this->Form->hidden("Office." . $key . '.id.', array(
                                                                'value'=>$_item['OfficeManager']['id']
                                                        ));

                                                        echo $this->Form->input("Office." . $key . '.employee_id.', array("type" => "select", 'label' => false, 'class' => 'form-control', "options" => $_data['employees'], 'default' => $_item['OfficeManager']['employee_id'], 'between' => '<div class="controls col-xs-12 col-sm-12">','empty'=>'', 'required'=>false));
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-4 ">
                                                        <div class="input-group date">
                                                            <input name="data[Office][<?php echo $key ?>][date][]"
                                                                   value="<?php echo date('Y-m', strtotime($_item['OfficeManager']['date'])); ?>"
                                                                   class="form-control dateSelect"
                                                                   role="join_date"
                                                                   type="text" placeholder="開始月"
                                                                   id="id_<?php echo $key.'_'.$_item['OfficeManager']['id'] ?>"
                                                            >
                                                            <label for="id_<?php echo $key.'_'.$_item['OfficeManager']['id'] ?>" class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 ">

                                                        <a class="pull-right btn btn-default btn-remove">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php
                                                $i++;
                                            }
                                        } else {
                                            ?>
                                            <div class="row office-row">
                                                <div class="col-md-6 ">
                                                    <?php
                                                    echo $this->Form->hidden("Office." . $key . '.id.');
                                                    echo $this->Form->input("Office." . $key . '.employee_id.', array("type" => "select", 'label' => false, 'class' => 'form-control', "options" => $_data['employees'], 'default' => '', 'between' => '<div class="controls col-xs-12 col-sm-12">','empty'=>'', 'required'=>false));
                                                    ?>
                                                </div>
                                                <div class="col-sm-4 ">
                                                    <div class="input-group date">
                                                        <input name="data[Office][<?php echo $key ?>][date][]" value=""
                                                               class="form-control dateSelect"
                                                               role="join_date"
                                                               type="text" placeholder="開始月"
                                                               id="date_select_0"
                                                        >
                                                        <label for="date_select_0" class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 ">

                                                    <a class="pull-right btn btn-default btn-remove">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row row-btn-add">
                                        <div class="col-md-12 ">
                                            <a class="col-md-1 btn btn-default btn-append">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <hr/>
                        </div>

                        <?php
                    }
                    ?>

                    <?php
                    if (!empty($collection_offices)) {
                        ?>
                        <div class="row clearfix">
                            <?php echo $this->Form->submit(__('保存'), array(
                                'class' => 'btn btn-primary',
                                'div' => 'form-group',
                                'before' => '<div class="col-xs-12 col-sm-8 col-sm-offset-4 col-md-offset-3">',
                                'after' => ' <input type="reset" class="btn btn-default" value="' . __('Cancel') . '"></div>',
                            ));
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php echo $this->Form->end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>

