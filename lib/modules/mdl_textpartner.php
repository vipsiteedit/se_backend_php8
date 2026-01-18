<?php
//BeginLib
//EndLib
function module_textpartner($razdel, $section = null)
{
   $__module_subpage = array();
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = " далее..";
if (empty($section->params[1]->value)) $section->params[1]->value = "Вернуться назад";
if (isRequest('object')) {
    $__data->page->titlepage = $__data->getObject($section, getRequest('object', 1));
}
$license = substr(md5($__data->prj->vars->sitelicense),0,10);
$section->title = str_replace("[%rfcode%]", $license, $section->title);
$section->text = str_replace("[%rfcode%]", $license, $section->text);
foreach($section->objects as $object)
{
    $object->title = str_replace("[%rfcode%]", $license, $object->title);
    $object->note = str_replace("[%rfcode%]", $license, $object->title);
    $object->text = str_replace("[%rfcode%]", $license, $object->title);
}
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
//EndRazdel
}
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"cont_txt\"[part.style]>
<noempty:part.title><h3 class=\"contentTitle\"[part.style_title]><span class=\"contentTitleTxt\">[part.title]</span> </h3> </noempty>
<noempty:part.image><img border=\"0\" class=\"contentImage\"[part.style_image] src=\"[part.image]\" alt=\"[part.image_alt]\"></noempty>
<noempty:part.text><div class=\"contentText\"[part.style_text]>[part.text]</div> </noempty>
[records]
</div> 
<!-- =============== END CONTENT ============= -->";
$__module_content['object'] = "
<div class=\"object\">
<noempty:record.title><h4 class=\"objectTitle\"><span class=\"objectTitleTxt\">[record.title]</span> </h4> </noempty>
<noempty:record.image><img border=\"0\" class=\"objectImage\" src=\"[record.image_prev]\" border=\"0\" alt=\"[record.image_alt]\"></noempty>
<noempty:record.note><div class=\"objectNote\">[record.note]</div> </noempty>
<noempty:record.text><a class=\"linkNext\" href=\"[record.link_detail]\">{$section->params[0]->value}</a> </noempty>
</div> 
";
$__module_content['show'] = "
<!-- =============== START SHOW PAGE ============= -->
<div class=\"content\" id=\"view\">
<noempty:record.title><h4 class=\"objectTitle\"><span class=\"contentTitleTxt\">[record.title]</span> </h4> </noempty>
<noempty:record.image><div id=\"objimage\"><img class=\"objectImage\" alt=\"[record.image_alt]\" src=\"[record.image]\" border=\"0\"></div> </noempty>
<noempty:record.note><div class=\"objectNote\">[record.note]</div> </noempty>
<div class=\"objectText\">[record.text]</div> 
<input class=\"buttonSend\" onclick=\"document.location = '$_page.html';\" type=\"button\" value=\"{$section->params[1]->value}\">
</div> 
";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};