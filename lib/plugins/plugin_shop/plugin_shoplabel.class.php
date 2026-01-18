<?php

class plugin_shoplabel
{
	private static $instance = null;

	private $cache_dir = '';
	private $labels = array();
	private $count = 0;
	private $images_dir;
	private $cache_count;
	private $cache_labels;

	public function __construct()
	{
		$this->cache_dir = SE_SAFE . 'projects/' . SE_DIR . 'cache/shop/labels/';
		$this->images_dir = '/images/' . se_getLang() . '/labels/';
		$this->cache_labels = $this->cache_dir . 'labels.json';
		$this->cache_count = $this->cache_dir . 'count.txt';
		//$this->id_main = 1;

		if (!is_dir($this->cache_dir)) {
			if (!is_dir(SE_SAFE . 'projects/' . SE_DIR . 'cache/'))
				mkdir(SE_SAFE . 'projects/' . SE_DIR . 'cache/');
			if (!is_dir(SE_SAFE . 'projects/' . SE_DIR . 'cache/shop/'))
				mkdir(SE_SAFE . 'projects/' . SE_DIR . 'cache/shop/');
			mkdir($this->cache_dir);
		}

		$this->checkCache();
	}

	private function checkCache()
	{
		$result = se_db_query('SELECT COUNT(*) AS cnt, UNIX_TIMESTAMP(GREATEST(MAX(sl.updated_at), MAX(sl.created_at))) AS time FROM shop_label sl');

		list($this->count, $time) = se_db_fetch_row($result);

		$cache_count = file_exists($this->cache_count) ? (int)file_get_contents($this->cache_count) : -1;

		$time = max(filemtime(__FILE__), $time);

		if (!file_exists($this->cache_labels) || filemtime($this->cache_labels) < $time || $cache_count != $this->count) {
			$t = new seTable('shop_label');
			$t->select('id, name, code, image');
			$list = $t->getList();

			if (!empty($list)) {
				foreach ($list as $val) {
					$this->labels[$val['id']] = $val;
					$this->labels['c_' . $val['code']] = $val['id'];
				}
			}

			$this->saveCache();
		} else {
			$this->labels = json_decode(file_get_contents($this->cache_labels), 1);
		}
	}

	private function saveCache()
	{
		$file = fopen($this->cache_labels, "w+");
		$result = fwrite($file, json_encode($this->labels));
		fclose($file);

		$file = fopen($this->cache_count, "w+");
		$result = fwrite($file, $this->count);
		fclose($file);
	}

	public function getLabelId($code)
	{
		return $this->labels['c_' . $code];
	}

	public function getLabel($id = 0)
	{
		if (empty($this->labels[$id])) return;
		$label = $this->labels[$id];
		if ($label['image'] && file_exists(SE_ROOT . $this->images_dir . $label['image']))
			$label['image'] = $this->images_dir . $label['image'];
		else
			$label['image'] = '';
		return $label;
	}

	public function getList()
	{
		return $this->labels;
	}

	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
