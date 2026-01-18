<?php
require_once dirname(__FILE__)."/base/seBaseUserAccount.class.php"; 

/**
 * класс Личного счета пользователя
 * @filesource seUserAccount.class.php
 * @copyright EDGESTILE
 */
class seUserAccount extends seBaseUserAccount {

	public function getSumm($user_id)
	{
		if ($user_id) $this->where('user_id=?', $user_id);
		$list = $this->getList();
		$result = 0;
		foreach($list as $line)
		{
			$result += $line['in_payee'] - $line['out_payee'] + $line['entbalanse'];
		}
		return $result;
		
	}
}	
	
?>