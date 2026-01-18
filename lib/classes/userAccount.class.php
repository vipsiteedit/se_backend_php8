<?php
/**
 * Внутренний счет пользователя
 * @filesource userAccount.class.php
 * @copyright EDGESTILE
 */
class userAccount {
  private $id;
  private $user_id;
  private $curr;
  public $message;
  public $data;

	function __construct($user_id = 0)
	{
		$this->curr = 'RUR';
		$this->user_id = $user_id;
		$this->open();
	}
	
	function __set($name, $value)
	{
		$this->data[$this->id][$name] = $value;
	}

	function __get($name)
	{
		return $this->data[$this->id][$name];
	}
	
	// Читаем таблицу для пользователя
	public function open($id = 0)
	{
		if ($this->user_id == 0 && $id == 0 ) return;
		if ($id == 0) $where = "`id_author` = '$this->user_id'";
		else $where = "`id` = '$id'";
		
		$this->id = $id;
		
		$this->data = array();
		$qr = se_db_query("SELECT * FROM shop_payee WHERE $where ORDER BY id");
		if (!empty($qr))
		while ($line = se_db_fetch_assoc($qr))
		{  
			  $this->data[$line['id']] = $line;
		}
		unset($this->data[$line['id']]['id']);
	}
	
	public function save()
	{
		$dataarr = $this->data[$this->id];
		if ($this->id > 0)
		{
			se_db_perform('shop_payee', $dataarr, 'update', "id = $this->id");
		}
		else 
		{
			if (empty($dataarr['docum']))
			{
				$dataarr['docum'] = $this->message;
			}
			$dataarr['date_payee'] = date('Y-m-d');
			se_db_perform('shop_payee', $dataarr);
			
		}
	}
	
	public function getSumm()
	{
		$result = 0;
		$this->open();
		foreach($this->data as $line)
		{
			$result += $line['in_payee'] - $line['out_payee'] + $line['entbalanse'];
		}
		return $result;
	}
	
	
}	
	
	

?>