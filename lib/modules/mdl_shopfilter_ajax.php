<?php
function module_shopfilter_ajax($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/shopfilter_ajax';
 else $__MDL_URL = 'modules/shopfilter_ajax';
 $__MDL_ROOT = dirname(__FILE__).'/shopfilter_ajax';
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
 setlocale(LC_NUMERIC, 'C');
 if($section->parametrs->param6=='') {
    $basegroup = $__data->getVirtualPage('choose_group_'.$_page);
     if (!$basegroup) {
        $plugin_groups = plugin_shopgroups::getInstance();
        $tree = $plugin_groups->getTree();
        if (count($tree) == 1)
        foreach($tree as $gr){
            $basegroup = $gr['code'];
            break;
        }
     }
 } else {
    $basegroup = strval($section->parametrs->param6);
 }
 
 //if (!$basegroup)
 //    return;
 
 $count_price_found = 0;
 
 if (isRequest('ajax'.$razdel)) {
     $plugin_filter = new plugin_shopfilter($basegroup);
     $filters = $plugin_filter->getFilterValues(null, (strval($section->parametrs->param5) == 'Y'));
     $filtercount = count($filters);
     if (isRequest('main')) {
         ob_start(); // Открываем буферизацию 
             include $__MDL_ROOT . "/php/subpage_main.php";
             include $__MDL_ROOT . "/tpl/subpage_main.tpl";
             $response = ob_get_contents();
             ob_end_clean();
             echo $response;
     } else {
         $count_price_found = $plugin_filter->getCountFiltered();
         $response = array();
         if ($section->parametrs->param5 == 'Y') {
             ob_start(); // Открываем буферизацию 
             include $__MDL_ROOT . "/php/subpage_filters.php";
             include $__MDL_ROOT . "/tpl/subpage_filters.tpl";
             $response['data'] =str_replace('[module_url]', '/'.$__MDL_URL.'/', ob_get_contents());
             ob_end_clean();
         } 
         $response['count'] = $count_price_found;
         echo json_encode($response);
     }
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
 //BeginSubPagefilters
 $__module_subpage['filters']['admin'] = "";
 $__module_subpage['filters']['group'] = 0;
 $__module_subpage['filters']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='filters' && file_exists($__MDL_ROOT . "/tpl/subpage_filters.tpl")){
	include $__MDL_ROOT . "/php/subpage_filters.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_filters");
	$__module_subpage['filters']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagefilters
 //BeginSubPagemain
 $__module_subpage['main']['admin'] = "";
 $__module_subpage['main']['group'] = 0;
 $__module_subpage['main']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='main' && file_exists($__MDL_ROOT . "/tpl/subpage_main.tpl")){
	include $__MDL_ROOT . "/php/subpage_main.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_main");
	$__module_subpage['main']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagemain
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}