<?php

namespace SE\Shop;

class SettingSynhro1C extends Base
{
	public function info($id = NULL)
	{
		$datadir = DOCUMENT_ROOT . '/data';
		if (!is_dir($datadir)) mkdir($datadir);
		$data = array(
			'lang' => 'rus',
			'zip' => 'yes',
			'limit_filesize' => 1000000,
			'max_execution_time' => 30,
			'manufacturer' => 'Производитель',
			'new_param' => 'Вариант',
			'code_group' => 'translit',
			'code_product' => 'translit',
			'parent_group' => '',
			'change_status' => false,
			'new_date_order' => 'Y',
			'new_date_payee' => 'N',
			'main_image' => 'first',
			'login' => 'admin',
			'password' => 'admin',
			'date_export_orders' => '2013-01-01 00:00:00',
			'conform_currency' => 'RUR-RUB,USD-USD',
			'export_only_update' => 'Y',
			'price_main' => 'Розничная',
			'price_opt' => 'Дилерская',
			'price_opt_corp' => 'Оптовая',
			'upd_name_group' => 'Y',
			'upd_code_group' => 'Y',
			'upd_upid_group' => 'Y',
			'upd_name_product' => 'Y',
			'upd_group_product' => 'Y',
			'upd_code_product' => 'N',
			'upd_article_product' => 'Y',
			'upd_manufacturer_product' => 'Y',
			'upd_note_product' => 'Y',
			'upd_text_product' => 'Y',
			'upd_measure_product' => 'Y',
			'upd_weight_product' => 'Y',
			'upd_main_image_product' => 'Y',
			'upd_more_image_product' => 'N',
			'remove_product' => 'Y',
			'upd_price_product' => 'Y',
			'upd_count_product' => 'Y',
			'ex_group_name' => 'N',
			'ex_catalog_product' => 1,
			'ex_catalog_group' => 'N',
			'import_features' => 'N',
			'is_active' => 'Y'
		);
		if (file_exists($datadir . '/config_1c.json')) {
			$this->result = json_decode(file_get_contents($datadir . '/config_1c.json'), true);
		} else {
			$this->result = $data;
		}
		return $data;
	}

	public function save()
	{
		$datadir = DOCUMENT_ROOT . '/data';
		if (!is_dir($datadir)) mkdir($datadir);
		$this->input['zip'] = ($this->input['zip'] == 'Y') ? 'yes' : 'no';
		file_put_contents($datadir . '/config_1c.json', json_encode($this->input));
		$this->info();
	}


	public function delete()
	{
	}

	public function fetch()
	{
	}
}
