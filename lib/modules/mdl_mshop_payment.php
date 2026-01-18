<?php
function module_mshop_payment($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_payment';
 else $__MDL_URL = 'modules/mshop_payment';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_payment';
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
 if (!seUserGroup()) {
     return;
 }
 $basecurr = se_baseCurrency();
 
 // Отображаемая Валюта
 $showCurr = $section->parametrs->param25;
 $thisdate = date('d.m.Y');
 if (isset($_SESSION['SHOPPAYMENT']['ORDER_ID'])) {
     $ORDER_ID = intval($_SESSION['SHOPPAYMENT']['ORDER_ID']);
 }
 if (isRequest('ORDER_PAY')) {
     $ORDER_ID = getRequest('ORDER_PAY', VAR_INT);
 }
 if (isRequest('ORDER_PAYEE')) {
     $ORDER_ID = getRequest('ORDER_PAYEE', VAR_INT);
 }
 if (isRequest('ORDER_ID')) {
     $_SESSION['SHOPPAYMENT']['ORDER_ID'] = $ORDER_ID = getRequest('ORDER_ID', VAR_INT);
 }
 // Язык проекта
 $lang = se_getlang();
 $NAMEFORAUTHOR = "";
 if ((isset($_SESSION['ID_AUTH'])) && ($_SESSION['ID_AUTH'] > 0)) {
     $id_author = $_SESSION['ID_AUTH'];
 } else {
     $id_author = seUserId();
 }
 //require_once SE_LIBS . 'lib_macrocomands.php';
 $payment = new seTable('shop_payment');//seShopPayment();
 //require_once("modules/msshop_payment/function.php");
 if (isRequest('merchant') && getRequest('merchant') == 'success') {
   // Переход на страницу success
     $payment->select('success');
     $__data->goSubName($section, 3);
 }
 if (isRequest('merchant') && getRequest('merchant') == 'result') {
   // Переход на страницу success
     $payment->select('result');
     $__data->goSubName($section, 4);
 }
 if (isRequest('merchant') && getRequest('merchant') == 'fail') {
   // Переход на страницу success
     $payment->select('fail');
     $__data->goSubName($section, 5);
 }
 if (isRequest('merchant') && getRequest('merchant') == 'blank') {
   // Переход на страницу Бланка счета
     $payment->select('blank');
     $__data->goSubName($section, 6);
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
 $__module_subpage['1']['group'] = "1";
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
 $__module_subpage['3']['group'] = "1";
 $__module_subpage['3']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='3' && file_exists($__MDL_ROOT . "/tpl/subpage_3.tpl")){
	include $__MDL_ROOT . "/php/subpage_3.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_3.tpl";
	$__module_subpage['3']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage3
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
 //BeginSubPage5
 $__module_subpage['5']['admin'] = "";
 $__module_subpage['5']['group'] = "1";
 $__module_subpage['5']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='5' && file_exists($__MDL_ROOT . "/tpl/subpage_5.tpl")){
	include $__MDL_ROOT . "/php/subpage_5.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_5.tpl";
	$__module_subpage['5']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage5
 //BeginSubPage6
 $__module_subpage['6']['admin'] = "";
 $__module_subpage['6']['group'] = 0;
 $__module_subpage['6']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='6' && file_exists($__MDL_ROOT . "/tpl/subpage_6.tpl")){
	include $__MDL_ROOT . "/php/subpage_6.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/subpage_6.tpl";
	$__module_subpage['6']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage6
 //Final PHP
 if ($__data->req->sub == 6) exit($__module_subpage[6]['form']);

 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}