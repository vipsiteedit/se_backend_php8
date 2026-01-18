<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка пользователей
 * @filesource seBaseUser.class.php
 * @copyright EDGESTILE
 */
class seBasePerson extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'person';
		$this->table_alias = 'p';
		
		$this->fields = array(
  			'id'=>'integer', 
  			'id_up'=>'integer', 
  			'first_name'=>'string', 
  			'last_name'=>'string', 
  			'sec_name'=>'string', 
  			'sex'=>'string',
  			'birth_date'=>'datetime', 
  			'nick'=>'string', 
  			'email'=>'string', 
  			'doc_ser'=>'string', 
  			'doc_num'=>'string',
  			'doc_registr'=>'string', 
  			'post_index'=>'string', 
  			'country_id'=>'integer', 
  			'state_id'=>'string', 
  			'town_id'=>'string', 
  			'overcity'=>'string',
  			'addr'=>'string', 
  			'phone'=>'string', 
  			'icq'=>'string', 
  			'discount'=>'float', 
  			'reg_date'=>'datetime',
  			'subscriber_news'=>'string', 
  			'enable'=>'string',
  			'avatar'=>'string');

		$this->fieldalias = array();
  		/*	'upid'=>'id_up', 
  			'first_name'=>'a_first_name',
			'last_name'=>'a_last_name',
  			'sec_name'=>'a_sec_name',
  			'sex'=>'a_sex',
  			'birth_date'=>'a_birth_date',
  			'nick'=>'a_nick',
  			'login'=>'a_login',
  			'password'=>'a_password',
  			'tmppassw'=>'a_tmppassw',
  			'treaty'=>'a_treaty',
  			'access_group'=>'a_group',
  			'access_name'=>'a_admin',
  			'email'=>'a_email',
  			'reg_date'=>'a_reg_date',
  			'log_date'=>'a_log_date',
  			'is_active'=>'a_enable'
		);*/

	}
}	
	
?>