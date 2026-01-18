<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс Платежные системы
 * @filesource seBaseShopGroup.class.php
 * @copyright EDGESTILE
 */
class seBaseShopPayment extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_payment';
		$this->table_alias = 'pay';
		
		$this->fields = array(
  'id','lang','logoimg','name_payment','startform',
  'type','blank','result','success','fail','sort',
  'active','authorize','filters','hosts');
	}
}	
	
?>