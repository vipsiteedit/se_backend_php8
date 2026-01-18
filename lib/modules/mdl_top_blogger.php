<?php
function module_top_blogger($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/top_blogger';
 else $__MDL_URL = 'modules/top_blogger';
 $__MDL_ROOT = dirname(__FILE__).'/top_blogger';
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
 //  начальная информация
     $user_group = seUserGroup();
 //  подписаться
     if(isRequest('ajax_setSubscribe')) {
         $user_id = seUserId();
         if($user_id > 0){
             $blogger = getRequest('id_bl', 1);
             $subscribe = new seTable('blog_user_subscribe');
             $subscribe->select('id, id_blogger');
             $subscribe->where("`id_user`=?", $user_id);
             $subscribe->andwhere("`id_blogger`=?", $blogger);
             $subscribe->fetchOne();
             if(!$subscribe->id){
                 $subscribe->id_blogger = $blogger;
                 $subscribe->id_user = $user_id;
                 $subscribe->save();
                 echo "OK";
                 exit;
             }
             echo "NO_CHANGE";
             exit;
         }
         echo "NO_ACCESS";
         exit;
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
 //BeginSubPagedebuted
 $__module_subpage['debuted']['admin'] = "";
 $__module_subpage['debuted']['group'] = 0;
 $__module_subpage['debuted']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='debuted' && file_exists($__MDL_ROOT . "/tpl/subpage_debuted.tpl")){
	include $__MDL_ROOT . "/php/subpage_debuted.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_debuted");
	$__module_subpage['debuted']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagedebuted
 //BeginSubPagelists
 $__module_subpage['lists']['admin'] = "";
 $__module_subpage['lists']['group'] = 0;
 $__module_subpage['lists']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='lists' && file_exists($__MDL_ROOT . "/tpl/subpage_lists.tpl")){
	include $__MDL_ROOT . "/php/subpage_lists.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_lists");
	$__module_subpage['lists']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagelists
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}