<?php
function module_foto_jquery($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/foto_jquery';
 else $__MDL_URL = 'modules/foto_jquery';
 $__MDL_ROOT = dirname(__FILE__).'/foto_jquery';
 $this_url_module = $__MDL_ROOT;
 $url_module = $__MDL_URL;
 if (file_exists($__MDL_ROOT.'/php/lib.php')){
	require_once $__MDL_ROOT.'/php/lib.php';
 }
 if (count($section->objects))
	foreach($section->objects as $record){ $__record_first = $record->id; break; }
 if (file_exists($__MDL_ROOT.'/php/parametrs.php')){
   include $__MDL_ROOT.'/php/parametrs.php';
 }
 // START PHP
 global $path;
 $path = SE_JS_LIBS . "lightbox/";

   $section->objectcount = intval($section->parametrs->param1);
 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__MDL_ROOT . "/tpl/content.tpl";
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //Final PHP
 global $_item,$_sel,$raz_txt;
 $count=count($obj[$razdel]);
 $raz_txt[$razdel]=str_replace("[SE_PARTSELECTOR]",SE_PARTSELECTOR($razdel,$count,$parametrtext[0],$_item,$_sel),$raz_txt[$razdel]);

 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}