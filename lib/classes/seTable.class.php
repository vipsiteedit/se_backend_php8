<?php
require_once dirname(__FILE__)."/base/seBase.class.php"; 

/**
 * Базовый класс списка пользователей
 * @filesource seTable.class.php
 * @copyright EDGESTILE
 */
class seTable extends seBase {
	
 	public function __construct($nametable = '', $alias = '')
  	{
  		$this->table_name = $nametable;
  		$this->table_alias = '__';
		if (!$alias) 
  		{
  			 $alias_arr = explode('_', $nametable);
			 foreach($alias_arr as $char)
			 	$this->table_alias .= $char;
  		}
  		else
  		{
  			$this->table_alias = $alias;
		}
		 parent::__construct();
	}
	
	protected function configure()
	{
		$this->fields = array();
		$this->is_table = true;
	}	

}	
	
?>