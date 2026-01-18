<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс схожих товаров Интернет-магазина
 * @filesource seBaseShopSameprice.class.php
 * @copyright EDGESTILE
 */
class seBaseShopSameprice extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_sameprice';
		$this->table_alias = 'ss';
		$this->fields = array('id','id_acc','id_price');
	}
}	
	
?>