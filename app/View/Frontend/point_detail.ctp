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
            <?php echo __('四半期獲得ポイント') ?>
            <span class="small">
				<?php echo date('Y') . __('年') . date('m') . __('月') . date('d') . __('日') . ' ' . __('現在') ?>
			</span>
        </h4>

    </div>
    <p></p>
    <div class="container-body">

        <?php foreach ($listAllPoint as $_point): ?>
            <div class="row mb10">
                <div class="col-xs-12">
                    <?php echo $_point['PointDetail']['date'] ?>
                    <?php echo number_format($_point['PointDetail']['value'], 0, '.', ',') ?> <?php echo __('pt') ?>
                </div>
            </div>
        <?php endforeach ?>

        <div class="clearfix">
        </div>
    </div>
</div>