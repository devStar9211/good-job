<?php

App::uses('Component', 'Controller');

class CsvContentChunkComponent extends Component
{

    public $column_name = array(
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y', 'Z'
    );

    public function get_col_at($position)
    {
        $num_cols = count($this->column_name);

        $line = ceil($position / $num_cols);

        $ix = $position - ($line - 1) * $num_cols - 1;

        $letter = isset($this->column_name[$ix]) ? $this->column_name[$ix] : '...';

        $group = 1;
        $p_total = $num_cols;
        while ($p_total < $position) {
            $group++;
            $p_total += pow($num_cols, $group);
        }

        $prefix = '';

        if ($group > 1) {
            $l = $line - ceil($num_cols * (1 - pow($num_cols, $group - 1)) / (1 - $num_cols) / $num_cols);
            $ix = ceil($l / (pow($num_cols, $group - 1) / $num_cols));
            $prefix .= isset($this->column_name[$ix - 1]) ? $this->column_name[$ix - 1] : '...';

            $i = $group;
            while ($i > 2) {
                $l = $l - ($ix - 1) * (pow($num_cols, $i - 1) / $num_cols);

                $ix = ceil($l / (pow($num_cols, $i - 2) / $num_cols));

                $prefix .= isset($this->column_name[$ix - 1]) ? $this->column_name[$ix - 1] : '...';

                $i--;
            }
        }

        return $prefix . $letter;
    }

    public function file_get_csv_contents_chunked($file, $chunk_size)
    {
        $response = array(
            'status' => 0,
            'data' => array(),
            'message' => __('unknown error occurred.')
        );

        if (
            isset($file['type'])
            && isset($file['tmp_name'])
        ) {
            if (!in_array($file['type'], Configure::read('CSV'))) {
                $response['message'] = __('not a csv file');
            } else {
                try {
                    ini_set("auto_detect_line_endings", true);

                    $handle = @fopen($file['tmp_name'], "r");
                    $data = array();

                    while (!feof($handle)) {
                        if (($row = fgetcsv($handle, $chunk_size)) !== FALSE) {
                            $data[] = $row;
                            unset($row);
                        }
                    }

                    fclose($handle);

                    $response['data'] = $data;
                    $response['status'] = 1;
                } catch (Exception $e) {
                    $response['message'] = __('couldn\'t read file.');
                }
            }
        } else {
            $response['message'] = __('invalid file.');
        }

        return $response;
    }

    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function validateCols($file, $valid)
    {
        $cols = array();
        $msg = array();

        foreach ($file[0] as $col => $title) {
            $title = str_replace("\xEF\xBB\xBF", "", trim($title));

            if ($title !== '') {
                $ix = array_search($title, $valid);
                if ($ix !== false) {
                    $cols[$col] = $ix;
                    unset($valid[$ix]);
                } else if (
                in_array($ix, $cols)
                ) {
                    $msg[] = array(
                        'line' => '1',
                        'col' => $this->get_col_at($col + 1),
                        'message' => __('column "%s" is duplicated', $title)
                    );
                } else {
                    $msg[] = array(
                        'line' => '1',
                        'col' => $this->get_col_at($col + 1),
                        'message' => __('unknown column "%s"', $title)
                    );
                }
            } else {
                $msg[] = array(
                    'line' => '1',
                    'col' => $this->get_col_at($col + 1),
                    'message' => __('empty column name')
                );
            }
        }

        if (!empty($valid)) {
            foreach ($valid as $alias => $title) {
                $msg[] = array(
                    'line' => '1',
                    'message' => __('missing column "%s"', $title)
                );
            }
        }

        return array('cols' => $cols, 'message' => $msg);
    }

    public function l_c($l = null, $c = null)
    {
        $ms = '';

        if ($l !== null) {
            $ms .= '&nbsp;<span style="text-decoration:underline;">' . __('line') . ':&nbsp;' . $l . '</span>';
        }
        if ($c !== null) {
            $ms .= '&nbsp;<span style="text-decoration:underline;">' . __('column') . ':&nbsp;' . $c . '</span>';
        }

        return $ms;
    }

    public function get_response($files, $method, $valid, $processer, $procession)
    {
        $response = array(
            'status' => 0,
            'success' => 0,
            'failure' => 0,
            'message' => array('Oops! Something went wrong.')
        );

        $warning = array();
        $csv_data = array();

        foreach ($files as $file) {
            $content = $this->file_get_csv_contents_chunked($file, 0);

            if (
                $content['status'] == 1
                && !empty($content['data'])
            ) {
                $csv_data[] = array(
                    'file' => $file['name'],
                    'content' => $content['data']
                );
            } else {
                $response['failure'] += 1;

                if (!isset($warning[$file['name']])) {
                    $warning[$file['name']] = array();
                }
                $warning[$file['name']][] = array(
                    'message' => $content['message']
                );
            }
        }


        foreach ($csv_data as $data) {
            $file = $data['content'];

            $chunk_data = call_user_func_array(array($this, $method), array($file, $valid));
            $chunk = $chunk_data['data'];

            if (
            empty($chunk_data['message'])
            ) {
                if (!empty($chunk)) {
                    $try = call_user_func(array($processer, $procession), $chunk);

                    if (!$try['status']) {
                        if (!isset($warning[$data['file']])) {
                            $warning[$data['file']] = array();
                        }
                        $warning[$data['file']][] = array(
                            'message' => '> ' . __('lưu dữ liệu thất bại')
                        );

                        if (!empty($try['message'])) {
                            foreach ($try['message'] as $msg) {
                                $warning[$data['file']][] = array(
                                    'message' => ' ' . $msg
                                );
                            }
                        }
                    }
                } else {
                    if (!isset($warning[$data['file']])) {
                        $warning[$data['file']] = array();
                    }
                    $warning[$data['file']][] = array(
                        'message' => __('no records found')
                    );
                }

                if (!empty($warning[$data['file']])) {
                    $response['failure'] += 1;
                } else {
                    $response['success'] += 1;
                }
            } else {
                $response['failure'] += 1;

                if (!isset($warning[$data['file']])) {
                    $warning[$data['file']] = array();
                }
                foreach ($chunk_data['message'] as $msg) {
                    $warning[$data['file']][] = array(
                        'message' => (
                            $msg['message']
                            . $this->l_c($msg['line'], !empty($msg['col']) ? $msg['col'] : null)
                        )
                    );
                }
            }
        }

        if (
        !empty($warning)
        ) {
            $response['status'] = 2;
            $response['message'] = array();

            foreach ($warning as $file => $wa) {
                $message = $file . ':' . '<br>';
                foreach ($wa as $ms) {
                    $message .= '---&nbsp;' . $ms['message'] . '<br>';
                }
                $response['message'][] = $message;
            }
        } else {
            $response['status'] = 1;
            $response['message'] = array(__('success'));
        }

        return $response;
    }

    public function get_content_file_point_detail($file, $valid = array())
    {
        $response = array(
            'data' => array(),
            'message' => array()
        );
        $row_data_format = array(
            'employee_id' => '',
            'point_type_id' => '',
            'year' => '',
            'month' => '',
            'value' => array(),
        );
        $valid = array(
            'employee_id' => 'ID',
            'point_type_id' => 'Point type',
            'year' => 'Year',
            'month' => 'Month',
            'value' => 'Point',
        );
        $cols = array();
        $r_name = $e_name = array();
        foreach ($file[0] as $col => $title) {
            $title = str_replace("\xEF\xBB\xBF", "", trim($title));
            if ($title !== '') {
                $ix = array_search($title, $valid);

                if ($ix !== false) {
                    $cols[$col] = $ix;
                    unset($valid[$ix]);
                } else {
                    $response['message'][] = array(
                        'line' => '1',
                        'col' => $this->get_col_at($col + 1),
                        'message' => __('unknown column "%s"', $title)
                    );
                }
            } else {
                $response['message'][] = array(
                    'line' => '1',
                    'col' => $this->get_col_at($col + 1),
                    'message' => __('empty column name')
                );
            }
        }

        if (!empty($valid)) {
            foreach ($valid as $alias => $title) {
                $response['message'][] = array(
                    'line' => '1',
                    'message' => __('missing column "%s"', $title)
                );
            }
        }
        if (empty($response['message'])) {

            for ($line = 1; $line < count($file); $line++) {
                $row_data = $row_data_format;
                $row = $file[$line];
                foreach ($cols as $ix => $col) {
                    switch ($col) {
                        case 'employee_id': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                $row_data[$col] = array(
                                    'value' => trim($row[$ix]),
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                    )
                                );
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;
                        case 'point_type_id': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                $row_data[$col] = array(
                                    'value' => trim($row[$ix]),
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                    )
                                );
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;

                        case 'year': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if ($this->validateDate($row[$ix], 'Y')) {
                                    $row_data[$col] = array(
                                        'value' => DateTime::createFromFormat('Y', $row[$ix])->format('Y'),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(yyyy)')
                                    );
                                }
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;
                        case 'month': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if (is_numeric($row[$ix])) {
                                    $row_data[$col] = array(
                                        'value' => $row[$ix],
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(mm)')
                                    );
                                }
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;
                        case 'value': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if (
                                is_numeric($row[$ix])

                                ) {
                                    $row_data[$col] = array(
                                        'value' => isset($row[$ix]) ? trim($row[$ix]) : '',
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng')
                                    );
                                }
                            } else {
                                $row_data[$col] = array(
                                    'value' => '',
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;

                    }
                }

                $response['data'][] = $row_data;
            }
        }

        return $response;
    }

    public function get_content_file_pb($file, $valid = array())
    {
        $response = array(
            'data' => array(),
            'message' => array()
        );
        $row_data_format = array(
            'employee_id' => '',
            'year' => '',
            'month' => '',
            'different_rate' => array(),

        );
        $valid = array(
            'employee_id' => 'Employee ID',
            'year' => 'Year',
            'month' => 'Month',
            'bonus_yen' => 'Amount',
        );
        $cols = array();
        $r_name = $e_name = array();
        foreach ($file[0] as $col => $title) {
            $title = str_replace("\xEF\xBB\xBF", "", trim($title));

            if ($title !== '') {
                $ix = array_search($title, $valid);

                if ($ix !== false) {
                    $cols[$col] = $ix;
                    unset($valid[$ix]);
                } else {
                    $response['message'][] = array(
                        'line' => '1',
                        'col' => $this->get_col_at($col + 1),
                        'message' => __('unknown column "%s"', $title)
                    );
                }
            } else {
                $response['message'][] = array(
                    'line' => '1',
                    'col' => $this->get_col_at($col + 1),
                    'message' => __('empty column name')
                );
            }
        }

        if (!empty($valid)) {
            foreach ($valid as $alias => $title) {
                $response['message'][] = array(
                    'line' => '1',
                    'message' => __('missing column "%s"', $title)
                );
            }
        }
        if (empty($response['message'])) {

            for ($line = 1; $line < count($file); $line++) {
                $row_data = $row_data_format;
                $row = $file[$line];
                foreach ($cols as $ix => $col) {
                    switch ($col) {
                        case 'employee_id': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                $row_data[$col] = array(
                                    'value' => trim($row[$ix]),
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                    )
                                );
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;
                        case 'year': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if ($this->validateDate($row[$ix], 'Y')) {
                                    $row_data[$col] = array(
                                        'value' => DateTime::createFromFormat('Y', $row[$ix])->format('Y'),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(yyyy)')
                                    );
                                }
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;
                        case 'month': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if (is_numeric($row[$ix])) {
                                    $row_data[$col] = array(
                                        'value' => $row[$ix],
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(mm)')
                                    );
                                }
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;
                        case 'bonus_yen': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if (
                                is_numeric($row[$ix])

                                ) {
                                    $row_data[$col] = array(
                                        'value' => isset($row[$ix]) ? trim($row[$ix]) : '',
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng')
                                    );
                                }
                            } else {
                                $row_data[$col] = array(
                                    'value' => '',
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;

                    }
                }

                $response['data'][] = $row_data;
            }
        }

        return $response;
    }

    public function get_content_file_r($file, $valid = array())
    {
        $response = array(
            'data' => array(),
            'message' => array()
        );

        $row_data_format = array(
            'office_id' => '',
            'year' => '',
            'month' => '',
            'revenues' => array(),
            'labor_cost' => '',
            'overtime_cost' => '',
            'expenses' => array(),
        );

        $valid = array(
            'office_id' => 'Office ID',
            'year' => 'Year',
            'month' => 'Month',
            'labor_cost' => 'Labor Cost',
            'overtime_cost' => 'Overtime Cost',
        );

        $cols = array();
        $r_name = $e_name = array();
        foreach ($file[0] as $col => $title) {
            $title = str_replace("\xEF\xBB\xBF", "", trim($title));

            if ($title !== '') {
                $ix = array_search($title, $valid);

                if ($ix !== false) {
                    $cols[$col] = $ix;
                    unset($valid[$ix]);
                } else if (preg_match('/^sales[0-9]*$/i', $title)) {
                    if (!in_array($title, $r_name)) {
                        $cols[$col] = 'revenues';
                        $r_name[] = $title;
                    } else {
                        $response['message'][] = array(
                            'line' => '1',
                            'col' => $this->get_col_at($col + 1),
                            'message' => __('"%s" is duplicated', $title)
                        );
                    }
                } else if (preg_match('/^expense[0-9]*$/i', $title)) {
                    if (!in_array($title, $e_name)) {
                        $cols[$col] = 'expenses';
                        $e_name[] = $title;
                    } else {
                        $response['message'][] = array(
                            'line' => '1',
                            'col' => $this->get_col_at($col + 1),
                            'message' => __('"%s" is duplicated', $title)
                        );
                    }
                } else {
                    $response['message'][] = array(
                        'line' => '1',
                        'col' => $this->get_col_at($col + 1),
                        'message' => __('unknown column "%s"', $title)
                    );
                }
            } else {
                $response['message'][] = array(
                    'line' => '1',
                    'col' => $this->get_col_at($col + 1),
                    'message' => __('empty column name')
                );
            }
        }

        if (!empty($valid)) {
            foreach ($valid as $alias => $title) {
                $response['message'][] = array(
                    'line' => '1',
                    'message' => __('missing column "%s"', $title)
                );
            }
        }

        if (empty($r_name)) {
            $response['message'][] = array(
                'line' => '1',
                'message' => __('mission column "%s"', 'Sales')
            );
        }

        if (empty($e_name)) {
            $response['message'][] = array(
                'line' => '1',
                'message' => __('mission column "%s"', 'Expense')
            );
        }

        if (empty($response['message'])) {
            $b_n_se = array('revenues' => '売上', 'expenses' => '経費');
            $i_se = array('revenues' => 0, 'expenses' => 0);
            $n_se = array();

            for ($line = 1; $line < count($file); $line++) {
                $row_data = $row_data_format;
                $row = $file[$line];

                foreach ($cols as $ix => $col) {
                    switch ($col) {
                        case 'company_id':
                        case 'office_id': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                $row_data[$col] = array(
                                    'value' => trim($row[$ix]),
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                    )
                                );
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;

                        case 'year': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if ($this->validateDate($row[$ix], 'Y')) {
                                    $row_data[$col] = array(
                                        'value' => DateTime::createFromFormat('Y', $row[$ix])->format('Y'),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(yyyy)')
                                    );
                                }
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;

                        case 'month': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if (is_numeric($row[$ix])) {
                                    $row_data[$col] = array(
                                        'value' => $row[$ix],
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(mm)')
                                    );
                                }
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;

                        case 'labor_cost':
                        case 'overtime_cost': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if (
                                is_numeric($row[$ix])

                                ) {
                                    $row_data[$col] = array(
                                        'value' => isset($row[$ix]) ? trim($row[$ix]) : '',
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng')
                                    );
                                }
                            } else {
                                $row_data[$col] = array(
                                    'value' => '',
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;

                        case 'revenues':
                        case 'expenses': {
                            if (isset($n_se[$file[0][$ix]])) {
                                $n = $n_se[$file[0][$ix]];
                            } else {
                                if ($i_se[$col] == 0) {
                                    $n = $b_n_se[$col];
                                } else {
                                    $n = $b_n_se[$col] . $i_se[$col];
                                }

                                $i_se[$col]++;
                                $n_se[$file[0][$ix]] = $n;
                            }

                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if (
                                is_numeric($row[$ix])

                                ) {
                                    $row_data[$col][$n] = array(
                                        'value' => isset($row[$ix]) ? trim($row[$ix]) : '',
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng')
                                    );
                                }
                            } else {
                                $row_data[$col][$n] = array(
                                    'value' => isset($row[$ix]) ? trim($row[$ix]) : '',
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                    }
                }

                $response['data'][] = $row_data;
            }
        }

        return $response;
    }

    public function get_content_file_c($file, $valid = array())
    {
        $response = array(
            'data' => array(),
            'message' => array()
        );

        $row_data_format = array(
            'id' => '',
            'created' => '',
            'updated' => '',
            'username' => '',
            'password' => '',
            'in_office' => '',
            'company_group_id' => '',
            'company_id' => '',
            'office_id' => '',
            'hiring_pattern_id' => '',
            'employee_number' => '',
            'name' => '',
            'kana_name' => '',
            'position_id' => '',
            'license_id' => array(),
            'occupation_id' => array(),
            'basic_salary' => '',
            'hourly_wage' => '',
            'daily_wage' => '',
            'allowance_id' => array(),
            'traffic_type' => '',
            'public_transportation' => '',
            'vehicle_cost' => '',
            'one_way_transportation' => '',
            'round_trip_transportation' => '',
            'commute_route' => '',
            'social_insurance' => '',
            'employment_insurance' => '',
            'join_date' => '',
            'postal_code' => '',
            'prefecture' => '',
            'municipality' => '',
            'municipal_town' => '',
            'dob' => '',
            'gender' => '',
            'email' => '',
            'phone' => '',
            'basis_pension_number' => '',
            'bank_name' => '',
            'branch_name' => '',
            'account_number' => '',
            'account_name' => '',
            'sos_contact_person' => '',
            'sos_contact_person_kana' => '',
            'sos_phone' => '',
            'sos_address' => '',
            'relationships' => array(
                'employee_relationships1' => array(
                    'name' => array('col' => '', 'value' => ''),
                    'name_kana' => array('col' => '', 'value' => ''),
                    'dob' => array('col' => '', 'value' => ''),
                    'relation' => array('col' => '', 'value' => ''),
                    'job' => array('col' => '', 'value' => ''),
                ),
                'employee_relationships2' => array(
                    'name' => array('col' => '', 'value' => ''),
                    'name_kana' => array('col' => '', 'value' => ''),
                    'dob' => array('col' => '', 'value' => ''),
                    'relation' => array('col' => '', 'value' => ''),
                    'job' => array('col' => '', 'value' => ''),
                ),
                'employee_relationships3' => array(
                    'name' => array('col' => '', 'value' => ''),
                    'name_kana' => array('col' => '', 'value' => ''),
                    'dob' => array('col' => '', 'value' => ''),
                    'relation' => array('col' => '', 'value' => ''),
                    'job' => array('col' => '', 'value' => ''),
                ),
                'employee_relationships4' => array(
                    'name' => array('col' => '', 'value' => ''),
                    'name_kana' => array('col' => '', 'value' => ''),
                    'dob' => array('col' => '', 'value' => ''),
                    'relation' => array('col' => '', 'value' => ''),
                    'job' => array('col' => '', 'value' => ''),
                ),
            ),
            'employee_register_only' => '',
            'have_sale_permission' => '',
        );

        $validateCols = $this->validateCols($file, $valid);
        $cols = $validateCols['cols'];
        $response['message'] = array_merge($response['message'], $validateCols['message']);

        if (empty($response['message'])) {
            for ($line = 2; $line < count($file); $line++) {
                $row_data = $row_data_format;
                $row = $file[$line];

                foreach ($cols as $ix => $col) {
                    switch ($col) {
                        case 'created':
                        case 'updated':
                        case 'password':
                        case 'employee_number':
                        case 'company_group_id':
                        case 'company_id':
                        case 'office_id':
                        case 'hiring_pattern_id':
                        case 'name':
                        case 'kana_name':
                        case 'position_id':
                        case 'commute_route':
                        case 'postal_code':
                        case 'prefecture':
                        case 'municipality':
                        case 'municipal_town':
                        case 'bank_name':
                        case 'branch_name':
                        case 'account_number':
                        case 'account_name':
                        case 'sos_contact_person':
                        case 'sos_contact_person_kana':
                        case 'sos_address': {
                            $row_data[$col] = array(
                                'value' => isset($row[$ix]) ? trim($row[$ix]) : '',
                                'position' => array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1)
                                )
                            );
                        }
                            break;

                        case 'id':
                        case 'username': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                $row_data[$col] = array(
                                    'value' => trim($row[$ix]),
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                    )
                                );
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;

                        case 'basic_salary':
                        case 'hourly_wage':
                        case 'daily_wage':
                        case 'public_transportation':
                        case 'vehicle_cost':
                        case 'one_way_transportation':
                        case 'round_trip_transportation':
                        case 'social_insurance':
                        case 'employment_insurance':
                        case 'basis_pension_number': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if (
                                is_numeric($row[$ix])

                                ) {
                                    $row_data[$col] = array(
                                        'value' => trim($row[$ix]),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1),
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng')
                                    );
                                }
                            } else {
                                $row_data[$col] = array(
                                    'value' => '',
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;

                        case 'dob': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                $d = explode('/', $row[$ix]);

                                $date = (
                                    (isset($d[0]) ? str_pad($d[0], 4, '0', STR_PAD_LEFT) : '')
                                    . '/' .
                                    (isset($d[1]) ? str_pad($d[1], 2, '0', STR_PAD_LEFT) : '')
                                    . '/' .
                                    (isset($d[2]) ? str_pad($d[2], 2, '0', STR_PAD_LEFT) : '')
                                );

                                if ($this->validateDate($date, 'Y/m/d')) {
                                    $row_data[$col] = array(
                                        'value' => DateTime::createFromFormat('Y/m/d', $date)->format('Y-m-d'),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(yyyy/mm/dd)')
                                    );
                                }
                            } else {
                                $row_data[$col] = array(
                                    'value' => '',
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;

                        case 'join_date': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                $d = explode('/', $row[$ix]);

                                $date = (
                                    (isset($d[0]) ? str_pad($d[0], 4, '0', STR_PAD_LEFT) : '')
                                    . '/' .
                                    (isset($d[1]) ? str_pad($d[1], 2, '0', STR_PAD_LEFT) : '')
                                    . '/' .
                                    (isset($d[2]) ? str_pad($d[2], 2, '0', STR_PAD_LEFT) : '')
                                );

                                if ($this->validateDate($date, 'Y/m/d')) {
                                    $row_data[$col] = array(
                                        'value' => DateTime::createFromFormat('Y/m/d', $date)->format('Y-m-d'),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(yyyy/mm/dd)')
                                    );
                                }
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;

                        case 'phone':
                        case 'sos_phone': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                // if(preg_match('/^\(\d{3}\) \d{3}-\d{4}$/', $row[$ix])) {
                                $row_data[$col] = array(
                                    'value' => trim($row[$ix]),
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                    )
                                );
                                // } else {
                                // 	$response['message'][] = array(
                                // 		'line' => $line + 1,
                                // 		'col' => $this->get_col_at($ix + 1),
                                // 		'message' => __('dữ liệu không đúng định dạng( (ddd) ddd-dddd )')
                                // 	);
                                // }
                            } else {
                                $row_data[$col] = array(
                                    'value' => '',
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;

                        case 'email': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if (preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $row[$ix])) {
                                    $row_data[$col] = array(
                                        'value' => trim($row[$ix]),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1),
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('email không hợp lệ')
                                    );
                                }
                            } else {
                                $row_data[$col] = array(
                                    'value' => '',
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;

                        case 'traffic_type':
                        case 'allowance_id':
                        case 'license_id':
                        case 'occupation_id': {
                            $row_data[$col] = array(
                                'value' => isset($row[$ix]) ? explode(',', $row[$ix]) : array(),
                                'position' => array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1)
                                )
                            );
                        }
                            break;

                        case 'in_office': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if ($row[$ix] == 0 || $row[$ix] == 1) {
                                    $row_data[$col] = array(
                                        'value' => trim($row[$ix]),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(0:not in office, 1:in office)')
                                    );
                                }
                            } else {
                                $row_data[$col] = array(
                                    'value' => 0,
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;

                        case 'gender': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if ($row[$ix] == 0 || $row[$ix] == 1) {
                                    $row_data[$col] = array(
                                        'value' => trim($row[$ix]),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(0:male, 1:femail)')
                                    );
                                }
                            } else {
                                $row_data[$col] = array(
                                    'value' => 0,
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;

                        case (preg_match('/^employee_relationships/i', $col) ? true : false): {
                            $sub_key = preg_replace('/employee_relationships+\d+_/', '', $col);
                            $key = rtrim($col, '_' . $sub_key);

                            $row_data['relationships'][$key][$sub_key]['col'] = $ix;
                            $row_data['relationships'][$key][$sub_key]['value'] = isset($row[$ix]) ? trim($row[$ix]) : '';
                        }
                            break;
                        case 'employee_register_only': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if ($row[$ix] == 0 || $row[$ix] == 1) {
                                    $row_data[$col] = array(
                                        'value' => trim($row[$ix]),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
//                                        'message' => __('dữ liệu không đúng định dạng(0:male, 1:femail)')
                                        'message' => __('dữ liệu không đúng định dạng')
                                    );
                                }
                            } else {
                                $row_data[$col] = array(
                                    'value' => 0,
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;
                        case 'have_sale_permission': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if ($row[$ix] == 0 || $row[$ix] == 1) {
                                    $row_data[$col] = array(
                                        'value' => trim($row[$ix]),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
//                                        'message' => __('dữ liệu không đúng định dạng(0:male, 1:femail)')
                                        'message' => __('dữ liệu không đúng định dạng')
                                    );
                                }
                            } else {
                                $row_data[$col] = array(
                                    'value' => 0,
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            }
                        }
                            break;

                    }
                }

                foreach ($row_data['relationships'] as $relationship) {
                    if (
                        $relationship['name']['value'] !== ''
                        || $relationship['name_kana']['value'] !== ''
                        || $relationship['dob']['value'] !== ''
                        || $relationship['relation']['value'] !== ''
                        || $relationship['job']['value'] !== ''
                    ) {
                        foreach ($relationship as $key => $relation) {
                            if ($relation['value'] === '') {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($relation['col'] + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            } else if ($key == 'dob') {
                                $d = explode('/', $relation['value']);

                                $date = (
                                    (isset($d[0]) ? str_pad($d[0], 4, '0', STR_PAD_LEFT) : '')
                                    . '/' .
                                    (isset($d[1]) ? str_pad($d[1], 2, '0', STR_PAD_LEFT) : '')
                                    . '/' .
                                    (isset($d[2]) ? str_pad($d[2], 2, '0', STR_PAD_LEFT) : '')
                                );

                                if (!$this->validateDate($date, 'Y/m/d')) {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($relation['col'] + 1),
                                        'message' => __('dữ liệu không đúng định dạng(yyyy/mm/dd)')
                                    );
                                }
                            }
                        }
                    }
                }

                foreach ($row_data['relationships'] as $key => $relationship) {
                    foreach ($relationship as $sub_key => $value) {
                        $row_data['relationships'][$key][$sub_key] = array(
                            'value' => $value['value'],
                            'position' => array(
                                'line' => $line + 1,
                                'col' => $this->get_col_at($ix + 1)
                            )
                        );
                    }
                }

                $response['data'][] = $row_data;
            }
        }

        return $response;
    }

    public function get_content_file_p($file, $valid = array())
    {
        $response = array(
            'data' => array(),
            'message' => array()
        );

        $row_data_format = array(
            'year' => '',
            'month' => '',
            'prize_id' => '',
            'employee_id' => '',
            'prize_value' => '',
        );

        $validateCols = $this->validateCols($file, $valid);
        $cols = $validateCols['cols'];
        $response['message'] = array_merge($response['message'], $validateCols['message']);

        if (empty($response['message'])) {
            for ($line = 1; $line < count($file); $line++) {
                $row_data = $row_data_format;
                $row = $file[$line];

                foreach ($cols as $ix => $col) {
                    switch ($col) {
                        case 'year': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                $year = str_pad($row[$ix], 4, '0', STR_PAD_LEFT);

                                if ($this->validateDate($year, 'Y')) {
                                    $row_data[$col] = array(
                                        'value' => DateTime::createFromFormat('Y', $year)->format('Y'),
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(yyyy)')
                                    );
                                }
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;

                        case 'month': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                $month = str_pad($row[$ix], 2, '0', STR_PAD_LEFT);
                                if (is_numeric($month)) {
                                    $row_data[$col] = array(
                                        'value' => $month,
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng(mm)')
                                    );
                                }
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;

                        case 'prize_id':
                        case 'employee_id': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                $row_data[$col] = array(
                                    'value' => isset($row[$ix]) ? trim($row[$ix]) : '',
                                    'position' => array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1)
                                    )
                                );
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;

                        case 'prize_value': {
                            if (
                                isset($row[$ix])
                                && trim($row[$ix]) !== ''
                            ) {
                                if (
                                is_numeric($row[$ix])

                                ) {
                                    $row_data[$col] = array(
                                        'value' => isset($row[$ix]) ? trim($row[$ix]) : '',
                                        'position' => array(
                                            'line' => $line + 1,
                                            'col' => $this->get_col_at($ix + 1)
                                        )
                                    );
                                } else {
                                    $response['message'][] = array(
                                        'line' => $line + 1,
                                        'col' => $this->get_col_at($ix + 1),
                                        'message' => __('dữ liệu không đúng định dạng')
                                    );
                                }
                            } else {
                                $response['message'][] = array(
                                    'line' => $line + 1,
                                    'col' => $this->get_col_at($ix + 1),
                                    'message' => __('dữ liệu không được để trống')
                                );
                            }
                        }
                            break;
                    }
                }

                $response['data'][] = $row_data;
            }
        }

        return $response;
    }
}