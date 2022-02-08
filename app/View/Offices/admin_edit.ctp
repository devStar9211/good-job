<?php
$this->start('css');
echo $this->Html->css([
    '/assets/css/v_css.css',
    '/assets/css/g_css.css',
]);
$this->end();

$this->start('script');
echo $this->Html->script([
    // Time picker
    '/assets/js/v_script/v_script.js',
    '/assets/components/jquery-mask/jquery.mask.min.js',
    '/assets/components/bootstrap-formHelper/bootstrap-formhelpers.min.js',
    '/assets/js/g_script/g_script.js',
]);

?>

<script type="text/javascript">
    $(document).ready(function () {
        $('.input-phone').mask('(000) 000-0000', {placeholder: '(000) 000-0000'});
    });
</script>
<?php
$this->end();
?>
<div class="container-fluid">
    <div class="row">
        <?php

        echo $this->Form->create('Office', array(
            'inputDefaults' => array(
                'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
                'div' => array('class' => 'form-group'),
                'label' => array('class' => 'control-label '),
                'between' => '<div class="controls ">',
                'after' => '</div><div class="clearfix"></div>',
                'error' => array('attributes' => array('wrap' => 'label', 'class' => 'control-label', 'before' => '<i class="fa fa-times-circle-o"></i> ')),
                'class' => 'form-control'
            ),
        ));
        echo $this->Form->hidden('id');
        ?>

        <div class="msg-report"><?php echo $this->element('flash-message') ?></div>


        <div class="row row-5">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-header">
                        <legend class="no-margin">
                            <h3 class="box-title"><?php echo $title_for_layout; ?></h3>
                        </legend>
                    </div>
                    <div class="box-body row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <?php
                            echo $this->Form->input("company_group_id", array('div' => 'form-group required', "type" => "select", 'class' => 'form-control select2', 'data-company-node' => '#company_id', 'onchange' => 'generateListCompanies(this)', "options" => $company_groups, 'data-source' => $this->Html->url(array('controller' => 'Offices', 'action' => 'admin_generate_list_companies')), 'label' => array('text' => __('Company group'), 'class' => 'control-label'), 'required' => true));
                            echo $this->Form->input("company_id", array('div' => 'form-group required', "type" => "select", 'class' => 'form-control select2', "id" => "company_id", "options" => $companies, 'required' => true, 'label' => array('text' => __('Company'), 'class' => 'control-label')));
                            echo $this->Form->input("Evaluation.evaluation_id", array('div' => 'form-group', "type" => "select", 'label' => array('text' => __('Evaluation'), 'class' => 'control-label'), 'class' => 'form-control select2', "options" => $evaluations, 'required' => false, 'multiple' => "multiple", 'selected' => $office_evaluations_default));
                            echo $this->Form->input("BusinessCategory.business_category_id", array('div' => 'form-group required', "type" => "select", 'label' => array('text' => __('Business category'), 'class' => 'control-label '), 'class' => 'form-control select2', "options" => $business_categories, 'required' => true, 'multiple' => "multiple", 'selected' => $business_category_default));
                            echo $this->Form->input("division_id", array('div' => 'form-group required', "type" => "select", 'label' => array('text' => __('Division'), 'class' => 'control-label'), 'class' => 'form-control select2', "options" => $divisions, 'required' => true));
                            echo $this->Form->input("office_group_id", array('div' => 'form-group required', "type" => "select", 'label' => array('text' => __('Office group'), 'class' => 'control-label'), 'class' => 'form-control select2', "options" => $office_groups, 'required' => true));
                            echo $this->Form->input('office_number', array('div' => 'form-group required', 'type' => 'number', 'label' => array('text' => __('Office number'), 'class' => 'control-label '), 'required' => true));
                            echo $this->Form->input('name', array('label' => array('text' => __('Name'), 'class' => ''), 'required' => true));
                            ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <?php
                            $prefecture = $this->Data->prefectures();
                            echo $this->Form->input("prefecture", array('div' => 'form-group required', "type" => "select", 'label' => array('text' => __('Prefecture'), 'class' => 'control-label'), 'class' => 'form-control select2', "options" => $prefecture, 'required' => true));
                            echo $this->Form->input('municipal_town', array('div' => 'form-group required', 'label' => array('text' => __('Municipal town'), 'class' => 'control-label '), 'required' => true));
                            echo $this->Form->input('phone', array('label' => array('text' => __('phone number'), 'class' => 'control-label '), 'div' => 'form-group required', 'class' => 'form-control ', 'required' => true));
                            ?>
                            <?php
                            echo $this->Form->input('fax', array('label' => array('text' => __('fax number'), 'class' => 'control-label '), 'required' => false));

                            echo $this->Form->input('day_capacity', array('label' => array('text' => __('Day capacity'), 'class' => 'control-label '), 'required' => false));
                            echo $this->Form->input('max_capacity', array('label' => array('text' => __('Maximum number of people'), 'class' => 'control-label '), 'required' => false));
                            echo $this->Form->input('honobono_office_id', array('type' => 'text', 'label' => array('text' => __('ほのぼのNEXT事業者番号'), 'class' => 'control-label '), 'required' => false));
                            echo $this->Form->input('api_shift_office_id', array('type' => 'text', 'label' => array('text' => __('Shift Office ID'), 'class' => 'control-label '), 'required' => false));

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-5">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="box">
                    <div class="box-body">
                        <?php
                        echo $this->Form->input('remuneration_factor', array('label' => array('text' => __('Remuneration factor'), 'class' => 'control-label '), 'default' => null, 'type' => 'text', 'required' => false, 'onkeydown' => 'numberInput(event)'));
                        echo $this->Form->input('region_classification_factor', array('label' => array('text' => __('Region classification factor'), 'class' => 'control-label '), 'default' => null, 'type' => 'text', 'onkeydown' => 'numberInput(event)', 'required' => false));
                        ?>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-body ">
                        <?php
                        echo $this->Form->input('business_start', array('between' => '<div class="controls " ><div class="input-group date datetimepicker3" id="datetimepicker3">', 'after' => '<div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div></div></div><div class="clearfix"></div>', 'label' => array('text' => __('Business start'), 'class' => 'control-label '), 'required' => false));
                        echo $this->Form->input('business_end', array('between' => '<div class="controls"><div class="input-group datetimepicker3">', 'after' => '<div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div></div></div><div class="clearfix"></div>', 'label' => array('text' => __('Business end'), 'class' => 'control-label'), 'required' => false));
                        echo $this->Form->input('service_start', array('between' => '<div class="controls "><div class="input-group datetimepicker3">', 'after' => '<div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div></div></div><div class="clearfix"></div>', 'label' => array('text' => __('Service start'), 'class' => 'control-label'), 'required' => false));
                        echo $this->Form->input('service_end', array('between' => '<div class="controls"><div class="input-group datetimepicker3">', 'after' => '<div class="input-group-addon"><i class="glyphicon glyphicon-time"></i></div></div></div><div class="clearfix"></div>', 'label' => array('text' => __('Service end'), 'class' => 'control-label'), 'required' => false));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="box box-default ">
                    <div class="box-header">
                        <legend class="no-margin">
                            <h3 class="box-title"><?php echo __('Paid service unit price (for schedule calculation)'); ?></h3>
                        </legend>
                    </div>
                    <div class="box-body ">
                        <div class=" office_additions">
                            <div class="group-field">
                                <?php
                                if (!empty($this->request->data['OfficeSelfPaid'])) {
                                    foreach ($this->request->data['OfficeSelfPaid'] as $key => $_item) {
//                                    pr($this->request->data['OfficeSelfPaid']);die;
                                        ?>
                                        <div class="item_additions form-group">
                                            <label class=" ">自費料金単価<?php echo $key + 1 ?></label>
                                            <div class="row row-5">
                                                <?php
                                                echo $this->Form->input('OfficeSelfPaid.' . ($key + 1) . '.name', array('type' => 'text', 'between' => '', 'after' => '', 'div' => 'col-md-7 col-sm-6 col-xs-6', 'label' => false, 'placeholder' => __('Name'), 'value' => $_item['name'], 'required' => false));
                                                ?>
                                                <?php
                                                echo $this->Form->input('OfficeSelfPaid.' . ($key + 1) . '.price', array('type' => 'number', 'between' => '', 'after' => '', 'div' => 'col-md-4 col-sm-4 col-xs-4', 'label' => false, 'placeholder' => __('Price'), 'value' => $_item['price'], 'required' => false));
                                                ?>
                                                <a class="col-md-1 col-sm-2 col-xs-2 btn btn-default btn-remove">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div class="item_additions form-group">
                                        <label class=" "><?php echo __('自費料金単価%d', 1) ?></label>
                                        <div class="row row-5">
                                            <?php
                                            echo $this->Form->input('OfficeSelfPaid.1.name', array('type' => 'text', 'between' => '', 'after' => '', 'div' => 'col-md-7 col-sm-6 col-xs-6', 'label' => false, 'placeholder' => __('Name'), 'required' => false));
                                            ?>
                                            <?php
                                            echo $this->Form->input('OfficeSelfPaid.1.price', array('type' => 'number', 'between' => '', 'after' => '', 'div' => 'col-md-4 col-sm-4 col-xs-4', 'label' => false, 'placeholder' => __('Price'), 'required' => false));
                                            ?>
                                            <a class="col-md-1 col-sm-2 col-xs-2 btn btn-default btn-remove">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <a class="col-md-1 btn btn-default btn-append">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="box box-default ">
                    <div class="box-body ">
                        <?php
                        echo $this->Form->input('display_on_shift', array('type' => 'checkbox', 'label' => array('text' => __('View in shift management system'), 'class' => 'control-label '), 'required' => false, 'class'=>''));
                        echo $this->Form->input('display_in_budget_ranking', array('type' => 'checkbox', 'label' => array('text' => __('Display in budget ranking'), 'class' => 'control-label '), 'required' => false, 'class'=>''));

                        $attributes=array('legend'=>false,'div' => 'col-md-7 col-sm-6 col-xs-6');
                        ?>
                        <div class="form-group radio-input">
                            <?php
                                echo $this->Form->radio("sortable", $sortable, $attributes);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        echo $this->Form->submit(__('Save'));
        ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
