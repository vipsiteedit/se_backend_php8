<?php

class plugin_shop_price_cache{ 
	private static $instance = null;
	
	private $cache_dir = '';
	private $price = array();
	private $count = 0;

    public function __construct()
	{
		$this->cache_dir = SE_SAFE . 'projects/' . SE_DIR . 'cache/shop/price/';
		$this->cache_price = $this->cache_dir . 'price.json';
		$this->cache_count = $this->cache_dir . 'count.txt';
		
		if (!is_dir($this->cache_dir)) {      
			mkdir($this->cache_dir);				
		}
    }
	
	public function checkCache()
	{
		$this->updateDB();
		
		$tables = array(
			'shop_price',
			'shop_modifications',
		);
		
		foreach ($tables as $key => $val) {
			$alias = 't' . $key;
			$table = $val . ' AS ' . $alias;
			$tables[$key] = 'SELECT COUNT(*) AS count, UNIX_TIMESTAMP(GREATEST(MAX(' . $alias . '.updated_at), MAX(' . $alias . '.created_at))) AS time FROM ' . $table;
		}
		
		$query = join(' UNION ALL ', $tables) . ' ORDER BY time DESC LIMIT 1';
		
		$result = se_db_query($query);
		
		list($this->count, $time) = se_db_fetch_row($result);
		
		$cache_count = file_exists($this->cache_count) ? (int)file_get_contents($this->cache_count) : -1;
		
		$time = max(filemtime(__FILE__), $time);
		
		
		if (!file_exists($this->cache_price) || filemtime($this->cache_price) < $time || $cache_count != $this->count) {
			echo "CACHE\n";
			$this->saveCache();
			$this->fillPrice();
		}
		echo "END";
	} 
	
	private function updateDB()
	{
		if (!file_exists(SE_ROOT . '/system/logs/shop_price_cache.upd')) {
			$sql = "CREATE TABLE IF NOT EXISTS shop_price_cache (
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				id_price int(10) UNSIGNED NOT NULL,
				modifications VARCHAR(255) DEFAULT NULL,
				price DOUBLE(10, 2) NOT NULL,
				updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
				created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id),
				CONSTRAINT FK_shop_price_cache_shop_price_id FOREIGN KEY (id_price)
				REFERENCES shop_price (id) ON DELETE CASCADE ON UPDATE CASCADE
				)
				ENGINE = INNODB
				CHARACTER SET utf8
				COLLATE utf8_general_ci;";
			
			se_db_query($sql);
			file_put_contents(SE_ROOT . '/system/logs/shop_price_cache.upd', date('Y-m-d H:i:s'));
		}
	}
	
	private function saveCache()
	{
		$file = fopen($this->cache_price, "w+");
		$result = fwrite($file, json_encode($this->price));
		fclose($file); 
		
		$file = fopen($this->cache_count, "w+");
		$result = fwrite($file, $this->count);
		fclose($file);	
		
		$file_log = fopen($this->cache_dir . 'price.log', 'a+');
		fwrite($file_log, date('[Y-m-d H:i:s] ') . $this->count . "\r\n");
	}
	
	private function fillPrice()
	{
		se_db_query('TRUNCATE TABLE shop_price_cache');
        $i = 0;
        $limit = 10;
        
		while (true) {
            
            $sp = new seTable('shop_price', 'sp');
            $sp->select('
                sp.id,
                sp.price,
                sp.price_opt_corp,
                sp.price_opt,
                sp.discount,
                sp.max_discount,
                sp.curr,
                (SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications
            ');
            
            $list = $sp->getList($i*$limit, $limit);
            
            if (empty($list)) {
                break;
            }
            
            if (!empty($list)) {
                
                $data = array();
                
                $basecurr = se_baseCurrency();
                
                foreach ($list as $val) {
                    $min_price = $val['price']; 
                    
                    
                    if ($val['modifications']) {
                        $prices = $this->getPriceModification($val['id'], $val['price']);
                        
                        foreach ($prices as $mod) {
                            $price = $mod['price'];
                            /*
                            if ($basecurr != $val['curr']) {
                                $price = se_MoneyConvert($price, $val['curr'], $basecurr);
                            }
                            */
                            $plugin_amount = new plugin_shopamount(0, $val, 0, 1, join(',', $mod['mod']), $basecurr);
                            
                            $data[] = array(
                                'id_price' => $val['id'],
                                'price' => $plugin_amount->getPrice(),
                                'modifications' => join(',', $mod['modifications']),
                            );
                        }
                    }
                    else {
                        $price = $val['price'];
                        
                        $plugin_amount = new plugin_shopamount(0, $val, 0, 1, '', $basecurr);
                        /*
                        if ($basecurr != $val['curr']) {
                            $price = se_MoneyConvert($price, $val['curr'], $basecurr);
                        }
                        */
                        $data[] = array(
                            'id_price' => $val['id'],
                            'price' => $plugin_amount->getPrice(),
                            'modifications' => 0,
                        );
                    }			
                }
                se_db_begin_transaction();
	            try {
                    se_db_multi_perform('shop_price_cache', $data);
                    se_db_commit();
                    //echo "Commit: ".count($data)."\n";
	            } catch (mysqli_sql_exception $exception) {
                    se_db_rollback();
                    echo "Ошибка";
                }

            }
            
            $i++;
        }
	}
	
	private function getPriceModification($id, $price)
	{
		$prices = array();
		
		$sm = new seTable('shop_modifications', 'sm');
		$sm->select('
			smg.vtype,
			smg.id,
			GROUP_CONCAT(sm.id, "~", sm.value) AS mg
		');
		$sm->innerJoin('shop_modifications_group smg', 'sm.id_mod_group = smg.id');
		$sm->where('sm.id_price=?', $id);
        $sm->andWhere('(sm.count <> 0 OR sm.count IS NULL)');
		$sm->groupBy('smg.id');
		$list = $sm->getList();
		
		if ($list) {
			foreach ($list as $group) {
				$mg = explode(',', $group['mg']);
				
				$prices[$group['id']] = array();
				
				foreach ($mg as $m) {
					list($id, $value) = explode('~', $m);
					
					$sum_price = $value;
					
					if ($group['vtype'] == '0') {
						$sum_price = $price + $value;  
					}
					elseif($group['vtype'] == '1') {
						$sum_price = $price * $value;
					}
					
					$prices[$group['id']][] = array(
						'price' => $sum_price,
						'modifications' => array($group['id'] . ':' . $id),
                        'mod' => array($id),
					);
				}
			}
		}
		
		$prices = $this->recursiveModifications($prices);
		
		return $prices;
	}
	
	private function recursiveModifications($modifications = array())
	{
		$result = array();
		if (!empty($modifications)) {
			$first = array_shift($modifications);
			if (!empty($modifications)) {
				$second = array_shift($modifications); 
				foreach($first as $val1) {
					foreach($second as $val2) {
						$result[] = array(
							'price' => $val1['price'] + $val2['price'],
							'modifications' => array_merge($val1['modifications'],  $val2['modifications']),
                            'mod' => array_merge($val1['mod'],  $val2['mod'])
						);
					}
				}
				array_unshift($modifications, $result);
				$result = $this->recursiveModifications($modifications);   
			}
			else
				$result = $first;     
		}
		return $result;
	}
	
	
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}	
}