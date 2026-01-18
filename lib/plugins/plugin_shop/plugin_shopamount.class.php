<?php

class plugin_shopamount
{
    private $curr = '';
    private $count = 0;
    private $price = 0;
    private $price_opt = 0;
    private $price_corp = 0;
    private $step = 1;
    public $sum_cart = 0;
    private $discount = null;
    private $goods;
    private $presence_count = 0;

    private $retail_price = 0;

    /**
     * @param integer $user_id    ID пользователя
     * @peram byte $type_price    Тип цены (0- Розничная, 1- корпоративная, 2- оптовая)
     * @param char $basecurr    Базовая валюта
     * @param array $goods    item good with fields (id,price,price_opt_corp,price_opt,bonus,discount,special_price,curr,presence_count,presence,measure)
     **/
    public function __construct($price_id, $goods = '', $type_price = 0, $count = 1, $modifications = '', $basecurr = '', $in_stock = true)
    {
        setlocale(LC_NUMERIC, 'C');

        $this->curr = empty($basecurr) ? se_getMoney() : $basecurr;

        $type_price = seUserTypePrice();

        $this->count = $count;
        if (empty($goods)) {
            $shopprice = new seShopPrice();
            $shopprice->select('id, price, id_group, price_opt_corp, price_opt, discount, max_discount, curr, presence_count, presence, step_count, measure, (SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications');
            $this->goods = $shopprice->find($price_id);
        } else {
            $this->goods = $goods;
        }

        if (!is_array($this->goods)) {
            $this->goods = array();
        }
        $defaults = array(
            'id' => 0,
            'price' => 0,
            'price_opt' => 0,
            'price_opt_corp' => 0,
            'discount' => 'N',
            'max_discount' => 0,
            'curr' => $this->curr,
            'presence_count' => -1,
            'presence' => '',
            'step_count' => 0,
            'measure' => '',
            'modifications' => 0,
        );
        $this->goods = array_merge($defaults, $this->goods);

        $select = 'sm.value AS value, sm.value AS retail_price';
        $this->retail_price = $this->price = $this->goods['price'];

        if (2 == $type_price) {
            $this->price = $this->goods['price_opt_corp'];
            $select = 'sm.value_opt_corp AS value, sm.value AS retail_price';
        } elseif (1 == $type_price) {
            $this->price = $this->goods['price_opt'];
            $select = 'sm.value_opt AS value, sm.value AS retail_price';
        }

        $this->price_opt = $this->goods['price_opt'];
        $this->price_corp = $this->goods['price_opt_corp'];

        $this->presence_count = 0;

        if (!empty($modifications)) {
            if (is_array($modifications))
                $modifications = join(',', $modifications);
            $shop_modifications = new seTable('shop_modifications', 'sm');
            $shop_modifications->select('sm.count, smg.vtype, sm.value_opt_corp, sm.value_opt, ' . $select);
            $shop_modifications->innerJoin('shop_modifications_group smg', 'sm.id_mod_group = smg.id');
            $shop_modifications->where('sm.id IN (?)', $modifications);
            $shop_modifications->andWhere('sm.id_price=?', $this->goods['id']);
            $list = $shop_modifications->getList();
            if (!empty($list)) {
                $this->presence_count = -1;
                $sum_retail_price = $sum_price = 0;
                foreach ($list as $val) {
                    if ($val['count'] === '0' || $val['count'] === '0.000') {
                        $this->presence_count = 0;
                    } elseif ($this->presence_count != 0) {
                        if ($val['count'] > 0 && ($this->presence_count > $val['count'] || $this->presence_count < 0))
                            $this->presence_count = $val['count'];
                    }

                    $retail_mod = isset($val['retail_price']) ? $val['retail_price'] : $val['value'];
                    if ($val['vtype'] == '0') {
                        $sum_price += ($this->price + $val['value']);
                        $sum_retail_price += ($this->retail_price + $retail_mod);
                    } elseif ($val['vtype'] == '1') {
                        $sum_price += ($this->price * $val['value']);
                        $sum_retail_price += ($this->retail_price * $retail_mod);
                    } else {
                        $sum_price += $val['value'];
                        $sum_retail_price += $retail_mod;
                    }
                }
                $this->price = $sum_price;
                $this->retail_price = $sum_retail_price;
            }
        } elseif (!empty($this->goods)) {
            $this->presence_count = ($this->goods['presence_count'] == '' || $this->goods['presence_count'] == -1) ? -1 : $this->goods['presence_count'];

            if (!empty($this->goods['modifications'])) {
                $this->presence_count = -1;
            }
        }
        if ((float)$this->goods['step_count'] > 0)
            $this->step = (float)$this->goods['step_count'];

        if ((float)$count > 0)
            $this->count = $this->stepRound($count, $this->step);
        else
            $this->count = $this->step;

        if (!$in_stock) {
            $this->presence_count = -1;
        }

        if ($this->presence_count >= 0) {
            $this->presence_count = ($this->presence_count < $this->step) ? 0 : $this->stepRound($this->presence_count, $this->step);
            if ($this->count > $this->presence_count)
                $this->count = $this->presence_count;
        }

        $this->price = round(se_MoneyConvert($this->price, $this->goods['curr'], $this->curr), 2);
        $this->price_opt = round(se_MoneyConvert($this->price_opt, $this->goods['curr'], $this->curr), 2);
        $this->price_corp = round(se_MoneyConvert($this->price_corp, $this->goods['curr'], $this->curr), 2);

        $this->retail_price = round(se_MoneyConvert($this->retail_price, $this->goods['curr'], $this->curr), 2);

        if ($this->price == 0)
            $this->price = $this->retail_price;
    }

    private function stepRound($count, $step)
    {
        return floor(round($count / $step, 4)) * $step;
    }

    public function getDiscountProc($round = true)
    {
        if (is_null($this->discount)) {
            $this->discount = 0;
            if ($this->goods['discount'] == 'Y') {
                $shopdiscount = plugin_shopdiscount::getInstance();
                $discount = $shopdiscount->getDiscount($this->goods, $this->sum_cart, $this->count);
                if (!empty($discount)) {
                    if ($discount['type'] == 'percent')
                        $this->discount = $discount['value'];
                    elseif (!empty($this->price)) {
                        $value = $discount['value'] / $this->price * 100;
                        $this->discount = min($value, 100);
                    }
                    $max_discount = (float)$this->goods['max_discount'];
                    if ($max_discount > 0)
                        $this->discount = min($this->discount, $this->goods['max_discount']);
                }
            }
        }
        return ($round) ? round($this->discount) : $this->discount;
    }

    public function getDiscountDate()
    {
        if ($this->goods['discount'] == 'Y') {
            $shopdiscount = plugin_shopdiscount::getInstance();
            $discount = $shopdiscount->getDiscount($this->goods, $this->sum_cart, $this->count);
            if (!is_array($discount)) {
                return null;
            }
            return array('start' => $discount['date_from'], 'end' => $discount['date_to']);
        }
    }

    // Получаем скидку товара
    public function getDiscount()
    {
        if (!empty($this->goods['bonus']) && $this->goods['bonus'] > 0) {
            $goodsprice = se_MoneyConvert($this->goods['bonus'], $this->goods['curr'], $this->curr);
        } else {
            $goodsprice = $this->price;
        }
        $discountproc = $this->getDiscountProc(false);

        $add_discount = 0;

        if ($this->retail_price > $this->price) {
            $add_discount = $this->retail_price -  $this->price;
        }

        return round($goodsprice * ($discountproc / 100), 2) + $add_discount;
    }

    // Получаем комбинированную цену учитывая параметры и скидки
    public function getPrice($discounted = true, $with_step = false)
    {
        $price = $this->price;

        if ($with_step)
            $price *= $this->step;

        if ($this->retail_price > $this->price) {
            $price = $this->retail_price;
        }

        return $price - ($discounted ? $this->getDiscount() : 0);
    }

    public function getAmount($discounted = true)
    {
        return round($this->getPrice($discounted) * $this->count, 2);
    }

    // Показать стоимость $round - округлить, $space - разделитель
    public function showPrice($discounted = true, $round = false, $separator = '&nbsp;', $with_step = false)
    {
        $price = $this->getPrice($discounted, $with_step);
        $price = $round ? ceil($price) : $price;
        return se_formatMoney($price, $this->curr, $separator, $round);
    }

    public function showAmount($discounted = true, $round = false, $separator = '&nbsp;')
    {
        $price = $this->getPrice($discounted) * $this->count;
        $price = $round ? ceil($price) : $price;
        return se_formatMoney($price, $this->curr, $separator, $round);
    }

    public function showDiscount($round = false, $separator = '&nbsp;')
    {
        $discount = $this->getDiscount();
        $discount = $round ? floor($discount) : $discount;
        return se_formatMoney($discount, $this->curr, $separator, $round);
    }

    // Возврашает текст доступного количества
    public function showPresenceCount($not_available = 'Нет в наличии', $in_stock = 'В наличии')
    {
        $pcount = $this->presence_count;
        if ($this->presence_count >= 0) {
            if ($this->presence_count == 0) {
                $pcount = !empty($this->goods['presence']) ? $this->goods['presence'] : $not_available;
            } else {
                $pcount .= '&nbsp;' . $this->goods['measure'];
            }
        } else {
            $pcount = !empty($this->goods['presence']) ? $this->goods['presence'] : $in_stock;
        }
        return $pcount;
    }

    // Получаем актуальное количество
    public function getActualCount()
    {
        return $this->count;
    }

    public function getPresenceCount()
    {
        return $this->presence_count;
    }

    public function getStepCount()
    {
        return $this->step;
    }

    public function getPriceOpt($discounted = true, $with_step = false)
    {
        $price = $this->price_opt;

        if ($with_step)
            $price *= $this->step;

        return $price;
    }

    public function getPriceCorp($discounted = true, $with_step = false)
    {
        $price = $this->price_corp;

        if ($with_step)
            $price *= $this->step;

        return $price;
    }

    public function showPriceOpt($discounted = true, $round = false, $separator = '&nbsp;', $with_step = false)
    {
        $price = $this->getPriceOpt($discounted, $with_step);
        $price = $round ? ceil($price) : $price;
        return se_formatMoney($price, $this->curr, $separator, $round);
    }

    public function showPriceCorp($discounted = true, $round = false, $separator = '&nbsp;', $with_step = false)
    {
        $price = $this->getPriceCorp($discounted, $with_step);
        $price = $round ? ceil($price) : $price;
        return se_formatMoney($price, $this->curr, $separator, $round);
    }
}
