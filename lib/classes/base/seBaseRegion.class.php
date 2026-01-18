<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка регионов
 * @filesource seBaseRegion.class.php
 * @copyright EDGESTILE
 */
class seBaseRegion extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'region';
		$this->table_alias = 'rg';
		
		$this->fields = array(
  			'id'=>'integer', 
  			'id_country'=>'integer', 
  			'name'=>'string');

	}
}	
	
?>