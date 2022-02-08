<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $title_for_layout ?></h3>

    </div>
    <div class="box-body">

        <div class="table-wrap clearfix mh55" role="tb-wrap">
            <div class="clearfix">
                <?php
                $option = array();
                $option['title'] = 'Office list';
                $option['col'] = array(
                    array(
                        'key_tab' => 'id',
                        'title_tab' => __('#'),
                        'option_tab' => 'sort',
                        'style' => 'width: 40px;'
                    ),
                    array(
                        'key_tab' => 'point tye',
                        'title_tab' => __('name'),
                        'option_tab' => 'sort'
                    ),
                    array(
                        'key_tab' => 'value',
                        'title_tab' => __('point'),
                        'option_tab' => 'sort'
                    ),
                    array(
                        'key_tab' => 'created',
                        'title_tab' => __('created'),
                        'option_tab' => 'sort'
                    ),


                );
                ?>
                <?php echo $this->grid->create($data, null, $option); ?>
                <?php foreach ($data as $_point_detail): ?>
                    <tr>
                        <td><?php echo h($_point_detail['PointDetail']['id']) ?></td>
                        <td>
                            <?php
                            echo h($_point_detail['PointType']['name']);
                            ?>
                        </td>
                        <td><?php echo $_point_detail['PointDetail']['value'] ?></td>
                        <td><?php echo !empty($_point_detail['PointDetail']['created']) ? date('Y-m-d', strtotime($_point_detail['PointDetail']['created'])) : '' ?></td>

                    </tr>
                <?php endforeach ?>

                <?php echo $this->grid->end_table($data, null, $option); ?>
            </div>
        </div>
    </div>
</div>