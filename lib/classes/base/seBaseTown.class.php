<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка пользователей
 * @filesource seBaseCity.class.php
 * @copyright EDGESTILE
 */
class seBaseCity extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'town';
		$this->table_alias = 'tw';
		
		$this->fields = array(
  			'id'=>'integer', 
  			'id_region'=>'integer', 
  			'name'=>'string');

	}
}	
	
?>