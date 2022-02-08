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
            'key_tab' => 'name',
            'title_tab' => __('name'),
            'option_tab' => 'sort'
        ),
        array(
            'key_tab' => 'point',
            'title_tab' => __('Point'),
            'option_tab' => 'sort'
        ),
    );
    ?>
    <?php echo $this->grid->create($data['employees'], null, $option); ?>
    <?php foreach ($data['employees'] as $employee):  ?>
        <tr>
            <td><?php echo $employee['Employee']['id'] ?></td>
            <td>
                <a href="<?php echo Router::url('/', true)."/admin/point_details/ajax_point_detail_view/".$employee['Employee']['id'] .'?date='.$data['date']  ?>" data-toggle="modal" data-target="#point_detail_view">
                    <?php
                    echo h($employee['Employee']['name'])
                    ?>
                </a>
            </td>
            <td class="format_number_text"><?php echo $employee['Employee']['point_total'] ?></td>
        </tr>
    <?php endforeach ?>

    <?php echo $this->grid->end_table($data['employees'], null, $option); ?>
</div>