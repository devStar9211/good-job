<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title"><?php echo __('Prize detail'); ?></h4>
</div>
<div class="modal-body">
    <div class="clearfix">
        <table class="table table-bordered table-striped dataTable table-hover ">
            <thead>
            <tr>

                <th><?php echo __('name'); ?></th>
                <th><?php echo __('Point'); ?></th>
                <th><?php echo __('date created'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(!empty($data)) {
                foreach ($data as $_point_detail): ?>
                    <tr>

                        <td>
                            <?php
                            echo $_point_detail['PointType']['name'];
                            ?>
                        </td>
                        <td class="format_number_text"><?php echo $_point_detail['PointDetail']['value'] ?></td>
                        <td>
                            <?php
                            $_date = date('Y-m-d',strtotime($_point_detail['PointDetail']['created'])) . ' ' . $_point_detail['PointType']['name'] . '（' . $_point_detail['PointDetail']['month'] . '月）';
                            echo $_date . number_format($_point_detail['PointDetail']['value'], 0, '.', ',') . '<i class="fa">pt</i>';
                            ?>
                        </td>

                    </tr>
                    <?php
                endforeach;
            }else{
            ?>
            <tr>
                <td colspan="10" class="text-center">
                    <?php echo __('Data empty'); ?>
                </td>
            <tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(e) {
        $('.format_number_text').each( function () {
            format_number(this);
        });
    });

</script>

