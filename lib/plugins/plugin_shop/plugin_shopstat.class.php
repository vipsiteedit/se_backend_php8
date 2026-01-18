<?php

class plugin_shopstat
{
	private $id_stat = 0;

	public function __construct()
	{
		if (!isset($_SESSION['shopstat'])) {
			$_SESSION['shopstat'] = array(
				'events' => array(),
				'id' => 0,
				'is_bot' => $this->isBot()
			);
		}

		if (!empty($_SESSION['shopstat']['is_bot'])) {
			return;
		}

		if (empty($_SESSION['shopstat']['id'])) {
			$sid = session_id();
			if (!file_exists(SE_ROOT . '/system/logs/shop_stat_session.upd') || filemtime(SE_ROOT . '/system/logs/shop_stat_session.upd') < filemtime(__FILE__)) {
				se_db_query("CREATE TABLE IF NOT EXISTS `shop_stat_session` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`sid` varchar(32) NOT NULL,
				`host` varchar(255) DEFAULT NULL,
				`ip` varchar(15) DEFAULT NULL,
				`id_user` int(10) unsigned DEFAULT NULL,
				`updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
				`created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY (`id`),
				UNIQUE KEY `UK_shop_stat_session_sid` (`sid`, `host`),
				INDEX KEY `host` (`host`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;");

				se_db_query("ALTER TABLE shop_stat_session DROP INDEX UK_shop_stat_session_sid;");
				se_db_query("ALTER TABLE shop_stat_session DROP INDEX host;");

				se_db_query("ALTER TABLE `shop_stat_session` ADD `host` VARCHAR(255) NULL DEFAULT NULL AFTER `sid`;");

				se_db_query("ALTER TABLE `shop_stat_session`
					ADD UNIQUE KEY `UK_shop_stat_session_sid` (`sid`,`host`) USING BTREE,
					ADD KEY `host` (`host`);");
				file_put_contents(SE_ROOT . '/system/logs/shop_stat_session.upd', date('Y-m-d H:i:s'));
			}

			$stat_session = new seTable('shop_stat_session');
			$stat_session->where('sid="?"', $sid);
			$stat_session->andwhere('host="?"', $_SERVER['HTTP_HOST']);
			if ($stat_session->fetchOne()) {
				$id = $stat_session->id;
			} else {
				$stat_session->insert();
				$stat_session->sid = $sid;
				$stat_session->host = $_SERVER['HTTP_HOST'];
				$stat_session->ip = !empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
				$id = $stat_session->save();
			}
			$this->id_stat = $_SESSION['shopstat']['id'] = $id;
			$this->saveInfo();
		} else
			$this->id_stat = $_SESSION['shopstat']['id'];

		if (empty($_SESSION['shopstat']['ident_user'])) {
			if ($id_user = seUserId()) {
				$_SESSION['shopstat']['ident_user'] = $id_user;

				$this->clearContact();

				$stat_session = new seTable('shop_stat_session');
				$stat_session->find($this->id_stat);
				$stat_session->id_user = $id_user;
				$stat_session->save();
			}
		}
	}

	private function saveInfo()
	{
		if (!$this->id_stat)
			return;
		if (!file_exists(SE_ROOT . '/system/logs/shop_stat_info.upd')) {
			se_db_query("CREATE TABLE IF NOT EXISTS `shop_stat_info` (
			`id_session` int(10) unsigned NOT NULL,
			`ip` varchar(15) DEFAULT NULL,
			`user_agent` varchar(255) DEFAULT NULL,
			`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
			`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			UNIQUE KEY `UK_shop_stat_info` (`id_session`),
			KEY `FK_shop_stat_info_shop_stat_session_id` (`id_session`),
			CONSTRAINT `FK_shop_stat_info_shop_stat_session_id` FOREIGN KEY (`id_session`) REFERENCES `shop_stat_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
			file_put_contents(SE_ROOT . '/system/logs/shop_stat_info.upd', date('Y-m-d H:i:s'));
		}
		$ssi = new seTable('shop_stat_info');
		$ssi->insert();
		$ssi->id_session = $this->id_stat;
		$ssi->ip = !empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
		$ssi->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$ssi->save();
	}

	public function saveEvent($event = '', $content = '')
	{
		if (!$event || !$this->id_stat)
			return;

		$final_event = $event == 'confirm order';

		if ($final_event) {
			$this->clearContact();
			$this->clearCart();
		}

		$event_number = $this->getEventNumber($final_event);

		if (!isset($_SESSION['shopstat']['events'][$event_number][$event])) {
			if (!file_exists(SE_ROOT . '/system/logs/shop_stat_events.upd')) {
				se_db_query("CREATE TABLE IF NOT EXISTS `shop_stat_events` (
				`id_session` int(10) unsigned NOT NULL,
				`event` varchar(50) NOT NULL,
				`number` smallint(5) unsigned NOT NULL DEFAULT '0',
				`content` varchar(100) DEFAULT NULL,
				`updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
				`created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
				KEY `FK_shop_stat_events_shop_stat_session_id` (`id_session`),
				CONSTRAINT `FK_shop_stat_events_shop_stat_session_id` FOREIGN KEY (`id_session`) REFERENCES `shop_stat_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=2340;");
				file_put_contents(SE_ROOT . '/system/logs/shop_stat_events.upd', date('Y-m-d H:i:s'));
			}
			$stat_event = new seTable('shop_stat_events');
			$stat_event->insert();
			$stat_event->id_session = $this->id_stat;
			$stat_event->event = $event;
			$stat_event->number = $event_number;
			if ($content)
				$stat_event->content = $content;
			$_SESSION['shopstat']['events'][$event_number][$event] = $stat_event->save();
		}
	}

	private function getEventNumber($increment = false)
	{
		if (!isset($_SESSION['shopstat']['event_number'])) {
			$_SESSION['shopstat']['event_number'] = 0;
		}
		$number = $_SESSION['shopstat']['event_number'];
		if ($increment) {
			$_SESSION['shopstat']['event_number']++;
		}
		return $number;
	}

	public function saveCart()
	{
		if (!$this->id_stat)
			return;
		if (!empty($_SESSION['shopcart']) && isset($_SESSION['shopstat']['ident_user'])) {
			if (!file_exists(SE_ROOT . '/system/logs/shop_stat_cart.upd')) {
				se_db_query("CREATE TABLE IF NOT EXISTS `shop_stat_cart` (
				`id_session` int(10) unsigned NOT NULL,
				`id_product` int(10) unsigned NOT NULL,
				`modifications` varchar(255) DEFAULT NULL,
				`count` double(10,3) unsigned DEFAULT NULL,
				`updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
				`created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
				KEY `FK_shop_stat_cart_shop_stat_session_id` (`id_session`),
				CONSTRAINT `FK_shop_stat_cart_shop_stat_session_id` FOREIGN KEY (`id_session`) REFERENCES `shop_stat_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=5461;");
				file_put_contents(SE_ROOT . '/system/logs/shop_stat_cart.upd', date('Y-m-d H:i:s'));
			}
			$this->clearCart();
			$stat_cart = new seTable('shop_stat_cart');
			foreach ($_SESSION['shopcart'] as $val) {
				$stat_cart->insert();
				$stat_cart->id_session = $this->id_stat;
				$stat_cart->id_product = (int)$val['id'];
				$stat_cart->modifications = is_array($val['modifications']) ? join(',', $val['modifications']) : $val['modifications'];
				$stat_cart->count = (float)$val['count'];
				$stat_cart->save();
			}
			$_SESSION['shopstat']['save_cart'] = true;
		}
	}

	public function saveContact($contact = '', $value = '')
	{
		if (!$this->id_stat)
			return;
		$this->saveEvent('input contact');
		if (empty($contact) || empty($value) || !empty($_SESSION['shopstat']['ident_user']))
			return;
		if (!file_exists(SE_ROOT . '/system/logs/shop_stat_contact.upd')) {
			se_db_query("CREATE TABLE IF NOT EXISTS `shop_stat_contact` (
			`id_session` int(10) unsigned NOT NULL,
			`contact` varchar(50) NOT NULL,
			`value` varchar(255) NOT NULL,
			`updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
			`created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
			UNIQUE KEY `UK_shop_stat_contact` (`id_session`,`contact`,`value`),
			CONSTRAINT `FK_shop_stat_contact_shop_stat_session_id` FOREIGN KEY (`id_session`) REFERENCES `shop_stat_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
			file_put_contents(SE_ROOT . '/system/logs/shop_stat_contact.upd', date('Y-m-d H:i:s'));
		}
		$stat_contact = new seTable('shop_stat_contact');
		$stat_contact->insert();
		$stat_contact->id_session = $this->id_stat;
		$stat_contact->contact = $contact;
		$stat_contact->value = $value;
		$stat_contact->save();

		$_SESSION['shopstat']['ident_user'] = 0;

		$this->saveViewsProducts();
		$this->saveCart();
	}

	public function clearContact()
	{
		/*
		$stat_contact = new seTable('shop_stat_contact');
		$stat_contact->where('id_session=?', $this->id_stat);
		$stat_contact->deleteList();
		*/
		se_db_query('DELETE LOW_PRIORITY QUICK FROM shop_stat_contact WHERE id_session = ' . $this->id_stat);
	}

	public function clearCart()
	{
		/*
		$stat_cart = new seTable('shop_stat_cart');
		$stat_cart->where('id_session=?', $this->id_stat);
		$stat_cart->deleteList();
		*/
		se_db_query('DELETE LOW_PRIORITY QUICK FROM shop_stat_cart WHERE id_session = ' . $this->id_stat);
	}

	public function saveViewsProducts()
	{
		if (!$this->id_stat)
			return;
		if (!empty($_SESSION['look_goods']) && empty($_SESSION['shopstat']['save_views'])) {
			if (!file_exists(SE_ROOT . '/system/logs/shop_stat_viewgoods.upd')) {
				se_db_query("CREATE TABLE IF NOT EXISTS `shop_stat_viewgoods` (
				`id_session` int(10) unsigned NOT NULL,
				`id_product` int(10) NOT NULL,
				`updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
				`created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
				KEY `FK_shop_stat_viewgoods_shop_stat_session_id` (`id_session`),
				CONSTRAINT `FK_shop_stat_viewgoods_shop_stat_session_id` FOREIGN KEY (`id_session`) REFERENCES `shop_stat_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
				file_put_contents(SE_ROOT . '/system/logs/shop_stat_viewgoods.upd', date('Y-m-d H:i:s'));
			}
			$stat_views = new seTable('shop_stat_viewgoods');
			foreach ($_SESSION['look_goods'] as $key => $val) {
				$stat_views->insert();
				$stat_views->id_session = $this->id_stat;
				$stat_views->id_product = (int)$key;
				$stat_views->save();
			}
			$_SESSION['shopstat']['save_views'] = true;
		}
	}

	private function isBot($botname = '')
	{
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		if (preg_match('/(^(?!.*\(.*(\w|_|-)+.*\))|bot[^a-z])/i', $user_agent)) {
			return true;
		}

		$bots = array(
			'crawler', 'rambler', 'googlebot', 'aport', 'yahoo', 'msnbot', 'turtle', 'mail.ru', 'omsktele',
			'yetibot', 'picsearch', 'sape.bot', 'sape_context', 'gigabot', 'snapbot', 'alexa.com',
			'megadownload.net', 'askpeter.info', 'igde.ru', 'ask.com', 'qwartabot', 'yanga.co.uk',
			'scoutjet', 'similarpages', 'oozbot', 'shrinktheweb.com', 'aboutusbot', 'followsite.com',
			'dataparksearch', 'google-sitemaps', 'appEngine-google', 'feedfetcher-google',
			'liveinternet.ru', 'xml-sitemaps.com', 'agama', 'metadatalabs.com', 'h1.hrn.ru',
			'googlealert.com', 'seo-rus.com', 'yaDirectBot', 'yandeG', 'yandex',
			'yandexSomething', 'Copyscape.com', 'AdsBot-Google', 'domaintools.com',
			'Nigma.ru', 'bing.com', 'dotnetdotcom'
		);
		foreach ($bots as $bot) {
			if (stripos($user_agent, $bot) !== false) {
				return true;
			}
		}
		return false;
	}
}
