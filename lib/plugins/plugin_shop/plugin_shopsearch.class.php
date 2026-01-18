<?php

class plugin_shopsearch
{
	private $query = '';
	private $page;

	public function __construct($page = '')
	{
		$this->query = trim(urldecode(getRequest('q', 3)));
		$this->page = empty($page) ? get('page', 0) : $page;
	}

	public function getQuery()
	{
		return $this->query;
	}

	public function getGroups($group = '')
	{
		$plugin_groups = plugin_shopgroups::getInstance();
		$search_groups = array();
		if (!empty($group))
			$groups = $plugin_groups->getParents($group);
		else
			$groups = $plugin_groups->getMainGroups();
		if (!empty($groups)) {
			$link = seMultiDir() . '/' . $this->page . '/';
			foreach ($groups as $val) {
				$search_groups[] = array(
					'name' => $val['name'],
					'link' => $link . $val['code'],
					'code' => $val['code']
				);
			}
		}
		return $search_groups;
	}

	public function getProducts($options)
	{
		$query = htmlspecialchars_decode($this->query, ENT_QUOTES);
		$result = array();
		if (!empty($query)) {
			$shop_price = new seTable('shop_price', 'sp');
			$shop_price->select('DISTINCT sp.id, sp.name, sp.code, sp.article, sp.presence_count, 
			sp.step_count, sp.price, sp.price_opt, sp.price_opt_corp, sp.discount, sp.max_discount, 
			sp.curr, sp.id_group, 
			(SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications, 
			(SELECT si.picture FROM shop_img si WHERE si.id_price = sp.id ORDER BY si.`default` DESC, 
			si.sort ASC LIMIT 1) AS img, concat_ws("/",sg.code_gr, sp.code) AS url');
			//$shop_price->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
			$shop_price->innerJoin('shop_group sg', 'sp.id_group = sg.id');

			$shop_price->where('sg.active = "Y"');
			//$shop_price->andWhere('sg.lang = "?"', se_getLang());
			$shop_price->andWhere('sp.enabled = "Y"');

			$query = addcslashes($query, '()$^*\/|?._%+');
			$words = preg_split('/[\s,]+/', $query);
			foreach ($words as $word) {
				if (strpos($word, '-') !== false) $words[] = str_replace('-', '', $word);
			}

			foreach ($words as $word) {
				$search_query = 'sp.name LIKE "%?%" OR REPLACE(sp.name,"-","") LIKE "%?%"';
				if ($options['search_article'])
					$search_query .= ' OR sp.article LIKE "%?%" OR REPLACE(sp.article,"-","") LIKE "%?%" ';
				$shop_price->andWhere('(' . $search_query . ')', $word);
			}

			if (!empty($options['start_group']) || isRequest('g')) {
				$code_group = trim(urldecode(getRequest('g', 3)));

				if (empty($code_group))
					$code_group = $options['start_group'];

				$plugin_groups = plugin_shopgroups::getInstance();

				if ($id = $plugin_groups->getGroupId($code_group)) {
					$shop_price->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');
					$shop_price->andWhere('sgt.id_parent=?', $id);
				}
			}

			//$options['sort'] = 'pd';//na nd aa ad pa pd

			if (!empty($options['sort'])) {
				switch ($options['sort'][0]) {
					case 'n':
						$shop_price->orderBy('sp.name', $options['sort'][1] == 'd');
						break;
					case 'a':
						$shop_price->orderBy('sp.article', $options['sort'][1] == 'd');
						break;
					case 'p':
						$shop_price->orderBy('(sp.price * (SELECT m.kurs FROM `money` `m` WHERE m.name = sp.curr ORDER BY m.date_replace DESC LIMIT 1))', $options['sort'][1] == 'd');
						break;
				}
			}

			$goods_list = $shop_price->getList(0, $options['max_count']);

			if (!empty($goods_list)) {
				$shop_image = new plugin_shopimages();
				$img_style = $shop_image->getSizeStyle($options['image_size']);
				$link = seMultiDir() . '/' . $this->page . '/';
				foreach ($goods_list as $val) {
					$goods = array();
					$goods['url'] = $link . $val['url'] . SE_END;

					$goods['name'] = preg_replace('/(' . join('|', $words) . ')/ui', "<strong>\${1}</strong>", $val['name']);
					$goods['image'] = '<img src="' . ($shop_image->getPictFromImage($val['img'], $options['image_size'])) . '" style="' . $img_style . '">';
					$goods['suggest'] = htmlspecialchars($val['name'], ENT_QUOTES);

					if (!empty($options['search_article']))
						$val['article'] = preg_replace('/(' . join('|', $words) . ')/ui', "<strong>\${1}</strong>", $val['article']);

					$goods['article'] = (!empty($val['article'])) ? '# ' . $val['article'] : '';

					if ($val['modifications']) {
						$plugin_modifications = new plugin_shopmodifications($val['id']);
						$plugin_modifications->getModifications(true);
					}
					$selected = !empty($_SESSION['modifications'][$val['id']]) ? $_SESSION['modifications'][$val['id']] : '';
					$plugin_amount = new plugin_shopamount(0, $val, null, 1, $selected);

					$goods['price'] = $plugin_amount->showPrice();
					$am = $plugin_amount->getPrice();
					if (empty($am)) {
						$goods['price'] = $options['textprice'];
					}
					$result[] = $goods;
				}
			}
		}
		return $result;
	}
}
