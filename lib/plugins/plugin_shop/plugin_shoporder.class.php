<?php

/**
 * Класс создания заказа
 * 
 */

class plugin_shopOrder
{
	private $incart = array();
	private $indelivery = array();
	private $basecurr = 'RUR';
	private $email;
	private $param = array('Код', 'Картинка', 'Наименование', 'Цена', 'Скидка', 'Кол-во', 'Сумма');
	private $user_id;
	private $company_id;
	private $username = '';
	private $userdiscount = 0;
	private $useremail = '';
	private $phone = '';
	private $goodslist = '';
	private $summ_discount = 0;
	private $summ_delivery = 0;
	private $summ_order = 0;
	private $show_summ_order = '';
	private $show_summ_discount = '';
	private $show_summ_delivery = '';
	private $show_summ_all = '';
	private $status = 'N';

	public $commentary = '';
	public $inpayee = false;
	public $date_credit = null;
	public $payment_type = '';
	private $order_id;
	private $summ_all = 0;

	/**
	 * @param integer $user_id   ID пользователя
	 * @param array  $incart	 Массив товаров  array('price_id'=>1,'count'=>1,'name'=>'','action'=>'1000011122:version=6')
	 **/
	function __construct($user_id, $incart = array(), $name_user = '')
	{
		setlocale(LC_NUMERIC, 'C');
		$this->incart = $incart;
		$this->user_id = $user_id;
		$this->basecurr = se_baseCurrency();

		if ($this->user_id) {
			$user = new seUser();
			$user->find($user_id);
			if (empty($user->id_company)) {
				$person = $user->getPerson();
				$this->username = trim($person->last_name . ' ' . $person->first_name . ' ' . $person->sec_name);
				$this->userdiscount = $person->discount;
				$this->useremail = $person->email;
				$this->phone = $person->phone;
			} else {
				$company = new seTable("company");
				$company->find($user->id_company);
				$this->username = $company->name;
				$this->useremail = $company->email;
				$this->phone = $company->phone;
				$this->company_id = $user->id_company;
			}
		} else {
			$this->userdiscount = 0;
			$this->username = $name_user;
		}
	}

	public function getItemList()
	{
		$data = array();
		foreach ($this->incart as $item) {
			if (isset($item['discount']) && ($item['curr'] != $this->basecurr)) {
				$item['discount'] = se_MoneyConvert($item['discount'], $item['curr'], $this->basecurr);
			}

			if (isset($item['price']) && ($item['curr'] != $this->basecurr)) {
				$item['price'] = se_MoneyConvert($item['price'], $item['curr'], $this->basecurr);
			}

			if (!empty($item['paramsname'])) {
				$item['name'] .= ' (' . $item['paramsname'] . ')';
			}

			if (!empty($item['action'])) {
				// Если заказ определяется по типу
				list($action_serial,) = explode(":", $item['action']);
				$item['name'] .= ' :: ' . $action_serial;
			} else $item['action'] = '';

			if (!empty($item['article']))
				$item['code'] = $item['article'];

			//echo $item['code'];
			//exit;

			$data[] = array(
				'price_id' => $item['price_id'],
				'code' => $item['code'],
				'discount' => $item['discount'],
				'count' => $item['count'],
				'action' => $item['action'],
				'name' => $item['name'],
				'price' => $item['price'],
				'commentary' => $item['commentary'],
				'params' => $item['params']
			);
		}

		return $data;
	}


	/**
	 * Формирование заказа
	 * @param array  $indelivery Параметры доставки ([id], [phone],[calltime],[address],[postindex])
	 * @param string $email	  Email пользователя, куда отправлять заказ
	 * @param array  $param	  Список заголовков  array('Код', 'Наименование','Цена','Скидка','Кол-во','Сумма')
	 */
	public function execute($indelivery = array(), $email = '', $param = array(), $discount = 0, $coupon = null)
	{
		// Формирование данных для отображения заказа
		if (!empty($email))
			$this->useremail = $email;

		if (!empty($indelivery['phone']) && empty($this->phone)) {
			$person = new seTable('person');
			$person->select('id, phone');
			$person->find($this->user_id);
			$person->phone = $indelivery['phone'];
			$person->save();
		}


		if (!empty($param))
			$this->param = $param;

		$this->indelivery = $indelivery;

		if (!empty($this->incart)) {
			$this->summ_discount = 0;
			$this->summ_delivery = 0;
			$this->summ_order = 0;
			$this->goodslist = '';

			$itemlist = $this->getItemList();

			if (empty($this->indelivery['summ'])) {
				if (!empty($this->indelivery['id'])) {
					$delivery = new seTable();
					$delivery->from('shop_deliverytype', 'dt')->find($this->indelivery['id']);
					if ($delivery->forone == 'Y') {
						$delsumm = 0;
						foreach ($itemlist as $item) {
							$delsumm += $delivery->price * $item['count'];
						}
					} else
						$delsumm = $delivery->price;

					$this->summ_delivery = se_MoneyConvert($delsumm, $delivery->curr, $this->basecurr);
				}
			} else {
				$this->summ_delivery = $this->indelivery['summ'];
			}

			if ($this->user_id) {
				$order = new seShopOrder();
				$order->user_id = $this->user_id;
				$order->date_order = date('Y-m-d');
				$order->discount = $discount;
				$order->curr = $this->basecurr;
				$order->status = $this->status;

				if (!empty($this->commentary))
					$order->commentary = $this->commentary;

				if ($this->inpayee) {
					$order->inpayee = 'Y';
					$order->payment_type = $this->payment_type;
				} else
					$order->inpayee = 'N';

				if (!empty($this->date_credit))
					$order->date_credit = $this->date_credit;

				$order->delivery_payee = $this->summ_delivery;

				if ($this->indelivery['id'] > 0)
					$order->delivery_type = $this->indelivery['id'];

				$order->delivery_status = 'N';

				if (!empty($_SESSION['payment_type_id'])) {
					$order->payment_type = $_SESSION['payment_type_id'];
				}

				$order_id = $order->save();

				if (!empty($coupon['id']) && $order_id) {
					$shop_coupon_history = new seTable('shop_coupons_history');
					$shop_coupon_history->insert();
					$shop_coupon_history->code_coupon = $_SESSION['code_coupon'];
					$shop_coupon_history->id_coupon = $coupon['id'];
					$shop_coupon_history->id_user = $this->user_id;
					$shop_coupon_history->id_order = $order_id;
					$shop_coupon_history->discount = $coupon['discount'];
					$shop_coupon_history->save();
				}

				$this->order_id = $order_id;
				if ($order_id > 0) {

					if (!seUserGroup()) {
						$_SESSION['orders'][] = $order_id;
					}

					if (!empty($this->indelivery)) {
						$delivery = new seTable('shop_delivery', 'sd');
						if (!file_exists(SE_ROOT . '/system/logs/shop_delivery_id_sub.upd')) {
							if (!$delivery->isFindField('id_subdelivery')) {
								$result = se_db_query('ALTER TABLE shop_delivery ADD COLUMN id_subdelivery INT(10) UNSIGNED DEFAULT NULL AFTER id_order');
								se_db_query('ALTER TABLE shop_delivery ADD CONSTRAINT FK_shop_delivery_shop_deliverytype_id FOREIGN KEY (id_subdelivery) REFERENCES shop_deliverytype(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
							} else
								$result = true;
							if ($result)
								file_put_contents(SE_ROOT . '/system/logs/shop_delivery_id_sub.upd', date('Y-m-d H:i:s'));
						}

						$delivery->id_order = $order_id;
						$delivery->telnumber = $this->indelivery['phone'];

						if (!empty($_SESSION['delivery_sub'][$this->indelivery['id']])) {
							$delivery->id_subdelivery = $_SESSION['delivery_sub'][$this->indelivery['id']];
						}

						if (!empty($_SESSION['user_region']['id_city'])) {
							$delivery->id_city = $_SESSION['user_region']['id_city'];
						}

						$delivery->email = $this->useremail;
						$delivery->calltime = $this->indelivery['calltime'];
						$delivery->address = $this->indelivery['address'];
						$delivery->postindex = $this->indelivery['postindex'];
						$delivery->save();
					}

					// позиции товаров
					$goods = new seTable('shop_tovarorder');
					foreach ($itemlist as $item) { // Добавляем позиции товаров в заказ

						$item['count'] = $this->setCount($item['price_id'], $item['count'], $item['params']);

						$goods->insert();
						$goods->id_order = $order_id;
						$goods->id_price = $item['price_id'];
						$goods->article = $item['code'];
						$goods->nameitem = $item['name'];
						$goods->price = $item['price'];
						$goods->discount = $item['discount'];
						$goods->count = $item['count'];
						if (!empty($item['commentary']))
							$goods->commentary = $item['commentary'];
						if (!empty($item['action']))
							$goods->action = $item['action'];
						if (!empty($item['params']))
							$goods->modifications = $item['params'];
						$goods->save();
						$this->mail_item($item);
					}

					$this->setShopAccount($this->order_id);
					$this->setShopContract($this->order_id);
				} else {
					return 0;
				}
			} else {
				$this->order_id = time() - strtotime('2012-01-01'); //date('ymdHis');
				foreach ($itemlist as $item) { // Добавляем позиции товаров в заказ
					$prices = new seShopPrice();
					$prices->select('presence_count');
					$prices->find($item['price_id']);
					if ($prices->isFind() && $prices->presence_count > 0) {
						if ($item['count'] >= $prices->presence_count) {
							$item['count'] = $prices->presence_count;
						}
					}
					$this->mail_item($item);
				}
			}

			$this->summ_all = floatval($this->summ_order - $this->summ_discount + $this->summ_delivery);

			$this->show_summ_order = se_formatMoney($this->summ_order, $this->basecurr);
			$this->show_summ_discount = se_formatMoney($this->summ_discount, $this->basecurr);
			$this->show_summ_delivery = se_formatMoney($this->summ_delivery, $this->basecurr);
			$this->show_summ_all = se_formatMoney($this->summ_all, $this->basecurr);

			$vars = $this->mailtemplate();

			// письмо клиенту
			$mails = new plugin_shopmail($this->order_id, 0, 'html');
			$mails->sendmail('orderuser', $this->useremail);
			$mails->sendmail('orderadm', '');

			return $order_id;
		}
	}

	public function getUserOrders($limit)
	{
		if (empty($this->user_id)) return;
		if (!$limit)
			$limit = 30;
		$orders = new seTable('shop_order', 'so');
		$orders->select("so.id as `idorder`, so.id_author, so.date_order, so.date_payee, so.discount, so.curr, 
			so.status, so.delivery_payee, so.delivery_type, so.delivery_status, so.delivery_date, 
			(SELECT SUM((st.price-st.discount)*st.count) FROM shop_tovarorder st WHERE st.id_order = `idorder`) AS `price_tovar`, sc.date, sc.number");
		$orders->leftjoin('shop_contract sc', 'sc.id_order = so.id');
		$orders->where('so.id_author = ?', $this->user_id);
		$orders->andWhere("so.is_delete <> '?'", 'Y');
		if (!seUserGroup()) {
			$orders->andWhere('so.id IN (?)', join(',', $_SESSION['orders']));
		}
		$orders->orderby('idorder', 1);
		$orders->groupby('so.id');
		$MANYPAGE = $orders->pageNavigator($limit);
		return array($orders->getlist(), $MANYPAGE);
	}

	public function returnGood($good)
	{
		if (!empty($good['count'])) {
			if (!empty($good['modifications'])) {
				$modifications = new seTable('shop_modifications');
				$modifications->select('id, count');
				$modifications->where('id IN (?)', $good['modifications']);
				$modifications->andWhere('id_price=?', $good['id_price']);
				$list = $modifications->getList();
				if (!empty($list)) {
					foreach ($list as $val) {
						if ($val['count'] === '0' || $val['count'] > 0) {
							$modifications->update('count', 'count+' . (int)$good['count']);
							$modifications->where('id=?', $val['id']);
							$modifications->save();
						}
					}
				}
			} else {
				$shop_price = new seShopPrice();
				$shop_price->find($good['id_price']);
				if ($shop_price->presence_count === '0' || $shop_price->presence_count > 0) {
					$shop_price->update('presence_count', 'presence_count+' . (int)$good['count']);
					$shop_price->where('id=?', $good['id_price']);
					$shop_price->save();
				}
			}
		}
	}

	public function getGoods($order_id)
	{
		if (empty($order_id)) return;
		$tord = new seTable('shop_tovarorder', 'sto');
		$tord->select('sto.id_order, sto.id_price, sto.count, sto.modifications');
		$tord->innerjoin('shop_order so', 'sto.id_order=so.id');
		$tord->where('sto.id_order=?', $order_id);
		$res = $tord->getList();
		return $res;
	}

	public function deleteOrder($order_id)
	{
		if (empty($order_id) || empty($this->user_id)) return;

		if (!seUserGroup() && (empty($_SESSION['orders']) || !in_array($order_id, $_SESSION['orders']))) {
			return;
		}

		$shop_order = new seTable('shop_order');
		$shop_order->update('is_delete', "'Y'");
		$shop_order->where('id=?', $order_id);
		$shop_order->andWhere('id_author=?', $this->user_id);
		$shop_order->andWhere("is_delete<>'Y'");
		if ($shop_order->save()) {
			$goods = $this->getGoods($order_id);
			if (!empty($goods)) {
				foreach ($goods as $good) {
					$this->returnGood($good);
				}
			}
		}
	}

	public function setStatus($status = 'N')
	{
		$this->status = $status;
	}

	public function setCount($price_id, $count, $params)
	{
		if (empty($params)) {
			$prices = new seShopPrice();
			$prices->select('presence_count');
			$prices->find($price_id);
			if ($prices->isFind() && $prices->presence_count > 0) {
				if ($count >= $prices->presence_count) {
					$count = $prices->presence_count;
				}
				$prices->update('presence_count', 'presence_count-' . $count);
				$prices->where('id=?', $price_id);
				$prices->save();
			}
		} else {
			$modifications = new seTable('shop_modifications');
			$modifications->select('id, count');
			$modifications->where('id IN (?)', $params);
			$list = $modifications->getList();
			if (!empty($list)) {
				foreach ($list as $val) {
					if ($val['count'] > 0) {
						if ($count >= $val['count'])
							$count = $val['count'];
						$modifications->update('count', 'count-' . $count);
						$modifications->where('id=?', $val['id']);
						$modifications->save();
					}
				}
			}
		}

		return $count;
	}

	// Подготовка данных для отправки письма (строка заказа в виде html и подсчет общей суммы)
	private function mail_item($item)
	{
		if ($item['price_id']) {
			$shop_img = new seTable('shop_img');
			$shop_img->select('picture');
			$shop_img->where('id_price=?', $item['price_id']);
			$shop_img->orderBy('`default`', 1);
			$shop_img->addorderBy('sort');
			$shop_img->fetchone();
			$imgurl = '/images/' . se_getLang() . '/shopprice/' . $shop_img->picture;
			$item['img'] = '<a href="http://' . $_SERVER['HTTP_HOST'] . $imgurl . '" target="_blank"><img src="http://' . $_SERVER['HTTP_HOST'] . $imgurl . '" width="100" border=0></a>';
		} else {
			$item['img'] = '';
		}
		$summ_price = ($item['price'] - $item['discount']) * $item['count'];
		$this->goodslist .=
			'<tr vAlign=middle>
			<td>' . (($item['img']) ? $item['img'] . '&nbsp;' : '') . '</td>
			<td width=50>' . $item['code'] . '&nbsp;</td>
			<td>' . $item['name'] . '&nbsp;</td>
			<td>' . se_formatNumber($item['price']) . '&nbsp;</td>
			<td>' . se_formatNumber($item['discount']) . '&nbsp;</td>
			<td>' . $item['count'] . '&nbsp;</td>
			<td>' . se_formatNumber($summ_price) . '&nbsp;</td>
			</tr>';
		$this->summ_discount += $item['discount'] * $item['count'];
		$this->summ_order += $item['price'] * $item['count'];
	}

	private function setShopAccount($order_id)
	{
		$table = new seShopAccount();
		$max = $table->maxAccount();
		$table->insert();
		$table->order_id = $order_id;
		$table->account = $max + 1;
		$table->date_order = date('Y-m-d H:i:s');
		$table->save();
	}

	private function setShopContract($order_id)
	{
		$table = new seShopContract();
		$max = $table->maxNumber();
		$table->insert();
		$table->id_author = $this->user_id;
		$table->id_order = $order_id;
		$table->number = $max + 1;
		$table->date = date('Y-m-d');
		$table->save();
	}

	private function mailtemplate()
	{
		// Создание шаблона письма
		if (!empty($this->indelivery)) {
			$mail['ORDER.TELNUMBER'] = $this->indelivery['phone'];
			$mail['ORDER.CALLTIME'] = $this->indelivery['calltime'];
			$mail['ORDER.ADDRESS'] = $this->indelivery['address'];
			$mail['ORDER.POSTINDEX'] = $this->indelivery['postindex'];
			$mail['ORDER.VOLUME'] = $this->indelivery['volume'];
			$mail['ORDER.WEIGHT'] = $this->indelivery['weight'];
		}

		$mail['ORDER.COMMENTARY'] = $this->commentary;
		$mail['ORDER.EMAIL'] = $this->useremail;
		$mail['THISNAMESITE'] = $_SERVER['HTTP_HOST'];
		$mail['CURDATE'] = date("d.m.Y H:i:s");
		$mail['NAMECLIENT'] = $this->username;
		$mail['SHOP_ORDER_NUM'] = sprintf("%06u", $this->order_id);
		$mail['SHOP_ORDER_VALUE_LIST'] = '
			<table cellSpacing="1" cellPadding="3" border="0" width="100%">
			<tr class="tableRow" id="tableHeader" vAlign="middle">
				<td class="cartorder_art" width="1%">&nbsp;</td>
				<td class="cartorder_pict" width="50">' . $this->param[0] . '</td>
				<td class="cartorder_name">' . $this->param[2] . '</td>
				<td class="cartorder_price" width="50">' . $this->param[3] . '</td>
				<td class="cartorder_discount" width="50">' . $this->param[4] . '</td>
				<td class="cartorder_cn" width="50">' . $this->param[5] . '</td>
				<td class="cartorder_summ" width="50">' . $this->param[6] . '</td>
			</tr>
			' . $this->goodslist . '
			</table>';
		$mail['SHOP_ORDER_SUMM'] = se_formatMoney($this->summ_order - $this->summ_discount, $this->basecurr);
		$mail['SHOP_ORDER_DEVILERY'] = $this->show_summ_delivery;
		$mail['SHOP_ORDER_TOTAL'] = $this->show_summ_all;
		$mail['SHOP_ORDER_DISCOUNT'] = $this->show_summ_discount;

		return $mail;
	}
}
