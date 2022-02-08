<?php
$this->start('css');
echo $this->Html->css([
    '/assets/components/responsive-table/responsive-tables.css',
    '/assets/css/v_css.css'
]);
$this->end();

$data = isset($data) ? $data : array();
?>
<?php $this->start('script') ?>
<?php
echo $this->Html->script([
    '/assets/components/responsive-table/responsive-tables.js',
    '/assets/js/g_script/g_validate.js',
    '/assets/js/g_script/g_script.js',
    '/assets/js/g_script/g_back.js',
    '/assets/js/price.js'
]);
?>

<?php $this->end() ?>
<div class="expense-sales-container">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $title_for_layout ?></h3>
        </div>
        <div class="box-body">
            <?php
            echo $this->Form->create('PastHighestSale', array(
                'url' => array(
                    'controller' => 'BudgetSales',
                    'action' => 'admin_past_highest_sales'
                ),
                'method' => 'post',
                'inputDefaults' => array(
                    'div' => false,
                    'label' => false
                ),
                'autocomplete' => 'off',
                'data-validate' => '1',
                'data-source' => $this->Html->url(array('controller' => 'BudgetSales', 'action' => 'admin_ajax_past_highest_sales_data'))
            ));
            ?>
             <div class="msg-report"><?php echo $this->element('flash-message') ?></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo $this->Form->select('company', $companies, array('empty' => __('会社'), 'default' => (isset($data['company_id']) ? $data['company_id'] : ''), 'class' => 'form-control select2', 'onchange' => 'generateOffices(this)', 'name' => 'company_id', 'role' => 'company_id')) ?>
                    </div>
                </div>
            </div>

            <div class="table-wrap dataTables_wrapper form-inline dt-bootstrap">
                <div class="loader" style="display:none;"><div class="text-loader">Loading</div></div>
                <div class="mh55" id="last_highest_sales">
                    <?php echo $this->element('past_highest_sales_table', array('data' => $data)) ?>
                </div>
            </div>
            <div class="clearfix group-btn">
                <?php
                echo $this->Form->button(__('保存'), array('type' => 'submit', 'class' => 'btn btn-primary btn-md'));
                echo $this->Form->button(__('入力キャンセル'), array('type' => 'button', 'class' => 'btn btn-primary btn-md', 'onclick' => 'need_confirm(\'generateOffices\',\'warning\',\''.__('保存していない項目は削除されます。よろしいですか。').'\',this)'));
                ?>
            </div>
            <?php echo $this->Form->end() ?>
        </div>
    </div>
</div>

<?php $this->start('script') ?> 
    <script type="text/javascript">
        function generateOffices(node) {
            var form = $(node).parents('form');
            var company_id = form.find('[role="company_id"]').val();
            var filter = [];
            var path_filter = '';
            var search = '';


            if (company_id) {
                filter.push('company=' + company_id);
            }
            filter.forEach(function (element, index) {
                path_filter += (element + (filter.length - 1 == index ? '' : '&'));
            });
            search = window.location.search;
            search = (
                search
                    .replace(/^\?company=[^\&]*\&/ig, '\?').replace(/\&company=[^\&]*\&/ig, '\&').replace(/\&?company=[^\&]*$/ig, '')
            );
            if (search.replace(/^\?/, '').length && path_filter.length) {
                path_filter = '\&' + path_filter;
            }
            window.location.search = search + path_filter;
        }
    </script> 
<?php $this->end() ?>