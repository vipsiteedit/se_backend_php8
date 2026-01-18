<?php
require_once dirname(__FILE__)."/base/seBaseShopContract.class.php"; 

/**
 * класс Контрактов
 * @filesource seShopCOntract.class.php
 * @copyright EDGESTILE
 */
class seShopContract extends seBaseShopContract {
	
	public function maxNumber()
	{
  		$contract = $this;
		$contract->select('MAX(number) as `maxnumber`');
		$contract->where('`date`=CURDATE()')->fetchOne();
	  	return intval($contract->data['maxnumber']);
	}
}	
	
?>