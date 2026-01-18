<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс Каталога Интернет-магазина
 * @filesource seBaseShopGroup.class.php
 * @copyright EDGESTILE
 */
class seBaseShopGroup extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_group';
		$this->table_alias = 'sg';
		
		$this->fields = array(
  'id','upid','name','lang','picture',
  'commentary','discount','special_price',
  'typegroup','scount','active');
	}
}	
	
?>