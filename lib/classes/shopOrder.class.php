<?php
/**
* Класс обработки заказов магазина
* @filesource shopOrder.class.php
* @copyright EDGESTILE
* 
*/

class shopOrder 
{
private $order_id;
private $user_id;
public $arrayorder = null;
public $data = null;
public $goods = null;


  function __construct($order_id = 0)
  {
  	
	  $this->order_id = $order_id;
	  if ($order_id > 0 && is_array($order_id))
	  {
	  	$this->openOrders($order_id);
	  }
	  
	  if ($order_id > 0 && !is_array($order_id))
	  {
	  	$this->openOrders($order_id);
	  	$this->data = $this->getOrder($order_id);
	  	unset($this->data['updated_at'],$this->data['created_at'],$this->data['deleted_at'],$this->data['username'],$this->data['summa']);
	  	$this->orderGoods($order_id);
	  }
	  
  }
	// Открываем заказ или массив заказов
	public function openOrders($order_id)
	{
		if (is_array($order_id)) $order_id = join(',', $order_id);
		
		$qr = se_db_query("SELECT `so`.*, concat_ws(' ',a.a_last_name,a.a_first_name,a.a_sec_name) as `username` 
		FROM `shop_order` `so`
		LEFT JOIN `author` `a` ON (`so`.`id_author` = `a`.`id`) 
		WHERE `so`.`id` IN ($order_id)");
		
		if (!empty($qr))
		while ($line = se_db_fetch_assoc($qr))
		{  
			  $this->arrayorder[$line['id']] = $line;
	  		  $this->arrayorder[$line['id']]['summa'] = $this->SummOrder($line['id']);
	  		//echo $line['id'].' = '.$this->arrayorder[$line['id']]['summa'].'!<br>';
		}
	}
	
	// Возврашаем параметры конкретного заказа
	public function getOrder($order_id = 0)
	{
		if ($order_id == 0 ) $order_id = $this->order_id;
		return $this->arrayorder[$order_id];
	}
	
	public function getOrderList($user_id)
	{
		if (is_array($user_id)) $user_id = join(',', $user_id);
		
		// = $this->user_id;
		$arrayorder = array();
		$qr = se_db_query("SELECT `so`.*, concat_ws(' ',a.a_last_name,a.a_first_name,a.a_sec_name) as `username` 
		FROM `shop_order` `so`
		LEFT JOIN `author` `a` ON (`so`.`id_author` = `a`.`id`) 
		WHERE `so`.`id_author` IN ($user_id)");
		
		if (!empty($qr))
		while ($line = se_db_fetch_assoc($qr))
		{  
			  
			  $this->arrayorder[$line['id']] = $line;
	  		  $this->arrayorder[$line['id']]['summa'] = $this->SummOrder($line['id']);
	  		//echo $line['id'].' = '.$this->arrayorder[$line['id']]['summa'].'!<br>';
		}
	}
	
	// Создание или сохранение заказа
	public function save()
	{
		if ($this->order_id == 0)
		{
			if (isset($this->data['id_author'])) $this->data['id_author'] = seUserId();
			$this->data['date_order'] = date('Y-m-d');
			$this->order_id = se_db_perform('shop_order',$this->data);
			
			// Сохраняем список услуг и товаров
			foreach($this->goods as $id=>$line)
			{
				$line['id_order'] = $this->order_id;
				$this->goods[$id]['id'] = se_db_perform('shop_tovarorder',$line);
			}
			// Создаем номер договора
			$number = se_db_fields_item('shop_contract',"`date`=CURDATE())","MAX(number)") + 1;
			$contract = array('id_order'=>$this->order_id, 'date'=>date('Y-m-d'),'number'=>$number);
			se_db_perform('shop_contract',$contract);

			// Создаем номер счета
			$number = se_db_fields_item('shop_account',"1","MAX(account)") + 1;
			$account = array('id_order'=>$this->order_id, 'date_order'=>date('Y-m-d'),'account'=>$number);
			se_db_perform('shop_account',$account);
		} 
		else
		{
			$dataarr = $this->data; 
			se_db_perform('shop_order',$this->data,'update','`id` = '. $this->order_id);
			// Сохраняем список услуг и товаров
			foreach($this->goods[$this->order_id] as $id=>$line)
			{
				unset($line['updated_at'], $line['created_at'],$line['nameprice']);
			//	echo $line['id']."@";
				$line['id_order'] = $this->order_id;
				
			//	echo $line['id'];
				if (empty($line['id']))
				{
			//	print_r($line);
					$this->goods[$id]['id'] = se_db_perform('shop_tovarorder',$line);
				} 
				else
				{
					//print_r($line);
					se_db_perform('shop_tovarorder',$line,'update','`id` = '.$line['id']);
				}	
			}
		}
	}

	// функция возвращает сумму заказа или всех заказов
	public function getSumm($order_id = 0, $curr = 'RUR')
	{
		$result = 0;
		if ($order_id == 0)
		{
			foreach($this->arrayorder as $order_id=>$value)
			{
				$result += seCurrency::getInstance()->convert($this->SummOrder($order_id), $value['curr'], $curr, $value['date_order']) ;
			}
			
		}
		else
		{
			$result = seCurrency::getInstance()->convert($this->SummOrder($order_id), $this->data['curr'], $curr, $this->data['date_order']);
		}
		return $result;
	}

	// Получить сумму одного заказа
	private function SummOrder($order_id = 0)
	{
		if ($order_id == 0 ) $order_id = $this->order_id;
		$result = 0;
		{
			$line = $this->arrayorder[$order_id];
			$result += $line['delivery_payee'] - $line['discount'];
			$this->orderGoods($order_id);
			
			if (!empty($this->goods[$order_id]))
			foreach($this->goods[$order_id] as $goods)
				$result += $goods['price']-$goods['discount']*$goods['count'];
		}
		return $result;
	}

	// Добавить товар в заказ	
	public function addGoods($article = '', $id_price = null, $nameprice = '', $price, $discount = 0.00, $count = 1)
	{
		if (!isset($this->goods)) $goods = array();
		if ($this->order_id > 0)
		{
			$this->goods[$this->order_id][] = array('id_order'=>$this->order_id,'article'=>$article, 'id_price'=>$id_price,
			'nameprice'=>$nameprice, 'price'=>$price, 'discount'=>$discount, 'count'=>$count);
		}	
	}
	

	/**
	 * Функция заполняет массив заказанных услуг
	 */
	private function orderGoods($order_id = 0)
	{
		if ($order_id == 0 ) $order_id = $this->order_id;
		$this->goods[$order_id] = array();
		$qr = se_db_query("SELECT st.*, sp.name as `nameprice` FROM shop_tovarorder st
		LEFT JOIN `shop_price` as `sp` ON (st.id_price = sp.id)
		WHERE `id_order`='$order_id'");
		if (!empty($qr))
		while ($line = se_db_fetch_assoc($qr))
		{
	  		$this->goods[$order_id][$line['id']] = $line;
		}
	}
	
	/**
	 * Функция возвращает массив заказанных услуг
	 */
	public function getGoods($order_id = 0)
	{
		if ($order_id == 0 ) $order_id = $this->order_id;
		return $this->goods[$order_id];
	}
	
	public function getBonus($order_id = 0)
	{
		if ($order_id == 0 ) $order_id = $this->order_id;
		$summ = 0;
	
		$qr = se_db_query("SELECT SUM(sp.bonus) FROM shop_tovarorder st
		INNER JOIN `shop_price` as `sp` ON (st.id_price = sp.id)
		WHERE st.`id_order`='$order_id'");
		if (!empty($qr))
		while ($line = se_db_fetch_row($qr))
		{
		  	$summ += $line[0];
		}
		
		return $summ;
	}
	
	public function saveGoods($id_goods)
	{
		
	}
}
?>