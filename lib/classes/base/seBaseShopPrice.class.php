<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Ѕазовый класс —четов
 * @filesource seBaseShopPrice.class.php
 * @copyright EDGESTILE
 */
class seBaseShopPrice extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_price';
		$this->table_alias = 'sp';
		$this->fields = array(
			'id'=>'integer',
			'id_group'=>'integer',
			'lang'=>'string',
			'article'=>'string',
			'name'=>'string',
			'price'=>'float',
			'img'=>'string',
			'note'=>'string',
			'text'=>'string',
			'discount'=>'string',
			'enabled'=>'string',
			'presence_count'=>'integer',
			'price_opt'=>'float',
			'curr'=>'string',
			'presence'=>'string',
			'nds'=>'float',
			'manufacturer'=>'string',
			'date_manufactured'=>'date',
			'max_discount'=>'float',
			'special_price'=>'string',
			'measure'=>'string',
			'volume'=>'integer',
			'weight'=>'integer',
			'id_action'=>'integer',
			'bonus'=>'float',
			'updated_at'=>'datetime',
			'created_at'=>'datetime');
		
		$this->fieldalias = array(
		);
	}
}	
	
?>