<?php

class plugin_shopamount
{
	private $curr = '';
	private $count = 0;
	private $price = 0;
	private $goodStyle = 1;
	public $sum_cart = 0;         

  /**
   * @param integer $user_id	ID пользователя
   * @peram byte $typprice	Тип цены (0- Розничная, 1- корпоративная, 2- оптовая)
   * @param char $basecurr	Базовая валюта
   * @param array $goods	item good with fields (id,price,price_opt_corp,price_opt,bonus,discount,special_price,curr,presence_count,presence,measure)
   **/
    public function __construct($price_id, $goods='', $typprice = 0, $count=1, $modifications = '', $basecurr = '') {
		if (empty($basecurr)) $basecurr = se_getMoney();
		$this->curr = $basecurr;
		
		$this->count = $count;
		if (empty($goods)){
			$shopprice = new seShopPrice();
			$shopprice->select('id, price, price_opt_corp, price_opt,  bonus, discount, special_price, curr, presence_count, presence, measure, (SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications');
			$this->goods = $shopprice->find($price_id);
			$this->price = $this->goods['price'];
        }
        else {
            $this->goods = $goods;
        }    
        $select = 'sm.value';
        $this->price = $this->goods['price'];
        if (2 == $typprice) {
            $this->price = $this->goods['price_opt_corp'];
            $select = 'sm.value_opt_corp AS value';
		} 
		elseif (1 == $typprice) {   
            $this->price = $this->goods['price_opt'];
            $select = 'sm.value_opt AS value';
		}
               

		if (!empty($modifications)) {
            $shop_modifications = new seTable('shop_modifications', 'sm');
			$shop_modifications->select('sm.id, sm.count, smg.vtype, ' . $select);
			$shop_modifications->innerJoin('shop_modifications_group smg', 'sm.id_mod_group = smg.id');
			$shop_modifications->where('sm.id IN (?)', join(',', $modifications));
            $list = $shop_modifications->getList();
			if (!empty($list)) {
				$this->presence_count = -1;
				$sum_price = 0;
				foreach($list as $val) {
					if ($val['count'] === '0') {
						$this->presence_count = 0;   
					}
					elseif ($this->presence_count != 0) {
						if ($val['count'] > 0 && ($this->presence_count > $val['count'] || $this->presence_count < 0))
							$this->presence_count = $val['count'];
					}
            
					if ($val['vtype'] == '0') {
						$sum_price += ($this->price + $val['value']);  
					}
					elseif($val['vtype'] == '1') {
						$sum_price += ($this->price * $val['value']);
					}
					else {
						$sum_price += $val['value'];
					}
				}
				$this->price = $sum_price;
			}	
		} 
		else {
			$this->presence_count = ($this->goods['presence_count'] == '') ? -1 : $this->goods['presence_count'];
		}
		
		if ($this->presence_count != -1 && $this->count > $this->presence_count)
			$this->count = $this->presence_count;
			
		$this->price = round(se_MoneyConvert($this->price, $this->goods['curr'], $this->curr), 2);
    }

    public function getDiscountProc($round = true) {
        if ($this->goods['special_price'] == 'Y' || $this->goods['discount'] == 'Y') {
            if ($this->goods['special_price'] == 'Y' && $this->goods['spec_proc']) {
				$discountproc = $this->goods['spec_proc'];
            } else {
				$shopdiscount = new plugin_shopDiscount($this->goods['id'],$this->sum_cart, 0, $this->count);
				$discountproc = $shopdiscount->execute();
            }
            return ($round) ? round($discountproc) : $discountproc;
        }
    }

	// Получаем цену товара
    public function getDiscount() {
        if ($this->goods['special_price'] == 'Y' || $this->goods['discount'] == 'Y') {
    	    if ($this->goods['bonus'] > 0) {
				$goodsprice = se_MoneyConvert($this->goods['bonus'], $this->goods['curr'], $this->curr);
			} 
			else {
				$goodsprice = $this->price;
			}
            $discountproc = $this->getDiscountProc(false);
            return round($goodsprice * ($discountproc / 100), 2);
        }
    }
	
	// Получаем комбинированную цену учитывая параметры и скидки
    public function getPrice($discounted = true) {
        $discountproc = 0;
        if ($discounted){
    	    $discountproc = $this->getDiscount();
        }
        return $this->price - $discountproc;
    }  

    public function getAmount($discounted = true) {
		return round($this->getPrice($discounted)* $this->count, 2);
    }

    // Показать стоимость $round - округлить, $space - разделитель
    public function showPrice($discounted = true, $round = false, $separator = '&nbsp;') {
		$price = $this->getPrice($discounted);
        $price = (!$round) ? $price : ceil($price);
		return se_formatMoney($price, $this->curr, $separator, $round);
    }

    public function showAmount($discounted = true, $round = false, $separator = '&nbsp;') {
		$price = $this->getPrice($discounted) * $this->count;
        $price = (!$round) ? $price : ceil($price);
		return se_formatMoney($price, $this->curr, $separator, $round);
    }

    public function showDiscount($round = false, $separator = '&nbsp;') {
		$price = $this->getDiscount();
        $price = (!$round) ? $price : floor($price);
		return se_formatMoney($price, $this->curr, $separator, $round);
    }

    // Возврашает текст доступного количества
    public function showPresenceCount($not_available='', $in_stock='') {
        $this->goodStyle = 1;
		$pcount = $this->presence_count;
        if ($this->presence_count != -1) {
            if ($this->presence_count == 0) {
                if (!empty($this->goods['presence'])) {
                    $pcount = $this->goods['presence'];
                } else {
                    $pcount = $not_available;
                }
                $this->goodStyle = 0;
            } else {
                $pcount .= '&nbsp;' . $this->goods['measure'];
            }
        } else {
            if (!empty($this->goods['presence'])) {
                $pcount = $this->goods['presence'];
            } else {
                $pcount = $in_stock;
            }
        }
        return $pcount;
    }

    // Получаем актуальное количество
    public function getActualCount() {
		return $this->count;
    }
   
    public function getPresenceCount() {
		return $this->presence_count;
    }
   
    public function getCountStyle() {
		return $this->goodStyle;
    }
}