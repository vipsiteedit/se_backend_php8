<?php

class yandex_market
{

	public function __construct($auto_create = false)
	{
		$this->updateDB();
		if ($auto_create)
			$this->createFileMarket();
	}

	public function updateDB()
	{
		//для параметров поле is_market
		//для товаров поле market_category
		//параметры интеграции exportModifications = 0, exportFeatures = 0, enabledVendorModel = 0, paramIdForModel = '', paramIdForTypePrefix = ''
		if (!file_exists(SE_ROOT . '/system/logs/market_2.upd')) {
			$u = new seTable('shop_feature');
			if (!$u->isFindField('is_market'))
				$u->addField('is_market', 'BOOL', 1);

			$u = new seTable('shop_price');
			if (!$u->isFindField('market_category'))
				$u->addField('market_category', 'SMALLINT', 1);

			$main = new seTable('main');
			$main->select('id');
			$main->where("`lang`='rus'");
			$main->fetchOne();
			$id_main = $main->id;

			$params = new seTable('shop_integration_parameter');
			$params->select('id');
			$params->where("code='exportFeatures'");
			$params->andWhere('id_main=?', $id_main);

			if (!$params->fetchOne()) {
				$params->insert();
				$params->id_main = $id_main;
				$params->code = 'exportFeatures';
				$params->value = 0;
				$params->save();
			}

			$params->where("code='exportModifications'");
			$params->andWhere('id_main=?', $id_main);

			if (!$params->fetchOne()) {
				$params->insert();
				$params->id_main = $id_main;
				$params->code = 'exportModifications';
				$params->value = 0;
				$params->save();
			}

			$params->where("code='enabledVendorModel'");
			$params->andWhere('id_main=?', $id_main);

			if (!$params->fetchOne()) {
				$params->insert();
				$params->id_main = $id_main;
				$params->code = 'enabledVendorModel';
				$params->value = 0;
				$params->save();
			}

			$params->where("code='paramIdForModel'");
			$params->andWhere('id_main=?', $id_main);

			if (!$params->fetchOne()) {
				$params->insert();
				$params->id_main = $id_main;
				$params->code = 'paramIdForModel';
				$params->value = '';
				$params->save();
			}

			$params->where("code='paramIdForTypePrefix'");
			$params->andWhere('id_main=?', $id_main);

			if (!$params->fetchOne()) {
				$params->insert();
				$params->id_main = $id_main;
				$params->code = 'paramIdForTypePrefix';
				$params->value = '';
				$params->save();
			}

			file_put_contents(SE_ROOT . '/system/logs/market_2.upd', date('Y-m-d H:i:s'));
		}
	}

	private function createFileMarket($filename = 'market_new.yml')
	{
		if (!SE_DB_ENABLE) return;

		$thisprj = '';
		if (file_exists('sitelang.dat'))
			$thisprj = trim(join('', file('sitelang.dat')));
		if (!empty($thisprj)) $thisprj .= '/';

		$link = se_db_query("SELECT * FROM `main` WHERE `lang`='rus'");

		if (!empty($link))
			$line = se_db_fetch_assoc($link);
		else return;

		$main_id   = $line['id'];
		$base_curr = $line['basecurr'];
		$is_manual = $line['is_manual_curr_rate'];
		$esupport  = $line['esupport'];

		$params = new seTable('shop_integration_parameter');
		$params->select('code, value');
		//$params->where('`id_main`=?', $main_id);
		$params = $params->getList();

		$is_store = $is_pickup = $is_delivery = $sales_note = '';
		$local_delivery_cost = $local_delivery_days = 0;
		$show_features = false;
		$show_modifications = false;
		$vendor_model = false;
		$model_id = 0;
		$type_pefix_id = 0;
		foreach ($params as $item) {
			switch ($item['code']) {
				case 'isYAStore':
					$is_store = $item['value'];
					break;
				case 'isPickur':
				case 'isPickup':
					$is_pickup = $item['value'];
					break;
				case 'isDelivery':
					$is_delivery = $item['value'];
					break;
				case 'localDeliveryCost':
					$local_delivery_cost = $item['value'];
					break;
				case 'localDeliveryDays':
					$local_delivery_days = $item['value'];
					break;
				case 'salesNotes':
					$sales_note = $this->replace($item['value']);
					break;
				case 'exportFeatures':
					$show_features = (bool)$item['value'];
					break;
				case 'exportModifications':
					$show_modifications = (bool)$item['value'];
					break;
				case 'enabledVendorModel':
					$vendor_model = (bool)$item['value'];
					break;
				case 'paramIdForModel':
					$model_id = (int)$item['value'];
					break;
				case 'paramIdForTypePrefix':
					$type_pefix_id = (int)$item['value'];
					break;
			}
		}
		//  записывать ли доставку
		//$show_delivery = (((int)$local_delivery_cost == 0) && ((int)$local_delivery_days == 0)) ? false : true;
		$show_delivery = $is_delivery;

		$is_store = $this->getBool($is_store);
		$is_pickup = $this->getBool($is_pickup);
		$is_delivery = $this->getBool($is_delivery);
		if (empty($line['domain'])) {
			$shopurl = _HTTP_ . $_SERVER['HTTP_HOST'];
		} else {
			if (strpos($line['domain'], '://') === false) {
				$line['domain'] = preg_replace("/.*:\\/\\//", '', $line['domain']);
				$shopurl = _HTTP_ . $line['domain'];
			} else {
				$shopurl = $line['domain'];
			}
			$hosts = (file_exists('hostname.dat')) ? file('hostname.dat') : array();
			foreach ($hosts as $host) {
				list($dom, $prj) = explode("\t", $host);
				if (str_replace('www.', '', $line['domain']) == str_replace('www.', '', $dom)) {
					$thisprj = trim($prj) . '/';
				}
			}
		}

		$page = $this->shoppage($thisprj);
		if (!$page) return;

		$name = $this->replace($line['shopname']);
		if (!$name) $name = $shopurl;
		if (strlen($name) > 0) $name = mb_substr($name, 0, 20);
		$company = $line['company'];
		$catpage = $page['page'];

		$text = '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE yml_catalog SYSTEM "shops.dtd"><yml_catalog></yml_catalog>';

		$yml = new SimpleXMLElement($text);
		$yml->addAttribute('date', date('Y-m-d H:i'));

		$shop = $yml->addChild('shop');
		$shop->addChild('name', $name);
		$shop->addChild('company', $company);
		$shop->addChild('url', $shopurl);
		//$shop->addChild('platform', 'CMS SiteEdit');
		//$shop->addChild('version', '5.2');
		$shop->addChild('email', $esupport);

		//  валюта
		$currencies = $shop->addChild('currencies');

		$money = $this->getCurrencies($is_manual);

		foreach ($money as $key => $val) {
			$currency = $currencies->addChild('currency');
			$currency->addAttribute('id', $key);
			$currency->addAttribute('rate', $val);
		}

		//  категории		
		$categories = $shop->addChild('categories');

		$pg = plugin_shopgroups::getInstance();
		$groups = $pg->getAllGroups();

		foreach ($groups as $key => $group) {
			if (is_numeric($key)) {
				$category = $categories->addChild('category', $this->replace($group['name']));
				$category->addAttribute('id', $group['id']);
				if (!empty($group['parent'])) {
					$category->addAttribute('parentId', $group['parent']);
				}
			}
		}

		//  получаем доставку
		if ($show_delivery) {
			$delivery_opt = array();

			$deliveries = $this->getDeliveries();

			foreach ($deliveries as $row) {
				$row['price'] = (int)$row['price'];
				$delivery_opt[$row['id_group']][] = $row;
			}
			$delivery_options = $shop->addChild('delivery-options');
			$delivery_option = $delivery_options->addChild('option');
			$delivery_option->addAttribute('cost', (int)$local_delivery_cost);
			$delivery_option->addAttribute('days', $local_delivery_days);
		}

		//  товары
		$offers = $shop->addChild('offers');

		$shop_price = new seTable('shop_price', 'sp');
		$shop_price->select("
			sp.id, 
			sp.code, 
			sg.code_gr,
			sp.price, 
			sp.article, 
			sp.curr, 
			sp.discount, 
			sp.max_discount, 
			sp.id_group, 
			sp.name, 
			sp.note, 
			sp.text, 
			sp.presence_count, 
			sp.step_count, 
			sg.lang, 
			(SELECT GROUP_CONCAT(sha.id_acc) FROM shop_accomp sha WHERE sha.id_price=sp.id) as rec, 
			(SELECT GROUP_CONCAT(shi.picture ORDER BY `default` DESC SEPARATOR '||') FROM shop_img shi WHERE shi.id_price=sp.id ORDER BY shi.`default` DESC, shi.sort ASC) as imgs, 
			(SELECT b.name FROM shop_brand b WHERE b.id=sp.id_brand) as brand, 
			sp.market_category,
			(SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications");
		$shop_price->innerjoin('shop_group sg', 'sg.id=sp.id_group');
		$shop_price->where('sp.`enabled`="Y"');
		$shop_price->andWhere('sp.`name`<>""');
		$shop_price->andWhere('sg.lang="rus"');
		$shop_price->andWhere('sp.is_market=1');
		if (!$show_modifications) {
			$shop_price->andWhere('sp.price>0');
			$shop_price->andWhere('(sp.presence_count!=0 OR sp.presence_count IS NULL)');
		} else {
			$shop_price->having('modifications > 0 OR (sp.price > 0 AND (sp.presence_count!=0 OR sp.presence_count IS NULL))');
		}
		$shop_price->orderBy('sp.name', 1);
		//echo $shop_price->getSql();

		$pricelist = $shop_price->getList();

		$available = 'true';

		if ($show_features || $show_modifications || $vendor_model) {
			$params = $this->getFeatureParams();
		}

		$mcategories = $this->getMarketCategories();

		foreach ($pricelist as $product) {
			if (empty($product['lang'])) $product['lang'] = 'rus';

			if ($show_features) {
				$features = $this->getProductFeatures($product['id']);
			}

			$images = array_slice(explode('||', $product['imgs']), 0, 10);

			if (empty($product['note'])) $product['note'] = $product['text'];
			$product['note'] = $this->replace(htmlspecialchars(strip_tags($product['note']), ENT_QUOTES));

			$product['name'] = $this->replace($product['name']);
			$product['brand'] = $this->replace($product['brand']);

			if ($vendor_model) {
				$product['model'] = $product['name'];
				$product['type_prefix'] = '';

				if (($model_id && $params[$model_id]) || ($type_pefix_id && $params[$type_pefix_id])) {
					list($model, $type_prefix) = $this->getVendorModel($product['id'], $model_id, $type_pefix_id, $features);
					if ($model)
						$product['model'] = $model;
					if ($type_prefix)
						$product['type_prefix'] = $type_prefix;
				}
			}


			if ($show_modifications && $product['modifications']) {
				$modifications = $this->getProductModifications($product['id']);
				if (!empty($modifications)) {
					foreach ($modifications as $mod) {
						$pa = new plugin_shopamount(0, $product, 0, 1, $mod['id']);
						$price = $pa->getPrice(true);

						if ($price == 0)
							continue;

						$oldprice = 0;
						if ($pa->getDiscount()) {
							$oldprice = $pa->getPrice(false);
						}

						$offer = $offers->addChild('offer');
						$offer->addAttribute('id', $product['id'] . str_replace(',', '', $mod['id']));
						$offer->addAttribute('available', $available);

						$offer->addAttribute('group_id', $product['id']);

						$offer->addChild('url', $shopurl  . '/' . $catpage . '/' . $product['code_gr'] . '/' . $product['code'] .  URL_END . '?m=' . $mod['id']);

						$offer->addChild('price', $price);

						$offer->addChild('vendorCode', $product['article']);

						if (!empty($oldprice))
							$offer->addChild('oldprice', $oldprice);

						$offer->addChild('currencyId', $this->convert_curr($product['curr']));
						$offer->addChild('categoryId', $product['id_group']);

						if (!empty($product['market_category']) && !empty($mcategories[$product['market_category']]))
							$offer->addChild('market_category', $mcategories[$product['market_category']]);

						if (!empty($images)) {
							foreach ($images as $val) {
								$offer->addChild('picture', $shopurl . '/images/' . $product['lang'] . '/shopprice/' . $val);
							}
						}

						$offer->addChild('store', $is_store);
						$offer->addChild('pickup', $is_pickup);
						$offer->addChild('delivery', $is_delivery);

						if (isset($delivery_opt[$product['id_group']]) && $show_delivery) {
							$delivery_options = $offer->addChild('delivery-options');
							foreach ($delivery_opt[$product['id_group']] as $item) {
								$delivery_option = $delivery_options->addChild('option');
								$delivery_option->addAttribute('cost', $item['price']);
								$delivery_option->addAttribute('days', $item['time']);
							}
						}

						if ($vendor_model) {
							$offer->addAttribute('type', 'vendor.model');
							if (!empty($product['type_prefix']))
								$offer->addChild('typePrefix', $product['type_prefix']);
							$offer->addChild('model', $product['model']);
						} else {
							$offer->addChild('name', $product['name']);
						}

						if (!empty($product['brand']))
							$offer->addChild('vendor', $product['brand']);
						if (!empty($product['note']))
							$offer->addChild('description', $product['note']);
						if (!empty($sales_note))
							$offer->addChild('sales_notes', $sales_note);
						if (!empty($product['rec']))
							$offer->addChild('rec', $product['rec']);

						if ($mod['features']) {
							foreach ($mod['features'] as $key => $val) {
								$param = $offer->addChild('param', $this->replace($val));
								$param->addAttribute('name', $params[$key]['name']);
							}
						}

						if (!empty($features)) {
							foreach ($features as $feature) {
								$param = $offer->addChild('param', $this->replace($feature['value']));
								$param->addAttribute('name', $params[$feature['id']]['name']);

								if ($params[$feature['id']]['measure'])
									$param->addAttribute('unit', $params[$feature['id']]['measure']);
							}
						}
					}
				}
			} else {
				$plugin_amount = new plugin_shopamount(0, $product, 0, 1);
				$count = (int)$plugin_amount->getPresenceCount();
				$price =  $plugin_amount->getPrice(true);
				$discount = $plugin_amount->getDiscount();
				$oldprice = '';
				if ($discount > 0) {
					$oldprice = $plugin_amount->getPrice(false);
				}
				unset($plugin_amount);

				$offer = $offers->addChild('offer');
				$offer->addAttribute('id', $product['id']);
				$offer->addAttribute('available', $available);

				$offer->addChild('url', $shopurl  . '/' . $catpage . '/' . $product['code_gr'] . '/' . $product['code'] . URL_END);
				$offer->addChild('price', $price);

				$offer->addChild('vendorCode', $product['article']);

				if (!empty($oldprice))
					$offer->addChild('oldprice', $oldprice);

				$offer->addChild('currencyId', $this->convert_curr($product['curr']));
				$offer->addChild('categoryId', $product['id_group']);

				if (!empty($product['market_category']) && !empty($mcategories[$product['market_category']]))
					$offer->addChild('market_category', $mcategories[$product['market_category']]);

				if (!empty($images)) {
					foreach ($images as $val) {
						if ($val) {
							$offer->addChild('picture', $shopurl . '/images/' . $product['lang'] . '/shopprice/' . $val);
						}
					}
				}

				$offer->addChild('store', $is_store);
				$offer->addChild('pickup', $is_pickup);
				$offer->addChild('delivery', $is_delivery);

				if (isset($delivery_opt[$product['id_group']]) && $show_delivery) {
					$delivery_options = $offer->addChild('delivery-options');
					foreach ($delivery_opt[$product['id_group']] as $item) {
						$delivery_option = $delivery_options->addChild('option');
						$delivery_option->addAttribute('cost', $item['price']);
						$delivery_option->addAttribute('days', $item['time']); // $item['time']
					}
				}

				if ($vendor_model) {
					$offer->addAttribute('type', 'vendor.model');
					if (!empty($product['type_prefix']))
						$offer->addChild('typePrefix', $product['type_prefix']);
					$offer->addChild('model', $product['model']);
				} else {
					$offer->addChild('name', $product['name']);
				}

				if (!empty($product['brand']))
					$offer->addChild('vendor', $product['brand']);
				if (!empty($product['note']))
					$offer->addChild('description', $product['note']);
				if (!empty($sales_note))
					$offer->addChild('sales_notes', $sales_note);
				if (!empty($product['rec']))
					$offer->addChild('rec', $product['rec']);

				if (!empty($features)) {
					foreach ($features as $feature) {
						$param = $offer->addChild('param', $this->replace($feature['value']));
						$param->addAttribute('name', $params[$feature['id']]['name']);

						if ($params[$feature['id']]['measure'])
							$param->addAttribute('unit', $params[$feature['id']]['measure']);
					}
				}
			}
		}

		$file = fopen($filename, 'w+');
		fwrite($file, $yml->asXML());
		fclose($file);

		return true;
	}

	public function getVendorModel($id_product, $id_model, $id_type_prefix, $features = array())
	{
		$model = $type_prefix = '';
		if (!empty($features)) {
			foreach ($features as $val) {
				if ($id_model == $val['id'])
					$model = $val['value'];
				if ($id_type_prefix == $val['id'])
					$type_prefix = $val['value'];
			}
		}
		if (!$model || !$type_prefix) {
			$shop_feature = new seTable('shop_feature', 'sf');
			$shop_feature->select("DISTINCT sf.id,
				GROUP_CONCAT(CASE    
					WHEN (sf.type = 'list' OR sf.type = 'colorlist') THEN (SELECT sfvl.value FROM shop_feature_value_list sfvl WHERE sfvl.id = smf.id_value)
					WHEN (sf.type = 'number') THEN smf.value_number
					WHEN (sf.type = 'bool') THEN smf.value_bool
					WHEN (sf.type = 'string') THEN smf.value_string 
					ELSE NULL
				END SEPARATOR ', ') AS value
			");
			$shop_feature->innerJoin('shop_modifications_feature smf', 'sf.id=smf.id_feature');
			$shop_feature->where('smf.id_price=?', $id_product);
			$shop_feature->andWhere('smf.id_modification IS NULL');
			$shop_feature->andWhere('sf.id IN (?)', $id_model . ',' . $id_type_prefix);
			$shop_feature->groupBy('sf.id');
			$list = $shop_feature->getList();
			foreach ($list as $val) {
				if ($id_model == $val['id'])
					$model = $val['value'];
				if ($id_type_prefix == $val['id'])
					$type_prefix = $val['value'];
			}
		}

		return array($model, $type_prefix);
	}

	public function getMarketCategories()
	{
		$filename = __DIR__ . '/market/market_categories.json';
		$categories = array();

		if (file_exists($filename))
			$categories = json_decode(file_get_contents($filename), 1);

		return $categories;
	}

	private function getCurrencies($is_manual)
	{
		$curr = new seTable('money_title', 'mt');
		$curr->select('mt.name, (SELECT m.kurs FROM money m WHERE m.money_title_id=mt.id ORDER BY created_at DESC LIMIT 1) as val');
		$curr->where("lang='rus'");
		$curr->andwhere('cbr_kod IS NOT NULL');
		$currlist = $curr->getList();

		$money = array();
		$base = $this->convert_curr(se_BaseCurrency());

		foreach ($currlist as $it) {
			if (!$is_manual) {
				$it['val'] = se_MoneyConvert(1, $it['name'], $base);
			}
			$it['name'] = $this->convert_curr($it['name']);

			$money[$it['name']] = str_replace(',', '.', $it['val']);
		}
		$money[$base] = 1;
		return $money;
	}

	private function getDeliveries()
	{
		$delivery = new seTable('shop_deliverygroup', 'sg');
		$delivery->select('sg.id_group, sd.price, sd.time');
		$delivery->innerjoin('shop_deliverytype sd', 'sd.id=sg.id_type');
		$delivery->where('sd.lang="rus" AND sd.status="Y"');
		$delivery->orderby('sg.id_group');
		$delivery->addorderby('sd.time');
		$list = $delivery->getList();

		return $list;
	}

	private function getFeatureParams()
	{
		$params = array();
		$shop_feature = new seTable('shop_feature');
		$shop_feature->select('id, name, type, measure');
		$list = $shop_feature->getList();
		if (!empty($list)) {
			foreach ($list as $val) {
				$val['name'] = $this->replace($val['name']);
				$params[$val['id']] = $val;
			}
		}
		return $params;
	}

	private function getProductFeatures($id_product)
	{
		if (empty($id_product)) return;

		$shop_feature = new seTable('shop_feature', 'sf');
		$shop_feature->select("DISTINCT sf.id,
			GROUP_CONCAT(CASE    
				WHEN (sf.type = 'list' OR sf.type = 'colorlist') THEN (SELECT sfvl.value FROM shop_feature_value_list sfvl WHERE sfvl.id = smf.id_value)
				WHEN (sf.type = 'number') THEN smf.value_number
				WHEN (sf.type = 'bool') THEN smf.value_bool
				WHEN (sf.type = 'string') THEN smf.value_string 
				ELSE NULL
			END SEPARATOR ', ') AS value
		");
		$shop_feature->innerJoin('shop_modifications_feature smf', 'sf.id=smf.id_feature');
		$shop_feature->where('smf.id_price=?', $id_product);
		$shop_feature->andWhere('smf.id_modification IS NULL');
		$shop_feature->andWhere('sf.is_market=1');
		$shop_feature->groupBy('sf.id');
		$shop_feature->addOrderBy('sf.sort', 0);
		$list = $shop_feature->getList();
		return $list;
	}

	private function recursiveModifications($modifications = array())
	{
		$result = array();
		if (!empty($modifications)) {
			$first = array_shift($modifications);
			if (!empty($modifications)) {
				$second = array_shift($modifications);
				foreach ($first as $val1) {
					foreach ($second as $val2) {
						$result[] = array(
							//'name' => array_merge($val1['name'],  $val2['name']),
							//'url' => $val1['url'] . '&' . $val2['url'],
							'id' => $val1['id'] . ',' . $val2['id'],
							'features' => $val1['features'] + $val2['features']
						);
					}
				}
				array_unshift($modifications, $result);
				$result = $this->recursiveModifications($modifications);
			} else
				$result = $first;
		}
		return $result;
	}

	private function getProductModifications($id_price, $in_stock = true)
	{

		$shop_modifications = new seTable('shop_modifications', 'sm');
		$shop_modifications->select('sm.id, sm.id_mod_group as gid, (SELECT sort FROM shop_modifications_group WHERE sm.id_mod_group = id) AS gsort, GROUP_CONCAT(sf.id , "#!#", sf.name, "#!#", sfvl.value, "#!#", sfvl.id SEPARATOR "~!~") AS feature');
		$shop_modifications->innerJoin('shop_modifications_feature smf', 'sm.id=smf.id_modification');
		$shop_modifications->innerJoin('shop_feature sf', 'sf.id=smf.id_feature');
		$shop_modifications->innerjoin('shop_feature_value_list sfvl', 'smf.id_value=sfvl.id');
		$shop_modifications->where('sm.id_price=?', $id_price);
		if ($in_stock)
			$shop_modifications->andWhere('(sm.count <> 0 OR sm.count IS NULL)');
		$shop_modifications->groupBy('sm.id');
		$shop_modifications->orderBy('gsort', 0);
		$shop_modifications->addOrderBy('sf.sort', 0);
		$shop_modifications->addOrderBy('sfvl.sort', 0);
		//$shop_modifications->addOrderBy('sfvl.value', 0);
		$list = $shop_modifications->getList();

		$modifications = array();
		if (!empty($list)) {
			foreach ($list as $val) {
				if (!empty($val['feature'])) {

					$feature_list = explode('~!~', $val['feature']);
					foreach ($feature_list as $line) {
						list($fid, $fname, $fvalue, $vid) = explode('#!#', $line);

						$gid = $val['gid'];
						$mid = $val['id'];

						if (!isset($modifications[$gid][$mid])) {
							$modifications[$gid][$mid] = array(
								//'name' => '',
								//'url' => 'm['.$gid.']='.$mid,
								'id' => $mid,
								'features' => array()
							);
						}
						$modifications[$gid][$mid]['features'][$fid] = $fvalue;
						//$modifications[$gid][$mid]['name'][] = $fname . ': ' . $fvalue;
					}
				}
			}
		}

		if ($in_stock) {
			$sm = new seTable('shop_modifications', 'sm');
			$sm->select('COUNT(DISTINCT sm.id_mod_group) AS cm');
			$sm->where('sm.id_price=?', $id_price);
			$sm->fetchOne();
			if ($sm->cm != count($modifications))
				return;
		}

		$modifications = $this->recursiveModifications($modifications);
		return $modifications;
	}

	private function shoppage($folder)
	{
		//  check pages
		$pages = simplexml_load_file('projects/' . $folder . 'pages.xml');
		foreach ($pages->page as $page) {
			$pagecontent = simplexml_load_file('projects/' . $folder . 'pages/' . $page['name'] . '.xml');
			foreach ($pagecontent->sections as $section) {
				if (strpos($section->type, 'vitrine') !== false) {
					return array('page' => $page['name'], 'id' => $section->id);
				}
			}
		}

		return false;
	}

	private function convert_curr($name)
	{
		return str_replace(array('KAT', 'BER', 'RUR', 'UKH'), array('KZT', 'BYR', 'RUB', 'UAH'), $name);
	}

	private function getBool($int)
	{
		return ($int) ? 'true' : 'false';
	}

	private function replace($text)
	{
		$search = array('&', '"', '>', '<', "'");
		$replace = array('&amp;', '&quot;', '&gt;', '&lt;', '&apos;');
		$text = str_replace($search, $replace, $text);
		return $text;
	}
}
