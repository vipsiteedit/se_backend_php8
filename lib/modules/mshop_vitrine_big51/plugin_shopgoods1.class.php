<?php

/**
 * @author Ponomarev Dmitry
 * @copyright 2012
 */
class plugin_shopgoods {
    private $id;
	private $lang;                          //язык проекта
    public $footertext = '';                //текст подвала  
    private $search = '';                   //параметр поиска
    private $sortval = '';                  //напрвление и поле сортировки
    private $makeimage;                     //вызов плагина plugin_ShopImages
    private $current_page;
    private $GroupId;
    private $baseGroupId = 0;
    private $find_group = true;

    /** Конструктор
    *   @string  page           текущая страница
    *   @string  group_name     код группы, переданное принудительно
    *   @string  base_group     стартовая страница
    *   @boolean oldformat      старый или новый формат
    **/
    public function __construct($page=false, $group_name='', $base_group='', $oldformat=false) {
	$base_group = strval($base_group);
		$this->lang = se_getLang();
		$this->current_page = ($page!==false) ? $page : get('page',0);
		$this->GroupId = $this->getRequestGroup($oldformat, $group_name);
		if ($base_group != '0' && $base_group) {
		    $this->baseGroupId = $this->getGroupId($base_group);
		    if (!$this->baseGroupId) $this->find_group = false;
		} 
		if (!$this->GroupId) $this->GroupId = $this->baseGroupId;
		$this->setRequest();                                                     //запросы сортировки
    }   
     
	/** запросы
	*   @return нечего не возвращает
	**/ 
	private function setRequest(){
        //запрос на сортировку
        if (get('orderby')) {
            $this->sortval = get('orderby',0);            
        } elseif (get('sortOrderby')) {
                $this->sortval = get('sortOrderby',0).get('sortDir',0);
        }
        if ($this->sortval) {
              $_SESSION['SHOP_VITRINE']['sortval'] = $this->sortval;
        } else {
            $this->sortval = (isset($_SESSION['SHOP_VITRINE']['sortval']))
			  	? $_SESSION['SHOP_VITRINE']['sortval']
				: null;
        }
	}

    /** Получить поле сортировки и порядок сортировки
    *   @return string 
    **/
	public function getSortVal(){
       return $this->sortval;
	}
    
    //получить характеристики товара
    public function getGoodsFeatures($id_goods) 
    {
        $price_feature_list = array();
        if (!empty($id_goods)) {
            $shop_feature = new seTable('shop_feature', 'sf');
            $shop_feature->select("
                sf.id AS fid,
                sf.name AS fname,
                sf.type,
                sf.measure,
                sf.image AS fimage,
                sf.description AS fdescription,
                sfg.id AS gid,
                sfg.name AS gname,
                sfg.image AS gimage,
                sfg.description AS gdescription,
                CASE    
                    WHEN (sf.type = 'list' OR sf.type = 'colorlist') THEN (SELECT sfvl.value FROM shop_feature_value_list sfvl WHERE sfvl.id = smf.id_value)
                    WHEN (sf.type = 'number') THEN smf.value_number
                    WHEN (sf.type = 'bool') THEN smf.value_bool
                    WHEN (sf.type = 'string') THEN smf.value_string 
                    ELSE NULL
                END AS value"
            );
            $shop_feature->innerJoin('shop_modifications_feature smf', 'sf.id=smf.id_feature');
            $shop_feature->leftJoin('shop_feature_group sfg', 'sf.id_feature_group=sfg.id');
            $shop_feature->where('smf.id_price=?', $id_goods);
            $shop_feature->orderBy('sfg.sort IS NULL', 0);
            $shop_feature->addOrderBy('sf.sort', 0);
            $shop_feature->addOrderBy('sf.sort', 0);
            $featurelist = $shop_feature->getList();

            if (!empty($featurelist)) {
                foreach($featurelist as $val) {
                    $gid = (int)$val['gid'];
                    if (!isset($price_feature_list[$gid])) {
                        $price_feature_list[$gid] = array();
                    }
                    $price_feature_list[$gid]['name'] = $val['gname'];        
                    $price_feature_list[$gid]['image'] = $val['gimage'];
                    $price_feature_list[$gid]['description'] = $val['gdescription'];
        
                    if (!isset($price_feature_list[$gid]['features'])) {
                        $price_feature_list[$gid]['features'] = array();
                    }
                    $feature = array(
                        'id' => $val['fid'],
                        'type' => $val['type'],
                        'name' => $val['fname'],
                        'image' => trim($val['fimage']),
                        'description' => trim($val['fdescription']),
                        'value' => $val['value'],
                        'measure' => $val['measure']      
                    );
                    $price_feature_list[$gid]['features'][] = $feature;
                }
            }
        }
        return $price_feature_list;
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
    public function getGoods($options=array(), $goods_name = '') {
        if (!$this->find_group && !$goods_name) {
            return false;
        }
		$where = $order = array();
		if (is_array($goods_name)) $goods_name = implode(',', $goods_name);
		if (!empty($options['is_base_group']) && $options['is_base_group']) $this->Group_id = $this->baseGroupId;
		
		if (!empty($_SESSION['SHOP_SEARCH']['pagesearch']) && $_SESSION['SHOP_SEARCH']['pagesearch']!=$this->current_page.':'.$this->GroupId) {
			unset($_SESSION['SHOP_SEARCH'], $_SESSION['SHOP_VITRINE']['sortval']);
		}
		$_SESSION['SHOP_SEARCH']['newsearch'] = 0;
		$_SESSION['SHOP_SEARCH']['pagesearch'] = $this->current_page.':'.$this->GroupId;

		$defoptions = array(
    		'interface'=>array(
	    		'sp.id',
	    	    'sp.id_group',
	    	    'sp.code',
	    	    'sp.article',
	    	    'sp.name',
	    	    'sp.img',
	    	    'sp.img_alt',
	    	    'sp.note',
	    	    'sp.text',
	    	    'sp.presence_count',
	    	    'sp.presence',
	    	    'sp.manufacturer',
	    	    'sp.measure', 
	    	    'sp.enabled',
	    	    'sp.unsold', 
	    	    'sp.flag_hit', 
	    	    'sp.flag_new', 
	    	    'sp.votes',
	    	    'sp.price',
	    	    'sp.price_opt_corp',
	    	    'sp.price_opt',
	    	    'sp.bonus',
	    	    'sp.discount',
	    	    'sp.curr'
			),
			'special'=>false,
			'is_under_group'=>true,
			'unsold'=>false,
			'sort'=>false,
			'limit'=>20,
		);
		$option = array_merge($defoptions,$options);	
		//var_dump($option);
        $price = new seShopPrice();
        if (!is_array($option['interface'])) {
    	    $selectfields = explode(',',$option['interface']);
        }
		else $selectfields = $option['interface'];
		if ($option['special']=='special') // Отображать только спецпредложения
           $selectfields[] = 'sl.id AS lid';
		
		//$selectfields[] = $this->getBrandSql('sp.id');
		//$selectfields[] = $this->getParamSql('sp.id');
		$selectfields[] = '(SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications';
        $selectfields[] = $this->getDiscountSql();
		$selectfields[] = $this->getSpecialSql();
		$selectfields[] = 'IF ((sp.presence_count=-1 OR sp.presence_count IS NULL), 1000000000, sp.presence_count) presence_count_adopt';
		$selectfields[] = 'sg.name AS group_name';
		$selectfields[] = 'sg.code_gr';

        $price->select(implode(',',$selectfields));
		$price->where('sp.enabled="Y"');

		$price->innerjoin('shop_group sg','sp.id_group = sg.id');
		if (empty($goods_name)) {
			// ################################# отображать ли подгруппы?
			if ($option['is_under_group'] && $this->GroupId)                          
				$groups = implode(',', $this->getTreeShopGroup($this->GroupId,true));
			else
				$groups = $this->GroupId;

			$where = array();
			if (!$option['special'] && !$option['unsold']) {
				$srch = plugin_shopsearch::getInstance();
				list($where,$order) = $srch->fetchQuery();
			}
			
			$wheregroup = "lang='{$this->lang}' AND active = 'Y'";
		
			if (!$option['is_under_group'] && !$this->GroupId && empty($where))
				$wheregroup .= " AND false";
			elseif ($this->GroupId)
				$wheregroup .= " AND id IN ($groups)";

			// Создаем запросы для групп
			$nwhere = "(sp.id_group IN (SELECT id FROM shop_group WHERE $wheregroup)";
			if ($this->GroupId){
				$nwhere .=	" OR sp.id IN (SELECT price_id FROM shop_group_price WHERE (group_id IN ($groups)))".
							" OR sp.id_group IN (SELECT group_id FROM shop_crossgroup WHERE id IN ($groups))";
			}
			$nwhere .=')';

			$where[] = $nwhere;

			if ($option['unsold']) // Залежавшийся товар
				$where[] = 'sp.unsold = "Y"';

			if ($option['special']=='special') {
				$price->innerjoin("shop_leader sl", "sp.id = sl.id_price");
			} else {  
                if (!get('clearsearch') && get('shop_search'))
					$where[] = "sp.lang = '{$this->lang}'";
			}  
			
			if ($option['special'] == 'hits') // Хиты продаж
				$where[] = 'sp.flag_hit="Y"';
			elseif ($option['special'] == 'news') // Новинки
				$where[] = 'sp.flag_new="Y"';
		
			foreach ($where as $w) {
				$price->andWhere($w);
			}
		} elseif(!empty($goods_name)) {
			$price->andWhere('sp.id IN (?)', $goods_name);
		}
        
        //сортировка по полям
        if (!empty($this->sortval)) {
            $sortval = $this->sortval;
            list($field,$asc) = $this->getSortByField();
        } else {
            list($field,$asc) = $option['sort'];              //первичная сортировка
            if(!$option['sort']){
				$field = 'name'; 
				$asc = '0';
			};
			switch ($field) {
                case 'name': { $tsort = 'n'; } break;
                case 'id': { $tsort = 'r';} break;
                case 'price': { $tsort = 'p';} break;
                case 'vizits': { $tsort = 'l';} break;
                case 'R': { $tsort = 'a';} break;
				case 'article': { $tsort = 'a';} break;
				default: $tsort = 'n';
            }
            switch ($asc) {
                case '0': { $tasc = 'a'; } break;
                case '1': { $tasc = 'd';} break;
				default: $tasc = 'a';
            }
            $this->sortval =  $tsort.$tasc;
        }
        if($option['sort'][0]!='R') { 
            $order[] = array("`$field`", $asc);            
            $price->groupby("sp.id");
        }

        if (!$option['limit']) {
            $option['limit'] = 30;
        }
        foreach ($order as $o) {
        	list($field,$asc) = $o;
        	$price->addorderby($field,$asc,false);
        }
        $SE_NAVIGATOR = $price->pageNavigator($option['limit']);
		//if (!is_ajax()) echo $price->getSQL(),"\r\n\r\n";
		//echo '<!--'.$price->getSQL().'-->';
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
        return  array($pricelist,$SE_NAVIGATOR);
	}
	
	public function getPriceRange($options=array()) {
		$defoptions = array(
			'special'=>false,
			'is_under_group'=>true,
			'unsold'=>false
		);
		$option = array_merge($defoptions,$options);	
		//var_dump($option);
        $price = new seShopPrice();
		$srch = plugin_shopsearch::getInstance();
		list($where,$order) = $srch->fetchQuery(array('noprice'=>true));
        $price->select('MAX(sp.price*(SELECT m.kurs
            FROM `money` `m`
            INNER JOIN money_title mt ON (m.money_title_id=mt.id)
            WHERE m.name =  sp.curr  AND mt.lang=sp.lang
            ORDER BY m.date_replace DESC 
            LIMIT 1)) as maxprice, MIN(sp.price*(SELECT m.kurs
            FROM `money` `m`
            INNER JOIN money_title mt ON (m.money_title_id=mt.id)
            WHERE m.name =  sp.curr  AND mt.lang=sp.lang
            ORDER BY m.date_replace DESC 
            LIMIT 1)) as minprice');
		$price->where('sp.enabled="Y"');

		$price->innerjoin('shop_group sg','sp.id_group = sg.id');
        // отображать ли подгруппы?
        if ($option['is_under_group'] && $this->GroupId)                          
            $groups = implode(',', $this->getTreeShopGroup($this->GroupId,true));
        else
            $groups = $this->GroupId;

        $wheregroup = "lang='{$this->lang}' AND active = 'Y'";
		
		if (!$option['is_under_group'] && !$this->GroupId)
			$wheregroup .= " AND false";
		elseif ($this->GroupId)
            $wheregroup .= " AND id IN ($groups)";

		$nwhere = "sp.id_group IN (SELECT id FROM shop_group WHERE $wheregroup)";

        if ($this->GroupId){
            $nwhere .=	" OR sp.id IN (SELECT price_id FROM shop_group_price WHERE (group_id IN ($groups)))".
            			" OR sp.id_group IN (SELECT group_id FROM shop_crossgroup WHERE id IN ($groups))";
        }
		$where[] = $nwhere;

        if ($option['unsold']) // Залежавшийся товар
        	$where[] = 'sp.unsold = "Y"';

        if ($option['special']=='special') {
            $price->innerjoin("shop_leader sl", "sp.id = sl.id_price");
        } else {  
            $funct = $this->getSearch();            //возвращается sql запрос ввиде строки
            if ($funct) {
            	$where[] = $funct;                      
            }
            if (!get('clearsearch') && get('shop_search'))
            	$where[] = "sp.lang = '{$this->lang}'";
        }  
		if ($option['special'] == 'hits') // Хиты продаж
			$where[] = 'sp.flag_hit="Y"';
        elseif ($option['special'] == 'news') // Новинки
			$where[] = 'sp.flag_new="Y"';
		
        if(!empty($goods_name))
        	$where[] = "sp.id IN ($goods_name)";

        foreach ($where as $w) {
			$price->andWhere($w);
		}
        
		
        $price = $price->fetchOne();
        return array($price['minprice'],$price['maxprice']);
	}
	
	private function getParamSql($goods_ident) {
		return "(SELECT GROUP_CONCAT(concat_ws(',',''+spp.id,spr.typevalue,spp.value,spp.imgparam,''+spp.price,''+spp.count),'|') 
		FROM `shop_price_param` `spp` INNER JOIN `shop_param` `spr` ON (`spp`.`param_id`=`spr`.`id`)
		WHERE spp.price_id={$goods_ident} AND `spp`.`parent_id` IS NULL) AS `params`";	
	}

    private function getBrandSql($goods_ident){
            return "(SELECT concat_ws('|',name,picture) FROM shop_group sgb
            INNER JOIN shop_group_price sgp ON (sgp.group_id=sgb.id)
            WHERE sgp.price_id={$goods_ident} AND sgb.upid IN (SELECT id FROM shop_group WHERE code_gr='manufacturers') LIMIT 1) as brand"; 
	}

    private function getDiscountSql(){
            return "(SELECT group_concat(sd.id) FROM shop_discount sd
            WHERE sd.id_price=sp.id OR sd.id_group=sp.id_group OR sd.id_user=".seUserId().") as discounts"; 
	}

    private function getSpecialSql(){
            return "(SELECT ss.`newproc` FROM shop_special ss
            WHERE (ss.id_price=sp.id OR ss.id_group=sp.id_group) AND ss.date_added<=CURDATE() AND ss.expires_date>CURDATE() LIMIT 1) as spec_proc"; 
	}


    public function getBrands($goods_id){
		$rq = se_db_query($this->getBrandSql(intval($goods_id)));
		$res = se_db_fetch_assoc($rq);
		return $res['brand'];
	}

    /** 
    *   @boolean old_format             какой формат используется(новый или старый)
    *   @string  group_name             код группы
    *   @return                      
    **/
    public function getRequestGroup($old_format=false, $group_name = ''){
        $shopcatgr = '';
		$md = SE_MULTI_DIR;
        if (get('shopcatgr')) {
            $shopcatgr = get('shopcatgr', 1);
            if ($old_format) {
                if ($shopcatgr) {
                    $tbl = new seTable("shop_group", "sg");
                    $tbl->select('code_gr, footertext');
                    $tbl->find($shopcatgr);
                    $catgr = $tbl->code_gr;
                    header('HTTP/1.1 301 Moved Permanently');
                    header("Location: $md/{$this->current_page}/cat/$catgr/");
                    exit;
                } else {
                    header('HTTP/1.1 301 Moved Permanently');
                    header("Location: $md/{$this->current_page}/");
                    exit;
                }
            }
        } else {
            if (get('cat'))
                $group_name = get('cat',3); 
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
    *   @boolean true               автоматически создается первый элемент
    *   @return string              список групп
    **/   
    public function getTreeShopGroup($shopcatgr,$first=false,$startline=0,$endline=0){
        $list = array();
        if($first && is_numeric($shopcatgr) && intval($shopcatgr)) $list[] = $shopcatgr;
        $shgroup = new seShopGroup();
        $shgroup->select('GROUP_CONCAT(id) as idlist');
        $shgroup->where('upid IN (?)', $shopcatgr);
        $shgroup->andwhere("active='Y'");
        $shgroup->fetchOne();
		$idlist = $shgroup->idlist;
        if (!empty($idlist))
        	$list = array_merge($list, $this->getTreeShopGroup($idlist), explode(',',$idlist));
        return $list;
    }
	
	private function parseSession($session = false) {
		if (!$session) $session = $_SESSION;
		$search = $user = $settings = array();
		if (isset($_SESSION['SHOP_FILTER']['manufacturer'])) $search['manufacturer'] = $_SESSION['SHOP_FILTER']['manufacturer'];
		elseif (isset($_SESSION['CATALOGSRH']['manufacturer'])) {
			$search['manufacturer'] = $_SESSION['SHOP_FILTER']['manufacturer'] = $_SESSION['CATALOGSRH']['manufacturer'];
			unset($_SESSION['CATALOGSRH']['manufacturer']);
		}
	}
	
    /**
        @return string      возвращается строка поиска
    **/
    private function getSearch() {
        return false;
    }       

    /** Сортировка по полям в таблице витрины
    *   @return array           возвращается поле сортировки и направление сортировки
    **/
    public function getSortByField(){
        $sortval = $this->sortval;
        if($sortval=='R'){
            return array('name',0);
        }
        if(($sortval=='name')||($sortval=='id')||($sortval=='price')||($sortval=='article')){
            if(substr($sortval, 1, 1) == 'a'){
                return array($sortval,0);
            } else {
                return array($sortval,1);
            }
        }
        $asc = (substr($sortval, 1, 1) == 'a') ? '0' : '1';
        switch ($sortval) {
            case 'ga': $field = 'group_name'; break;
            case 'gd': $field = 'group_name'; break;
            case 'aa': $field = 'article'; break;
            case 'ad': $field = 'article'; break;
            case 'na': $field = 'name'; break;
            case 'nd': $field = 'name'; break;
            case 'ma': $field = 'manufacturer'; break;
            case 'md': $field = 'manufacturer'; break;
            case 'pa': $field = 'price'; break;
            case 'pd': $field = 'price'; break;
            case 'ca': $field = 'presence_count_adopt'; break;
            case 'cd': $field = 'presence_count_adopt'; break;
            case 'ra': $field = 'id'; break;
            case 'rd': $field = 'id'; break;
            case 'la': $field = 'vizits'; break;
            case 'ld': $field = 'vizits'; break;
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
    public function saveGoodsComment($viewgoods='', $comm_note, $admin) {           
        if($viewgoods=='') return;
        $comments = new seTable('shop_comm');
        $comments->id_price = $viewgoods;
        if (seUserGroup() < 3) {
            $person = new seTable('person');
            $person->select('email, last_name, first_name, sec_name');
            $person->find(seUserId());
            $comments->name = trim("{$person->last_name} {$person->first_name} {$person->sec_name}");
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
    public function showGoodsDescription($goods_id='') {
        if($goods_id=='') return;
        $shop_price = new seTable('shop_price', 'sp');
        //$show->select('id, id_group, enabled, title, name, keywords, description, img, img_alt, article, note, text, manufacturer, presence_count, presence, measure');
        $shop_price->select('sp.*');
        $shop_price->where('sp.id=?', $goods_id);
		$goods = $shop_price->fetchOne();

		$name = htmlspecialchars($goods['name']);
        $goods['title'] = (trim($goods['title'])) ? htmlspecialchars($goods['title']) : $name;
		$goods['keywords'] = (trim($goods['keywords'])) ? htmlspecialchars($goods['keywords']) : $name;
		$goods['description'] = (trim($goods['description'])) ? htmlspecialchars($goods['description']) : htmlspecialchars($goods['note']);
		$goods['img_alt'] = (trim($goods['img_alt'])) ? htmlspecialchars($goods['img_alt']) : $name;
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
        /*if(!se_db_is_field('shop_price', 'vizits')){
            se_db_add_field('shop_price', 'vizits', 'INT( 10 ) UNSIGNED NOT NULL AFTER `unsold`');        
        }*/ //Роман Кинякин: включить в апдейт - лишний запрос постоянный
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
        $arr = array();
        if (!empty($_SESSION['SHOP_VITRINE']['selected'][$price_id])) {
            // Обнулим значение количества параметров
            $nlist = $_SESSION['SHOP_VITRINE']['selected'][$price_id];
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
        $prc->select('votes');
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
    public function getGalleryImage($price_fields='') {
        $goodsimg = new seTable('shop_img', 'si');
        $goodsimg->select('si.id, si.id_price, si.picture, si.title');
        $goodsimg->where("si.id_price IN (SELECT id FROM shop_price WHERE id=?)", $price_fields);
        $imglist = $goodsimg->getList(); 
        return $imglist;
    }

    public function viewgalleryImages($price_id, $size_prev, $size_mid, $size_full, $res= 's', $watermark = '') {
        $i = 0;
        $images = array();
        $img = new plugin_ShopImages();
        $imglist = $this->getGalleryImage($price_id);
        if(empty($imglist)) return false;
        foreach($imglist as $imgs) {
            if($imgs['picture']!='') {
                $i++;
                $images[$i]['title'] = $imgs['title'];
                list($images[$i]['image'],) = $this->getGoodsImage($imgs['picture'], $size_full, $res, $watermark);
                list($images[$i]['image_prev'],) = $this->getGoodsImage($imgs['picture'], $size_prev, $res);
                list($images[$i]['image_mid'],) = $this->getGoodsImage($imgs['picture'], $size_mid, $res, $watermark);
            }
        }  
        return $images;
    }		
	
    public function getGoodsImage($imagename='', $size = 0, $res = 's', $watermark = ''){
        $im = new plugin_ShopImages();
        $imagename = $im->getPictFromImage($imagename, $size, $res, $watermark);
        $nofoto = explode('/', $imagename);  
        $nofoto = end($nofoto);  
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
        se_db_query($sql); //Роман Кинякин: также включить в апдейт!
        
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
	 * @param integer $id_price	           id товара
	 * @param unix time integer $expire    время до которого товар остается просмотренным пользователем
	 */
    public function setUserLooks($id_price, $expire = '-1') {
        if (!$id_price) return;
        if ($expire == -1){
            $expire = time() + 604800;//
        }
        if (!empty($_SESSION["look_goods"][$id_price])) {
            $looks = $_SESSION["look_goods"][$id_price];
            $arr = explode('#', $looks);
            (int)$arr[1]++;
            $looks = time().'#'.$arr[1];
            $_SESSION["look_goods"][$id_price] = $looks;
        } 
        else {
            $_SESSION["look_goods"][$id_price] = time().'#1';
        } 
        if (isset($_COOKIE["look_goods"][$id_price])){
            $looks = $_COOKIE["look_goods"][$id_price];
            $arr = explode('#', $looks);
            (int)$arr[1]++;
            $looks = time().'#'.$arr[1];
        } else {
                $looks = time().'#1';
        }
        setcookie("look_goods[$id_price]", $looks, $expire, "/");
    }

	public function getUserLooks($price_id = 0, $lastcount = 0){
        if (isset($_SESSION['look_goods'])){
            $list = array();
            $spec = $_SESSION['look_goods'];
            foreach ($spec as $key => $val){
				if ($price_id == $key) continue;
                $arr = explode('#', $val);
				$list[$key] = (int)$arr[0] + (int)$arr[1] * 60;    
            }
            arsort($list);
			$list = array_keys(array_slice($list, 0, $lastcount, true));
			if ($lastcount > 0) {
				array_splice($list, 0, -$lastcount);
			}
            return join(',', $list);
		}
	}

	//** Парсим пользовательскую маску
	public function parseUserMask($mask, $goods)
	{	$in = array('{name}', 
		   '{название товара}',
		   '{brand}', 
		   '{производитель}',
		   '{new price}',
		   '{новая цена}',
		   '{discount}',
		   '{скидка}',
		   '{description}',
		   '{описание товара}');
		if (!isset($goods['brandname'])) {
            $goods['brandname'] = '';
        }
        $out = array(
			$goods['name'],$goods['name'], 
			$goods['brandname'], $goods['brandname'],
			$goods['price'], $goods['price'],
			$goods['discount'], $goods['discount'],
			$goods['description'], $goods['description']
			);
		while (preg_match("/\[(.+)\]/", $mask, $m)) {
			if (preg_match("/(\{[^\}]+\})/", $m[1], $mm)) {
				$mm[1] = str_replace($in, $out, $mm[1]);
				if (!$mm[1]) $m[1] = '';
			}
			$mask = str_replace($m[0], $m[1], $mask);
		}
		$result = str_replace($in, $out, $mask);
			return htmlspecialchars(trim(str_replace('&nbsp;',' ', strip_tags(htmlspecialchars_decode($result)))));
	}

	// Выводим хлебные крошки
	public function getPathGroup($id_goods, $base_group = '') {
        $dt = array();
        $tbl = new seTable("shop_group", "sg");
        $tbl->select("sg.id, sg.code_gr, sg.name, sg.upid");
        $tbl->innerjoin("shop_price sp", "sp.id_group = sg.id");
        $tbl->where("sp.id=?", $id_goods);
        $tbl->fetchOne();
        while ($tbl->isFind() && $tbl->id != $base_group) {
            $dt[] = array(
                'cat' => $tbl->code_gr,
                'cat_nm' => $tbl->name
            );
            $gr = $tbl->upid;
            unset($tbl);
            $tbl = new seTable("shop_group", "sg");
            $tbl->select("sg.id, sg.code_gr, sg.name, sg.upid");
            $tbl->find($gr);
        }
        unset($tbl);
			
        return $dt;
	} 
	
}