<?php

class HonobonoKaigoResult extends AppModel
{
    public $useDbConfig = 'honobono';
    public $useTable = 'honobono_kaigo_results';
    public $primaryKey = 'id';

    public function data_monthly($offices, $date)
    {

        $get_flag_update_sale = $this->get_flag_update_sale();
        $dateQuery = null;
        foreach ($date as $_d) {
            if (
                isset($get_flag_update_sale[$_d['year']][(int)$_d['month']])
                && $get_flag_update_sale[$_d['year']][(int)$_d['month']] == 1
            ) {
                $dateQuery[] =
                    array(
                        'MONTH(HonobonoKaigoResult.kaigo_ymd)' => $_d['month'],
                        'YEAR(HonobonoKaigoResult.kaigo_ymd)' => $_d['year'],
                    );
            }
        }
        if ($dateQuery != null) {
            $data = array();
            foreach ($offices as $office) {
                $office_remotes = array();
                foreach ($office['OfficeRemoteLabel'] as $_office_remote) {
                    $office_remotes[] = $_office_remote['office_remotes']['value'];
                }
                $this->virtualFields['sale'] = '(SUM(HonobonoKaigoResult.seikyuu) + SUM(HonobonoKaigoResult.tseikyuu) + SUM(HonobonoKaigoResult.k1seikyuu) + SUM(HonobonoKaigoResult.k2seikyuu) + SUM(HonobonoKaigoResult.k3seikyuu))';
                $dataSale = $this->find('first', array(
                    'conditions' => array(
                        'HonobonoKaigoResult.jigyo_id' => $office_remotes,
                        'OR' => $dateQuery,
                    ),
                ));
                $data[$office['Office']['id']]['sale'] = $dataSale['HonobonoKaigoResult']['sale'];
            }
            return $data;
        } else {
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
                    $query[$year]['YEAR(HonobonoKaigoResult.kaigo_ymd)'] = $year;
                    $query[$year]['MONTH(HonobonoKaigoResult.kaigo_ymd)'][] = $_month;
                }
            }
        }
        if($query != null) {
            $data = array();
            foreach ($offices as $office) {
                $office_remotes = array();
                foreach ($office['OfficeRemoteLabel'] as $_office_remote) {
                    $office_remotes[] = $_office_remote['office_remotes']['value'];
                }
                $this->virtualFields['sale'] = '(SUM(HonobonoKaigoResult.seikyuu) + SUM(HonobonoKaigoResult.tseikyuu) + SUM(HonobonoKaigoResult.k1seikyuu) + SUM(HonobonoKaigoResult.k2seikyuu) + SUM(HonobonoKaigoResult.k3seikyuu))';
                $dataSale = $this->find('first', array(
                    'conditions' => array(
                        'HonobonoKaigoResult.jigyo_id' => $office_remotes,

                        'OR' => $query
                    ),
                ));
                $data[$office['Office']['id']]['sale'] = $dataSale['HonobonoKaigoResult']['sale'];
            }
            return $data;
        }else{
            return 0;
        }
    }

}