<?php

/**
 * @author Vladimir Sukhopyatkin
 * @copyright 2011
 */
 
require_once $__MDL_URL.'/shop_cart_classes.php';  

class shop_cart_View {
    public $fl_even_str;
    public $section;
    public $data;
    public $error_message;
    public $ex_error;
    
    // -- условия --
    public $display_author;
    public $display_resume;
    public $display_unregBtn;
    public $display_pageNavigator;
    public $display_error;
    public $display_ex_error;
        
    // -- значения --
    public $sum_subtotal;
    public $sum_discount;
    public $sum_delivery;
    public $sum_all;
    

    public function __construct($section) {
        $this->section = $section;
        $this->data = seData::getInstance(); 
    }

    private function getOrderedBefore($id_user, $id_price)
    {
        // Получить последний заказ этого пользователя с товаром $id_price
        // shop_order: id_author, date_order
        // shop_tovarorder: id_order, id_price, count
        
        $tovar = new seTable('shop_order', 'so');
        $tovar->select('so.date_order, st.count');
        $tovar->innerjoin('shop_tovarorder st', 'id_order = so.id');
        $tovar->where('id_author=?', $id_user);
        $tovar->andwhere('id_price=?', $id_price);
        $tovar->orderby('date_order', 1);   
        $tovar_res = $tovar->fetchOne();
        
        if ($tovar_res) {
            $res['flag_ordered_before'] = true;  
            $res['date_ordered_before'] = date('d.m.Y', strtotime($tovar_res['date_order']));
            $res['count_ordered_before'] = $tovar_res['count'];        
        }
        else {                                               
            $res['flag_ordered_before'] = false;  
            $res['date_ordered_before'] = 0;
            $res['count_ordered_before'] = 0;    
        }
        
        return $res;
    }
    
    public function prepareObjectRow($order_line_obj)
    {
        if (!isset($order_line_obj))
            exit;
            
        $plugin_amount = new plugin_shopAmount($order_line_obj->price_id, '', $order_line_obj->user->priceType, 
                                               $order_line_obj->count, $order_line_obj->params, $order_line_obj->basecurr);
        
        $row['class'] = ($this->fl_even_str = !$this->fl_even_str) ? "tableRowOdd" : "tableRowEven";
        
        $row['key'] = $order_line_obj->key;
        $row['id'] = $order_line_obj->price_id;
        $row['article'] =$order_line_obj->article;
        $row['name'] = $order_line_obj->name;
        $row['params'] = $order_line_obj->params;
        $row['paramstr'] = $order_line_obj->paramstr;
        $row['unsold'] = $order_line_obj->flagUnsold;
        
        $ordered_before = $this->getOrderedBefore($order_line_obj->user->idUser, $order_line_obj->price_id);
        $row['flag_ordered_before'] = $ordered_before['flag_ordered_before'];  
        $row['date_ordered_before'] = $ordered_before['date_ordered_before'];
        $row['count_ordered_before'] = $ordered_before['count_ordered_before'];
        
        $row['presence_count'] = 
            $plugin_amount->showPresenceCount($this->section->parametrs->param69);  // param69 - альтернативный текст
          //  echo 'show_pres='.$row['presence_count'];                     
        if ($order_line_obj->presence_count == '-1' || $order_line_obj->presence_count == '0')
            $row['lencn'] = 5; // бесконечное количество или нет товара 
        else 
            $row['lencn'] = utf8_strlen($row['presence_count']); // определенное количество товара
        
        // --- Округление и сепараторы ---
        $rounded = ($this->section->parametrs->param97 == 'Y'); // округление

        if ($this->section->parametrs->param98 == 'Y') // сепаратор между 1 000 000
            $separator = ' ';
        else
            $separator = '';
        // -------------------------------
        
        if ($order_line_obj->flagUnsold) // == ЗАЛЕЖАВШИЙСЯ ТОВАР ==
        {
            if ($order_line_obj->flagUserChangedPrice)
            {                               // Пользователь изменил цену
                $row['userprice'] = $order_line_obj->price;
                $row['price'] = $this->showPrice($order_line_obj);     
                $row['discount'] = 0;               
                $row['sum'] = $this->showSum($order_line_obj);       
            }
            else
            {                               // Пользователь не менял цену
                $row['price'] = $plugin_amount->getPrice(false);
                $row['discount'] = 0;
                $row['sum'] = $plugin_amount->showAmount(false, $rounded, $separator);
            }           
        }
        else                             // == ОБЫЧНЫЙ НОРМАЛЬНЫЙ ТОВАР ==
        {
            $row['price_discounted'] = $plugin_amount->showPrice(true, // discounted
                                                                      $rounded, // округлять ли цену
                                                                      $separator); // разделять ли пробелами 000 000
            $row['price'] = $plugin_amount->showPrice(false, // discounted
                                                                      $rounded, // округлять ли цену
                                                                      $separator); // разделять ли пробелами 000 000
            $row['discount'] = $plugin_amount->showDiscount($rounded, $separator);
            $row['sum'] = $plugin_amount->showAmount(true, $rounded, $separator);     
        }
                          
        $row['count'] = $order_line_obj->count;

        unset($plugin_amount);
                                                                                     
        return $row;
    }

       
    public function showPrice($order_line_obj)
    {
        $rounded = ($this->section->parametrs->param97 == 'Y'); // округлени
        return myFormatMoney($order_line_obj->price, $order_line_obj->basecurr, $rounded);    
    }
    
    public function showSum($order_line_obj)
    {
        $rounded = ($this->section->parametrs->param97 == 'Y'); // округлени
        return myFormatMoney($order_line_obj->price * $order_line_obj->count, $order_line_obj->basecurr, $rounded);
    }

	public function showRecord($order_line_obj) 
    {
        $this->data->setItemList($this->section, 'objects', $this->prepareObjectRow($order_line_obj));
	}
	
	public function showResumeLine($order_line_obj) 
    {
        $this->data->setItemList($this->section, 'resumeobj', $this->prepareObjectRow($order_line_obj));    
    }
	
	public function showOrderSum($order_obj) 
    {
        $rounded = ($this->section->parametrs->param97 == 'Y'); // округлени
        $this->sum_subtotal = myFormatMoney($order_obj->subtotal, $order_obj->basecurr, $rounded);
    }
    
    public function showAuthor() {
        $this->display_author = true;    
    }
    
    public function showResume($order_obj) {
        $this->display_resume = true;
        
        unset($this->section->resumeobj);                
        foreach ($order_obj->orderLines as $order_line_obj) {
            $this->showResumeLine($order_line_obj);
        }     

        $rounded = ($this->section->parametrs->param97 == 'Y'); // округлени
        
        $this->sum_subtotal = myFormatMoney($order_obj->subtotal, $order_obj->basecurr, $rounded);
        $this->sum_discount = myFormatMoney($order_obj->totalDiscount, $order_obj->basecurr, $rounded);
        $this->sum_delivery = myFormatMoney($order_obj->deliverySum, $order_obj->basecurr, $rounded);
        $this->sum_all =      myFormatMoney($order_obj->total, $order_obj->basecurr, $rounded);
    }
    
    public function showUnregBtn() {
        $this->display_unregBtn = true;
    }
    
    public function showPageNavigator() {
        $this->displayPageNavigator = true;
    }
    
	public function showError($error) {
        if (!empty($error)) {
            $this->display_error = true;
            $this->error_message = $error;
        }
        else {
            $this->display_error = false;
            $this->error_message = '';
        }
    }
    
    public function showExError($error) {
        if (!empty($error)) {
            $this->display_ex_error = true;
            $this->ex_error = $error;
        }
        else {
            $this->display_ex_error = false;
            $this->ex_error = '';
        }
    }
    
} // class View

?>