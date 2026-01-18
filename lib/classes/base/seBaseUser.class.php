<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка пользователей
 * @filesource seBaseUser.class.php
 * @copyright EDGESTILE
 */
class seBaseUser extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'se_user';
		$this->table_alias = 'u';
		
		$this->fields = array(
  			'id'=>'integer', 
  			'username'=>'string', 
  			'password'=>'string', 
  			'tmppassw'=>'string', 
  			'is_active'=>'string',
  			'is_super_admin'=>'string', 
  			'last_login'=>'datetime');

		$this->fieldalias = array();

	}
}	
	
?>