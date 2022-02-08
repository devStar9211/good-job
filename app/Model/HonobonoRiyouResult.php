<?php

class HonobonoRiyouResult extends AppModel
{
    public $useDbConfig = 'honobono';
    public $useTable = 'honobono_riyou_results';
    public $primaryKey = 'id';

    public function data_monthly($offices, $date)
    {
        $get_flag_update_sale = $this->get_flag_update_sale();
        $dateQuery = '';
        foreach ($date as $_date) {
            if (
                isset($get_flag_update_sale[$_date['year']][(int)$_date['month']])
                && $get_flag_update_sale[$_date['year']][(int)$_date['month']] == 1
            ) {
                $dateQuery[] =
                    array(
                        'MONTH(HonobonoRiyouResult.riyou_ymd)' => $_date['month'],
                        'YEAR(HonobonoRiyouResult.riyou_ymd)' => $_date['year'],
                    );
            }
        }
        if($dateQuery != null) {
            $data = array();
            foreach ($offices as $office) {
                $office_remotes = array();
                foreach ($office['OfficeRemoteLabel'] as $_office_remote) {
                    $office_remotes[] = $_office_remote['office_remotes']['value'];
                }
                $this->virtualFields['sale'] = 'SUM(HonobonoRiyouResult.total_seikyuu_yen)';
                $dataSale = $this->find('first', array(
                    'conditions' => array(
                        'HonobonoRiyouResult.jigyo_id' => $office_remotes,
                        'OR' => $dateQuery,
                    ),
                ));
                $data[$office['Office']['id']]['sale'] = $dataSale['HonobonoRiyouResult']['sale'];
            }
            return $data;
        }else{
            return 0;
        }
    }

    public function data_current_year($offices, $dateCurrentYear)
    {
        $get_flag_update_sale = $this->get_flag_update_sale();
        $query = '';
        foreach ($dateCurrentYear as $year => $months) {
            foreach ($months as $_month) {
                if (
                    isset($get_flag_update_sale[$year][(int)$_month])
                    && $get_flag_update_sale[$year][(int)$_month] == 1
                ) {
                    $query[$year]['YEAR(HonobonoRiyouResult.riyou_ymd)'] = $year;
                    $query[$year]['MONTH(HonobonoRiyouResult.riyou_ymd)'][] = $_month;
                }
            }
        }
        if ($query != null) {
            $data = array();
            foreach ($offices as $office) {
                $office_remotes = array();
                foreach ($office['OfficeRemoteLabel'] as $_office_remote) {
                    $office_remotes[] = $_office_remote['office_remotes']['value'];
                }

                $this->virtualFields['sale'] = 'SUM(HonobonoRiyouResult.total_seikyuu_yen)';
                $dataSale = $this->find('first', array(
                    'conditions' => array(
                        'HonobonoRiyouResult.jigyo_id' => $office_remotes,
                        'OR' => $query
                    ),
                ));
                $data[$office['Office']['id']]['sale'] = $dataSale['HonobonoRiyouResult']['sale'];
            }
            return $data;
        } else {
            return 0;
        }
    }
}