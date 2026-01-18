<?php
require_once dirname(__FILE__)."/seBase.class.php"; 

/**
 * Базовый класс Личного счета пользователя
 * @filesource seBaseUserAccount.class.php
 * @copyright EDGESTILE
 */
class seBaseUserAccount extends seBase {
	
	protected function configure()
	{
		$this->table_name = 'se_user_account';
		$this->table_alias = 'ua';
		
		$this->fields = array(
  			'id'=>'integer', 
			'user_id'=>'integer', 
			'order_id'=>'integer', 
			'account'=>'string', 
			'date_payee'=>'datetime',
  			'in_payee'=>'float', 
			'out_payee'=>'float', 
			'entbalanse'=>'float', 
			'operation'=>'integer', 
			'docum'=>'string', 
			'curr'=>'string');

		$this->fieldalias = array(
			'id_author'=>'user_id', 
			'id_order'=>'order_id', 
			'operation_id'=>'operation',
			'message'=>'docum'
		);


	}
}	
	
?>