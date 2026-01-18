<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс сопутствующих товаров Интернет-магазина
 * @filesource seBaseShopAccomp.class.php
 * @copyright EDGESTILE
 */
class seBaseShopAccomp extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_accomp';
		$this->table_alias = 'sa';
		$this->fields = array('id','id_acc','id_price');
	}
}	
	
?>