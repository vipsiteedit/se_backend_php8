<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс бонусной таблицы
 * @filesource seBaseBonusList.class.php
 * @copyright EDGESTILE
 */
class seBaseBonusList extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'bonuslist';
		$this->table_alias = 'bl';
		
		$this->fields = array(
  			'id'=>'integer', 
  			'id_author'=>'integer', 
  			'id_order'=>'integer', 
  			'bonus'=>'integer', 
  			'premium'=>'float', 
  			'date_payee'=>'date', 
  			'sp'=>'string',
  			'updated_at'=>'datetime',
			'created_at'=>'datetime');

		$this->fieldalias = array(
  			'user_id'=>'id_author', 
  			'order_id'=>'id_order'
		); 
	}
}	
	
?>