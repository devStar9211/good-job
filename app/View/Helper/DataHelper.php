<?php

App::uses('FormHelper', 'View/Helper');

class DataHelper extends AppHelper {
	public $helpers = array('Form'); 

	private $prefectures = [
		['code' => '01', 'name' => 'Hokkaido',  'display' => '北海道'],
		['code' => '02', 'name' => 'Aomori',    'display' => '青森県'],
		['code' => '03', 'name' => 'Iwate',     'display' => '岩手県'],
		['code' => '04', 'name' => 'Miyagi',    'display' => '宮城県'],
		['code' => '05', 'name' => 'Akita',     'display' => '秋田県'],
		['code' => '06', 'name' => 'Yamagata',  'display' => '山形県'],
		['code' => '07', 'name' => 'Fukushima', 'display' => '福島県'],
		['code' => '08', 'name' => 'Ibaraki',   'display' => '茨城県'],
		['code' => '09', 'name' => 'Tochigi',   'display' => '栃木県'],
		['code' => '10', 'name' => 'Gunma',     'display' => '群馬県'],
		['code' => '11', 'name' => 'Saitama',   'display' => '埼玉県'],
		['code' => '12', 'name' => 'Chiba',     'display' => '千葉県'],
		['code' => '13', 'name' => 'Tokyo',     'display' => '東京都'],
		['code' => '14', 'name' => 'Kanagawa',  'display' => '神奈川県'],
		['code' => '15', 'name' => 'Niigata',   'display' => '新潟県'],
		['code' => '16', 'name' => 'Toyama',    'display' => '富山県'],
		['code' => '17', 'name' => 'Ishikawa',  'display' => '石川県'],
		['code' => '18', 'name' => 'Fukui',     'display' => '福井県'],
		['code' => '19', 'name' => 'Yamanashi', 'display' => '山梨県'],
		['code' => '20', 'name' => 'Nagano',    'display' => '長野県'],
		['code' => '21', 'name' => 'Gifu',      'display' => '岐阜県'],
		['code' => '22', 'name' => 'Shizuoka',  'display' => '静岡県'],
		['code' => '23', 'name' => 'Aichi',     'display' => '愛知県'],
		['code' => '24', 'name' => 'Mie',       'display' => '三重県'],
		['code' => '25', 'name' => 'Shiga',     'display' => '滋賀県'],
		['code' => '26', 'name' => 'Kyoto',     'display' => '京都府'],
		['code' => '27', 'name' => 'Osaka',     'display' => '大阪府'],
		['code' => '28', 'name' => 'Hyogo',     'display' => '兵庫県'],
		['code' => '29', 'name' => 'Nara',      'display' => '奈良県'],
		['code' => '30', 'name' => 'Wakayama',  'display' => '和歌山県'],
		['code' => '31', 'name' => 'Tottori',   'display' => '鳥取県'],
		['code' => '32', 'name' => 'Shimane',   'display' => '島根県'],
		['code' => '33', 'name' => 'Okayama',   'display' => '岡山県'],
		['code' => '34', 'name' => 'Hiroshima', 'display' => '広島県'],
		['code' => '35', 'name' => 'Yamaguchi', 'display' => '山口県'],
		['code' => '36', 'name' => 'Tokushima', 'display' => '徳島県'],
		['code' => '37', 'name' => 'Kagawa',    'display' => '香川県'],
		['code' => '38', 'name' => 'Ehime',     'display' => '愛媛県'],
		['code' => '39', 'name' => 'Kochi',     'display' => '高知県'],
		['code' => '40', 'name' => 'Fukuoka',   'display' => '福岡県'],
		['code' => '41', 'name' => 'Saga',      'display' => '佐賀県'],
		['code' => '42', 'name' => 'Nagasaki',  'display' => '長崎県'],
		['code' => '43', 'name' => 'Kumamoto',  'display' => '熊本県'],
		['code' => '44', 'name' => 'Oita',      'display' => '大分県'],
		['code' => '45', 'name' => 'Miyazaki',  'display' => '宮崎県'],
		['code' => '46', 'name' => 'Kagoshima', 'display' => '鹿児島県'],
		['code' => '47', 'name' => 'Okinawa',   'display' => '沖縄県'],
	];

	private $transportation = [
		['code' => '48', 'name' => 'Public transport', 'display' => '公共交通機関'],
		['code' => '49', 'name' => 'Walk',             'display' => '徒歩'],
		['code' => '50', 'name' => 'Bicycle',          'display' => '自転車'],
		['code' => '51', 'name' => 'Bike',             'display' => 'バイク'],
		['code' => '52', 'name' => 'Car',              'display' => '車'],
	];

	public function prefectures() {
		$prefectures = array();
		foreach($this->prefectures as $prefecture) { $prefectures[$prefecture['display']] = $prefecture['display']; }
		return $prefectures;
	}

	public function transportation($alias, $traffic_type = '', $options = array()) {
		$transportation = '';
		$traffic = explode(',', $traffic_type);

		foreach($this->transportation as $transport) {
			$transportation .= '<label class="align-middle h34 checkbox-wrap">'.$this->Form->checkbox($alias, array('hiddenField' => false, 'value' => $transport['display'], 'checked' => in_array($transport['display'], $traffic), 'class' => 'larger')).$transport['display'].'</label>';
		}

		return $transportation;
	}
}