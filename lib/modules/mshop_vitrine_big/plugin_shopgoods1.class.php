<?php

/**
 * @author Ponomarev Dmitry
 * @copyright 2012
 */

class plugin_shopgoods1 {
    private $id;
	private $lang;                          //язык проекта
    public $footertext = '';                //текст подвала
    public $pricemoney;                     //текущая валюта сайта
    private $search = '';                   //параметр поиска
    private $sortval = '';                  //напрвление и поле сортировки
    private $makeimage;                     //вызов плагина plugin_ShopImages

    /** Конструктор
    *   @string  page           текущая страница
    *   @string  group_name     код группы, переданное принудительно
    *   @string  base_group     стартовая страница
    *   @boolean oldformat      старый или новый формат
    **/
    
    
    public function __construct($page='home', $group_name='', $base_group='', $oldformat=false) {
		$this->lang = se_getLang();
		$this->current_page = $page;
		$this->GroupId = $this->getRequestGroup($oldformat, $group_name);
		$this->baseGroupId = $this->getGroupId($base_group); 
		if (!$this->GroupId) $this->GroupId = $this->baseGroupId;
		$this->setRequest();                                                     //запросы сортировки
		
    }   
     
	/** запросы
	*   @return нечего не возвращает
	**/                                                    
	private function setRequest(){
           //запрос на сортировку
           if (isRequest('orderby')) {
                $this->sortval = getRequest('orderby');            
           } elseif (isRequest('sortOrderby')) {
                $this->sortval = getRequest('sortOrderby').getRequest('sortDir');
           }
           if ($this->sortval) {
              $_SESSION['SHOP_VITRINE']['sortval'] = $this->sortval;
           } else {
              $this->sortval = $_SESSION['SHOP_VITRINE']['sortval'];
           }
           //определить текущую валюту сайта                                       
           if (isRequest('pricemoney')) {
                $pricemoney = $_SESSION['pricemoney'] = getRequest('pricemoney');
                $this->pricemoney = $pricemoney;
           } elseif (empty($_SESSION['pricemoney'])) {
                $_SESSION['pricemoney'] = $basecurr = se_baseCurrency();
                $pricemoney = $basecurr;
                $this->pricemoney = $pricemoney;
           } else {
                $pricemoney = $_SESSION['pricemoney'];
                $this->pricemoney = $pricemoney;
           }
	}

    /** Получить поле сортировки и порядок сортировки
    *   @return string 
    **/
	public function getSortVal(){
       return $this->sortval;
	}

    
    /** получить массив для поиска
	*   @array  search_all     массив параметров
    *   ничего не возвращает
	**/
	public function setSearch($search_all = array()){
	   $this->search = $search_all;
	}
	
    /** Возвращает список товаров и постраничная навигация
    *   @array option             массив входных параметров
    *   @array goods_name         список товаров, которые надо отобразить
	*   @return array()     возвращает список товаров и постраничную навигацию
    **/    
    public function getGoods($option=array(), $goods_name=''){		
        $price = new seShopPrice();                  
        $lid = '';
        if ($option['special']) { // Отображать только спецпредложения
           $lid = 'sl.id lid,'; 
        }
        $selectfields = 'sp.*,'; 
        $price->select($selectfields.$lid.'if ((presence_count=-1 OR presence_count IS NULL), 1000000000, presence_count) presence_count_adopt,
        (SELECT name FROM shop_group WHERE id=sp.id_group) AS group_name');
        
        // отображать ли подгруппы?
        if ($option['is_under_group'] && $this->GroupId) {                            
            $goups = join(',', $this->getTreeShopGroup($this->GroupId, 'true'));
        } else {                                                                      
            $goups = $this->GroupId;
        }
        
        
        $where = "sp.id_group IN (SELECT id FROM shop_group WHERE lang='{$this->lang}' AND active = 'Y'";
        if ($this->GroupId){
            $where .= " AND id IN (?)";
        } 
        $where .= ")"; 
  
        if ($this->GroupId){
            $where .= " OR sp.id IN (SELECT price_id FROM shop_group_price WHERE group_id IN (?))";
        }  
       
        $price->where('('.$where.')', $goups);
       
        if ($option['unsold']) {                    // Залежавшийся товар
            $price->andWhere("sp.unsold = 'Y'");
        }  
       
        if ($option['special']) {
            $price->innerjoin("shop_leader sl", "sp.id = sl.id_price");
        } else {            
            $funct = $this->getSearch();            //возвращается sql запрос ввиде строки
            if ($funct) { 
                $price->andwhere($funct);
            }
            if(!isRequest('clearsearch') && isRequest('shop_search')){ 
                $price->andwhere("sp.lang = '?'", $this->lang);
            }
           // echo $price->getSQL();
        }  

        $price->andWhere("sp.enabled = 'Y'");
        
        if(!empty($goods_name)){
            //$other = "'".implode("','",$goods_name)."'";
            $price->andWhere("sp.id IN ($goods_name)");
        }
        
        //сортировка по полям
        if (!empty($this->sortval)){
            $sortval = $this->sortval;
            list($field,$asc) = $this->getSortByField();
        } else {
            list($field,$asc) = $option['sort'];              //первичная сортировка
            switch ($field) {
                case 'name': { $tsort = 'n'; } break;
                case 'id': { $tsort = 'r';} break;
                case 'price': { $tsort = 'p';} break;
                case 'R': { $tsort = 'a';} break;
            }
            switch ($asc) {
                case '0': { $tasc = 'a'; } break;
                case '1': { $tasc = 'd';} break;
            }
            $this->sortval =  $tsort.$tasc;
        }
        if($option['sort'][0]!='R'){ 
            $price->orderby($field, $asc);            
            $price->groupby("sp.id");
        }

        if (!$option['limit']) {
            $option['limit'] = 30;
        }
        
        $SE_NAVIGATOR = $price->pageNavigator($option['limit']);
        $pricelist = $price->getList();
        $goodscount = count($pricelist);

        // сортировка случайным образом
        if (($option['sort'][0]=='R')&&($option['special'])) { 
            if (!empty($pricelist)) {
                $dataarr = array();
                $rand_keys = range(0, $goodscount - 1);
                shuffle($rand_keys);
                for ($i = 0; $i < $goodscount; $i++) {
                    $dataarr[$i] = $pricelist[intval($rand_keys[$i])]; 
                }        
                unset($pricelist);
                $pricelist = $dataarr;
            }
        } 
//echo $price->getSql();
        return  array($pricelist,$SE_NAVIGATOR);
	}

    /** 
    *   @boolean old_format             какой формат используется(новый или старый)
    *   @string  group_name             код группы
    *   @return                      
    **/
    public function getRequestGroup($old_format=false, $group_name = ''){
        $shopcatgr = '';
        if (isRequest('shopcatgr')) {
            $shopcatgr = getRequest('shopcatgr', 1);
            if ($old_format) {
                if ($shopcatgr) {
                    $tbl = new seTable("shop_group", "sg");
                    $tbl->select('code_gr, footertext');
                    $tbl->find($shopcatgr);
                    $catgr = $tbl->code_gr;
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: '.seMultiDir().'/'.$this->current_page.'/cat/'.$catgr.'/');
                    exit;
                } else {
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: '.seMultiDir() . "/".$this->current_page."/");
                    exit;
                }
            }
        } else {
            if (isRequest('cat'))
                $group_name = getRequest('cat', 3); 
            if ($group_name)
                $shopcatgr = $this->getGroupId($group_name, true);
        }     
        return $shopcatgr;
    }
   
    /** Получить id стартовой группы 
    *   @string  group_name         код стартовой группы
    *   @boolean ifFooter           надо ли получить текст подвала
    *   @return int                 код группы
    **/
    public function getGroupId($group_name, $ifFooter = false){
        if ($group_name) {
            $tbl = new seTable('shop_group', 'sg');
            if ($ifFooter)
                $tbl->select('id, footertext');
            else 
                $tbl->select('id');
            $tbl->where("sg.code_gr = '?'", $group_name);
            $tbl->fetchOne();
            if ($ifFooter) 
                $this->footertext = $tbl->footertext;        
            return $tbl->id;
        }
    }
   
    /** Выбирает id всех вложенных подгрупп группы $shopcatgr
    *   @int     shopcatgr          значение id группы, из которой выбираем подгруппы
    *   @boolean true               автоматически создается
    *   @return string              список групп
    **/  
    public function getTreeShopGroup($shopcatgr,$true){
        $list = array();
        if($true=='true'){
            $list[] = $shopcatgr;        
        }
        $shgroup = new seShopGroup();
        $shgroup->select('id');
        $shgroup->where('upid=?', $shopcatgr);
        $shgroup->andwhere("active='Y'");
        $glist = $shgroup->getList();    
        foreach($glist as $item){
            if ($item['id'])
                $list = array_merge($list, $this->getTreeShopGroup($item['id'],'false'));
                                  
            $list[] = $item['id'];     
        // Перебираем деревья до встречи с группой
        }             //  echo  $shgroup->getSql().'---4--';
        return $list;
    }
    /**
        @return string      возвращается строка поиска
    **/
    
    private function getSearch(){
        if(empty($this->search))return;
		$sql = '';				   
		if(date('w')!='0'){
			$week = date('w');
		}else{
			$week = '7';
		}
		$all_search = $this->search;    // $_SESSION['SHOP_VITRINE']
		$lng = $this->lang;
	
	$thiscurr = se_getMoney();
    $kcurr = se_MoneyConvert(1, $thiscurr,$basecurr);
        			 
            foreach($all_search as $name=>$val){
                if(isset($val) && $val!="" && $val!=$name && strpos($name, "param_")===false && 
                strpos($name,"start_group")===false && strpos($name,"group_for")===false){
                    if (!empty($sql)) $sql .= ' AND ';  
                    if($name=="flag_new" || $name=="flag_hit" && $name!= "discount"){
                        if($val=="yes"){
                            $sql .= "(sp.{$name} = 'Y')";
                        }elseif($val=="no"){
                            $sql .= "(sp.{$name} = 'N')";
                        }elseif($val == "none"){
                            $sql .= "";
                        }
                    }elseif($name == "discount"){
                        if($val == "yes"){
                        $sql .= "(sp.id IN(SELECT id_price FROM `shop_discount` `sd` WHERE IF((if_date1 IS NULL AND 
                        date2 >= CURRENT_TIMESTAMP())AND(MID(week,{$week},1)='1')AND(sp.discount='Y'),
                        (sp.id = sd.id_price),'') OR IF((if_date2 IS NULL AND 
                        date1 <= CURRENT_TIMESTAMP())AND(MID(week,{$week},1)='1')AND(sp.discount='Y'),
                        (sp.id = sd.id_price),'') OR 
                        IF((((if_date2 AND date2 >= CURRENT_TIMESTAMP())AND(if_date1 AND date1 <= CURRENT_TIMESTAMP()))AND
                        (MID(week,{$week},1)='1')AND(sp.discount='Y')),(sp.id = sd.id_price),(IF(((sp.discount='Y') AND 
                        (MID(week,{$week},1)='1') AND(if_date2 IS NULL AND if_date1 IS NULL)),(sp.id = sd.id_price),'')))) OR 
                        (sp.id IN(SELECT id_price FROM `shop_special` WHERE expires_date >= CURRENT_TIMESTAMP()
                        AND sp.special_price = 'Y')))";
                        $sql .= " AND (sp.lang = '{$lng}')";  
                        }elseif($val == "no"){
                           // $sql .= "(IF((sp.id IN(SELECT id_price FROM `shop_discount` WHERE id_price = sp.id)),'', sp.id))";
                        $sql .= "(IF(sp.id IN(SELECT id_price FROM `shop_discount` WHERE IF((if_date1 IS NULL AND 
                        date2 >= CURRENT_TIMESTAMP())AND(MID(week,{$week},1)='1')AND(sp.discount='Y'),
                        sp.id = id_price, '') OR IF((if_date2 IS NULL AND 
                        date1 <= CURRENT_TIMESTAMP())AND(MID(week,{$week},1)='1')AND(sp.discount='Y'),
                        sp.id = id_price, '')
                        OR IF((((if_date2 AND date2 >= CURRENT_TIMESTAMP())AND
                        (if_date1 AND date1 <= CURRENT_TIMESTAMP()))AND (MID(week,{$week},1)='1')AND(sp.discount='Y')),
                        sp.id = id_price,'')),'', sp.id))";
                           // $sql .= " AND (sp.lang = '{$lng}')";
                        }elseif($val == "none"){
                            $sql .= "";
                        }            
                    }elseif(strpos($name, '_from')!==false){
                        $name = str_replace("_from", "", $name);
                        if($name == "presence_count"){
                            $sql .= "(sp.{$name} >= '{$val}' AND sp.{$name} <> -1)";
                        }else if($name=="price" || $name=="price_opt" || $name=="price_opt_corp"){
                            $curr_val_from = round($val*$kcurr,2);
                            if($thiscurr == "RUR"){
                                $curr_this = "USD";
                                $kcurr_val = se_MoneyConvert(1, $curr_this,$basecurr);
                                $curr_val_fr = round($val / $kcurr_val,2);
                                $curr_this_euro = "EUR";
                                $kcurr_val_euro = se_MoneyConvert(1, $curr_this_euro,$basecurr);
                                $curr_val_euro = round($val / $kcurr_val_euro,2);
                                $sql .= "((sp.{$name} >= '{$curr_val_from}' AND sp.curr = 'RUR') OR (sp.{$name} >= '{$curr_val_fr}' AND sp.curr = 'USD')
                                        OR (sp.{$name} >= '{$curr_val_euro}' AND sp.curr = 'EUR'))";
                            }elseif($thiscurr == "USD"){
                                $curr_this_euro = "EUR";
                                $kcurr_val_euro = se_MoneyConvert(1, $curr_this_euro,$basecurr);
                                $curr_val_euro = round($curr_val_from / $kcurr_val_euro,2);
                                $sql .= "((sp.{$name} >= '{$curr_val_from}' AND sp.curr = 'RUR') OR (sp.{$name} >= '{$val}' AND sp.curr = 'USD') OR 
                                        (sp.{$name} >= '{$curr_val_euro}' AND sp.curr = 'EUR'))";
                            }elseif($thiscurr == "EUR"){
                                $curr_this = "USD";
                                $kcurr_val = se_MoneyConvert(1, $curr_this,$basecurr);
                                $curr_val_fr = round($curr_val_from / $kcurr_val,2);
                                $sql .= "((sp.{$name} >= '{$curr_val_from}' AND sp.curr = 'RUR') OR (sp.{$name} >= '{$curr_val_fr}' AND sp.curr = 'USD') OR 
                                        (sp.{$name} >= '{$val}' AND sp.curr = 'EUR'))";
                            }  
                        }else{
                            $sql .= "(sp.{$name} >= '{$val}')";
                        }
                    }elseif(strpos($name, '_to')!==false){
                        $name = str_replace("_to", "", $name);
                        if($name == "presence_count"){
                            $sql .= "(sp.{$name} <= '{$val}' AND sp.{$name} <> -1 AND sp.{$name} <> 0)";
                        }else if($name=="price" || $name=="price_opt" || $name=="price_opt_corp"){
                            $curr_val_to = round($val*$kcurr,2);
                            if($thiscurr == "RUR"){
                                $curr_this = "USD";
                                $kcurr_val = se_MoneyConvert(1, $curr_this,$basecurr);
                                $curr_val_tdiv = round($val / $kcurr_val,2);
                                $curr_this_euro = "EUR";
                                $kcurr_val_euro = se_MoneyConvert(1, $curr_this_euro,$basecurr);
                                $curr_val_euro = round($val / $kcurr_val_euro,2);
                                $sql .= "((sp.{$name} <= '{$curr_val_to}' AND sp.curr = 'RUR' AND sp.{$name} > 0) OR (sp.{$name} <= '{$curr_val_tdiv}' AND sp.curr = 'USD' AND sp.{$name} > 0)
                                        OR (sp.{$name} <= '{$curr_val_euro}' AND sp.curr = 'EUR' AND sp.{$name} > 0))";
                            } elseif($thiscurr == "USD"){
                                $curr_this_euro = "EUR";
                                $kcurr_val_euro = se_MoneyConvert(1, $curr_this_euro,$basecurr);
                                $curr_val_euro = round($curr_val_to / $kcurr_val_euro,2);
                                $sql .= "((sp.{$name} <= '{$curr_val_to}' AND sp.curr = 'RUR' AND sp.{$name} > 0) OR (sp.{$name} <= '{$val}' AND sp.curr = 'USD' AND sp.{$name} > 0) OR 
                                        (sp.{$name} <= '{$curr_val_euro}' AND sp.curr = 'EUR' AND sp.{$name} > 0))";
                            }elseif($thiscurr == "EUR"){
                                $curr_this = "USD";
                                $kcurr_val = se_MoneyConvert(1, $curr_this,$basecurr);
                                $curr_val_tdiv = round($curr_val_to / $kcurr_val,2);
                                $sql .= "((sp.{$name} <= '{$curr_val_to}' AND sp.curr = 'RUR' AND sp.{$name} > 0) OR (sp.{$name} <= '{$curr_val_tdiv}' AND sp.curr = 'USD' AND sp.{$name} > 0) OR 
                                        (sp.{$name} <= '{$val}' AND sp.curr = 'EUR' AND sp.{$name} > 0))";
                            }   
                        }else{
                            $sql .= "(sp.{$name} <= '{$val}' AND sp.{$name} > 0)";
                        }
                    }elseif($name == "manufacturer"){
                        $sql .= "(sp.{$name} IN('{$val}'))";
                    }elseif($name == "measure"){
                        $sql .= "(sp.{$name} IN('{$val}'))";
                    }elseif($name == "search_live_group"){
                        $sql .= "(sp.name LIKE '%{$val}%')";
                    }elseif($name == "name_livesearch"){
                        $sql .= "(sp.name LIKE '%{$val}%')";
                    }else{ 
                        $sql .= "(sp.{$name} LIKE '%{$val}%')";
                    }          
                }          
            }   
		
            foreach($all_search as $paramn=>$paramv){
                if(strpos($paramn,"param_")!==false){
                    $paramn = str_replace("param_","",$paramn);
                    if($paramv != ""){
                        if (!empty($sql)) $sql .= ' AND '; 
                        $sql .= "(sp.id IN(SELECT price_id FROM `shop_price_param` WHERE value IN('{$paramv}') AND param_id IN('{$paramn}')))";
                    }
                }
            }  
            
            foreach($all_search as $groupn=>$groupv){
                if($groupn == "start_group"){
                    $start_group = $groupv;    
                }
                if($groupn == "group_for"){
                    if(isset($groupn) && $groupn != "" && empty($start_group) && $groupv!=""){
                    if (!empty($sql)) $sql .= ' AND ';
                        $sql .= "(sp.id_group IN({$groupv}))";
                    } 
                }
                if($groupn == "start_group"){
                    if(isset($start_group)){
                        if(isset($groupn) && $groupn != ""){
                            if (!empty($sql)) $sql .= ' AND ';
                            $sql .= "(sp.id_group IN({$groupv}))";
                        }else{
                            if (!empty($sql)) $sql .= ' AND ';
                            $sql .= "(sp.id_group IN({$start_group}))";
                        }
                    } 
                }
            }  
    
       // echo $sql;
    
	   return $sql;	
    } 
    /** Сортировка по полям в таблице витрины
    *   @return array           возвращается поле сортировки и направление сортировки
    **/
    public function getSortByField(){
        $sortval = $this->sortval;
        if($sortval=='R'){
            return array('name',0);
        }
        if(($sortval=='name')||($sortval=='id')||($sortval=='price')){
            if(substr($sortval, 1, 1) == 'a'){
                return array($sortval,0);
            } else {
                return array($sortval,1);
            }
        }
        $asc = (substr($sortval, 1, 1) == 'a') ? '0' : '1';
        switch ($sortval) {
            case 'ga': { $field = 'group_name'; } break;
            case 'gd': { $field = 'group_name';} break;
            case 'aa': { $field = 'article';} break;
            case 'ad': { $field = 'article';} break;
            case 'na': { $field = 'name';} break;
            case 'nd': { $field = 'name';} break;
            case 'ma': { $field = 'manufacturer';} break;
            case 'md': { $field = 'manufacturer';} break;
            case 'pa': { $field = 'price';} break;
            case 'pd': { $field = 'price';} break;
            case 'ca': { $field = 'presence_count_adopt';} break;
            case 'cd': { $field = 'presence_count_adopt';} break;
            case 'ra': { $field = 'created_at';} break;
            case 'rd': { $field = 'created_at';} break;
        } 
        return array($field,$asc);   
    }
    
    /** Получить все комментарии
    *   @viewgoods int          id товара
    *   @return array           комментарии
    **/
    public function getGoodsComment($viewgoods=''){
        if($viewgoods=='') return;
        $comms = new seTable('shop_comm');
        $comms->where('id_price=?',  $viewgoods);
        $comms->orderby('id', 1);
        $commlist = $comms->getList();
        $flstyle = false;
        $a = 0;
        foreach ($commlist as $comm) {         
            $flstyle = !$flstyle;
            $commlist[$a]['style'] = ($flstyle) ? 'tableRowOdd' : 'tableRowEven';
            $commlist[$a]['date'] = date('d.m.Y', strtotime($comm['date']));
            list($comments, $response) = explode('<%comment%>', $comm['commentary']);
            if (empty($response))
                $response = $comm['response'];
            unset($comm['commentary']);
    
            $commlist[$a]['comment'] = str_replace("\r\n", '<br>', $comments);
            if (!empty($response)) {
                $commlist[$a]['adminnote'] = str_replace("\r\n", '<br>', $response);
            }
            $a++;
        }          
        return $commlist;
    }

    /** сохранить отзыв(комметарии)
    *   @viewgoods  int             id товара
    *   @comm_note  string          текст сообщения
    *   @admin      string          имя администратора
    **/
    public function saveGoodsComment($viewgoods='', $comm_note, $admin){           
        if($viewgoods=='') return;
        $comments = new seTable('shop_comm');
        $comments->id_price = $viewgoods;
        if (seUserGroup() < 3) {
            $person = new seTable('person');
            $person->select('email, last_name, first_name, sec_name');
            $person->find(seUserId());
            $comments->name = trim($person->last_name . ' ' . $person->first_name . ' ' . $person->sec_name);
            $comments->email = $person->email;
            unset($person);
        } else {
            $comments->name = "$admin";
            $comments->email = '';
        }
        $comments->commentary = $comm_note;
        $comments->date = date('Y-m-d', time());    
        return $comments->save();
    }
        
    /** получить информацию о товаре
    *   @viewgoods  string              id товара
    *   @return  array                  информацию о товаре
    **/
    public function showGoodsDescription($viewgoods=''){
        if($viewgoods=='') return;
        $show = new seShopPrice();
        $show->select();
        $show->Where("id = '?'", $viewgoods);
        $goods = $show->fetchOne();
        //проверка на существование и на статус активный товара
        if(($goods['enabled'] == 'N') || (!$goods['enabled'])){
            Header('404 Not Found', true, 404);
            exit();
        }
        if (trim($goods['title'])){
            $goods['title'] = htmlspecialchars($goods['title']);
        } else {
           $goods['title'] = htmlspecialchars($goods['name']);
        }         
        if (trim($goods['keywords'])){
            $goods['keywords'] = htmlspecialchars($goods['keywords']);   
        } else {                                           
            $goods['keywords'] = htmlspecialchars($goods['name']);
        }
        if (trim($goods['description'])){
            $goods['description'] = htmlspecialchars($goods['description']);   
        } else {                                           
            $goods['description'] = htmlspecialchars($goods['note']);
        }
        if (empty($goods['img_alt'])){
            $goods['img_alt'] = htmlspecialchars($goods['name']);
        } else {
            $goods['img_alt'] = htmlspecialchars($goods['img_alt']);
        }
        return $goods;
    }
    
    /** Подсчет кол-ва посещении группы
    *   @id_visit_group string              id группы
    *   @return                             сохранение данных БД
    **/
    public function countVizit($id_visit_group=''){
        if($id_visit_group=='') return;
        $visits = new seShopGroup();
        $visits->update('visits', '`visits`+1');
        $visits->where('id=?', $id_visit_group);
        return $visits->save();    
    }
    
    /** Подсчет кол-ва просмотра товара
    *   @id_goods   string                  id товара
    *   @return                             сохранение данных БД
    **/
    public function countGoodsVizit($id_goods=''){
        if($id_goods=='') return;
        if(!se_db_is_field('shop_price', 'vizits')){
            se_db_add_field('shop_price', 'vizits', 'INT( 10 ) UNSIGNED NOT NULL AFTER `unsold`');        
        }
		$this->setNowLooks($id_goods);
		$this->setUserLooks($id_goods);		
        $visits = new seTable('shop_price');
        $visits->update('vizits', '`vizits`+1');
        $visits->where('id=?', $id_goods);
        return $visits->save();
    }

    /** Восстановление дерева доп параметров
    *   @price_id int                       id товара
    *   @return array                       
    **/
    public function getPreviousParamsState($price_id='') {               
        if($price_id=='') return;
        // Определяем все бывшие состояния параметров товара на основе последнего сохраненного параметра
        // и записывает их в сессию
        $nlist = $_SESSION['SHOP_VITRINE']['selected'][$price_id];
        if (!empty($nlist)) { 
            // Обнулим значение количества параметров
            $i = 1;  
            foreach($nlist as $n){  
                $_SESSION['SHOP_VITRINE']['paramcount'][$price_id][$i] = 0;
                // Восстановим состояние всех параметров данного товара
                $arr = array();

                $spp = new seTable('shop_price_param');
                $j = 100;
                while ($n) {
                    $arr[$j++] = $n;
                    $spp->select('parent_id');
                    $spp->Find($n);
                    $n = $spp->parent_id;
                } 
                // Развернем массив
                $arr = array_reverse($arr);
                unset($spp);
                $i++;
            }     
        }
        return $arr;
    }

    /** Увеличение кол-ва голосов и сохранение голоса за товар
    *   @viewgoods int                       id товара
    *   @vote string                         кол-во голосов
    *   @return int                          возвращает кол-во голосов за товар
    **/
    public function GoodsVotes($viewgoods='',$vote='') {               
        if($viewgoods=='') return;
        $prc = new seTable('shop_price');
        $prc->find($viewgoods);
        if($vote=='') return $prc->votes;   //получить голос
        $_SESSION['VOTED'][$viewgoods] = 1; // Признак того, что пользователь уже проголосовал
        $votes = $prc->votes + $vote;
        $prc->update('votes', "'{$votes}'");
        $prc->where('id=?', $viewgoods);
        $prc->save();
        return $votes;
    }  
    
    /** Выбор похожих и сопутствующих товаров
    *   @option array                           начальные параметры
    *   @viewgoods int                          кол-во голосов
    *   @types string                           название таблицы shop_sameprice или shop_accomp
    *   @return array                           возвращает массив товаров
    **/
        public function sameGoods($option, $viewgoods, $types){
            $same = new seTable('shop_price', 'sp');
            $same->select("*");
            $same->where("`id` in (SELECT id_acc from $types WHERE `id_price`='$viewgoods')");
            if($types=='shop_sameprice'){
                $same->orwhere("`id` in (SELECT id_price from $types WHERE `id_acc`='$viewgoods')");
            }
            $samegoods = $same->getList();    
            $rez = '';
            foreach($samegoods as $item){
                if($rez==''){
                    $rez .= $item['id'];
                } else {
                    $rez .= ",".$item['id'];
                }
            }                                        
            if($rez=='') return;
            $e = array();      
            $e = $this->getGoods($option, $rez);
            return $e;
        }	

    /** Отображение дополнительных фото
    *   @$price_fields  array                           информация товара из БД
    *   @return array                                   возвращает массив товаров
    **/
    public function getGalleryImage($price_fields=''){
        $goodsimg = new seTable('shop_img', 'si');
        $goodsimg->select('si.id, si.id_price, si.picture, si.title');
        $goodsimg->where("si.id_price IN (SELECT id FROM shop_price WHERE id='{$price_fields}')");
        $imglist = $goodsimg->getList(); 
//        var_dump($imglist);
        return $imglist;
    }

    public function viewgalleryImages($price_id, $size_prev, $size_mid, $size_full, $res= 's', $watermark = ''){
        $imglist = $this->getGalleryImage($price_fields='');
        $i = 0;                
        if(empty($imglist)) return $i;
        foreach($imglist as $imgs){
            if($imgs['picture']!=''){
                $i++;
                $images[$i]['image'] = $this->getGoodsImage($imgs['picture'], $size, $res, $watermark);  
                $images[$i]['title'] = $imgs['title'];
                $images[$i]['image_prev'] = $this->getGoodsImage($imgs['picture'], $size_prev, $res);
                $images[$i]['image_mid'] = $this->getGoodsImage($imgs['picture'], $size_mid, $res, $watermark);
            }
        }  
        return $images;
    }



    public function getGoodsImage($imagename='', $size = '', $res = 's', $watermark = ''){
        $im = new plugin_ShopImages();
        $imagename = $im->getPictFromImage($imagename, $size, $res, $watermark);
        $nofoto = end(explode('/', $imagename));  
        if($nofoto=='no_foto.gif')
            $nofoto = 1;
        else 
            $nofoto = 0;
        return array($imagename, $nofoto);     
    }
    
    /** Проверка есть ли аналогичные товары у данного товара
    *   @option     array                   начальные параметры 
    *   @viewgoods  string(int)             id товара
    *   return      boolean
    **/
    public function isSetGoodsAnalog($option, $viewgoods=''){
        if($viewgoods=='') return;
        $analog = new seTable('shop_sameprice');
        $analog->select('id, id_price');
        $analog->where("`id_price`='$viewgoods'");
        $good = $analog->getList();
        $rez = '';
        foreach($good as $item){
            if($rez==''){
                $rez .= $item['id'];
            } else {
                $rez .= ",".$item['id'];
            }
        }
        $isAnalog = false;                                       
        if($rez=='') return $isAnalog;
        $e = array();      
        $e = $this->getGoods($option, $rez);
        if(($e!='')&&(!empty($e))) $isAnalog = true;
        return $isAnalog;
    }
	
	/**
	 * Добавление/обновление таблицы 'Сейчас смотрят'
	 *
	 * @param integer $id_price	 id товара
	 * @param integer $period    время(сек) в течение которого товар считается просматриваемым
	 */
	public function setNowLooks($id_price, $period = 600){
        if (!$id_price) return;
        
        $sql = "CREATE TABLE IF NOT EXISTS `shop_nowlooks` (
            `id` int(10) unsigned NOT NULL,
            `time_expire` int(11) NOT NULL,
            `count_looks` int(10) unsigned default NULL,
            `updated_at` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
            `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        se_db_query($sql);
        
        $time = time();
        
        $tbl = new seTable('shop_nowlooks');
        $tbl->where("time_expire < $time");
        $tbl->deletelist();
        
        if($tbl->find($id_price)){
            $tbl->time_expire = $time + $period;
            $tbl->count_looks++;
            $tbl->save(); 
        }
        else{
            $tbl->insert();
            $tbl->id = $id_price;
            $tbl->time_expire = $time + $period;
            $tbl->count_looks = 1;
            $tbl->save();
        }
    }
    
	/**
	 * Установить товар который просмотрел пользователь ("Вы смотрели")
	 *
	 * @param integer $id_price	           id товара
	 * @param unix time integer $expire    время до которого товар остается просмотренным пользователем
	 */
    public function setUserLooks($id_price, $expire = '-1'){
        if (!$id_price) return;
        if ($expire == -1){
            $expire = time() + 604800;// неделя по умолчанию
        }
        $count = $_COOKIE["look_goods"][$id_price] + 1;
        setcookie("look_goods[$id_price]", $count, $expire);
    }	
}
?>