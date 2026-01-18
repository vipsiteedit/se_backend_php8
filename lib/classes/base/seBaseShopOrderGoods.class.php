<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка товаров в заказе
 * @filesource seBaseShopOrderGoods.class.php
 * @copyright EDGESTILE
 */
class seBaseShopOrderGoods extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'shop_tovarorder';
		$this->table_alias = 'st';
		
		$this->fields = array(
  			'id'=>'integer',
			'id_order'=>'integer',
			'id_price'=>'integer',
			'article'=>'string',
			'nameitem'=>'string',
			'price'=>'float',
			'discount'=>'float',
			'count'=>'integer',
			'license'=>'string',
  			'commentary'=>'string',
			'date_payee'=>'datetime',
			'payee_doc'=>'string', 
			'action'=>'string',
			'created_at'=>'datetime',
			'updated_at'=>'datetime');

		$this->fieldalias = array(
  			'order_id'=>'id_order', 
			  'price_id'=>'id_price', 
			  'code'=>'article');

	}
}	
	
?>