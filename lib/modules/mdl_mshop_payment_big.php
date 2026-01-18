<?php
function module_mshop_payment_big($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_payment_big';
 else $__MDL_URL = 'modules/mshop_payment_big';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_payment_big';
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
 $basecurr = se_getMoney();
 
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
 
 if (isRequest('invnum') && isRequest(md5(getRequest('invnum').'3dfgvj'))) {
     list($payment_id, $order_id) = explode('_', getRequest('invnum'));
     
     if ($payment_id == 0 && !seUserGroup())
     {
         header('Location: '.seMultiDir().'/'.$_page.'/?'.time());
         exit;
     }
     
     if (!empty($order_id) && isPostPayment($order_id)) {
         header('Location: '.seMultiDir().'/'.$_page.'/?'.time());
         exit;
     }
         
     $_SESSION['SHOPPAYMENT']['ORDER_ID'] = $ORDER_ID = trim($order_id);
     $__data->goSubName($section, 'payment');
 }
 
 if (isRequest('merchant') && getRequest('merchant') == 'success') {
   // Переход на страницу success
     $payment->select('success');
     $__data->goSubName($section, 'success');
 }
 if (isRequest('merchant') && getRequest('merchant') == 'result') {
   // Переход на страницу success
     $payment->select('result');
     $__data->goSubName($section, 'result');
 }
 if (isRequest('merchant') && getRequest('merchant') == 'fail') {
   // Переход на страницу success
     $payment->select('fail');
     $__data->goSubName($section, 'fail');
 }
 if (isRequest('merchant') && getRequest('merchant') == 'blank') {
   // Переход на страницу Бланка счета
     $payment->select('blank');
     $__data->goSubName($section, 'blank');
 }

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //BeginSubPagepersonal
 $__module_subpage['personal']['admin'] = "";
 $__module_subpage['personal']['group'] = "1";
 $__module_subpage['personal']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='personal' && file_exists($__MDL_ROOT . "/tpl/subpage_personal.tpl")){
	include $__MDL_ROOT . "/php/subpage_personal.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_personal");
	$__module_subpage['personal']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagepersonal
 //BeginSubPagepayment
 $__module_subpage['payment']['admin'] = "";
 $__module_subpage['payment']['group'] = 0;
 $__module_subpage['payment']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='payment' && file_exists($__MDL_ROOT . "/tpl/subpage_payment.tpl")){
	include $__MDL_ROOT . "/php/subpage_payment.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_payment");
	$__module_subpage['payment']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagepayment
 //BeginSubPagesuccess
 $__module_subpage['success']['admin'] = "";
 $__module_subpage['success']['group'] = "1";
 $__module_subpage['success']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='success' && file_exists($__MDL_ROOT . "/tpl/subpage_success.tpl")){
	include $__MDL_ROOT . "/php/subpage_success.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_success");
	$__module_subpage['success']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagesuccess
 //BeginSubPageresult
 $__module_subpage['result']['admin'] = "";
 $__module_subpage['result']['group'] = 0;
 $__module_subpage['result']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='result' && file_exists($__MDL_ROOT . "/tpl/subpage_result.tpl")){
	include $__MDL_ROOT . "/php/subpage_result.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_result");
	$__module_subpage['result']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageresult
 //BeginSubPagefail
 $__module_subpage['fail']['admin'] = "";
 $__module_subpage['fail']['group'] = "1";
 $__module_subpage['fail']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='fail' && file_exists($__MDL_ROOT . "/tpl/subpage_fail.tpl")){
	include $__MDL_ROOT . "/php/subpage_fail.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_fail");
	$__module_subpage['fail']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagefail
 //BeginSubPageblank
 $__module_subpage['blank']['admin'] = "";
 $__module_subpage['blank']['group'] = 0;
 $__module_subpage['blank']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='blank' && file_exists($__MDL_ROOT . "/tpl/subpage_blank.tpl")){
	include $__MDL_ROOT . "/php/subpage_blank.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_blank");
	$__module_subpage['blank']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageblank
 //Final PHP
 if ($__data->req->sub == 6) exit($__module_subpage[6]['form']);

 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}