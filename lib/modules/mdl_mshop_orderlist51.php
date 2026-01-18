<?php
function module_mshop_orderlist51($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_orderlist51';
 else $__MDL_URL = 'modules/mshop_orderlist51';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_orderlist51';
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
 
 $id_author = seUserId();
 $showcurr = se_getMoney();
 $round_price = ($section->parametrs->param37 == 'Y');
 
 $plugin_order = new plugin_shoporder($id_author); 
 
 if (isRequest('detail_order')) {
     $order_id = getRequest('detail_order', 1);
     
     $order = new seShopOrder();
     $order->where('id_author=?', $id_author);
     $order->andWhere('id=?', $order_id);
     $order->andWhere("is_delete='?'", 'N');
     if ($order->fetchOne()){
         $__data->goSubName($section, 'detail');
     }
     else{
         header('Location: '.seMultiDir().'/'.$_page.'/?'.time());
         exit;
     }   
 }
 elseif(isset($_POST['delete_order']) && isRequest('order')) {
     $order_id = getRequest('order', 1);
     if ($order_id)
         $plugin_order->deleteOrder($order_id); 
     header('Location: '.seMultiDir().'/'.$_page.'/?'.time());
     exit();           
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
 //BeginSubPagedetail
 $__module_subpage['detail']['admin'] = "";
 $__module_subpage['detail']['group'] = 0;
 $__module_subpage['detail']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='detail' && file_exists($__MDL_ROOT . "/tpl/subpage_detail.tpl")){
	include $__MDL_ROOT . "/php/subpage_detail.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_detail");
	$__module_subpage['detail']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagedetail
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}