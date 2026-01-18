<?php

/**
 * @copyright EDGESTILE
 */
class plugin_shopsections
{

	private static $instance = null;
	private static $sections = array();
	private $count = 0;
	private static $options = array();
	private $cache_dir;
	private $cache_sections;
	private $cache_count;

	public function __construct($opt = array())
	{
		$this->cache_dir = SE_SAFE . 'projects/' . SE_DIR . 'cache/shop/sections/';

		$this->cache_sections = $this->cache_dir . 'sections.json';
		$this->cache_count = $this->cache_dir . 'count.json';

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
		$sql_cache = "SELECT
			  'section' AS type,
			  COUNT(*) AS cnt,
			  UNIX_TIMESTAMP(GREATEST(MAX(ifnull(ss.updated_at, 0)), MAX(ss.created_at))) AS time
			FROM shop_section ss
			UNION ALL
			SELECT
			  'item',
			  COUNT(*),
			  UNIX_TIMESTAMP(GREATEST(MAX(ifnull(ssi.updated_at, 0)), MAX(ssi.created_at)))
			FROM shop_section_item ssi
			UNION ALL
			SELECT
			  'page',
			  COUNT(*),
			  UNIX_TIMESTAMP(GREATEST(MAX(ifnull(ssp.updated_at, 0)), MAX(ssp.created_at)))
			FROM shop_section_page ssp";

		$result = se_db_query($sql_cache);

		$cache_count = file_exists($this->cache_count) ? (int)file_get_contents($this->cache_count) : -1;

		$update_time = 0;


		if (!empty($result)) {
			while ($line = se_db_fetch_assoc($result)) {
				$this->count += $line['cnt'];
				$update_time = max($update_time, $line['time']);
			}
		}

		$update_time = max(filemtime(__FILE__), $update_time);

		if (!file_exists($this->cache_sections) || filemtime($this->cache_sections) < $update_time || $cache_count != $this->count) {
			$this->parseSectionsFromDB();
		} else {
			$this->parseSectionsFromCache();
		}
	}

	private function parseSectionsFromDB()
	{
		$shop_section = new seTable('shop_section');
		$shop_section->select('id, code');
		$list = $shop_section->getList();

		if (!empty($list)) {
			foreach ($list as $val) {
				self::$sections[$val['code']] = array(
					'id' => $val['id'],
					'items' => array(),
					'pages' => array()
				);
			}

			$shop_section_page = new seTable('shop_section_page');
			$shop_section_page->select('id, (SELECT code FROM shop_section WHERE id = id_section LIMIT 1) AS code, title, page, se_section');
			$shop_section_page->where('enabled <> 0');
			$list = $shop_section_page->getList();

			if (!empty($list)) {
				foreach ($list as $val) {
					if (isset(self::$sections[$val['code']])) {
						$key = $val['page'] . '_' . $val['se_section'];
						self::$sections[$val['code']]['pages'][$key] = array(
							'id' => $val['id'],
							'title' => $val['title'],
							'page' => $val['page'],
							'se_section' => $val['se_section']
						);
					}
				}
			}

			$shop_section_item = new seTable('shop_section_item');
			$shop_section_item->select('id, (SELECT code FROM shop_section WHERE id = id_section LIMIT 1) AS code, name, note, id_price, id_group, id_brand, id_new as id_news, url, picture, picture_alt');
			$shop_section_item->where('enabled <> 0');
			$shop_section_item->orderBy('sort');
			$list = $shop_section_item->getList();

			if (!empty($list)) {
				foreach ($list as $val) {
					if (isset(self::$sections[$val['code']])) {
						self::$sections[$val['code']]['items'][] = array(
							'id' => $val['id'],
							'id_price' => (int)$val['id_price'],
							'id_group' => (int)$val['id_group'],
							'id_brand' => (int)$val['id_brand'],
							'id_news' => (int)$val['id_news'],
							'title' => $val['name'],
							'text' => $val['note'],
							'url' => $val['url'],
							'picture' => $val['picture'],
							'pictute_alt' => $val['picture_alt']
						);
					}
				}
			}
		}

		$this->saveCache();
	}

	private function parseSectionsFromCache()
	{
		self::$sections = json_decode(file_get_contents($this->cache_sections), 1);
	}

	private function saveCache()
	{
		$file = fopen($this->cache_sections, "w+");
		$result = fwrite($file, json_encode(self::$sections));
		fclose($file);

		$this->writeLog($this->cache_sections . ' - ' . $result);

		$file = fopen($this->cache_count, "w+");
		$result = fwrite($file, $this->count);
		fclose($file);
	}

	private function writeLog($text)
	{
		$file_log = fopen($this->cache_dir . 'sections.log', 'a+');
		fwrite($file_log, date('[Y-m-d H:i:s] ') . $text . "\r\n");
		fclose($file_log);
	}

	public static function getInstance($opt = array())
	{
		if ($opt['section'] > 100000)
			$opt['page'] = '';
		self::$options = $opt;
		if (is_null(self::$instance)) {
			self::$instance = new self($opt);
		}
		self::checkSection($opt);
		return self::$instance;
	}

	public function getItems()
	{
		$items = array();

		$code = self::$options['code'];
		$key = self::$options['page'] . '_' . self::$options['section'];

		if (isset(self::$sections[$code]['pages'][$key])) {
			$items = self::$sections[$code]['items'];
		}

		if (!empty($items)) {
			$table_targets = array('shop_brand', 'shop_price', 'shop_group', 'news');
			$targets = array();
			$image = new plugin_ShopImages('section');
			foreach ($items as $key => $val) {
				if (!empty($val['picture']))
					$val['picture'] = $image->getPictFromImage($val['picture'], self::$options['size_image']);

				$items[$key] = array(
					'type' => '+++',
					'title' => $val['title'],
					'text' => $val['text'],
					'picture' => $val['picture'],
					'url' => $val['url']
				);

				if (!empty($val['id_price']))
					$targets['shop_price'][$key] = $val['id_price'];
				elseif (!empty($val['id_group']))
					$targets['shop_group'][$key] = $val['id_group'];
				elseif (!empty($val['id_brand']))
					$targets['shop_brand'][$key] = $val['id_brand'];
				elseif (!empty($val['id_news']))
					$targets['news'][$key] = $val['id_news'];
			}
			$items = $this->getItemsFromTarget($items, $targets);
		}

		return $items;
	}

	private function getItemsFromTarget($items, $targets)
	{

		$page_catalog = seData::getInstance()->getVirtualPage('shop_vitrine');
		$size_image = self::$options['size_image'];
		if (!empty($targets['shop_price'])) {
			$list_id = $targets['shop_price'];
			$image = new plugin_ShopImages('price');

			$shop_price = new seTable('shop_price', 'sp');
			$shop_price->select('sp.id, sp.name, sp.code, sp.note, (SELECT si.picture FROM shop_img si WHERE si.id_price = sp.id ORDER BY si.`default` DESC, si.sort ASC LIMIT 1) AS img, price, id_group, price_opt_corp, price_opt, discount, max_discount, curr, presence_count, presence, step_count, measure');
			$shop_price->where('sp.id IN (?)', join(',', $list_id));
			$shop_price->andWhere('sp.enabled="Y"');
			$list = $shop_price->getList();
			$objects = array();
			if ($list) {
				foreach ($list as $obj) {
					$objects[$obj['id']] = $obj;
				}
			}
			foreach ($list_id as $key => $id) {
				if (!empty($objects[$id])) {
					$plugin_amount = new plugin_shopamount(0, $objects[$id]);
					$items[$key] = array(
						'type' => 'product',
						'id' => $id,
						'price' => $plugin_amount->showPrice(true),
						'presence' => $plugin_amount->showPresenceCount(),
						'count' => (int)$plugin_amount->getPresenceCount(),
						'title' => empty($items[$key]['title']) ? $objects[$id]['name'] : $items[$key]['title'],
						'text' => empty($items[$key]['text']) ? $objects[$id]['note'] : $items[$key]['text'],
						'picture' => empty($items[$key]['picture']) ? $image->getPictFromImage($objects[$id]['img'], $size_image) : $items[$key]['picture'],
						'url' => empty($items[$key]['url']) ? seMultiDir() . '/' . $page_catalog . '/show/' . urlencode($objects[$id]['code']) . URL_END : $items[$key]['url']
					);
				} else
					unset($items[$key]);
			}
		}

		if (!empty($targets['shop_group'])) {
			$list_id = $targets['shop_group'];
			$plugin_groups = plugin_shopgroups::getInstance();
			$image = new plugin_ShopImages('group');
			foreach ($list_id as $key => $id) {
				if ($group = $plugin_groups->getGroup((int)$id)) {
					$items[$key] = array(
						'type' => 'group',
						'id' => $id,
						'title' => empty($items[$key]['title']) ? $group['name'] : $items[$key]['title'],
						'text' => empty($items[$key]['text']) ? $group['commentary'] : $items[$key]['text'],
						'picture' => empty($items[$key]['picture']) ? $image->getPictFromImage($group['image'], $size_image) : $items[$key]['picture'],
						'url' => empty($items[$key]['url']) ? seMultiDir() . '/' . $page_catalog . '/cat/' . urlencode($group['code']) . URL_END : $items[$key]['url']
					);
				} else
					unset($items[$key]);
			}
		}

		if (!empty($targets['shop_brand'])) {
			$list_id = $targets['shop_brand'];

			$plugin_brands = plugin_shopbrands::getInstance();

			$image = new plugin_ShopImages('brand');

			foreach ($list_id as $key => $id) {
				if ($brand = $plugin_brands->getBrand($id)) {
					$items[$key] = array(
						'type' => 'brand',
						'id' => $id,
						'title' => empty($items[$key]['title']) ? $brand['name'] : $items[$key]['title'],
						'text' => empty($items[$key]['text']) ? $brand['text'] : $items[$key]['text'],
						'picture' => empty($items[$key]['picture']) ? $image->getPictFromImage($brand['image'], $size_image) : $items[$key]['picture'],
						'url' => empty($items[$key]['url']) ? seMultiDir() . '/' . $page_catalog . URL_END . '?brand=' . urlencode($brand['code']) : $items[$key]['url']
					);
				} else
					unset($items[$key]);
			}
		}

		if (!empty($targets['news'])) {
			$list_id = $targets['news'];
			$page_news = 'news';
			$lang = se_getlang();

			$news = new seTable('news');
			$news->select('id, title, img, text');
			$news->where('id IN (?)', join(',', $list_id));
			$news->andWhere('active = "Y"');
			$list = $news->getList();
			$objects = array();
			if ($list) {
				foreach ($list as $obj) {
					$objects[$obj['id']] = $obj;
				}
			}
			foreach ($list_id as $key => $id) {
				if (!empty($objects[$id])) {
					$items[$key] = array(
						'type' => 'news',
						'id' => $id,
						'title' => empty($items[$key]['title']) ? $objects[$id]['title'] : $items[$key]['title'],
						'text' => empty($items[$key]['text']) ? $objects[$id]['text'] : $items[$key]['text'],
						'picture' => empty($items[$key]['picture']) ? '/images/' . $lang . '/newsimg/' . $objects[$id]['img'] : $items[$key]['picture'],
						'url' => empty($items[$key]['url']) ? seMultiDir() . '/show' . $page_news . '/' . $objects[$id]['id'] . URL_END : $items[$key]['url']
					);
				} else
					unset($items[$key]);
			}
		}

		return $items;
	}

	public function getTitle()
	{

		$code = self::$options['code'];
		$key = self::$options['page'] . '_' . self::$options['section'];
		$title = self::$options['title'];

		if (!empty(self::$sections[$code]['pages'][$key]['title'])) {
			$title = self::$sections[$code]['pages'][$key]['title'];
		}

		return $title;
	}

	public function parseProject()
	{
		$project_dir = SE_SAFE . 'projects/' . SE_DIR;
		$project_file = $project_dir . 'project.xml';
		$pages_dir = $project_dir . 'pages/';

		$files = glob($pages_dir . '*.xml');
		$sections = $pages = $codes = $se_sections = array();
		if (file_exists($project_file)) {
			$files[] = $project_file;
		}

		if (!empty($files)) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					$xml = simplexml_load_file($file);

					foreach ($xml->sections as $section) {
						$type = (string)$section->type;

						if ($type == 'ashop_section') {
							foreach ($section->parametrs as $param) {
								$code = (string)$param->param1;
								if ($code) {
									$page = $file != $project_file ? substr(basename($file), 0, -4) : '';
									$id = trim($section->id);
									$sections[] = array(
										'section' => $id,
										'title' => trim($section->title),
										'code' => $code,
										'page' => $page
									);
									if (!in_array($page, $pages))
										$pages[] = $page;
									if (!in_array($code, $codes))
										$codes[] = $code;
									if (!in_array($id, $se_sections))
										$se_sections[] = $id;
								}
							}
						}
					}
				}
			}
		}

		if (!empty($sections)) {
			foreach ($sections as $section) {
				$this->checkSection($section);
			}
		}

		$sql_delete = 'DELETE FROM shop_section_page WHERE page NOT IN ("' . join('","', $pages) . '") 
		OR se_section NOT IN ("' . join('","', $se_sections) . '") OR id_section NOT IN (SELECT ss.id FROM shop_section ss WHERE ss.code IN ("' . join('","', $codes) . '"))';

		se_db_query($sql_delete);
	}

	private static function checkSection($options = array())
	{
		if (!empty($options))
			self::$options = $options;
		$code = self::$options['code'];

		if (empty($code))
			return;

		if (!isset(self::$sections[$code])) {
			$section = new seTable('shop_section');
			$section->insert();
			$section->code = $code;
			if ($id = $section->save()) {
				self::$sections[$code] = array(
					'id' => $id,
					'items' => array(),
					'pages' => array()
				);
			}
		} else {
			$id = self::$sections[$code]['id'];
		}
		$key = self::$options['page'] . '_' . self::$options['section'];

		if (!isset(self::$sections[$code]['pages'][$key]) && $id) {
			$page = new seTable('shop_section_page');
			$page->insert();
			$page->id_section = $id;
			$page->title = self::$options['title'];
			$page->page = self::$options['page'];
			$page->se_section = self::$options['section'];
			if ($id = $page->save()) {
				self::$sections[$code]['pages'][$key] = array(
					'id' => $id,
					'title' => self::$options['title'],
					'page' => self::$options['page'],
					'se_section' => self::$options['section']
				);
			}
		}
	}
}
