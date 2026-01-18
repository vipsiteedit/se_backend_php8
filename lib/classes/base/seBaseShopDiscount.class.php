<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс Скидок
 * @filesource seBaseShopDiscount.class.php
 * @copyright EDGESTILE
 */
class seBaseShopDiscount extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_discount';
		$this->table_alias = 'sd';
		$this->fields = array(
			'id'=>'integer',
			'id_price'=>'integer',
			'id_group'=>'integer',
			'id_user'=>'integer',
			'priority'=>'integer',
			'type'=>'string',
			'if_date1'=>'string',
			'date1'=>'date',
			'time1'=>'string',
			'if_date2'=>'string',
			'date2'=>'date',
			'time2'=>'string',
			'if_summ1'=>'string',
			'summ1'=>'float',
			'if_summ2'=>'string',
			'summ2'=>'float',
			'if_count1'=>'string',
			'count1'=>'integer',
			'if_count2'=>'string',
			'count2'=>'integer',
			'text'=>'string',
			'percent'=>'float',
			'week'=>'string',
			'rules'=>'string',
			'commentary'=>'string',
			'summ_type'=>'integer',
			'updated_at'=>'datetime',
			'created_at'=>'datetime');
		
		$this->fieldalias = array(
			'price_id'=>'id_price',
			'group_id'=>'id_group'
		);
	}
}	
	
?>