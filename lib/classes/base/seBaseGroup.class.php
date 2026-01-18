<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка пользователей
 * @filesource seBaseUser.class.php
 * @copyright EDGESTILE
 */
class seBaseGroup extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'se_group';
		$this->table_alias = 'g';
		
		$this->fields = array(
  			'id'=>'integer', 
  			'level'=>'integer', 
  			'name'=>'string', 
  			'title'=>'string');

		$this->fieldalias = array();

	}
}	
	
?>