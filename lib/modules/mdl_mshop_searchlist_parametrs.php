<?php
function module_mshop_searchlist_parametrs($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_searchlist_parametrs';
 else $__MDL_URL = 'modules/mshop_searchlist_parametrs';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_searchlist_parametrs';
 $this_url_module = $__MDL_ROOT;
 $url_module = $__MDL_URL;
 if (file_exists($__MDL_ROOT.'/php/lib.php')){
	require_once $__MDL_ROOT.'/php/lib.php';
 }
 if (count($section->objects))
	foreach($section->objects as $record){ $__record_first = $record->id; break; }
 if (file_exists($__MDL_ROOT.'/i18n/'.se_getlang().'.xml')){
	$__langlist = simplexml_load_file($__MDL_ROOT.'/i18n/'.se_getlang().'.xml');
	append_simplexml($section->language, $__langlist);
	foreach($section->language as $__langitem){
	  foreach($__langitem as $__name=>$__value){
	   $__name = strval($__name);
	   $__value = strval($section->traslates->$__name);
	   if (!empty($__value))
	     $section->language->$__name = $__value;
	  }
	}
 }
 if (file_exists($__MDL_ROOT.'/php/parametrs.php')){
   include $__MDL_ROOT.'/php/parametrs.php';
 }
 // START PHP
 $page_vitrine = $__data->getVirtualPage('shop_vitrine');
 //echo "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
 
 $errorlabel=1;
  // конец проверки на модера 
 $flag = 0; 
 $moderi = $section->parametrs->param140;
 $moders = explode(",", $moderi);
 for ($j = 0; $j < count($moders); $j++) {
     $userlogin = trim($moders[$j]); // очищаем от лишних пробелов массив модераторов
     if (($userlogin == seUserLogin())&& (seUserLogin() != "")) {
         $flag = 1;
     }
 }
 
 if  (!$flag && seusergroup()>1) {
    $flag = 1;
 } 
 
 $lang = se_getLang(); 
 
 // Диапазоны параметров
 $max_price = MaxMinValue('MAX(price)');
 $min_price = MaxMinValue('MIN(price)');
 $max_count = MaxMinValue('MAX(presence_count)');
 $min_count = MaxMinValue('MIN(presence_count)');
 $max_price_opt = MaxMinValue('MAX(price_opt)');
 $min_price_opt = MaxMinValue('MIN(price_opt)');
 $max_price_opt_corp = MaxMinValue('MAX(price_opt_corp)');
 $min_price_opt_corp = MaxMinValue('MIN(price_opt_corp)');
 $max_weight = MaxMinValue('MAX(weight)');
 $min_weight = MaxMinValue('MIN(weight)');
 $max_volume = MaxMinValue('MAX(volume)');
 $min_volume = MaxMinValue('MIN(volume)'); 
 
 $curgroup = $_SESSION['SHOP_VITRINE']['CURGROUP'];
                 
 if ($section->parametrs->param115 == Y) // Использовать скрытие блока поиска   
     $showSearchBlock = isRequest('shop_search');
 else
     $showSearchBlock = true;        
                                                                   
 //выбрать подгруппы
 if(isRequest('addgroup'.$razdel)){              
     $id = getRequest('idgroup', 1);                     //поймал следующую группу
     $lev = getRequest('levels', 1);                     //поймал текущий уровень
   //  $_SESSION['look_for']["0"] = -1;
     $_SESSION['look_for']["$lev"+1] = $id;                //сессия текущего уровня на другой
     for($a=$lev+2;$a<100;$a++){unset($_SESSION['look_for']["$a"]);}
     $grps_extra = '';
     $gr = '';                                           
     $start1 = trim($section->parametrs->param106);   
     $start = getStartCategory($start1);                   
     $groups = new seTable('shop_group','sg');                   
     for($a=0;$a<=($lev+1);$a++){                          
         $groups->select('*');                                   
         $ids = $_SESSION['look_for']["$a"];                    
         if(($a == 0) || (($id == '-1')&&($a == 0))){    
 
             if($start1==''){                                   
                 $groups->where("`upid` IS NULL");               
             } else {                                            
                 $groups->where("`id`='$start'");              
             }                                                   
         }else{                                        
             $groups->where("`upid`='$ids'");                        
         }
         $groups->andwhere("`lang` like '$lang'");
         $groups->andwhere("`active`='Y'");
         $group = $groups->getList();                    
         $currentid = $_SESSION['look_for']["$a"];        //сохранение id группы
         if($currentid == -1){
             $currentid = $_SESSION['look_for']["$a"-1];
         }
         if(!$group){
             unset($_SESSION['look_for']["$a"+1]);      
             break;
         } 
         //вывод на экран
         $grps_extra .= "    <select name='selectGroup".$a."' class='sel_common sel_".$a."' onchange='selectgroup(this.value, ".$a.")'>
                                 <option value='-1'>".$section->language->lang020."</option>";
         $_SESSION['level_group'] = $a+1;
         foreach($group as $val){  
             $grps_extra .= "  <option value='".$val['id']."' ";
             $gr = $_SESSION['look_for']["$a"+1];                //текущая выделенная
             if($val['id'] == $gr){
                 $grps_extra .= "selected";
             }
             $grps_extra .= ">".$val['name']."</option>";
         }          
         $grps_extra .= "  </select>";   
                                    
     }                                                          //список групп        
     if($currentid!=-1){
         $arr_grp = getArrayOfCategory($currentid);
         $grps_extra .= "<input name='group_hidden' type='hidden' value='".$arr_grp."' class='currentidgroup'>";
     } else {                                     
         $grps_extra .= "<input name='group_hidden' type='hidden' value='".$currentid."' class='currentidgroup'>";
     }  
    // echo "<br> 1 = ".$groups->getSQL();
     unset($groups);                            
     echo  $grps_extra;
     exit;
 } 
 $level_group = $_SESSION['level_group'];
 
 $start2 = trim($section->parametrs->param106);   
 $start_group = getStartCategory($start2); 
 $tree_group = getArrayOfCategory($start_group);
 $_SESSION['SHOP_VITRINE']['START_GROUP'] = $tree_group; 
 if($start2==""){
     unset($_SESSION['SHOP_VITRINE']['START_GROUP']);
 }
     
 if(isRequest('clearsearch') 
 || !is_referer_page($_page)) {
 
 //|| ($_SERVER['HTTP_REFERER']!="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']))) { // Очищаем поля ввода
     $_SESSION['SHOP_VITRINE']['PARAM_NAME'] = "";
     $_SESSION['SHOP_VITRINE']['PARAM_NAME_VAL'] = "";
     $_SESSION['SHOP_VITRINE']['PARAM_VAL'] = "";
     $_SESSION['SHOP_VITRINE']['MAN'] = "";
     $_SESSION['SHOP_VITRINE']['MAN_VAL'] = "";
     $_SESSION['SHOP_VITRINE']['GROUP'] = "";
     $_SESSION['SHOP_VITRINE']['MES'] = "";
     $_SESSION['SHOP_VITRINE']['PARAM_VAL']['measure'] = "";
     $_SESSION['SHOP_VITRINE']['GROUP_FOR'] = "";
     $_SESSION['look_for'] = "";
     foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB1'] as $name=>$value){
         $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name] = ""; 
         $_POST[$name.'_search'] = "";
         $_POST[$name.'_from_search'] = "";
         $_POST[$name.'_to_search'] = "";         
     }
     foreach($_SESSION['SHOP_VITRINE']['ALL_PARAMETRS']['TAB2'] as $name=>$value){
         $_SESSION['SHOP_VITRINE']['ALL_SEARCH'][$name] = ""; 
         $_POST[$name.'_search'] = "";
         $_POST[$name.'_from_search'] = "";
         $_POST[$name.'_to_search'] = "";            
     }  
     $_SESSION['SHOP_VITRINE']['ALL_SEARCH'] = ""; 
 }
                                     

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/content.tpl";
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //BeginSubPage1
 $__module_subpage['1']['admin'] = "";
 $__module_subpage['1']['group'] = 0;
 $__module_subpage['1']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='1' && file_exists($__MDL_ROOT . "/tpl/subpage_1.tpl")){
	include $__MDL_ROOT . "/php/subpage_1.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_1.tpl";
	$__module_subpage['1']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage1
 //BeginSubPage3
 $__module_subpage['3']['admin'] = "";
 $__module_subpage['3']['group'] = 0;
 $__module_subpage['3']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='3' && file_exists($__MDL_ROOT . "/tpl/subpage_3.tpl")){
	include $__MDL_ROOT . "/php/subpage_3.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_3.tpl";
	$__module_subpage['3']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage3
 //BeginSubPage2
 $__module_subpage['2']['admin'] = "";
 $__module_subpage['2']['group'] = 0;
 $__module_subpage['2']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='2' && file_exists($__MDL_ROOT . "/tpl/subpage_2.tpl")){
	include $__MDL_ROOT . "/php/subpage_2.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_2.tpl";
	$__module_subpage['2']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage2
 //BeginSubPage4
 $__module_subpage['4']['admin'] = "";
 $__module_subpage['4']['group'] = 0;
 $__module_subpage['4']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='4' && file_exists($__MDL_ROOT . "/tpl/subpage_4.tpl")){
	include $__MDL_ROOT . "/php/subpage_4.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_4.tpl";
	$__module_subpage['4']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage4
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}