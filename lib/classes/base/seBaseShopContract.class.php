<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс Контрактов
 * @filesource seBaseShopContract.class.php
 * @copyright EDGESTILE
 */
class seBaseShopContract extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_contract';
		$this->table_alias = 'sc';
		$this->fields = array(
			'id'=>'integer',
			'id_author'=>'integer',
			'id_order'=>'integer',
			'number'=>'integer',
			'date'=>'datetime',
			'file'=>'string',
			'created_at'=>'datetime',
			'updated_at'=>'datetime');
		$this->fieldalias = array(
		'user_id'=>'id_author',
		'order_id'=>'id_order',
		'date_order'=>'date'
		);
	}
}	
	
?>