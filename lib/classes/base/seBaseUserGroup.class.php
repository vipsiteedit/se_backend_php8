<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка пользователей
 * @filesource seBaseUser.class.php
 * @copyright EDGESTILE
 */
class seBaseUserGroup extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'se_user_group';
		$this->table_alias = 'ug';
		
		$this->fields = array(
		        'id'=>'integer',
  			'user_id'=>'integer', 
  			'group_id'=>'integer' 
  			);

		$this->fieldalias = array();

	}
}	
	
?>