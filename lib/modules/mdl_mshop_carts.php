<?php
function module_mshop_carts($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_carts';
 else $__MDL_URL = 'modules/mshop_carts';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_carts';
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
 if (isRequest('jquery'.$razdel)) {
 
   /*  if (isRequest('isauthorized')) {  не требуется
         if (seUserGroup())
             echo '1';
         exit();    
     } */
    
     if (isRequest('getUserAddr')) // Возвращение почтового индекса и адреса по id
     { 
         $userAddrId = getRequest('getUserAddr');
         $_SESSION['shopdelivery']['select_addr_id'] = $userAddrId;
         
         $se_person = new seTable();
         $se_person->from('person', 'p');
         $se_person->select("p.*, concat_ws(', ', c.name, r.name, t.name, overcity, addr) address");
         $se_person->leftjoin('country c', 'c.id = p.country_id');
         $se_person->leftjoin('region r', 'r.id = p.state_id');
         $se_person->leftjoin('town t', 't.id = p.town_id');       
         $se_person->where('p.id=?', $userAddrId); 
         $se_person->fetchOne();
 
         if ($se_person->id)
         {
             echo $se_person->post_index.'|'.$se_person->address;
         }    
         exit;
     }
    
     if (isRequest('clogin')) { // Проверка существования заданного логина
         $clogin = getRequest('clogin');
                           
         if ($section->parametrs->param74 == 'email') {
             if (!$clogin) {
                 echo 'err_empty_email';
                 exit();
             }
             elseif (!se_CheckMail($clogin)) {
                 echo 'err_email';
                 exit(); 
             }
         }
         elseif ($section->parametrs->param74 == 'phone') {
             if (!$clogin) {
                 echo 'err_empty_phone';
                 exit();
             }
         }                       
         
         $tbl = new seTable('se_user');
         $tbl->select('id');
         $tbl->where("username='?'", $clogin); 
         $tbl->fetchOne();
         if ($tbl->id) 
             echo '1';
         else
             echo '0';
         exit();
     }
     
     if (isRequest('login')) { // Введен логин и пароль
         $login = getRequest('login');
         $password = getRequest('password');
         $passmd5 = md5($password);
         $tbl = new seTable('se_user', 'u');
         $tbl->select('u.id, password, p.last_name, p.first_name');
         $tbl->leftjoin('person p', 'p.id = u.id'); 
         $tbl->where("username='?'", $login);   
         $tbl->fetchOne();         
         if ($tbl->password == $passmd5) { 
             check_session(true); // Удаляем старую сессию
             $SESSION_VARS['AUTH_PW']    = $tbl->password;
             $SESSION_VARS['AUTH_USER']  = $login;
             $SESSION_VARS['IDUSER']     = $tbl->id;
             $SESSION_VARS['USER']       = $tbl->first_name;
             $SESSION_VARS['GROUPUSER']  = 1;
             $SESSION_VARS['ADMINUSER']  = '';
             check_session(false, $SESSION_VARS);
                             
             echo '1'. '|'. $tbl->last_name . ' ' . $tbl->first_name;
         }
         else
             echo '0'. '|'; 
         exit();
     }
 
     exit();
 }
 
 if (file_exists($__MDL_ROOT.'/shop_cart_controller.php')) {
     require_once $__MDL_ROOT.'/shop_cart_controller.php';
 }
 //error_reporting(E_ALL);
 //echo '$_SESSION[\'shopcart\']='; print_r($_SESSION['shopcart']); echo '///<br>';  
 $controller = new shop_cart_Controller($section);   
 $controller->processData();
 //echo '$_SESSION[\'shopcart\']='; print_r($_SESSION['shopcart']); echo '///end<br>';

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
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}