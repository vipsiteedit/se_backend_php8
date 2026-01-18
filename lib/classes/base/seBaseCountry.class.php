<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка стран
 * @filesource seBaseCountry.class.php
 * @copyright EDGESTILE
 */
class seBaseCountry extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'country';
		$this->table_alias = 'cn';
		
		$this->fields = array(
  			'id'=>'integer', 
  			'name'=>'string');

	}
}	
	
?>