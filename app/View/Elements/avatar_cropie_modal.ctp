<div class="modal fade" id="avatar_cropie_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Avatar') ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="file" id="upload-image" accept="image/*">
                    <div id="croppie-modal-alert" class="msg-report"></div>
                </div>
                <div id="upload-image-demo"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="bind_image_demo()">Ok</button>
            </div>
        </div>
    </div>
</div>