<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс списка пользователей
 * @filesource seBaseUserUrid.class.php
 * @copyright EDGESTILE
 */
class seBaseUserUrid extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'user_urid';
		$this->table_alias = 'uu';
		
		$this->fields = array(
  			'id'=>'integer', 
  			'id_author'=>'integer', 
  			'company'=>'string', 
  			'director'=>'string', 
  			'uradres'=>'string', 
  			'fizadres'=>'string',
  			'tel'=>'string', 
  			'fax'=>'string', 
  			'posthead'=>'string', 
  			'bookkeeper'=>'string', 
  			'postbookk'=>'integer', 
  			'created_at'=>'datetime', 
  			'updated_at'=>'datetime');

		$this->crfielalias = array(
  			'user_id'=>'id_author' 
		);

	}
}	
	
?>