<?php

class plugin_shopvariables
{

	private static $instance = null;
	private $shop_variables = array();
	private $string_features = array();
	private $geo_data = null;

	private $skipped_words = null;

	public function __construct()
	{

		$this->shop_variables = $this->getShopVariables();
	}

	private function getStringFeatures($id_goods)
	{
		$feature_sting = '';
		if (!empty($id_goods)) {
			$shop_feature = new seTable('shop_feature', 'sf');
			$shop_feature->select('
				GROUP_CONCAT(CASE 
					WHEN (sf.type = "list" OR sf.type = "colorlist") THEN CONCAT_WS(": ", sf.name, CONCAT_WS(" ", (SELECT sfvl.value FROM shop_feature_value_list sfvl WHERE sfvl.id = smf.id_value), sf.measure))
					WHEN (sf.type = "number") THEN CONCAT_WS(": ", sf.name, CONCAT_WS(" ", smf.value_number, sf.measure))
					WHEN (sf.type = "bool") THEN IF (smf.value_bool > 0, sf.name, NULL)
					WHEN (sf.type = "string") THEN CONCAT_WS(": ", sf.name, smf.value_string)
					ELSE NULL 
				END SEPARATOR ", ") AS features
			');
			$shop_feature->innerJoin('shop_modifications_feature smf', 'sf.id=smf.id_feature');
			$shop_feature->leftJoin('shop_feature_group sfg', 'sf.id_feature_group=sfg.id');
			$shop_feature->where('smf.id_price=?', $id_goods);
			$shop_feature->andWhere('smf.id_modification IS NULL');
			$shop_feature->andWhere('sf.seo<>0');
			$shop_feature->orderBy('sfg.sort IS NULL', 0);
			$shop_feature->addOrderBy('sfg.sort', 0);
			$shop_feature->addOrderBy('sf.sort', 0);
			$featurelist = $shop_feature->fetchOne();
			if ($shop_feature->isFind())
				$feature_sting = $shop_feature->features;
		}
		return $feature_sting;
	}

	private function replaceSufix($text)
	{

		$text = preg_replace("/\s+/ui", ' ', trim($text));

		$words = explode(' ', $text);

		$this->getSkippedWords();

		foreach ($words as &$word) {
			if (strpos($word, '«') !== false || strpos($word, '"') !== false || in_array($word, $this->skipped_words))
				continue;
			$word = preg_replace("/(ая)$/ui", 'ую', $word);
			$word = preg_replace("/(яя)$/ui", 'юю', $word);
			$word = preg_replace("/(а)$/ui", 'у', $word);
			$word = preg_replace("/(я)$/ui", 'ю', $word);
		}

		$text = join(' ', $words);

		return trim($text);
	}

	private function getSkippedWords()
	{
		if (is_null($this->skipped_words)) {
			$this->skipped_words = array();
			$we = new seTable('word_exclude');
			$we->select('value');
			$list = $we->getList();
			if ($list) {
				foreach ($list as $val) {
					$this->skipped_words[] = $val['value'];
				}
			}
			//$this->skipped_words = array('для', 'на', 'за', 'из-за');
		}
	}

	private function getShopVariables()
	{
		$variables = array();
		$shop_variables = new seTable('shop_variables');
		$shop_variables->select('name, value');
		$list = $shop_variables->getList();
		if (!empty($list)) {
			foreach ($list as $val) {
				$variables['{' . $val['name'] . '}'] = $val['value'];
			}
		}
		return $variables;
	}

	private function parseShopText($string, $fields = array(), $type = 'product')
	{
		$geo = new plugin_geovalues();
		$string = $geo->parseValues($string);
		if (empty($string))
			return;
		if (empty($fields) || !preg_match('/{.+}/', $string))
			return $string;

		$in = $out = array();

		if ($type == 'product') {
			if (strpos($string, '{features}') !== false || strpos($string, '{характеристики}') !== false) {
				if (!isset($this->string_features[$fields['id']]))
					$this->string_features[$fields['id']] = $this->getStringFeatures($fields['id']);
				$fields['features'] = $this->string_features[$fields['id']];
			}

			if (!isset($fields['name'])) $fields['name'] = '';
			if (!isset($fields['brand'])) $fields['brand'] = '';
			if (!isset($fields['price'])) $fields['price'] = '';
			if (!isset($fields['new price'])) $fields['new price'] = '';
			if (!isset($fields['old price'])) $fields['old price'] = '';
			if (!isset($fields['discount'])) $fields['discount'] = '';
			if (!isset($fields['description'])) $fields['description'] = '';
			if (!isset($fields['features'])) $fields['features'] = '';
			if (!isset($fields['article'])) $fields['article'] = '';
			if (!isset($fields['note'])) $fields['note'] = '';
			if (!isset($fields['title'])) $fields['title'] = '';
			if (!isset($fields['keywords'])) $fields['keywords'] = '';
			if (!isset($fields['group_name'])) $fields['group_name'] = '';
			if (!isset($fields['measure'])) $fields['measure'] = '';

			$name = $fields['name'];
			if (!(strpos($string, '{name}') === 0 || strpos($string, '{название товара}') === 0 || strpos($string, '{asname}') === 0))
				$fields['name'] = utf8_strtolower($fields['name']);

			$array_changes = array(
				'{name}' => $this->replaceSufix($fields['name']),
				'{название товара}' => $this->replaceSufix($fields['name']),
				'{asname}' => $fields['name'],
				'{nameup}' => $this->replaceSufix($name),
				'{asnameup}' => $name,
				'{brand}' => $fields['brand'],
				'{производитель}' => $fields['brand'],
				'{price}' => $fields['price'],
				'{цена}' => $fields['price'],
				'{new price}' => $fields['new price'],
				'{новая цена}' => $fields['new price'],
				'{old price}' => $fields['old price'],
				'{старая цена}' => $fields['old price'],
				'{discount}' => $fields['discount'],
				'{скидка}' => $fields['discount'],
				'{description}' => $fields['description'],
				'{описание товара}' => $fields['description'],
				'{features}' => $fields['features'],
				'{характеристики}' => $fields['features'],
				'{article}' => $fields['article'],
				'{артикул}' => $fields['article'],
				'{note}' => $fields['note'],
				'{краткое описание}' => $fields['note'],
				'{title}' => $fields['title'],
				'{заголовок}' => $fields['title'],
				'{keywords}' => $fields['keywords'],
				'{ключевые слова}' => $fields['keywords'],
				'{groupname}' => $fields['group_name'],
				'{название группы}' => $fields['group_name'],
				'{measure}' => $fields['measure'],
				'{единица измерения}' => $fields['measure']
			);
		} else {
			if (!isset($fields['name'])) $fields['name'] = '';
			if (!isset($fields['commentary'])) $fields['commentary'] = '';
			if (!isset($fields['title'])) $fields['title'] = '';
			if (!isset($fields['keywords'])) $fields['keywords'] = '';
			if (!isset($fields['description'])) $fields['description'] = '';

			$array_changes = array(
				'{name}' => $this->replaceSufix($fields['name']),
				'{название группы}' => $this->replaceSufix($fields['name']),
				'{asname}' => $fields['name'],
				'{note}' => $fields['commentary'],
				'{краткое описание}' => $fields['commentary'],
				'{title}' => $fields['title'],
				'{заголовок}' => $fields['title'],
				'{keywords}' => $fields['keywords'],
				'{ключевые слова}' => $fields['keywords'],
				'{description}' => $fields['description'],
				'{описание группы}' => $fields['description']
			);

			$pattern = '/\{groupname([0-9]+)\}/';
			if (preg_match_all($pattern, $string, $arr)) {
				$pg = plugin_shopgroups::getInstance();
				foreach ($arr[0] as $key => $val) {
					$group = $pg->getGroup((int)$arr[1][$key]);
					$array_changes[$val] = $group['name'];
				}
			}
		}

		if (strpos($string, '{city}') !== false || strpos($string, '{город}') !== false) {
			if (is_null($this->geo_data)) {
				$plugin_shopgeo = new plugin_shopgeo();
				$this->geo_data = $plugin_shopgeo->getSelected();
			}
			if (!empty($this->geo_data['city']))
				$array_changes['{city}'] = $array_changes['{город}'] = $this->geo_data['city'];
		}

		$result = array_merge($this->shop_variables, $array_changes);

		while (preg_match("/\[(.+)\]/", $string, $m)) {
			if (preg_match("/(\{[^\}]+\})/", $m[1], $mm) && empty($result[$mm[1]])) {
				$m[1] = '';
			}
			$string = str_replace($m[0], $m[1], $string);
		}

		$string = strtr($string, $result);

		$string = preg_replace('/{[^\}]*}/', '', $string);

		return $string;
	}

	public function parseGroupText($string, $group = array())
	{

		return $this->parseShopText($string, $group, 'group');
	}

	public function parseProductText($string, $product = array())
	{

		return $this->parseShopText($string, $product);
	}

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
