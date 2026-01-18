<?php

/**
 * @author 
 * @copyright 2011
 */
 
class shop_cart_Base
// Служебный класс
{
    // Служебные
    public $section;
    public $data;
                 
    protected function __construct($section) 
    {
        // Служебные        
        $this->section = $section; // для правильной работы (param82 будет заменяться на $section->param82)
        $this->data = seData::getInstance(); // класс содержимого HTML-страницы
    }   
} 
 
                              
class shop_cart_User extends shop_cart_Base
// Авторизованный пользователь
{
    public $idUser;  // id авторизованного пользователя
    public $userGroup; // User == 1, SuperUser == 2, Administrator == 3;
    public $priceType; // 0 - розн., 1 - optovik, 2 - optcorp
    public $client_name; // ФИО клиента
    public $client_last_name; // Фамилия клиента
    public $client_first_name; // Имя клиента
    public $client_sec_name; // Отчество клиента
    public $email; // e-mail клиента
    public $personal_discount; // Персональная скидка клиента
    public $post_index; // Почтовый индекс
    public $addr; // Полный адрес 
    public $phone; // Телефон
                
    public function __construct($section) 
    {
        parent::__construct($section);
          
        $this->idUser = seUserId();  
        $this->userGroup = seUserGroup();    
        if (seUserGroupName() == $section->parametrs->param82) { 
            $this->priceType = 1; // optovik
        } elseif (seUserGroupName() == $section->parametrs->param83) { 
            $this->priceType = 2; // optcorp
        } else { 
            $this->priceType = 0;   
        }     
        // ### Данные о клиенте
        if (!empty($this->idUser))  // Авторизованный клиент
        {
            $se_person = new seTable();
            $se_person->from('person', 'p');
            $se_person->select("*, concat_ws(', ', c.name, r.name, t.name, overcity, addr) address");
            $se_person->leftjoin('country c', 'c.id = p.country_id');
            $se_person->leftjoin('region r', 'r.id = p.state_id');
            $se_person->leftjoin('town t', 't.id = p.town_id');       
            $se_person->where('p.id=?', $this->idUser); 
            $se_person->fetchOne();
        
            $this->client_last_name = $se_person->last_name;
            $this->client_first_name = $se_person->first_name;
            $this->client_sec_name = $se_person->sec_name;
            $this->client_name = $se_person->last_name . ' ' . $se_person->first_name . ' ' . $se_person->sec_name;
            $this->email = $se_person->email;
            $this->post_index = $se_person->post_index;
            $this->addr = $se_person->address;
            if (trim($this->addr) == ',')  // Чтоб не оставалась запятая в пустом адресе, а то мало ли что
                $this->addr = '';
            
            $this->personal_discount = $se_person->discount; 
            if (empty($this->personal_discount))
                $this->personal_discount = 0;
        }
        else                        // Неавторизованный клиент
        {                          
            if (isRequest('shcart_client_last_name')) {
                $this->client_last_name = getRequest('shcart_client_last_name', VAR_STRING);
            }

            if (isRequest('shcart_client_first_name')) {    
                $this->client_first_name = getRequest('shcart_client_first_name', VAR_STRING);
            }
                                
            if (isRequest('shcart_client_sec_name')) {
                $this->client_sec_name = getRequest('shcart_client_sec_name', VAR_STRING);
            }
            
            $_SESSION['shopcart']['client_name'] = trim($this->client_last_name.' '.$this->client_first_name.' '.$this->client_sec_name); 
            if (!empty($_SESSION['shopcart']['client_name']))  
                $this->client_name = $_SESSION['shopcart']['client_name'];
    
            $this->email = '';
            $this->post_index = '';
            $this->addr = '';
            $this->personal_discount = 0;
        }
    }   
}

class shop_cart_Order extends shop_cart_Base
// Заказ
{
    public $user; // Авторизованный пользователь (объект)
    public $delivery; // Указатель на объект shop_cart_delivery
    public $orderLines; // Строки заказа (объекты)
    public $basecurr;
    public $subtotal;
    public $totalDiscount;
    public $deliverySum;
    public $total;
    public $minsumcart; // Минимальная допустимая сумма в валюте $basecurr

    public function __construct($section, $user, $delivery) 
    {
        parent::__construct($section);
                                 
        $this->user = $user;        
        $this->delivery = $delivery;
          
        // Определяем базовую валюту
        if (isRequest('pricemoney')) // Если пользователь сам задал альтернативную валюту как базовую 
            $_SESSION['pricemoney'] = getRequest('pricemoney');
        if (!empty($_SESSION['pricemoney'])) 
            $this->basecurr = $_SESSION['pricemoney'];
        else {
            $this->basecurr = se_baseCurrency();
            if (empty($this->basecurr)) 
                $this->basecurr ='RUR';
        }        
        
           
        // Формируем строки заказа                   
        $this->addOrderLines();                           
        
        // Определяем минимальную сумму, разрешенную к покупке
        $list_curr = array();
        $money = new seTable('money_title');
        $money->where('lang="?"', se_getLang()); 
        $rlistcurr = $money->getList(); 
        foreach($rlistcurr as $row) { 
            $list_curr[] = $row['name'];
        }                                               
                                        
        $tmp_minsumc = explode(";", $this->section->parametrs->param55); // Минимальная сумма покупки "500;RUR"

        if (round($tmp_minsumc[0]) > 0) {
            $minsumc = $tmp_minsumc[0];
                                               
            if (!empty($tmp_minsumc[1]) && (in_array($tmp_minsumc[1], $list_curr))) 
                $minsumc_valut = $tmp_minsumc[1];                                    
            else 
                $minsumc_valut = $this->basecurr;
        
            $this->minsumcart = se_MoneyConvert($minsumc, $minsumc_valut, $this->basecurr, date("Ymd"));  
        }
    }           
            
    public function addOrderLines() 
    // Собирает из сессии все строки заказа корзины и приводит их в правильный вид
    {                                         
        // Получаем корзину из сессии
        $sessionShopcart = array();
        if (!empty($_SESSION['shopcart'])) {
            $sessionShopcart = $_SESSION['shopcart'];
        } 
        elseif (!empty($_COOKIE['shopcart'])) {
            $sessionShopcart = $_COOKIE['shopcart'];
        }                                               
            
        $this->orderLines = array();                          
        foreach($sessionShopcart as $key => $value) {  
            if (!empty($value['id']) && ($value['count'] > 0)) {      
               $this->orderLines[$key] = new shop_cart_OrderLine($this->section, $key, $this->user); 
               $this->orderLines[$key]->recalculate();              
               $this->orderLines[$key]->save();                 
            }                                           
        }                                                
    }
               
    public function saveOrderLines() 
    // Сохраняет все строки заказа в сессию (старая $_SESSIN['shopcart'] очищается)
    {   
        unset($_SESSION['shopcart']);
        foreach($this->orderLines as $key => $value) {
            $value->save();
        }   
    }             
    
    public function clearOrderLines() 
    // Очищает все строки заказа и $_SESSION['shopcart']
    {        
        foreach($this->orderLines as $key => $value) {
            unset($this->orderLines[$key]);
        }
        unset($_SESSION['shopcart']);
    }            
    
    public function delOrderLine($delKey)
    {
    // Удаляет строку заказа с ключом $delKey из заказа и из $_SESSION['shopcart'] 
        $this->orderLines[$delKey]->delete();
        unset($this->orderLines[$delKey]);
    }       
             
    public function getOrderWeight() 
    {
        $weight = 0;     
        
        foreach ($this->orderLines as $key => $value) 
            $weight += $this->orderLines[$key]->weight * $this->orderLines[$key]->count;
        
        if (floatval($weight) == 0)
            $weight = 0.001;  
              
        return $weight;
    }             
             
    public function updateOrderLine($lineKey, $id, $params, $count, $user_price) 
    {
        $this->orderLines[$lineKey]->price_id = $id;
        $this->orderLines[$lineKey]->params = $params;
        $this->orderLines[$lineKey]->count = $count;   
        if ($this->orderLines[$lineKey]->count < 0)
            $this->orderLines[$lineKey]->count = 0; 
        
        // -- Вот здесь определяем значение flagUserChangedPrice --
        $this->orderLines[$lineKey]->flagUserChangedPrice = (($user_price != $this->orderLines[$lineKey]->original_price)
                                                             && $this->orderLines[$lineKey]->flagUnsold); 
        
        if ($this->orderLines[$lineKey]->flagUserChangedPrice)  
            $this->orderLines[$lineKey]->acceptUserPrice($user_price);
        
        $this->recalculate($lineKey);
    }         
             
    /**     
     * @return string $incart_ids - список id товаров в корзине
     */    
    public function getIncartIds() 
    {
        $ids = array();
        foreach($this->orderLines as $line) 
            $ids[] = $line->price_id;
        $incart_ids = join(',', $ids);
        return $incart_ids;
    }             
             
    public function recalculate($lineKey = '')
    {               
        if (strval($lineKey) == '') { // пересчитываем все строки заказа
            foreach($this->orderLines as $key => $line) {
                $this->orderLines[$key]->recalculate();
                $this->orderLines[$key]->save();    
                if ($this->orderLines[$key]->count == 0)
                    $this->delOrderLine($key); 
            }
        }
        else {           
            $this->orderLines[$lineKey]->recalculate(); 
            $this->orderLines[$lineKey]->save();
            if ($this->orderLines[$lineKey]->count == 0)
                $this->delOrderLine($lineKey); 
        }

        // ------ ИТОГО --------
        
        // Обнуляем
        $this->subtotal = 0;
        $this->totalDiscount = 0; 
        $this->deliverySum = 0; 
        $this->total = 0;
        
        foreach($this->orderLines as $key => $value) {
            $this->subtotal += $this->orderLines[$key]->sum; 
            $this->totalDiscount += ($this->orderLines[$key]->discount * $this->orderLines[$key]->count);
        }    
        
        // Сумма доставки        
        $this->delivery->recalculate($this->getIncartIds(), $this->getItemsCount(), $this->getOrderWeight()); // пересчет цены доставки
        $this->deliverySum = $this->delivery->deliverySum;
        
        // Общая сумма
        $this->total = $this->subtotal + $this->deliverySum;
        
        // ------------------------    
    }  
    
    public function getLinesCount()
    {
        $count = 0;
        if (!empty($this->orderLines)) {
            foreach ($this->orderLines as $key => $line) {
                $count++;
            }
        }        
        return $count;
    }
    
    public function getItemsCount()
    {
        $count = 0;
        if (!empty($this->orderLines)) {
            foreach ($this->orderLines as $key => $line) {
                $count += $this->orderLines[$key]->count;
            }
        }        
        return $count;
    }
              
}

                
class shop_cart_OrderLine extends shop_cart_Base
{
    public $plugin_params;
    public $tbl;
    public $qr_priceline; // расшифровывается как "query_result of priceline"
    
    public $user;
    public $basecurr;
    public $key;
    public $price_id;
    public $article;
    public $name;
    public $weight;
    public $params;
    public $paramstr;
    public $presence_count;
    public $presence;
    public $measure;
    public $price;
    public $original_price; // Исходная цена из базы
    public $count;
    public $discount;
    public $sum;
    public $curr;
    public $flagUnsold;
    public $flagUserChangedPrice;
    public $max_discount;
                
    public function __construct($section, $key, $user)
    {
        parent::__construct($section);
      
        // Определяем базовую валюту
        if (isRequest('pricemoney')) // Если пользователь сам задал альтернативную валюту как базовую 
            $_SESSION['pricemoney'] = getRequest('pricemoney');
        if (!empty($_SESSION['pricemoney'])) 
            $this->basecurr = $_SESSION['pricemoney'];
        else {
            $this->basecurr = se_baseCurrency();
            if (empty($this->basecurr)) 
                $this->basecurr ='RUR';
        }        
        
        $this->user = $user;
        $this->key = $key;
       
        // Получаем данные из сессии
        if (isset($_SESSION['shopcart'][$key]['id'])) 
            $this->price_id = $_SESSION['shopcart'][$key]['id'];
        elseif (isset($_COOKIE['shopcart'][$key]['id']))
            $this->price_id = $_COOKIE['shopcart'][$key]['id'];
            
        if (isset($_SESSION['shopcart'][$key]['count'])) 
            $this->count = $_SESSION['shopcart'][$key]['count'];
        elseif (isset($_COOKIE['shopcart'][$key]['count'])) 
            $this->count = $_COOKIE['shopcart'][$key]['count'];             
        if ($this->count < 0)
            $this->count = 0;
            
        // ------ Опрашиваем таблицу "shop_price" ------
        $this->tbl = new seTable("shop_price");
        $this->qr_priceline = $this->tbl->find($this->price_id); 
        
        $this->article = $this->qr_priceline['article'];
        $this->name = $this->qr_priceline['name'];
        $this->weight = $this->qr_priceline['weight'] / 1000;  // В килограммах
        $this->presence = $this->qr_priceline['presence'];
        $this->measure = $this->qr_priceline['measure']; 
        $this->curr = $this->qr_priceline['curr'];
        $this->flagUnsold = ($this->qr_priceline['unsold'] == 'Y');
        
        if (isset($_SESSION['shopcart'][$key]['params'])) 
            $this->params = $_SESSION['shopcart'][$key]['params'];
        elseif (isset($_COOKIE['shopcart'][$key]['params'])) 
            $this->params = $_COOKIE['shopcart'][$key]['params'];
            
        // ------ Опрашиваем плагин "параметры" ------    
        $this->plugin_params = new plugin_shopParams($this->params);
        $this->paramstr = $this->plugin_params->getParamsName();
        
        // ------ Опрашиваем плагин "общая сумма" ------     
        $plugin_amount = new plugin_shopAmount($this->price_id, '', $this->user->priceType, 
                                                     $this->count, $this->params, $this->basecurr);

        $this->original_price = $plugin_amount->getPrice(false);  
        $this->presence_count = $plugin_amount->getPresenceCount();  
                                                 
        $this->count = $plugin_amount->getActualCount();    
                  
                                             
        unset ($plugin_amount);
        
        if (isset($_SESSION['shopcart'][$key]['price']))    
            $this->price = $_SESSION['shopcart'][$key]['price'];
        elseif (isset($_COOKIE['shopcart'][$key]['price']))
            $this->price = $_COOKIE['shopcart'][$key]['price']; 
        
        
        if (isset($_SESSION['shopcart'][$key]['flagUserChangedPrice'])) 
            $this->flagUserChangedPrice = $_SESSION['shopcart'][$key]['flagUserChangedPrice'];
        elseif (isset($_COOKIE['shopcart'][$key]['flagUserChangedPrice'])) 
            $this->flagUserChangedPrice = $_COOKIE['shopcart'][$key]['flagUserChangedPrice'];
          
        $this->max_discount = $this->qr_priceline['max_discount'];
    }
    
    
    public function acceptUserPrice($user_price) // Назначает пользовательскую цену
    {
        // Проверяет ограничение минимальной цены, и, если слишком низкая, увеличивает до установленного минимума
        $minimal_price = $this->original_price - ($this->original_price * $this->max_discount) / 100;
                  
        if ($user_price >= $minimal_price)
            $this->price = $user_price;
        else
            $this->price = $minimal_price;      
    }      
    
    
    public function recalculate() 
    {   
        if ($this->count < 0)
            $this->count = 0;   
            
        $plugin_amount = new plugin_shopAmount($this->price_id, '', $this->user->priceType, 
                                                     $this->count, $this->params, $this->basecurr);
        
        $this->count = $plugin_amount->getActualCount();
                                                                
        if ($this->flagUnsold) // == ЗАЛЕЖАВШИЙСЯ ТОВАР ==
        {
            if ($this->flagUserChangedPrice)
            {                               // Пользователь изменил цену
                $this->discount = 0.00;
                $this->sum = $this->price * $this->count;       
            }
            else
            {                               // Пользователь не менял цену
                $this->price = $plugin_amount->getPrice(false);
                $this->discount = 0.00;
                $this->sum = $plugin_amount->getAmount(false);
            }           
        }
        else                             // == ОБЫЧНЫЙ НОРМАЛЬНЫЙ ТОВАР ==
        {
            $this->price = $plugin_amount->getPrice(false);
            $this->discount = $plugin_amount->getDiscount(); 
            $this->sum = $plugin_amount->getAmount();         
        }        
        
        unset($plugin_amount);
    }              

    public function save() { 
        $_SESSION['shopcart'][$this->key]['id'] = $this->price_id;
        $_SESSION['shopcart'][$this->key]['params'] = $this->params;
        $_SESSION['shopcart'][$this->key]['price'] = $this->price;                                          
        $_SESSION['shopcart'][$this->key]['count'] = $this->count;
        $_SESSION['shopcart'][$this->key]['flagUserChangedPrice'] = $this->flagUserChangedPrice;
    }
        
    public function delete() {
        unset($_SESSION['shopcart'][$this->key]); 
    }

}
              

?>