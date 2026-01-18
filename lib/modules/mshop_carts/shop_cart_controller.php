<?php

/**
 * @author Vladimir Sukhopyatkin
 * @copyright 2011
 */
  
require_once $__MDL_ROOT.'/shop_cart_classes.php';      
require_once $__MDL_ROOT.'/shop_cart_view.php'; 
require_once $__MDL_ROOT.'/shop_cart_delivery.class.php';   
require_once $__MDL_ROOT.'/shop_cart_register.php';     
         
class shop_cart_Controller extends shop_cart_Base
{    
    public $user;
    public $order;
    public $view;
    public $backpage;
    public $delivery;
    public $flagWithoutReg; // Говорит о том, что пользователь пожелал заказ без регистрации
    public $registration; 
  
    public function __construct($section) 
    {  
        parent::__construct($section);    
        $this->user = new shop_cart_User($section);
        
      //  echo $this->delivery->_phone;
        //            echo $this->delivery->_email;
                    
        $this->delivery = new shop_cart_Delivery($section, $this->user);
        $this->order = new shop_cart_Order($section, $this->user, $this->delivery);
        $this->view = new shop_cart_View($section);
        $this->setUnregStatus();        
    } 
         
    public function setUnregStatus() // Устанавливает значение переменной flagWithoutReg 
                                     // и соответствующее ей значения сессии 'shopcartunreg'
    {                          
        if (isRequest('GoTo_OrderUnreg') || ($this->section->parametrs->param102 == 'N')) 
            $_SESSION['shopcartunreg'] = true;
                    
        if (!empty($_SESSION['shopcartunreg']) && empty($this->user->userGroup))  
            $this->flagWithoutReg = $_SESSION['shopcartunreg'];
        else
            unset($_SESSION['shopcartunreg']);
    }         
         
    public function unserializeShopCart() 
    {
        $filename = getCwd() . '/projects/session/' . getRequest('idcart') . '.ser';
        if (file_exists($filename)) {
            $shopcart = $_SESSION['shopcart'];
            $ser = se_file($filename);
            $ser = unserialize($ser[0]);
            foreach ($ser as $id1 => $item1) 
                $shopcart[strval($id1)] = $item1;
            $_SESSION['shopcart'] = $shopcart;
            unlink($filename);
        }    
    }     
         
    public function getIncartForOrderPlugin() 
    {
        $ingoods = array();
        $rounded = ($this->section->parametrs->param97 == 'Y'); // округление
                                          
        foreach ($this->order->orderLines as $order_line_obj) {
            $namefull = $order_line_obj->name;                   
            if (!empty($order_line_obj->paramstr))
                $namefull .= ' ('.$order_line_obj->paramstr.')';                    
                            
            $ingoods[] = array(
                                'price_id' => $order_line_obj->price_id, 
                                'count' => $order_line_obj->count, 
                                'name' => $namefull, 
                                'price'=> $rounded ? round($order_line_obj->price) : $order_line_obj->price, 
                                'discount' => $rounded ? round($order_line_obj->discount) : $order_line_obj->discount,
                                'curr' => $order_line_obj->basecurr
                              );
        }                             
        return $ingoods;
    }                                      
         
    public function getBackpage() 
    {
       // ------ Страница для возврата назад -----
        unset($_SESSION['TOBACK']);
        $referer = $_SERVER['HTTP_REFERER'];
        $thispage = $this->order->data->getPageName();
        list(, , $host, $referpage) = explode('/', $referer);
        if (($host != $_SERVER['HTTP_HOST']) && !empty($referpage)) 
            $_SESSION['TOBACK'] = $referer;
        elseif (!empty($referpage) && ($referpage != $thispage)) 
            $_SESSION['TOBACK'] = $referer;
            
        if (isset($_SESSION['TOBACK'])) 
            return $_SESSION['TOBACK'];
        else 
            return "/" . $this->section->parametrs->param1 . "/";
    }
         
    public function reloadShopCart()
    {                                                
        if (isset($_POST['countitem'])) {   
            foreach ($_POST['countitem'] as $key => $value) 
            {                                              
                if (!isset($value['price']))
                    $value['price'] = 0;                         
                $this->order->updateOrderLine($key, $value['id'], $value['params'], $value['count'], $value['price']);
            }
        }                                            
    }
    
    public function goToOrders() 
    {                                       
        if ($this->validate()) 
        {                         
            $ingoods = $this->getIncartForOrderPlugin(); 
                //     print_r($ingoods);
            if ($this->flagWithoutReg)             
                $plugin_order = new plugin_shopOrder(0, $ingoods, $this->user->client_name);
            else
                $plugin_order = new plugin_shopOrder($this->user->idUser, $ingoods);
            
            $plugin_order->commentary = $this->delivery->_ordercomment;  
                   
            $arr_delivery = array (
                                'id' => $this->delivery->delivery_type_id, 
                                'phone' => $this->delivery->_phone, 
                                'email' => $this->user->email, //$this->delivery->_email,  
                                'calltime' => $this->delivery->_calltime, 
                                'address' => $this->delivery->_addr, 
                                'postindex' => $this->delivery->_post_index,
                                'summ' => $this->delivery->deliverySum,
                                'volume' => $this->delivery->volume,
                                'weight' => $this->delivery->weight
                            );
                        
            $order_id = $plugin_order->execute($arr_delivery, $this->delivery->_email);
            unset($plugin_order);    
                               
            if ($this->section->parametrs->param97 == 'Y') { 
                se_db_query("UPDATE shop_tovarorder
                            SET
                                price = CEILING(price),
                                discount = FLOOR(discount)
                            WHERE
                                id_order = '$order_id'");
            }
            $_SESSION['TOBACK'] = $_SESSION['old_shopcart'] = $_SESSION['shopcart'] = 
            $_SESSION['shopdelivery'] = $_SESSION['shopcartunreg'] = null;
                    
            if (!empty($this->flagWithoutReg))                                                    
                header("Location: /".seMultiDir().$this->data->getPageName().'/'.$this->section->id.'/sub3/?'.time()); 
            else               {                                                      
                header("Location: /".$this->section->parametrs->param2."/?".time()); } 
            exit();
        }
    }    
    
    public function minSumCartOk()
    {
        return ($this->order->subtotal >= $this->order->minsumcart); 
    }
         
    public function isLoginExists() {
    
    }
         
	public function processData() // диспетчеризация и обработка запросов от пользователя
    { 
        // Если в параметре param74 указана регистрация по логину, то интеллектуальный режим выключается, включается смешанный режим.
        if ($this->section->parametrs->param74 == 'username')
            $this->section->parametrs->param102 == 'M';
    
        // --------- Определяем страницу для возврата назад
        $this->backpage = $this->getBackpage(); 
        
        // --------- Un-Сериализация --------------
        if (isRequest('idcart'))  
            $this->unserializeShopCart();  
   
        
        if (isRequest('shcart_reload')) 
        { // ------------- Пересчет корзины ------------ 
            $this->reloadShopCart();     
            unset($this->flagWithoutReg);
        }
        elseif (isRequest('shcart_clear')) 
        { // ------------- Очистка корзины ---------------
            $this->order->clearOrderLines();
            $this->order->recalculate();      
            unset($this->flagWithoutReg);                 
        }
        elseif (isRequest('dellcart')) 
        { // ------------- Удаление одного пункта заказа -----------
            $dellcart = $_POST['dellcart'];
            foreach ($dellcart as $key => $value) {
                $this->order->delOrderLine($key);
            }
            $this->order->recalculate();
        }
        else
        {                                                      
            $this->order->recalculate();
            if (!$this->delivery->validate())
                $this->view->showExError($this->delivery->delivery_error); // Ошибка службы доставки
        }
                                         
        
        // --------- Вывод строк корзины и итоговой суммы ----------
        unset($this->section->objects);                
        foreach ($this->order->orderLines as $order_line_obj) {
            $this->view->showRecord($order_line_obj);
        }     
        $this->view->showOrderSum($this->order);
        
      
        // --------- Вывод резюме ----------------------------------
        if ((($this->section->parametrs->param102 == 'N') ||
             ($this->section->parametrs->param102 == 'A') ||
             ($this->section->parametrs->param102 == 'M' && $this->user->userGroup) ||
             ($this->section->parametrs->param102 == 'M' && $this->flagWithoutReg && !$this->user->userGroup) ||
             ($this->section->parametrs->param102 == 'Y' && $this->user->userGroup))
            && 
            $this->order->getLinesCount()) 
        { 
            $this->view->showResume($this->order); 
            $this->delivery->showDeliveryBlock();
        }

        // --------- Определяем показ формы авторизации и кнопки "Без регистрации"
        if (!$this->user->userGroup && ($this->section->parametrs->param102 == 'Y')) {   
            $this->view->showAuthor(); 
        } 
        elseif (!$this->user->userGroup && !$this->flagWithoutReg && ($this->section->parametrs->param102 == 'M')) {
            $this->view->showAuthor();
            $this->view->showUnregBtn();                
        }        
                                                            
        // ------------- Способ доставки ---------------
  //      if (isRequest('delivery_type_id')) { 
  //          $_SESSION['shopdelivery']['delivery_type_id'] = getRequest('delivery_type_id');
  //      }

        // ------------- Авторизация -------------------
        if (isRequest('GoToAuthor')) {
            $_SESSION['shopcartunreg'] = false;
            $this->flagWithoutReg = false;
        }

        // ------------- Регистрация покупателя --------
        if (isRequest('GoToRegShop')) { 
            $this->registration = new shop_cart_Registration($this->section, $this->view);
            $this->registration->register();
        }

        // ------------- Формирование заказа --------
        if (isRequest('GoTo_ORDERS')) {      
            if ($this->section->parametrs->param102 == 'A') // Если автоматическая регистрация
            {          
                if ($this->validate())
                {                       
                  //  echo $this->delivery->_phone;
                  //  echo $this->delivery->_email;
                    if ($this->isLoginExists()) // Если найден такой логин, то авторизация
                    {             // se_user->username (таблица->поле)
                        // 1. Выводим окошко с предложением ввести пароль и ссылкой "Забыли пароль?"
                    }
                    else // Если логин не найден
                    { 
                        // 1. создаем учетную запись
                            // 1. генерируем пароль
                        
                        
                        // 2. отправляем письмо о создании аккаунта с логином и паролем
                    }
                }                         
            }
            else // Если не автоматическая регистрация
                $this->goToOrders();
        }
	}   
	
    function validate() {
        $res = false;   
                            
        if (($this->user->userGroup > 0) && ($this->user->idUser == 0)) 
            $this->view->showExError($this->section->parametrs->param100); // "Администратор не может осуществлять заказ!" 
        elseif (empty($this->user->userGroup) && empty($this->user->client_last_name))
            $this->view->showExError($this->section->parametrs->param61); // для неавторизированного пользователя "Поле Фамилия не должно быть пустым!"
        elseif (empty($this->user->userGroup) && empty($this->user->client_first_name))
            $this->view->showExError($this->section->parametrs->param18); // для неавторизированного пользователя "Поле Имя не должно быть пустым!"
        elseif (empty($this->user->userGroup) && ($this->section->parametrs->param85=='Y') && empty($this->user->client_sec_name)) // Если поле Отчество обязательно
            $this->view->showExError($this->section->parametrs->param62); // для неавторизированного пользователя "Поле Отчество не должно быть пустым!"                
        elseif (($this->section->parametrs->param74 == 'email') && (!$this->delivery->_email)) 
            $this->view->showExError($this->section->parametrs->param99); // "Поле e-mail не должно быть пустым!"
        elseif (($this->section->parametrs->param74 == 'email') && !se_CheckMail($this->delivery->_email)) 
            $this->view->showExError($this->section->parametrs->param64); // "e-mail введен неверно!"
        elseif ((($this->section->parametrs->param90 == 'Y')||($this->section->parametrs->param74 == 'phone')) 
                && empty($this->delivery->_phone)) 
            $this->view->showExError($this->section->parametrs->param92); // "Номер телефона не должен быть пустым!"
        elseif ($this->delivery->delivery_type_id && (trim($this->delivery->_post_index) == '') && ($this->section->parametrs->param123 == 'Y')) 
            $this->view->showExError($this->section->parametrs->param121); // "Поле индекс не должно быть пустым!"
        elseif ($this->delivery->delivery_type_id && (!validate_postindex(trim($this->delivery->_post_index))) && ($this->section->parametrs->param123 == 'Y')) 
            $this->view->showExError($this->section->parametrs->param123); // "Поле индекс введено неверно!"        
        elseif ($this->delivery->delivery_type_id && (trim($this->delivery->_addr) == '')) 
            $this->view->showExError($this->section->parametrs->param93); // "Поле адрес не должно быть пустым!"
        elseif ($this->delivery->delivery_type_id && // не пустой
        ($this->delivery->_addr && (utf8_strlen($this->delivery->_addr) < intval($this->section->parametrs->param94)))) 
            $this->view->showExError(str_replace('%N%',intval($this->section->parametrs->param94), $this->section->parametrs->param66));
                                                                        // "Адрес должен содержать как минимум N символов!"  
        elseif (!$this->minSumCartOk()) {
            $rounded = ($this->section->parametrs->param97 == 'Y'); // округление
            $this->view->showExError($this->section->parametrs->param56.' '.
                                     myFormatMoney($this->order->minsumcart, $this->order->basecurr, $rounded)); 
                                                                        // "Минимальная сумма заказа составляет..."
        }
        elseif (!empty($this->delivery->delivery_error))
            $this->view->showExError($this->delivery->delivery_error); // Ошибка службы доставки
        else 
            $res = true;	
                
        return $res;
    }
    
}              

?>