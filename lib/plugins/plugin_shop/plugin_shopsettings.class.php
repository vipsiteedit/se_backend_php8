<?php

class plugin_shopsettings
{

	private static $instance = null;
	private $settings = array();

	public function __construct()
	{

		$this->getSettingList();
	}

	private function getSettingList()
	{
		$id_main = 1;
		$shop_settings = new seTable('shop_settings', 'ss');
		$shop_settings->select('ss.code, ss.type, ss.default, ss.list_values, (SELECT ssv.value FROM shop_setting_values AS ssv WHERE ssv.id_setting=ss.id LIMIT 1) AS value');
		$setting_list = $shop_settings->getList();
		if (!empty($setting_list)) {
			foreach ($setting_list as $val) {
				if (is_null($val['value'])) {
					$value = $val['default'];
				} else {
					$value = $val['value'];
				}

				$this->settings[$val['code']] = $value;
			}
		}
	}

	public function getSettings()
	{
		return $this->settings;
	}

	public function getValue($key = '')
	{
		$value = null;
		if (!empty($key) && isset($this->settings[$key])) {
			$value = $this->settings[$key];
		}
		return $value;
	}

	public function __get($name)
	{
		return $this->getValue($name);
	}

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
