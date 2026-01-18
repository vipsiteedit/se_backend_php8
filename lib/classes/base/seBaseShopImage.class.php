<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс Дополнительных изображений
 * @filesource seBaseShopImage.class.php
 * @copyright EDGESTILE
 */
class seBaseShopImage extends seBase {

	protected function configure()
	{
		$this->table_name = 'shop_img';
		$this->table_alias = 'si';
		
		$this->fields = array('id'=>'integer',
		'id_price'=>'integer',
		'picture'=>'string',
		'title'=>'string');
		
		$this->fieldalias = array('price_id'=>'id_price');
	}
}	
	
?>