<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс Специальные предложения
 * @filesource seBaseShopSpecial.class.php
 * @copyright EDGESTILE
 */
class seBaseShopSpecial extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_special';
		$this->table_alias = 'ss';
		$this->fields = array(
			'id'=>'integer',
			'id_price'=>'integer',
			'id_group'=>'integer',
			'newprice'=>'float',
			'newproc'=>'float',
			'curr'=>'string',
			'date_added'=>'datetime',
			'last_modified'=>'datetime',
			'expires_date'=>'datetime',
			'status'=>'string',
			'updated_at'=>'datetime',
			'created_at'=>'datetime');
		
		$this->fieldalias = array(
			'price_id'=>'id_price',
			'group_id'=>'id_group'
		);
	}
}	
	
?>