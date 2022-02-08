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
            'key_tab' => 'bonus',
            'title_tab' => __('bonus'),
            'option_tab' => 'sort'
        ),
        array(
            'key_tab' => 'quarters',
            'title_tab' => __('quarters'),
            'option_tab' => 'sort'
        ),
        array(
            'key_tab' => 'year',
            'title_tab' => __('year'),
            'option_tab' => 'sort'
        ),


        array(
            'key_tab' => 'office',
            'title_tab' => __('office'),
            'option_tab' => 'sort'
        ),
        array(
            'key_tab' => 'company',
            'title_tab' => __('company'),
            'option_tab' => 'sort'
        ),

    );
    ?>
    <?php echo $this->grid->create($data['employees'], null, $option); ?>
    <?php foreach ($data['employees'] as $employee): ?>
        <tr>
            <td><?php echo $employee['BonusQuarters']['id'] ?></td>
            <td>

<!--                <a-->
<!--                        href="--><?php //echo Router::url('/', true)."/admin/point_headers/ajax_point_detail_view/".$employee['PointHeader']['id'] ?><!--" data-toggle="modal" data-target="#point_detail_view">-->
                    <?php
                    echo h($employee['Employee']['name'])
                    ?>
<!--                </a>-->
            </td>
            <td><?php echo $employee['BonusQuarters']['bonus'] ?></td>
            <td><?php echo $employee['BonusQuarters']['quarters'] ?></td>
            <td><?php echo $employee['BonusQuarters']['year'] ?></td>

            <td><?php echo !empty($employee['Office']) ? h($employee['Office']['name']) : '' ?></td>
            <td><?php echo !empty($employee['Company']) ? h($employee['Company']['name']) : '' ?></td>
        </tr>
    <?php endforeach ?>

    <?php echo $this->grid->end_table($data['employees'], null, $option); ?>
</div>