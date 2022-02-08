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
    '/assets/js/v_script/point.js'
]);
$this->end();
?>
    <div class="revenue-budget-container">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $title_for_layout ?></h3>
                <div class="pull-right box-tools">
                    <div class="btn-group">
                        <?php

                        echo $this->Html->link(__('CSVアップロード'), array('controller' => 'point_bonus', 'action' => 'admin_csv_import'), array('class' => 'btn btn-primary'));

                        ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">

                        <?php echo $this->Html->link(__(' Autorun calculator Bonus Quarter all of Employee'), '/bonus_quarters/auto_calculator_bonus_all'); ?>
                    </div>
                </div>
                <p></p>
                
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
                            <?php echo $this->Form->select('company', $companies, array('empty' => __('会社'), 'default' => (isset($data['company_id']) ? $data['company_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'points_bonus_filter_employee(this)', 'data-office-node' => '#office_id', 'data-source' => $this->Html->url(array('controller' => 'Offices', 'action' => 'admin_generate_list_offices')), 'role' => 'company_id')) ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo $this->Form->select('office', $offices, array('empty' => __('事業所'), 'default' => (isset($data['office_id']) ? $data['office_id'] : ''), 'class' => 'form-control select2', 'id' => 'office_id', 'role' => 'office_id', 'onchange' => 'points_bonus_filter_employee(this)')) ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class='input-group date'>
                                <?php echo $this->Form->select('quarters', $quarters, array('empty' => __('Select quarters'), 'class' => 'form-control select2', 'id' => 'quarter', 'role' => 'quarter', 'onchange' => 'points_bonus_filter_employee(this)', 'value' => (isset($data['quarter']) ? $data['quarter'] : ''))); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class='input-group date'>
                                <?php
                                echo $this->Form->input('year', array('type' => 'text', 'class' => 'form-control', 'id' => 'yearSelect', 'placeholder' => __('年度'), 'role' => 'date', 'value' => (isset($data['date']) ? $data['date'] : ''), 'onchange' => 'points_bonus_filter_employee(this)' )) ?>
                                <label for="getYearCalender" class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <?php echo $this->Form->end() ?>
                <div class="table-wrap dataTables_wrapper form-inline dt-bootstrap">
                    <div class="loader" style="display:none;">
                        <div class="text-loader">Loading</div>
                    </div>
                    <div class="mh55" id="budget_revenue_tb" role="tb">
                        <?php echo $this->element('point_bonus_list_employee_table', array('data' => $data)) ?>
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

<?php $this->start('script') ?>

    <script type="text/javascript">
        function points_bonus_filter_employee(node) {
            var form = $(node).parents('form');
            var company_id = form.find('[role="company_id"]').val();
            var office_id = form.find('[role="office_id"]').val();
            var quarter = form.find('[role="quarter"]').val();
            var date = form.find('[role="date"]').val();
            var filter = [];
            var path_filter = '';
            var search = '';
            if (company_id) {
                filter.push('company=' + company_id);
                if (office_id) {
                    filter.push('office=' + office_id);
                }
            }
            if (date) {
                filter.push('date=' + date);
            }
            if (quarter) {
                filter.push('quarter=' + quarter);
            }

            filter.forEach(function (element, index) {
                path_filter += (element + (filter.length - 1 == index ? '' : '&'));
            });

            search = window.location.search;
            search = (
                search
                    .replace(/^\?company=[^\&]*\&/ig, '\?').replace(/\&company=[^\&]*\&/ig, '\&').replace(/\&?company=[^\&]*$/ig, '')
                    .replace(/^\?office=[^\&]*\&/ig, '\?').replace(/\&office=[^\&]*\&/ig, '\&').replace(/\&?office=[^\&]*$/ig, '')
                    .replace(/^\?quarter=[^\&]*\&/ig, '\?').replace(/\&quarter=[^\&]*\&/ig, '\&').replace(/\&?quarter=[^\&]*$/ig, '')
                    .replace(/^\?date=[^\&]*\&/ig, '\?').replace(/\&date=[^\&]*\&/ig, '\&').replace(/\&?date=[^\&]*$/ig, '')
            );

            if (search.replace(/^\?/, '').length && path_filter.length) {
                path_filter = '\&' + path_filter;
            }

            window.location.search = search + path_filter;
        }

        function generateListOffices(node) {
            var self = node,
                $office = $($(self).data('office-node')),
                source = $(self).data('source'),
                id = $(self).val();

            $office.val('');

            if(id !== '' && source != undefined) {
                $.ajax({
                    url: source,
                    type: 'POST',
                    dataType: 'json',
                    data: {company_id: id},
                })
                    .done(function(res, status, xhr) {
                        if(res.status) {
                            var data = res.data;
                            $office.html($office.find('option:first-child'));
                            data.forEach(function(elm, index) {
                                $office.append($('<option value="'+ elm[0] +'">'+ elm[1] +'</option>'));
                            });
                        }
                    });
            } else {
                $office.html($office.find('option:first-child'));
            }
        }


        $(document).ready(function (e) {
            // for  year conprison
            $('#yearSelect').datetimepicker({
                locale: 'ja',
                viewMode: 'years',
                format: 'YYYY',
                minDate: moment('2000-01-01'),
                maxDate: moment('2050-12-31')
            }).on('dp.change', function(e) {
                points_bonus_filter_employee(this);
            });
        });






    </script>
<?php $this->end() ?>