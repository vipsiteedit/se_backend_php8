<?php

class plugin_shoppreorder
{

	public function __construct()
	{
		$this->updateDB();
	}

	public function updateDB()
	{
		if (!file_exists(SE_ROOT . '/system/logs/shop_preorder.upd')) {
			$sql = "CREATE TABLE IF NOT EXISTS shop_preorder (
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				id_price int(10) UNSIGNED NOT NULL,
				count float(5,2) UNSIGNED DEFAULT 1,
				name varchar(255) NOT NULL,
				email varchar(255) NOT NULL,
				phone varchar(255) DEFAULT NULL,
				modifications varchar(255) DEFAULT NULL,
				send_mail tinyint(1) NOT NULL DEFAULT 0,
				updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
				created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id),
				CONSTRAINT FK_shop_preorder_shop_price_id FOREIGN KEY (id_price)
				REFERENCES shop_price (id) ON DELETE CASCADE ON UPDATE CASCADE
				)
				ENGINE = INNODB
				CHARACTER SET utf8
				COLLATE utf8_general_ci;";
			se_db_query($sql);
			file_put_contents(SE_ROOT . '/system/logs/shop_preorder.upd', date('Y-m-d H:i:s'));
		}
		if (!file_exists(SE_ROOT . '/system/logs/shop_mail_preorder.upd')) {
			$mail_template['preorderuser'] = array(
				'title' => 'Письмо клиенту о предзаказе',
				'subject' => 'Заявка на поступление товара',
				'letter' => '<table style="margin-right:auto;margin-left:auto" cellpadding="0" cellspacing="0" width="700px">
					<tbody>
						<tr><td>
						<p>Здравствуйте, <strong>[CLIENT.NAME].</strong></p>
						<br>
						<p><strong>Ваш предзаказ</strong></p>
						<p>[PRODUCT.NAME]</p>
						<br>
						<p>Ваша заявка принята.</p>
						<p>Когда товар появится на складе, Вам поступит уведомление о наличии.</p>
						<br>
						<p>С уважением, [ADMIN_COMPANY]</p>
						<br>
						<p>Наши контакты</p>
						<p>[ADMIN_MAIL_SALES] - отдел продаж</p>
						<p>[ADMIN_MAIL_SUPPORT] - техподдержка</p>
						</td></tr>
					</tbody>
				</table>'
			);

			$mail_template['preorderadmin'] = array(
				'title' => 'Письмо администратору о предзаказе',
				'subject' => 'Заявка на поступление товара',
				'letter' => '<table style="margin-right:auto;margin-left:auto" cellpadding="0" cellspacing="0" width="700">
					<tbody>
						<tr><td>
						<p>Здравствуйте, <strong>Администратор.</strong></p>
						<br>
						<p>С вашего сайта [THISNAMESITE] поступила заявка на предзаказ.</p>
						<br>
						<p>[PRODUCT.NAME]</p>
						<br>
						<p>Данные пользователя:</p>
						<p>Имя: [CLIENT.NAME]</p>
						<p>E-mail: [CLIENT.EMAIL]</p>
						<p>Телефон: [CLIENT.PHONE]</p>
						</td></tr>
					</tbody>
				</table>'
			);

			$mail_template['notifystockuser'] = array(
				'title' => 'Письмо клиенту о поступлении товара',
				'subject' => 'Товар поступил на склад',
				'letter' => '<table style="margin-right:auto;margin-left:auto" cellpadding="0" cellspacing="0" width="700">
					<tbody>
						<tr><td>
						<p>Здравствуйте, <strong>[CLIENT.NAME].</strong></p>
						<br>
						<p>Товар, на который Вы оставляли заявку, поступил на склад:</p>
						<br>
						<p>[PRODUCT.NAME]</p>
						<br>
						<p><a href="[PRODUCT.LINK]">Перейти на сайт</a></p>
						<br>
						<p>С уважением, [ADMIN_COMPANY]</p>
						<br>
						<p>Наши контакты</p>
						<p>[ADMIN_MAIL_SALES] - отдел продаж</p>
						<p>[ADMIN_MAIL_SUPPORT] - техподдержка</p>
						</td></tr>
					</tbody>
				</table>'
			);

			foreach ($mail_template as $key => $val) {
				$sm = new seTable('shop_mail');
				$sm->select('id');
				$sm->where('mailtype="?"', $key);
				if (!$sm->fetchOne()) {
					$sm->insert();
					$sm->title = $val['title'];
					$sm->subject = $val['subject'];
					$sm->letter = $val['letter'];
					$sm->mailtype = $key;
					$sm->save();
				}
			}

			file_put_contents(SE_ROOT . '/system/logs/shop_mail_preorder.upd', date('Y-m-d H:i:s'));
		}
	}

	public function addPreorder($fields = array())
	{

		if (empty($_SESSION['modifications'][$fields['id_product']])) $_SESSION['modifications'][$fields['id_product']] = null;
		$modifications = join(',', $_SESSION['modifications'][$fields['id_product']]);
		$fields['modifications'] = $modifications;
		$sp = new seTable('shop_preorder');
		$sp->addField('modifications', 'varchar(255)');
		$sp->insert();
		$sp->id_price = $fields['id_product'];
		$sp->modifications = $modifications;
		$sp->name = $fields['name'];
		$sp->email = $fields['email'];
		if (!empty($fields['count']))
			$sp->count = $fields['count'];
		if (!empty($fields['phone']))
			$sp->phone = $fields['phone'];
		$id = $sp->save();

		if ($id) {
			$this->log('new preorder - ' . $id);
			$this->sendMailPreorder($fields);
		}

		return $id;
	}

	private function sendMailPreorder($fields)
	{
		$mails = new plugin_shopmail();

		$params = array(
			'THISNAMESITE' => $_SERVER['HTTP_HOST'],
			'CLIENT.NAME' => $fields['name'],
			'CLIENT.EMAIL' => $fields['email'],
			'CLIENT.PHONE' => $fields['phone']
		);

		if ($fields['id_product']) {
			if ($fields['modifications']) {
				$mod = new plugin_shopmodifications($fields['id_product']);
				$modification = $mod->getName(explode(',', $fields['modifications']));
			}
			$psg = new plugin_shopgoods();
			$product = $psg->getGoods(array(), $fields['id_product']);
			if ($product = $product[0][0]) {
				$plugin_amount = new plugin_shopamount(0, $product);
				if ($modification) $product['name'] .= ' (' . $modification . ')';
				$params['PRODUCT.NAME'] = $product['name'];
				if ($product['img']) {
					$imgurl = '/images/' . se_getLang() . '/shopprice/' . $product['img'];
					$params['PRODUCT.IMAGE'] = '<a href="http://' . $_SERVER['HTTP_HOST'] . $imgurl . '" target="_blank"><img src="http://' . $_SERVER['HTTP_HOST'] . $imgurl . '" width="100" border=0></a>';
				}
				$params['PRODUCT.PRICE'] = $plugin_amount->showPrice(true);
			}
		}
		$result = $mails->sendmail('preorderuser', $fields['email'], $params);
		$this->log('send mail "preorderuser" - ' . $result);

		$result = $mails->sendmail('preorderadmin', '', $params);
		$this->log('send mail "preorderadmin" - ' . $result);
	}

	public function checkProductCount($id_price = 0)
	{
		if (!$id_price) return;

		$plugin_amount = new plugin_shopamount($id_price);
		$count = $plugin_amount->getPresenceCount();

		$this->log('check product - ' . $id_price . ', count - ' . $count);

		if ($count != 0) {
			$sp = new setable('shop_preorder');
			$sp->addField('modifications', 'varchar(255)');
			$sp->select('id, id_price, modifications, email, name, phone');
			$sp->where('id_price=?', $id_price);
			$sp->andWhere('send_mail=0');
			$list = $sp->getList();

			if (!empty($list)) {
				$this->log('find preorders, count - ' . count($list));
				foreach ($list as $val) {
					if ($this->sendMailNotify($val)) {
						$sp->find($val['id']);
						$sp->send_mail = 1;
						$sp->save();
					}
				}
			}
		}
	}

	private function sendMailNotify($fields)
	{
		$mails = new plugin_shopmail();

		$params = array(
			'THISNAMESITE' => $_SERVER['HTTP_HOST'],
			'CLIENT.NAME' => $fields['name'],
			'CLIENT.EMAIL' => $fields['email'],
			'CLIENT.PHONE' => $fields['phone']
		);

		if ($fields['id_price']) {
			if ($fields['modifications']) {
				$mod = new plugin_shopmodifications($fields['id_price']);
				$modification = $mod->getName(explode(',', $fields['modifications']));
			}
			$psg = new plugin_shopgoods();
			$product = $psg->getGoods(array(), $fields['id_price']);
			if ($product = $product[0][0]) {
				$plugin_amount = new plugin_shopamount(0, $product);
				if ($modification) $product['name'] .= ' (' . $modification . ')';
				$params['PRODUCT.NAME'] = $product['name'];
				if ($product['img']) {
					$imgurl = '/images/' . se_getLang() . '/shopprice/' . $product['img'];
					$params['PRODUCT.IMAGE'] = '<a href="http://' . $_SERVER['HTTP_HOST'] . $imgurl . '" target="_blank"><img src="http://' . $_SERVER['HTTP_HOST'] . $imgurl . '" width="100" border=0></a>';
				}
				$params['PRODUCT.PRICE'] = $plugin_amount->showPrice(true);
			}
		}

		$result = $mails->sendmail('notifystockuser', $fields['email'], $params);
		$this->log('send mail "notifystockuser" ' . $fields['email'] . ' - ' . $result);
		$result = true;
		return $result;
	}

	private function log($text)
	{
		$log_dir = SE_ROOT . 'system/logs/preorder/';
		if (!is_dir($log_dir))
			mkdir($log_dir);
		$log_file = $log_dir . 'preorder_' . date('Y-m') . '.log';
		$text = date('[Y-m-d H:i:s]') . ' ' . $text . "\r\n";
		$file = fopen($log_file, 'ab');
		fwrite($file, $text);
		fclose($file);
	}
}
