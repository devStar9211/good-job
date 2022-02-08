<?php
$this->start('css');
echo $this->Html->css([
    '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
    '/assets/components/morris/morris.css',
    '/assets/components/morris/prettify.min.css',
    '/assets/css/g_css.css'
]);
$this->end();
$data = isset($data) ? $data : array();
?>
<div class="my-container container">
    <div class="container-header">
        <h4 class="no-margin bold list-header has-border">
            <?php echo __('Cumulative earned points') ?>
            <span class="small">
				<?php echo date('Y') . __('年') . date('m') . __('月') . date('d') . __('日') . ' ' . __('現在') ?>
			</span>
        </h4>

    </div>
    <p></p>
    <div class="container-body">
        <?php
        if (!empty($earned_points_earch_quarter['detail'])) {
            foreach ($earned_points_earch_quarter['detail'] as $_point_detail) {
                ?>
                <div class="col-xs-12">
                    <?php echo $_point_detail; ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>