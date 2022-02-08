<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?=$title_for_layout?></h3>
    </div>
    <?php echo $this->Form->create('Config'); ?>
    <div class="box-body">
        <?php echo $this->element('flash-message'); ?>
        <?php
        echo $this->Form->input('value',  array('type' => 'text', 'label' => array('text' => __('number posts'),'class'=>'control-label col-xs-12 col-sm-2')));
        ?>
    </div>
    <div class="box-footer clearfix">
        <?php echo $this->Form->submit(__('save'));?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>