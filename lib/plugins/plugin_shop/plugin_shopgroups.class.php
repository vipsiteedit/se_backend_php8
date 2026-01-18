<?php

class plugin_shopgroups
{
	private static $instance = null;
	private $cache_dir = '';
	private $cache_groups = '';
	private $cache_tree = '';
	private $cache_count = '';
	private $groups = array();
	private $tree = array();
	private $count = 0;
	private $treepath = array();
	private $shoppath = '';


	public function __construct()
	{
		$this->cache_dir = SE_SAFE . 'projects/' . SE_DIR . 'cache/shop/groups/';
		$this->cache_groups = $this->cache_dir . 'groups.json';
		$this->cache_tree = $this->cache_dir . 'tree.json';
		$this->cache_count = $this->cache_dir . 'count.txt';
		$this->shoppath = seData::getInstance()->getVirtualPage('shop_vitrine');

		if (!is_dir($this->cache_dir)) {
			@mkdir($this->cache_dir, 0777, true);
		}

		if (!$this->cacheActual()/*!file_exists($this->cache_groups) || filemtime($this->cache_groups) < $this->lastTimeModify()*/) {
			$this->parseGroupsFromDB();
		} else {
			$this->parseGroupsFromCache();
		}
		//$this->setPriceGroup();
		$this->updateGroupTable();
	}

	public function checkUrl($base = false)
	{
		global $SE_REQUEST_NAME;
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$url = str_replace(seMultiDir() . '/' . $this->shoppath . '/', '',  $url[0]);
		if (substr($url, -1, 1) == URL_END) $url = substr($url, 0, -1);
		$u = explode('/', $url);
		if (!$url) $url = $base;
		$result = $this->getId(trim($url));
		if ($result) {
			$SE_REQUEST_NAME[$url] = 1;
		}
		return $result;
	}

	public function updateGroupTable()
	{
		if (!file_exists(SE_ROOT . '/system/logs/shop_price_group.upd')) {
			$sql = "CREATE TABLE IF NOT EXISTS shop_price_group (
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				id_price int(10) UNSIGNED NOT NULL,
				id_group int(10) UNSIGNED NOT NULL,
				is_main tinyint(1) NOT NULL DEFAULT 1,
				updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
				created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id),
				UNIQUE INDEX UK_shop_price_group (id_price, id_group),
				CONSTRAINT FK_shop_price_group_shop_group_id FOREIGN KEY (id_group)
				REFERENCES shop_group (id) ON DELETE CASCADE ON UPDATE CASCADE,
				CONSTRAINT FK_shop_price_group_shop_price_id FOREIGN KEY (id_price)
				REFERENCES shop_price (id) ON DELETE CASCADE ON UPDATE CASCADE
				)
				ENGINE = INNODB
				CHARACTER SET utf8
				COLLATE utf8_general_ci;";

			se_db_query($sql);
			file_put_contents(SE_ROOT . '/system/logs/shop_price_group.upd', date('Y-m-d H:i:s'));

			$shop_price_group = new seTable('shop_price_group');
			$shop_price_group->select('*');
			$shop_price_group_list = $shop_price_group->getList();
			if (empty($shop_price_group_list))
				$this->fillPriceGroup();
		}

		if (!file_exists(SE_ROOT . '/system/logs/shop_group_tree.upd')) {
			$sql = "CREATE TABLE IF NOT EXISTS shop_group_tree (
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				id_parent int(10) UNSIGNED NOT NULL,
				id_child int(10) UNSIGNED NOT NULL,
				level tinyint(4) NOT NULL,
				updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
				created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id),
				UNIQUE INDEX UK_shop_group_tree (id_parent, id_child),
				CONSTRAINT FK_shop_group_tree_shop_group_id FOREIGN KEY (id_child)
				REFERENCES shop_group (id) ON DELETE CASCADE ON UPDATE CASCADE,
				CONSTRAINT FK_shop_group_tree_shop_group_tree_id_parent FOREIGN KEY (id_parent)
				REFERENCES shop_group (id) ON DELETE CASCADE ON UPDATE RESTRICT
				)
				ENGINE = INNODB
				CHARACTER SET utf8
				COLLATE utf8_general_ci;";

			se_db_query($sql);

			$shop_group_tree = new seTable('shop_group_tree');
			$shop_group_tree->select('*');
			$shop_group_tree_list = $shop_group_tree->getList();
			if (empty($shop_group_tree_list)) {
				$tree = array();
				$tbl = new seTable('shop_group', 'sg');
				$tbl->select('upid, id');
				$list = $tbl->getList();
				foreach ($list as $it) {
					$tree[intval($it['upid'])][] = $it['id'];
				}
				unset($list);
				$data = $this->addInTree($tree);
				se_db_query("TRUNCATE TABLE `shop_group_tree`");
				se_db_multi_perform('shop_group_tree', $data);
			}

			file_put_contents(SE_ROOT . '/system/logs/shop_group_tree.upd', date('Y-m-d H:i:s'));
		}
	}

	private function addInTree($tree, $parent = 0, $level = 0)
	{
		if ($level == 0) {
			$this->treepath = array();
		} else
			$this->treepath[$level] = $parent;

		foreach ($tree[$parent] as $id) {
			$data[] = array('id_parent' => $id, 'id_child' => $id, 'level' => $level);
			if ($level > 0)
				for ($l = 1; $l <= $level; $l++) {
					$data[] = array('id_parent' => $this->treepath[$l], 'id_child' => $id, 'level' => $level);
				}
			if (!empty($tree[$id])) {
				$data = array_merge($data, $this->addInTree($tree, $id, $level + 1));
			}
		}
		return $data;
	}

	public function fillPriceGroup($truncate = true)
	{
		if ($truncate)
			se_db_query('TRUNCATE TABLE shop_price_group');
		$sql = 'INSERT LOW_PRIORITY IGNORE INTO shop_price_group (id_price, id_group, is_main)
			  SELECT
				sp.id,
				sp.id_group,
				1
			  FROM shop_price AS sp
				INNER JOIN shop_group sg
				  ON sp.id_group = sg.id
			  UNION ALL
			  SELECT
				sp.id,
				scg.id,
				0
			  FROM shop_price AS sp
				INNER JOIN shop_crossgroup AS scg
				  ON sp.id_group = scg.group_id
				INNER JOIN shop_group AS sg
				  ON scg.id = sg.id
			  UNION ALL
			  SELECT
				sgp.price_id,
				sgp.group_id,
				0
			  FROM shop_group_price sgp
				INNER JOIN shop_group AS sg
				  ON sgp.group_id = sg.id
				INNER JOIN shop_price AS sp
				  ON sgp.price_id = sp.id';
		$result = se_db_query($sql);
	}

	public function fillGroupTree($truncate = true)
	{
		if ($truncate)
			se_db_query('TRUNCATE TABLE shop_price_group');
	}

	private function parseGroupsFromDB()
	{
		$this->tree = $this->parseTreeGroups();
		$this->checkCount($this->tree);
		$this->saveCache();
	}

	private function parseGroupsFromCache()
	{
		$groups_json = file_exists($this->cache_groups) ? file_get_contents($this->cache_groups) : false;
		$tree_json = file_exists($this->cache_tree) ? file_get_contents($this->cache_tree) : false;
		$this->groups = $groups_json ? json_decode($groups_json, true) : array();
		$this->tree = $tree_json ? json_decode($tree_json, true) : array();
	}

	private function checkCount(&$tree = array())
	{
		foreach ($tree as &$val) {
			$val['count'] = $this->groups[$val['id']]['count'];
			if (!empty($val['menu']))
				$this->checkCount($val['menu']);
		}
	}

	private function saveCache()
	{
		if (!is_dir($this->cache_dir)) {
			@mkdir($this->cache_dir, 0777, true);
		}
		if (!is_dir($this->cache_dir) || !is_writable($this->cache_dir)) {
			return;
		}

		$file = fopen($this->cache_groups, "w+");
		$result = ($file) ? fwrite($file, json_encode($this->groups)) : false;
		if ($file) fclose($file);

		$file_log = fopen($this->cache_dir . 'groups.log', 'a+');
		if ($file_log) {
			fwrite($file_log, date('[Y-m-d H:i:s] ') . $this->cache_groups . ' - ' . $result . "\r\n");
		}

		$file = fopen($this->cache_tree, "w+");
		$result = ($file) ? fwrite($file, json_encode($this->tree)) : false;
		if ($file) fclose($file);

		if ($file_log) {
			fwrite($file_log, date('[Y-m-d H:i:s] ') . $this->cache_tree . ' - ' . $result . "\r\n");
			fclose($file_log);
		}

		$file = fopen($this->cache_count, "w+");
		if ($file) {
			fwrite($file, $this->count);
			fclose($file);
		}
	}

	/*
	private function lastTimeModify() {
		$result = se_db_query('SELECT UNIX_TIMESTAMP(GREATEST(MAX(sg.updated_at), MAX(sg.created_at))) AS time FROM shop_group sg');
		$time = se_db_fetch_row($result);
		return $time[0];
	} 
	*/

	private function cacheActual()
	{
		$result = se_db_query('
			SELECT
			  COUNT(*) AS count,
			  UNIX_TIMESTAMP(GREATEST(MAX(sg.updated_at), MAX(sg.created_at))) AS time
			FROM shop_group sg
			UNION ALL
			SELECT
			  COUNT(*) AS count,
			  UNIX_TIMESTAMP(GREATEST(MAX(sgt.updated_at), MAX(sgt.created_at))) AS time
			FROM shop_group_tree sgt
			ORDER BY time DESC LIMIT 1
		');

		list($this->count, $time) = se_db_fetch_row($result);

		$cache_count = file_exists($this->cache_count) ? (int)file_get_contents($this->cache_count) : -1;

		$time = max(filemtime(__FILE__), $time);

		if (!file_exists($this->cache_groups) || !file_exists($this->cache_tree) || filemtime($this->cache_groups) < $time || $cache_count != $this->count) {
			return false;
		} else {
			return true;
		}
	}

	public function getTree($code = '')
	{
		$id = $this->getId($code);
		if (!empty($this->groups[$id])) {
			$parents = $this->getParentsId($id, true);
			if (!empty($parents)) {
				$tree = $this->tree;
				while (!empty($parents)) {
					$id = array_pop($parents);
					$tree = $tree[$id]['menu'];
				}
			} else {
				$tree = $this->tree[$id]['menu'];
			}
			return $tree;
		}

		return $this->tree;
	}


	public function getId($key = 0)
	{
		if (is_string($key) && !empty($key)) {
			if (!empty($this->groups['cat_' . $key]))
				$key = (int)$this->groups['cat_' . $key];
			else
				$key = 0;
		} else {
			$key = (int)$key;
		}

		return $key;
	}

	public function getAllGroups()
	{
		return $this->groups;
	}

	public function getGroup($id)
	{
		$id = $this->getId($id);
		if (empty($this->groups[$id])) return;
		return $this->groups[$id];
	}

	public function getGroupId($code = '')
	{
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$url = str_replace(seMultiDir() . '/' . $this->shoppath . '/', '',  $url[0]);
		if (substr($url, -1, 1) == URL_END) $url = substr($url, 0, -1);
		//$u = explode('/', $url);
		//if (!$url) $url = $base;
		$id = $this->getId(trim($url));
		if (isset($this->groups[$id]))
			return $id;
		elseif (isRequest('cat'))
			seData::getInstance()->go404();
		return;
	}

	public function getChildrensId($id, $add_current = true)
	{
		$id = $this->getId($id);
		if (empty($this->groups[$id])) return;
		$childrens = $this->groups[$id]['childrens'];
		if ($add_current)
			array_push($childrens, $id);
		return $childrens;
	}

	public function getChildrens($id, $add_current = true)
	{
		$childrens_id = $this->getChildrensId($id, $add_current);
		if (empty($childrens_id)) return;
		$groups = array();
		foreach ($childrens_id as $val) {
			$groups[] = $this->groups[$val];
		}
		foreach ($this->getRelated($id) as $val) {
			$groups[] = $val;
		}
		
		return $groups;
	}

	public function getChildsId($id, $add_current = false)
	{
		$id = $this->getId($id);
		if (empty($this->groups[$id])) return;
		$childs = $this->groups[$id]['children'];
		if ($add_current)
			array_push($childs, $id);
		return $childs;
	}

	public function getChilds($id, $add_current = false)
	{
		$childs_id = array_merge($this->getChildsId($id, $add_current), $this->getCrossgroup($id));
		if (empty($childs_id)) return;
		$groups = array();
		foreach ($childs_id as $val) {
			$groups[] = $this->groups[$val];
		}
		return $groups;
	}

	public function getParentsId($id, $add_current = false)
	{
		$id = $this->getId($id);
		if (empty($this->groups[$id])) return;
		$parents = $this->groups[$id]['parents'];
		if ($add_current)
			array_unshift($parents, $id);
		return $parents;
	}

	public function getParents($id, $add_current = false)
	{
		$parents_id = $this->getParentsId($id, $add_current);
		if (empty($parents_id)) return;
		$groups = array();
		foreach ($parents_id as $val) {
			$groups[] = $this->groups[$val];
		}
		return $groups;
	}

	public function getSiblingsId($id)
	{
		$id = $this->getId($id);
		if (!empty($this->groups[$id]['parent'])) {
			$parent_id = $this->groups[$id]['parent'];
			$id_list = $this->groups[$parent_id]['children'];
		} elseif (!empty($this->groups[$id])) {
			$id_list = $this->getMainGroupsId();
		} else
			return;
		return $id_list;
	}

	public function getSiblings($id)
	{
		$siblings_id = $this->getSiblingsId($id);
		if (empty($siblings_id)) return;
		$groups = array();
		foreach ($siblings_id as $val) {
			$groups[] = $this->groups[$val];
		}
		return $groups;
	}

	public function getMainGroupsId()
	{
		$list = array();
		foreach ($this->tree as $val) {
			$list[] = $val['id'];
		}
		return $list;
	}

	public function getMainGroups()
	{
		$groups_id = $this->getMainGroupsId();
		if (empty($groups_id)) return;
		$groups = array();
		foreach ($groups_id as $val) {
			$groups[] = $this->groups[$val];
		}
		return $groups;
	}

	public function getCrossgroup($id)
	{
        $groups = array();
        if ($id) {
            $t = new seTable('shop_group', 'sg');
            $t->select('sg.id');
            $t->where('sg.id IN (SELECT scg.id FROM shop_crossgroup AS scg WHERE scg.group_id = ?)', $id);
            $t->andWhere('sg.id<>?', $id);
            $t->orderBy('sg.position');
            foreach ($t->getList() as $val) {
                $groups[] = $val['id'];
            };
        }
        
        return $groups;
	}

	public function getRelated($id)
	{
		$id = $this->getId($id);

		$groups = array();

		if ($id) {
			$t = new seTable('shop_group', 'sg');
			$t->select('sg.id');
			$t->where('sg.id IN (SELECT id_related FROM shop_group_related WHERE id_group = ? AND type=1 UNION SELECT id_group FROM shop_group_related WHERE id_related = ? AND type=1 AND is_cross)', $id);
			$t->andWhere('sg.id<>?', $id);
			$t->orderBy('sg.position');
			$list = $t->getList();

			foreach ($list as $val) {
				if (isset($this->groups[$val['id']])) {
					$groups[] = $this->groups[$val['id']];
				}
			}
		}

		return $groups;
	}

	private function parseTreeGroups($upid = 0, $level = 0)
	{
		$groups = array();
		$sg = new seTable('shop_group', 'sg');
		$sg->select('sg.id, sg.name, sg.code_gr, sg.picture, sg.picture_alt, sg.scount, sg.commentary, sg.footertext, sg.title, sg.keywords, sg.description');
		if ($sg->isFindField('page_title'))
			$sg->addSelect('sg.page_title');
		$sg->innerJoin('shop_group_tree sgt', 'sg.id = sgt.id_child');
		$sg->where('sgt.level = ?', $level);
		if ($upid) {
			$sg->andWhere('sgt.id_parent = ?', $upid);
		}

		$sg->andWhere('sg.active = "Y"');
		$sg->orderby('sg.position');
		$list = $sg->getList();

		if (!empty($list)) {
			foreach ($list as $val) {
				$this->groups['cat_' . $val['code_gr']] = $val['id'];
					$picture = isset($val['picture']) ? $val['picture'] : '';
					$picture_alt = isset($val['picture_alt']) ? $val['picture_alt'] : '';
					$this->groups[$val['id']] = array(
						'id' => (int)$val['id'],
						'name' => $val['name'],
						'image' => trim((string)$picture),
						'image_alt' => trim((string)$picture_alt),
						'picture_alt' => trim((string)$picture_alt),
						'code' => $val['code_gr'],
						'title' => $val['title'],
						'keywords' => $val['keywords'],
					'description' => $val['description'],
					'footertext' => $val['footertext'],
					'commentary' => $val['commentary'],
					'page_title' => !empty($val['page_title']) ? $val['page_title'] : $val['name'],
					'children' => array(),
					'childrens' => array(),
					'parent' => $upid,
					'parents' => array(),
					'count' => $val['scount'],
					'link' => seMultiDir() . '/' . $this->shoppath . '/' . $val['code_gr'] . URL_END
				);
				if (!empty($upid)) {
					$this->groups[$upid]['children'][] = $val['id'];
					$this->groups[$val['id']]['parents'] = $this->groups[$upid]['parents'];
					array_unshift($this->groups[$val['id']]['parents'], $upid);
					$this->recursiveChildren($upid, $val['id']);
				}
					$groups[$val['id']] = array(
						'id' => $val['id'],
						'name' => $val['name'],
						'link' => seMultiDir() . '/' . $this->shoppath . '/' . urlencode($val['code_gr']) . URL_END,
						'code' => $val['code_gr'],
						'menu' => $this->parseTreeGroups($val['id'], $level + 1),
						'image' => trim((string)$picture),
						'image_alt' => trim((string)$picture_alt),
						'count' => $val['scount']
					);
			}
		}
		//foreach($groups as $gr){
		//	$data[] = array('id_parent'=>'', 'id_child'=>'', 'level'=>'');
		//}
		return $groups;
	}

	private function recursiveChildren($parent, $child)
	{
		$this->groups[$parent]['childrens'][] = $child;
		$this->groups[$parent]['count'] += $this->groups[$child]['count'];
		if (!empty($this->groups[$parent]['parent']) && $this->groups[$parent]['parent'] != $parent) {
			$this->recursiveChildren($this->groups[$parent]['parent'], $child);
		}
	}

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
