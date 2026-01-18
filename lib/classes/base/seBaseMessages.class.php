<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка пользователей
 * @filesource seBaseMessages.class.php
 * @copyright EDGESTILE
 */
class seBaseMessages extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'messages';
		$this->table_alias = 'msg';
		
		$this->fields = array(
  			'id'=>'integer', 
  			'user_id_from'=>'integer', 
  			'user_id_to'=>'integer', 
  			'title'=>'string', 
  			'message'=>'string', 
  			'is_read'=>'integer',
  			'created_at'=>'datetime',
  			'updated_at'=>'datetime');

		$this->crfield = array();

	}
}	
	
?>