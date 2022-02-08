<?php

class HonobonoResult extends AppModel
{
    public $useDbConfig = 'honobono';
    public $useTable = 'honobono_results';
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
                        'MONTH(HonobonoResult.yymm_ymd)' => $_d['month'],
                        'YEAR(HonobonoResult.yymm_ymd)' => $_d['year'],
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
                $this->virtualFields['sale'] = '(SUM(HonobonoResult.seikyuu) + SUM(HonobonoResult.tseikyuu) + SUM(HonobonoResult.k1seikyuu) + SUM(HonobonoResult.k2seikyuu) + SUM(HonobonoResult.k3seikyuu) + SUM(HonobonoResult.k1tokutei) + SUM(HonobonoResult.k2tokutei) + SUM(HonobonoResult.k3tokutei) + SUM(HonobonoResult.total_seikyuu_yen))';
                $this->virtualFields['total_nursing_care_level'] = 'SUM(HonobonoResult.yokai_kbn)';
                $this->virtualFields['total_user'] = 'COUNT(user_id)';
                $dataSale = $this->find('first', array(
                    'conditions' => array(
                        'HonobonoResult.jigyo_id' => $office_remotes,
                        'OR' => $dateQuery,
                    ),
                ));
                /* calculator tỷ suất hoạt động */
                $totalNursingCareLevel = !empty($dataSale['HonobonoResult']['total_nursing_care_level']) ? $dataSale['HonobonoResult']['total_nursing_care_level'] : 0;
                $totalUser = !empty($dataSale['HonobonoResult']['total_user']) ? $dataSale['HonobonoResult']['total_user'] : 0;
                $data[$office['Office']['id']]['sale'] = $dataSale['HonobonoResult']['sale'];
                $data[$office['Office']['id']]['total_user'] = $totalUser;
                $data[$office['Office']['id']]['total_nursing_care_level'] = $totalNursingCareLevel;
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
                    $query[$year]['YEAR(HonobonoResult.yymm_ymd)'] = $year;
                    $query[$year]['MONTH(HonobonoResult.yymm_ymd)'][] = $_month;
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
                $this->virtualFields['sale'] = '(SUM(HonobonoResult.seikyuu) + SUM(HonobonoResult.tseikyuu) + SUM(HonobonoResult.k1seikyuu) + SUM(HonobonoResult.k2seikyuu) + SUM(HonobonoResult.k3seikyuu) + SUM(HonobonoResult.k1tokutei) + SUM(HonobonoResult.k2tokutei) + SUM(HonobonoResult.k3tokutei) + SUM(HonobonoResult.total_seikyuu_yen))';
                $this->virtualFields['total_nursing_care_level'] = 'SUM(HonobonoResult.yokai_kbn)';
                $this->virtualFields['total_user'] = 'COUNT(user_id)';
                $dataSale = $this->find('first', array(
                    'conditions' => array(
                        'HonobonoResult.jigyo_id' => $office_remotes,

                        'OR' => $query

                    ),
                ));
                /* calculator tỷ suất hoạt động */
                $totalNursingCareLevel = !empty($dataSale['HonobonoResult']['total_nursing_care_level']) ? $dataSale['HonobonoResult']['total_nursing_care_level'] : 0;
                $totalUser = !empty($dataSale['HonobonoResult']['total_user']) ? $dataSale['HonobonoResult']['total_user'] : 0;
                $data[$office['Office']['id']]['sale'] = $dataSale['HonobonoResult']['sale'];
                $data[$office['Office']['id']]['total_user'] = $totalUser;
                $data[$office['Office']['id']]['total_nursing_care_level'] = $totalNursingCareLevel;
            }
            return $data;
        }else {
            return 0;
        }
    }
}