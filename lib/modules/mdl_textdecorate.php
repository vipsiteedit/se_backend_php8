<?php
//BeginLib
//EndLib
function module_textdecorate($razdel, $section = null)
{
   $__module_subpage = array();
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if (strpos(dirname(__FILE__), 'lib/modules')) $this_url_module = '/lib/modules/textdecorate';
   else $this_url_module = '/modules/textdecorate';
   if ($section == null) return;
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
//EndRazdel
}
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<!-- =============== END CONTENT ============= -->";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}