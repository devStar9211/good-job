<?php
$header_1 = array(
    array(__('&nbsp;') => array('rowspan' => 2, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 35px')),
    array(__('office').'('.$month.'月)' => array('rowspan' => 2, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 120px')),
);
$header_2 = array();
$header2Map = array(
    'col_gr_1' => array(__('sales') => array('rowspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    'col_gr_2' => array(__('operating income') => array('rowspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    'col_gr_3' => array(__('expense') => array('rowspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
);
$header_group = array(
    'col_gr_1' => array(COL_1, COL_2, COL_3, COL_4, COL_5, COL_6),
    'col_gr_2' => array(COL_7, COL_8, COL_9, COL_10, COL_11),
    'col_gr_3' => array(COL_12, COL_13, COL_14, COL_15, COL_20),
);
$headerMap = array(
    COL_1 => array(__('highest ever sales') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 110px')),
    COL_2 => array(__('sales target') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_3 => array(__('revenue sales') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_4 => array(__('budget achievement rate') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_5 => array(__('current year') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_6 => array(__('cumulative percent complete') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),


    COL_7 => array(__('operating profit target') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 110px')),
    COL_8 => array(__('profit sales') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_9 => array(__('operating income achievement rate') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_10 => array(__('current year') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_11 => array(__('cumulative percent complete') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),


    COL_12 => array(__('personnel expenses') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_13 => array(__('overtime') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_14 => array(__('budget contrast') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_15 => array(__('contrast with last year') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),


//    COL_16 => array(__('occupancy rate') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style'=>'min-width: 100px')),
//    COL_17 => array(__('average care level') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style'=>'min-width: 100px')),
//    COL_18 => array(__('number of users') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style'=>'min-width: 100px')),

//    COL_19 => array(__('stay') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
    COL_20 => array(__('other expenses') => array('rowspan' => 1, 'colspan' => 1, 'class' => 'text-center', 'style' => 'min-width: 100px')),
);
$last_key_gr = '';

foreach ($headerMap as $key => $col) {
    if (!empty($gridConfig) && in_array($key, $gridConfig)) {
        array_push($header_2, $col);
        foreach ($header_group as $key_gr => $_group) {
            if (in_array($key, $_group) && $key_gr != $last_key_gr) {
                $last_key_gr = $key_gr;
                $col_group_matching = count(array_intersect($_group, $gridConfig));
                $header2Map[$key_gr][key($header2Map[$key_gr])]['colspan'] = $col_group_matching;
                array_push($header_1, $header2Map[$key_gr]);
            }
        }
    }
}
?>
<table id="tb-daily" class="table table-bordered table-hover dataTable lightgray responsive" data-collumn='2'
       data-fixed="155px" data-mobile_fixed="155">
    <thead>
    <?php echo $this->Html->tableHeaders($header_1, array('class' => 'lightgray')); ?>
    <?php echo $this->Html->tableHeaders($header_2, array('class' => 'lightgray')); ?>
    </thead>
    <tbody>
    <?php
    if (!empty($data)) {
        foreach ($data as $group => $office) {
            foreach ($office['office'] as $data_row) {

                $content = array(
                    array(
                        $data_row['ranking'],
                        array(
                            'colspan' => 1,
                            'class' => 'text-center',
                            'style' => !empty($gridConfigColor) ? 'background-color: ' . $gridConfigColor['company'][$data_row['company']['id']] : ''
                        )
                    ),
                    array(
                        $data_row['office']['name'],
                        array('colspan' => 1,
                            'class' => 'text-left',
                            'style' => !empty($gridConfigColor) ? 'background-color: ' . $gridConfigColor['company'][$data_row['company']['id']] : ''
                        )
                    )
                );
                $contentMap = array(
                    COL_1 => array(
                        !empty($data_row['past_highest_sale']['sale']) ? number_format($data_row['past_highest_sale']['sale'], 0, '.', ',') : '&nbsp;',
                        array('colspan' => 1, 'class' => 'text-right ')
                    ),
                    COL_2 => array(
                        !empty($data_row['revenue']['budget']) ? number_format($data_row['revenue']['budget'], 0, '.', ',') : '0',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),
                    COL_3 => array(
                        !empty($data_row['revenue']['sales']) ? number_format($data_row['revenue']['sales'], 0, '.', ',') : '0',
                        array('colspan' => 1, 'class' => 'text-right ')
                    ),
                    COL_4 => array(
                        $data_row['revenue']['rates'] > 0 ? number_format($data_row['revenue']['rates'], 1, '.', ',') . '%' : '-',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),
                    COL_5 => array(
                        !empty($data_row['revenue']['current_year_sales']) ? number_format($data_row['revenue']['current_year_sales'], 0, '.', ',') : '0',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),
                    COL_6 => array(
                        $data_row['revenue']['current_year_rates'] > 0 ? number_format($data_row['revenue']['current_year_rates'], 1, '.', ',') . '%' : '-',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),

                    COL_7 => array(
                        !empty($data_row['profit']['last-month']['budget']) ? number_format($data_row['profit']['last-month']['budget'], 0, '.', ',') : '0',
                        array('colspan' => 1, 'class' => 'text-right ')
                    ),
                    COL_8 => array(
                        !empty($data_row['profit']['last-month']['sales']) ? number_format($data_row['profit']['last-month']['sales'], 0, '.', ',') : '0',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),
                    COL_9 => array(
                        ($data_row['profit']['last-month']['rates'] > 0) ? number_format($data_row['profit']['last-month']['rates'], 1, '.', ',') . '%' : '-',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),
                    COL_10 => array(
                        !empty($data_row['profit']['last-month']['current_year_sales']) ? number_format($data_row['profit']['last-month']['current_year_sales'], 0, '.', ',') : '0',
                        array('colspan' => 1, 'class' => 'text-right')),
                    COL_11 => array(
                        ($data_row['profit']['last-month']['current_year_rates'] > 0) ? number_format($data_row['profit']['last-month']['current_year_rates'], 1, '.', ',') . '%' : '-',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),

                    COL_12 => array(
                        !empty($data_row['labor_cost']['sales']) ? number_format($data_row['labor_cost']['sales'], 0, '.', ',') : '0',
                        array('colspan' => 1, 'class' => 'text-right', 'style' => '')
                    ),
                    COL_13 => array(
                        !empty($data_row['labor_cost']['sales_overtime']) ? number_format($data_row['labor_cost']['sales_overtime'], 0, '.', ',') : '0',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),
                    COL_14 => array(
                        ($data_row['labor_cost']['rates'] > 0) ? number_format($data_row['labor_cost']['rates'], 1, '.', ',') . '%' : '-',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),
                    COL_15 => array(
                        !empty($data_row['labor_cost']['past-year-compare']) ? number_format($data_row['labor_cost']['past-year-compare'], 1, '.', ',') . '%' : '0',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),

//                    COL_16 => array(
//                        !empty($data_row['labor_cost']['rate_of_operation']) ? number_format($data_row['labor_cost']['rate_of_operation'], 1, '.', ',') . '%' : '',
//                        array('colspan' => 1, 'class' => 'text-right')
//                    ),
//                    COL_17 => array(
//                        !empty($data_row['labor_cost']['avg_nursing_care_level']) ? number_format($data_row['labor_cost']['avg_nursing_care_level'], 1, '.', ',') : '',
//                        array('colspan' => 1, 'class' => 'text-right')
//                    ),
//                    COL_18 => array(
//                        !empty($data_row['labor_cost']['number_of_user']) ? number_format($data_row['labor_cost']['number_of_user'], 1, '.', ',') : '',
//                        array('colspan' => 1, 'class' => 'text-right')
//                    ),

//                    COL_19 => array(
//                        '',
//                        array('colspan' => 1, 'class' => 'text-right')
//                    ),

                    COL_20 => array(
                        !empty($data_row['labor_cost']['total_expense']) ? number_format($data_row['labor_cost']['total_expense'], 0, '.', ',') : '0',
                        array('colspan' => 1, 'class' => 'text-right')
                    ),

                );
                foreach ($contentMap as $key => $col) {

                    $style = $col[0] < 0 && $data_row['office']['sortable'] != 3 && $data_row['office']['id'] != 22 ? 'color: red;' : '';
                    $style .= (isset($gridConfigColor['collumn'][$key]) && !empty( $gridConfigColor['collumn'])) ? ';background-color:'.$gridConfigColor['collumn'][$key] : '';
                    $col[1]['style'] = $style;

                    if (!empty($gridConfig) && in_array($key, $gridConfig)) array_push($content, $col);
                }


                echo $this->Html->tableCells(array($content));
            }

            if (

                !empty($office['office'])
                || $group == $last_row
            ) {
                $i = 0;
                $row_total = sizeof($office['summary']);
                foreach ($office['summary'] as $summary) {
                    $i++;
                    if ($i == $row_total && $group == $last_row) $style = null;
                    $summaryData = array(
                        array(
                                '&#65279;',
                                array(
                                    'colspan' => 1,
                                    'class' => 'text-right lightgray no-border-right',
                                    'style'=>
                                        !empty($gridConfigColor) ? 'background-color: '. $gridConfigColor['company'][$data_row['company']['id']] .';border-right-color: ' . $gridConfigColor['company'][$data_row['company']['id']] . ' !important;' : ''
                                )
                        ),
                        array(
                                $summary['alias'],
                                array(
                                    'colspan' => 1,
                                    'class' => 'text-right lightgray',
                                    'style'=> !empty($gridConfigColor) ? 'background-color: '. $gridConfigColor['company'][$data_row['company']['id']] : ''
                                )
                        ),
                    );
                    $summaryMap = array(
                        COL_1 => array(
                            '&nbsp;',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_2 => array(
                            !empty($summary['data']['revenue']['budget']) ? number_format($summary['data']['revenue']['budget'], 0, '.', ',') : '0',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_3 => array(
                            !empty($summary['data']['revenue']['sales']) ? number_format($summary['data']['revenue']['sales'], 0, '.', ',') : '0',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_4 => array(
                            ($summary['data']['revenue']['rates'] != 0) ? number_format($summary['data']['revenue']['rates'], 1, '.', ',') . '%' : '-',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_5 => array(
                            !empty($summary['data']['revenue']['current_year_sales']) ? number_format($summary['data']['revenue']['current_year_sales'], 0, '.', ',') : '0',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_6 => array(
                            ($summary['data']['revenue']['current_year_rates'] > 0) ? number_format($summary['data']['revenue']['current_year_rates'], 1, '.', ',') . '%' : '-',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_7 => array(
                            !empty($summary['data']['profit']['last-month']['budget']) ? number_format($summary['data']['profit']['last-month']['budget'], 0, '.', ',') : '0',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_8 => array(
                            !empty($summary['data']['profit']['last-month']['sales']) ? number_format($summary['data']['profit']['last-month']['sales'], 0, '.', ',') : '0',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_9 => array( !empty($summary['data']['profit']['last-month']['rates']) ? ($summary['data']['profit']['last-month']['budget'] < 0 && $summary['data']['profit']['last-month']['sales']) || ($summary['data']['profit']['last-month']['budget'] < 0 && $summary['data']['profit']['last-month']['sales'] < 0) > 0 ? '-' : number_format($summary['data']['profit']['last-month']['rates'], 1, '.', ',') . '%' : '-',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),

                        COL_10 => array(
                            !empty($summary['data']['profit']['last-month']['current_year_sales']) ? number_format($summary['data']['profit']['last-month']['current_year_sales'], 0, '.', ',') : '0',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_11 => array(
                            ($summary['data']['profit']['last-month']['current_year_rates'] > 0) ? number_format($summary['data']['profit']['last-month']['current_year_rates'], 1, '.', ',') . '%' : '-',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),

                        COL_12 => array(
                            !empty($summary['data']['labor_cost']['sales']) ? number_format($summary['data']['labor_cost']['sales'], 0, '.', ',') : '0',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_13 => array(
                            !empty($summary['data']['labor_cost']['sales_overtime']) ? number_format($summary['data']['labor_cost']['sales_overtime'], 0, '.', ',') : '0',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_14 => array(
//                            ($summary['data']['labor_cost']['rates'] > 0) ? number_format($summary['data']['labor_cost']['rates'], 1, '.', ',') . '%' : '-',
                            '',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),
                        COL_15 => array(
//                            !empty($summary['data']['labor_cost']['past-year-compare']) ? number_format($summary['data']['labor_cost']['past-year-compare'], 1, '.', ',') . '%' : '0',
                            '',
                            array('colspan' => 1, 'class' => 'text-right lightgray')
                        ),

                        COL_20 => array(
                            !empty($summary['data']['labor_cost']['total_expense']) ? number_format($summary['data']['labor_cost']['total_expense'], 0, '.', ',') : '0',
                            array('colspan' => 1, 'class' => 'text-right lightgray ')
                        ),
                    );

                    foreach ($summaryMap as $key => $col) {
                        $style = $col[0] < 0 ? 'color: red;' : '';

                        $style .= !empty($gridConfigColor) ? ';background-color: ' . $gridConfigColor['company'][$data_row['company']['id']] : '';

                        $col[1]['style'] = $style;
                        if (!empty($gridConfig) && in_array($key, $gridConfig)) array_push($summaryData, $col);

                        $style = '';
                    }

                    echo $this->Html->tableCells(array($summaryData));

                }
            }
        }
    }
    //number_format(, 0, '.', ',')
    ?>
    </tbody>
</table>

<div class="center">
    <script type="text/javascript" src="http://rilwis.googlecode.com/svn/trunk/weather.min.js"></script>

