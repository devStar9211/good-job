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
    );
    ?>

    <?php echo $this->grid->create($data['employees'], null, $option); ?>
    <?php foreach ($data['employees'] as $employee): //pr($employee);die; ?>
        <tr>
            <td><?php echo $employee['Employee']['id'] ?></td>
            <td>
                <?php
                echo h($employee['Employee']['name'])
                ?>
            </td>
            <td><?php echo $employee['BonusQuarter']['bonus'] ?></td>
            <td><?php echo $employee['BonusQuarter']['quarter'] ?></td>
            <td><?php echo $employee['BonusQuarter']['year'] ?></td>

        </tr>
    <?php endforeach ?>

    <?php echo $this->grid->end_table($data['employees'], null, $option); ?>


</div>

