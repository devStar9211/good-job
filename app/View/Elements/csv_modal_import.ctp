<div class="modal fade" id="<?php echo $id ?>" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('CSV Import') ?></h4>
            </div>
            <div class="modal-body">
                <?php
                    echo $this->Form->create('CsvUpload', array(
                        'inputDefaults' => array(
                            'div' => false,
                            'label' => false
                        ),
                        'url' => array(
                            'controller' => isset($controller) ? $controller : '',
                            'action' => isset($action) ? $action : ''
                        ),
                        'role' => 'form'
                    ));
                ?>
                <div class="msg-modal-report"></div>
                <div class="form-group">
                    <?php echo $this->Form->input('file.', array('type' => 'file', 'accept' => 'text/csv, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel', 'role' => 'file_upload', 'multiple' => isset($multiple) ? $multiple : null)) ?>
                    <?php
                        if(isset($attachment)) {
                            foreach($attachment as $key => $item) {
                                echo $this->Form->input($item['name'], array('type' => 'hidden', 'hidden' => 'hidden', 'value' => $item['value'], 'data-alias' => $item['data-alias']));
                            }
                        }
                    ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->button(__('アップロード'), array('type' => 'button', 'class' => 'btn btn-primary csv_modal_btn_upload', 'onclick' => 'csv_upload(\'#'.$id.'\')')) ?>
                </div>
                <?php echo $this->Form->end() ?>
                <div class="clearfix" role="progress-wrap">
                    <div class="progress g-progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;">
                            0% Complete
                        </div>
                    </div>
                </div>
                <div class="clearfix file-log">
                    <div><?php echo __('files') ?>:&nbsp;<span class="num-files">0</span></div>
                    <div><?php echo __('success') ?>:&nbsp;<span class="num-success success">0</span></div>
                    <div><?php echo __('failure') ?>:&nbsp;<span class="num-failure danger">0</span></div>
                </div>
                <div class="clearfix mt10">
                    <div class="error-log warning"></div>
                    <div class="grippie"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('close') ?></button>
            </div>
        </div>
    </div>
</div>