<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс Лидеров продаж
 * @filesource seBaseShopGroup.class.php
 * @copyright EDGESTILE
 */
class seBaseShopLeader extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_leader';
		$this->fields = array('id','id_group','id_price');
	}
}	
	
?>