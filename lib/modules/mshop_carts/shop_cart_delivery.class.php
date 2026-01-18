<?php

/**
 * @author Vladimir Sukhopyatkin
 * @copyright 2011
 */
 
require_once $__MDL_URL.'/shop_cart_classes.php';    
require_once $__MDL_URL.'/plugin_shopdeliveryservice.class.php';  
//require_once $__MDL_URL.'/shop_cart_view.php'; 

class shop_cart_Delivery extends shop_cart_Base
{
    public $user;
    public $delivery_type_id; // id выбранного способа доставки
    public $delivery_code; // Код для стандартных служб доставки (EMS, ...) или NULL, если нет API.
    public $delivery_price; // Цена выбранной доставки delivery_type_id
    public $deliverySum; // Сумма доставки
    public $delivery_term_min;  // минимальный срок доставки
    public $delivery_term_max;  // максимальный срок доставки  
    public $forone; // true, если это цена за единицу товара, а не за весь набор
    public $_phone;
    public $_email;
    public $_post_index;
    public $_addr;
    public $_calltime;
    public $_ordercomment;
    public $city_from; // Город отправки для служб доставки (city--abakan)
    public $city_to; // Город доставки для служб доставки (city--moskva)
    public $DELIVERY_NOTE; // Комментарий администратора к способу доставки 
    public $DELIVERY_TYPES;  // Фрагмент HTML-кода, содержащий список <option...></option>
    public $CITIES; // Города доставки
    public $display_cities;
    public $show_delivery_term;
    public $volume;
    public $weight;
    public $delivery_error;
    public $incart_ids; // список id товаров в корзине
    public $select_addr_id; // id выбранного адреса из селекта адресов пользователя
    
    public function __construct($section, $user) 
    {
        parent::__construct($section);  
        
        $this->user = $user;
                                            
        // ---Собираем данные о реквизитах доставки (для сохранения при перезагрузке формы)
        if (isRequest('email')) 
            $_SESSION['shopdelivery']['email'] = getRequest('email', VAR_STRING);
        if (!empty($_SESSION['shopdelivery']['email']))  
            $this->_email = $_SESSION['shopdelivery']['email'];
        else
            $this->_email = $this->user->email;
            
        if (isRequest('phone'))
            $_SESSION['shopdelivery']['phone'] = getRequest('phone', VAR_STRING);
        if (!empty($_SESSION['shopdelivery']['phone']))  
            $this->_phone = $_SESSION['shopdelivery']['phone'];
        else
            $this->_phone = $this->user->phone;

        if (isRequest('calltime'))
            $_SESSION['shopdelivery']['calltime'] = getRequest('calltime', VAR_STRING);
        if (!empty($_SESSION['shopdelivery']['calltime']))  
            $this->_calltime = $_SESSION['shopdelivery']['calltime'];
                                                               
        if (isRequest('post_index'))
            $_SESSION['shopdelivery']['post_index'] = getRequest('post_index', VAR_STRING);
        if (isset($_SESSION['shopdelivery']['post_index']))  
            $this->_post_index = $_SESSION['shopdelivery']['post_index'];
        if ($this->_post_index == 0)
            $this->_post_index = '';
                                                                  
        if (isRequest('addr'))
            $_SESSION['shopdelivery']['addr'] = getRequest('addr', VAR_STRING);
        if (isset($_SESSION['shopdelivery']['addr']))
            $this->_addr = $_SESSION['shopdelivery']['addr'];
        
        if (isRequest('select_addr_id'))
            $_SESSION['shopdelivery']['select_addr_id'] = getRequest('select_addr_id', VAR_INT);
        if (isset($_SESSION['shopdelivery']['select_addr_id']))
            $this->select_addr_id = $_SESSION['shopdelivery']['select_addr_id'];                 
        
        if (isRequest('ordercomment'))
            $_SESSION['shopdelivery']['ordercomment'] = getRequest('ordercomment', VAR_STRING);
        if (!empty($_SESSION['shopdelivery']['ordercomment']))  
            $this->_ordercomment = $_SESSION['shopdelivery']['ordercomment'];
        
        // Определяем выбранный способ доставки - $this->delivery_type_id
        $this->delivery_type_id = 0;
        if (isRequest('delivery_type_id'))    
            $_SESSION['shopdelivery']['delivery_type_id'] = getRequest('delivery_type_id', VAR_INT); 
        if (!empty($_SESSION['shopdelivery']['delivery_type_id']))  
            $this->delivery_type_id = $_SESSION['shopdelivery']['delivery_type_id'];
        if (empty($this->delivery_type_id)) 
            $this->delivery_type_id = 0;
    } 
    
    public function recalculate($incart_ids, $items_count, $order_weight) 
    {
        $this->incart_ids = $incart_ids;
        $dl = sc_getDeliverySelect($incart_ids);
           
        if (empty($dl))                                                                   // Если нет доставки для выбранных товаров, 
            $this->delivery_type_id = $_SESSION['shopdelivery']['delivery_type_id'] = 0;  // то очищаем сохраненный выбранный способ             
        
        // ----- ИНИЦИАЛИЗИРУЕМ -----
        
        $this->delivery_code = 0;            
        $this->delivery_price = 0;
        $this->forone = 0;
        
        if (empty($this->delivery_type_id)) // Инициализируем первый способ доставки и получаем значения 
        {   
            $this->delivery_type_id = 0; // обнуляем
            
            $rdelivery_list = sc_getDeliverySelect($incart_ids);  // массив способов доставки
            if (!empty($rdelivery_list)) {
                list($first_val) = $rdelivery_list;

                if ($this->section->parametrs->param95 == 'Y')                
                    $this->delivery_type_id = $first_val['id'];
                
                if (!empty($this->delivery_type_id)) 
                {
                    $this->delivery_code = $first_val['code'];           
                    $this->delivery_price = $first_val['price'];
                    $this->forone = ($first_val['forone'] == 'Y');
                }
            }
        }
        else                                 // Берем значения с существующего delivery_type_id
        {
            $se_delivery = new seTable('shop_deliverytype');
            $se_delivery->find($this->delivery_type_id);
            
            $this->delivery_code = $se_delivery->code;            
            $this->delivery_price = $se_delivery->price;
            $this->forone = ($se_delivery->forone == 'Y');
                             
            unset($se_delivery);
        }
        // --------------------------
        
        // ----- ВЫЧИСЛЯЕМ -----
        $this->weight = $order_weight;
        
        if (empty($this->delivery_code)) // ----- для обычных доставок 
        {
            if ($this->forone)        
                $this->deliverySum = $this->delivery_price * $items_count;
            else
                $this->deliverySum = $this->delivery_price;
        }
        elseif ($this->delivery_code == 'EMS') // ----- для EMS
        { 
            $this->display_cities = true; 
            
            // Определяем выбранный город доставки - $this->city_to
            if (isRequest('city_to'))    
                $_SESSION['shopdelivery']['city_to'] = getRequest('city_to', VAR_STRING); 
            if (!empty($_SESSION['shopdelivery']['city_to']))  
                $this->city_to = $_SESSION['shopdelivery']['city_to'];
            if (empty($this->city_to)) 
                $this->city_to = 0;
                                             
            if (empty($this->city_to)) 
            {
                $this->deliverySum = 0;
                $this->delivery_error = $this->section->parametrs->param113; // Не выбран город доставки
            }                          
            else
            {
                $this->getCityFrom(); // Инициализируем город отправки $this->city_from      
                if (empty($this->city_from))
                    $this->deliverySum = 0; // в случае отсутствия города отправки
                else  
                    // Вычисляем сумму доставки для EMS              
                    $this->deliverySum = $this->calculateEMS();     
            }                                                                                                         
        }
    }
    
    public function showDeliveryBlock() 
    {                     
        if ($this->display_cities)
            $this->showCitiesList();
            
        $this->DELIVERY_NOTE = str_replace("\r\n", "<br>", $this->section->parametrs->param59); // 59 - Комментарий администратора к заказу
        $rdelivlist = sc_getDeliverySelect($this->incart_ids); 
        
        // ---- Формируем первую строку списка доставок ----
        if (!empty($rdelivlist)                           // Если есть список доставок  
        && ($this->section->parametrs->param95 == 'N')) { // и доставка необязательна 
            $this->DELIVERY_TYPES = '<option value="0">&nbsp;'.$this->section->parametrs->param57.'</option>'; 
                                                                                    // текст в списке "без доставки"
        }
        else {                                   
            $this->DELIVERY_TYPES = ''; // пустая строка
        }
        // -------------------------------------------------
          
        foreach ($rdelivlist as $rowdeliv) 
        {
            if ($rowdeliv['id'] == $this->delivery_type_id) // Определяем, какой элемент сделать выбранным
            {
                $sel = " selected"; 
                $this->DELIVERY_NOTE = str_replace("\r\n", "<br>", $rowdeliv['note']);
            } 
            else 
                $sel = "";
            
            $this->DELIVERY_TYPES .= '<option value="'.$rowdeliv['id'].'"'.$sel.'>&nbsp;'.$rowdeliv['name'].
                                    ' ('.$rowdeliv['time'].' '.$this->section->language->lang005.')</option>';
        } 
        
        $this->showAddressesList();                                        
    }              
    
    public function showCitiesList() // Готовит массив options CITIES для отображения в селекте
    {
        $pluginDeliv = new plugin_shopDeliveryService('EMS');
        $arr_cities = $pluginDeliv->getLocations('EMS', 'russia');
        
        if (empty($this->city_to))
            $sel = "selected";
        else
            $sel = "";                   
        $this->CITIES = '<option class="opt_title" value="0" '.$sel.'>&nbsp;'.$this->section->parametrs->param106.'</option>'; 
                                        
        foreach($arr_cities['rsp']['locations'] as $value) {
            if ($value['type'] == 'cities') {
                if (strval($this->city_to) == strval($value['value']))
                    $sel = "selected";
                else
                    $sel = "";       
                $this->CITIES .= '<option value="'.$value['value'].'" '.$sel.'>&nbsp;'.$value['name'].'</option>';
            }
        }
        
        $this->CITIES .= '<option class="opt_title" value="0">&nbsp;'.$this->section->parametrs->param107.'</option>'; 
        
        foreach($arr_cities['rsp']['locations'] as $value) {
            if ($value['type'] == 'regions') {
                if (strval($this->city_to) == strval($value['value']))
                    $sel = "selected";
                else
                    $sel = "";         
                $this->CITIES .= '<option value="'.$value['value'].'" '.$sel.'>&nbsp;'.$value['name'].'</option>';
            }        
        }
        
        unset($pluginDeliv);
    }
    
    public function showAddressesList() // Готовит массив $SELECT_ADDRESSES для отображения в селекте
    {   
        if ($this->user->addr) {
            
            $this->SELECT_ADDRESSES = '<option value="0">'.$this->section->parametrs->param122.'</option>';

            if ($this->select_addr_id == $this->user->idUser)
                $sel = ' selected';
            else
                $sel = '';
                
            $this->SELECT_ADDRESSES .= '<option'.$sel.' value="'.$this->user->idUser.'">'.$this->user->addr.'</option>';
        }
        else 
            $this->SELECT_ADDRESSES = '';
    }
    
    public function getCityFrom() // Вычисляет код города отправки (city--abakan) по значению поля в таблице main (Абакан)
    {
        $tbl = new seTable("main");
        $tbl->select("city_from_delivery");
        $tbl->where("lang = '?'", se_getLang());
        $tbl->fetchOne();
        $city_from_name = $tbl->city_from_delivery;
        unset($tbl);
                
        $pluginDeliv = new plugin_shopDeliveryService('EMS');
        $arr_cities = $pluginDeliv->getLocations('EMS', 'russia');
        unset($pluginDeliv);
        
        foreach($arr_cities['rsp']['locations'] as $value) {
            if (strval($city_from_name) == strval($value['name'])) 
            {
                $this->city_from = $value['value'];
                break;
            }
        }
        
        if (empty($this->city_from))
            $this->delivery_error = $this->section->parametrs->param109; // Отсутствует город отправки
    }
    
   
    public function calculateEMS() // Вычисляет стоимость доставки заказа для стандартных служб (EMS)
    {    
        $pluginDeliv = new plugin_shopDeliveryService('EMS');

        $weight_arr = $pluginDeliv->getMaxWeight('EMS');
        $max_weight = $weight_arr['rsp']['max_weight'];                                      
        if ($this->weight <= $max_weight) 
        {                                                                     
            $prc_arr = $pluginDeliv->calculate('EMS', $this->city_from, $this->city_to, $this->weight);  
            unset($pluginDeliv);
            $this->delivery_price = $prc_arr['rsp']['price'];
            $this->delivery_term_min = $prc_arr['rsp']['term']['min'];
            $this->delivery_term_max = $prc_arr['rsp']['term']['max'];
            $this->show_delivery_term = strval($this->delivery_term_min.'-'.$this->delivery_term_max);
            return $this->delivery_price; // сумма доставки будет такова
        }
        else
        {
            unset($pluginDeliv);
            $this->delivery_error = $this->section->parametrs->param108 ; // Превышен допустимый вес
            return 0;
        }
    }
    
    public function validate()
    {
        return (empty($this->delivery_error)); 
    }
}
?>