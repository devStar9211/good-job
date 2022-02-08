<?php
$this->start('css');
echo $this->Html->css([
    '/assets/css/g_css.css'
]);
echo $this->Html->css([
    '/assets/components/responsive-table/responsive-tables.css',
    '/assets/css/v_css.css'
]);
$this->end();

$this->start('script');
echo $this->Html->script([
    '/assets/components/responsive-table/responsive-tables.js',
    '/assets/js/g_script/g_validate.js',
    '/assets/js/g_script/g_script.js',

]);
$this->end();
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $title_for_layout ?></h3>
    </div>
    <div class="box-body">
        <?php
        echo $this->Form->create('SettingApi', array(
            'url' => array(
                'controller' => 'setting_api',
                'action' => 'index'
            ),
            'method' => 'post',
            'inputDefaults' => array(
                'div' => false,
                'label' => false
            ),
            'autocomplete' => 'off',
            'data-validate' => '1',
            'data-source' => ''

        ));
        ?>
        <?php echo $this->element('flash-message') ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo $this->Form->select('company', $companies, array('empty' => __('会社'), 'default' => (isset($data['company_id']) ? $data['company_id'] : ''), 'class' => 'form-control select2', 'id' => 'company_id', 'onchange' => 'generateOffice(this);', 'role' => 'company_id')) ?>

                </div>
            </div>
        </div>

        <div class="table-wrap dataTables_wrapper form-inline dt-bootstrap">
            <div class="loader" style="display:none;">
                <div class="text-loader">Loading</div>
            </div>
            <div class="mh55" id="budget_revenue_tb" role="tb">
                <?php echo $this->element('setting_api_input_office_id_table', array('data' => $data)) ?>
            </div>
        </div>


        <div class="clearfix group-btn">
            <?php
            echo $this->Form->button(__('保存'), array('type' => 'submit', 'class' => 'btn btn-primary btn-md'));
            echo $this->Form->button(__('入力キャンセル'), array('type' => 'button', 'class' => 'btn btn-primary btn-md', 'onclick' => 'need_confirm(\'generateMonths\',\'warning\',\'' . __('保存していない項目は削除されます。よろしいですか。') . '\',this)'));
            ?>
        </div>
        <?php echo $this->Form->end() ?>

    </div>
</div>


<?php $this->start('script') ?> 
<script type="text/javascript">
    function generateOffice(node) {
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
