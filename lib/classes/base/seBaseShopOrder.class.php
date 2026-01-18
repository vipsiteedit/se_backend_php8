<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка заказов
 * @filesource seBaseShopOrder.class.php
 * @copyright EDGESTILE
 */
class seBaseShopOrder extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'shop_order';
		$this->table_alias = 'so';
		
		$this->fields = array(
  			'id'=>'integer',
			'id_author'=>'integer',
			'date_order'=>'date',
			'discount'=>'float',
			'curr'=>'string',
  			'status'=>'string',
			'date_payee'=>'datetime',
			'payee_doc'=>'string',
			'commentary'=>'string',
			'payment_type'=>'integer',
			'transact_amount'=>'float',
  			'transact_id'=>'integer',
			'transact_curr'=>'string',
	  		'delivery_payee'=>'float',
			'delivery_type'=>'integer',
  			'delivery_status'=>'string',
			'delivery_date'=>'datetime',
			'id_admin'=>'integer',
			'inpayee'=>'string',
			'date_credit'=>'datetime');

		$this->fieldalias = array(
  			'user_id'=>'id_author', 
			'paymentsystem_id'=>'payment_type');

	}
}	
	
?>