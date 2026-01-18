<?php
/**
 * Внутренний счет пользователя
 * @filesource user.class.php
 * @copyright EDGESTILE
 */
class user {
  private $where;
  private $user_id;
  private $curr;
  public $message;
  public $data;
  private $datalist;

	function __construct($id = 0)
	{
		$this->user_id = $id;
		if (!empty($id)) $this->find($id);
	}
	
	public function find($id)
	{
		$this->user_id = $id;
		$this->data = se_db_fields_item('author',"`id` = '$id'","*");
  		
  		unset($this->data['a_password'],$this->data['a_tmppassw'],
		  $this->data['updated_at'], $this->data['created_at']);

	} 
	
	public function save()
	{
		if (!empty($this->user_id))
			return se_db_perform('author',$this->data,'update',"`id` = $this->user_id");
		else 			
			return se_db_perform('author',$this->data);
	}
	
	
	public function delete($id = 0)
	{
		if (is_array($id))
		{
			$id = join(',', $id);
		} else
		if ($id == 0)
		{
			$id = $this->user_id;
		}
		se_db_delete('author',"id IN ($id)");
	}

	// Выводит список записей
	public function findlist($findtext = '', $offset = 0, $limit = 30)
	{
		if (is_numeric($findtext))
			$where = " WHERE `id` = '$findtext'";
		else 
		if (!empty($findtext))
			$where = "WHERE $findtext";
		else $where = '';
		$this->datalist = array();
		
		$limitstr = 'LIMIT ' . ($offset*$limit) . ',' . $limit;
		$data = se_db_query("SELECT * FROM `author` $where $limitstr");
		if (!empty($qr))
		while ($line = se_db_fetch_assoc($qr))
		{  
			  unset($line['a_password'],$line['a_tmppassw'],$line['updated_at'], $line['created_at']);
			  $this->datalist[$line['id']] = $line;
		}
		return $this->datalist;
	}


	function __set($name, $value)
	{
		$this->data[$name] = $value;
	}

	function __get($name)
	{
		return $this->data[$name];
	}
	
}	
	
	

?>