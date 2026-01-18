<?php
function module_mvalutkurs($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mvalutkurs';
 else $__MDL_URL = 'modules/mvalutkurs';
 $__MDL_ROOT = dirname(__FILE__).'/mvalutkurs';
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
 if (!function_exists('parse_kurs')) {
     function parse_kurs($vals) {
         $params = array();
         $level = array();
         foreach ($vals as $xml_elem) {
             if ($xml_elem['type'] == 'open') {
                 if (array_key_exists('attributes', $xml_elem)) {
                     @list($level[$xml_elem['level']], $extra) = array_values($xml_elem['attributes']);
                 } else {
                     $level[$xml_elem['level']] = $xml_elem['tag'];
                 }
             }
             if ($xml_elem['type'] == 'complete') {
                 $start_level = 1;
                 $php_stmt = '$params';
                 while($start_level < $xml_elem['level']) {
                     $php_stmt .= '[$level[' . $start_level . ']]';
                     $start_level++;
                 }
                 $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
                 eval($php_stmt);
             }
         }
         return $params;
     }
 }
 $file = 'www.cbr.ru';
 $data = '';
 $next_data = '';
 if (!($fp = @fsockopen($file, 80, $errno, $errstr, 10))) {
     return;
 }
 $query  = "GET http://www.cbr.ru/scripts/XML_daily.asp?date_req=" . date('d/m/Y', time()) . " HTTP/1.0\r\nHost: www.cbr.ru\r\n\r\n";
 fputs($fp, $query);
 while (!feof($fp)) {
     $data .= fgets($fp);
 }
 fclose($fp);
 $data = substr($data, strpos($data, "\r\n\r\n") + strlen("\r\n\r\n"));
 $xml_parser = xml_parser_create();
 xml_parse_into_struct($xml_parser, $data, $vals, $index);
 xml_parser_free($xml_parser);
 $params = array();
 $params = parse_kurs($vals);
 $temp = array_keys($params);
 $curs_date = $temp[0];
 $datadef = strval($section->parametrs->param2);
 if (empty($datadef) || ($datadef == 'R00000')) {
     $datadef = 1;
 } else {
     $datadef = str_replace(',', '.', $params[$curs_date][$datadef]['VALUE']) / str_replace(',', '.', $params[$curs_date][$datadef]['NOMINAL']);
 }
 $i = 0;
 foreach ($section->objects as $objit) {
     list($ndt, ) = explode('|', $objit->text1);
     if (($ndt == 'R00000') && ($ndt == '')) {
         $param = "1.00";
     } else {
         $param = str_replace(',', '.', $params[$curs_date][$ndt]['VALUE']) / str_replace(',', '.', $params[$curs_date][$ndt]['NOMINAL']);
     }
     $res = explode('.', str_replace(',', '.', round($param / $datadef, 2)));
     while (strlen(@$res[1]) < 2 ) {
         $res[1] = $res[1] . "0";
     }
     $objit->text1 = $section->parametrs->param3 . $res[0] . '.' . $res[1] . $section->parametrs->param4;
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
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}