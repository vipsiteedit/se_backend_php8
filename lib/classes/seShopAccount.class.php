<?php
require_once dirname(__FILE__)."/base/seBaseShopAccount.class.php"; 

/**
 * класс —четов
 * @filesource seShopAccount.class.php
 * @copyright EDGESTILE
 */
class seShopAccount extends seBaseShopAccount {
	
	public function maxAccount()
	{
  		$account = $this;
		$account->select('MAX(account) as `maxaccount`');
		$account->fetchOne();
	  	return intval($account->data['maxaccount']);
	}
}	
	
?>