<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Ѕазовый класс —четов
 * @filesource seBaseShopAccount.class.php
 * @copyright EDGESTILE
 */
class seBaseShopAccount extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_account';
		$this->table_alias = 'sa';
		$this->fields = array(
			'id'=>'integer',
			'id_order'=>'integer',
			'account'=>'integer',
			'date_order'=>'datetime',
			'id_payment'=>'integer',
			'created_at'=>'datetime',
			'updated_at'=>'datetime');
		$this->fieldalias = array(
		'payment_id'=>'id_payment',
		'order_id'=>'id_order'
		);
	}
}	
	
?>