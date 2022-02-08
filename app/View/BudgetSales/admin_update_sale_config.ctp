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
        // for revenue budget only
        $('#yearSelect').datetimepicker({
            locale: 'ja',
            viewMode: 'years',
            format: 'YYYY',
            minDate: moment('2000-01-01'),
            maxDate: moment('2050-12-31')
        }).on('dp.change', function (e) {
            generateMonthList(this);
        });

        function generateMonthList(node) {
            var year= $(node).val();
            var filter = [];
            var path_filter = '';
            if(year) {
                filter.push('year='+ year);
            }
            filter.forEach( function(element, index) {
                path_filter += (element + (filter.length - 1 == index ? '' : '&'));
            });
            search = window.location.search;
            search = (
                search
                    .replace(/^\?year=[^\&]*\&/ig,'\?').replace(/\&year=[^\&]*\&/ig,'\&').replace(/\&?year=[^\&]*$/ig,'')
            );
            if(search.replace(/^\?/,'').length && path_filter.length) { path_filter = '\&'+ path_filter; }
            window.location.search = search + path_filter;
        }

    </script>
<?php $this->end() ?>

    <div class="page-update-sale-config container-fluid">
        <div class="msg-report"><?php echo $this->element('flash-message') ?></div>
        <div class="row">

            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header">
                        <legend class="no-margin">
                            <h3 class="box-title"><?php echo $title_for_layout; ?></h3>
                        </legend>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class='input-group date'>
                                        <?php echo $this->Form->input('year', array('type' => 'text', 'value' => (isset($year) ? $year : ''), 'class' => 'form-control', 'id' => 'yearSelect', 'placeholder' => __('年度'), 'role' => 'date', 'label' => false, 'div' => false)) ?>
                                        <label for="yearSelect" class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p>チェックマークを付けた月は、“ほのぼの“から取得した売上数値を、日次決算の売上に反映します。</p>
                                <br>
                            </div>
                        </div>

                        <?php
                        echo $this->Form->create('Config', array(
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
                        <div class="row month-list">
                            <?php
                            for ($i = 1; $i <= 12; $i++) { ?>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <?php
                                        echo $this->Form->input('Config.'.$year.'.'.$i, array('type' => 'checkbox', 'class' => '', 'label' => array('text' => $year.'年' . $i . '月', 'class' => 'control-label '), 'checked' => isset($flag_update_sale[$year][$i]) && $flag_update_sale[$year][$i]==1 ? 'true' : ''));
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>


                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <?php echo $this->Form->button(__('save'), array('type' => 'submit', 'class' => 'btn btn-primary')) ?>
                            </div>
                        </div>
                        <?php echo $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
