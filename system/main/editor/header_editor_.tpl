<style>
/*
глюки: - отмена не работает в иконке Редактор страницы в Опере
*/



.txtedithover:hover { border: 1px dashed #ff0000; padding: 2px; margin:-2px; display: inline-block; }

.itemRecordHeader { position: absolute; }

#selivehint { width:100%; height: 16px; position: absolute; top: 47px; left: 0px; white-space: nowrap; z-index: 999; color: #ffffff; font-size: 11px; font-family: arial; }
#sepastlivehint { position: absolute; }

#seselpages {
	border: 1px solid #969696;
    border-radius: 3px 3px 3px 3px;
    float: left;
	margin: 10px 16px 0 0;
}

#seselpages #filePages-button {
    color: #404040;
    width: 170px !important;
}

#seselpages #filePages-button .ui-selectmenu-item-header {
	font-family: arial;
}

#seselpages .ui-state-default .ui-icon {
    background-image: url("/system/main/editor/images/ui-icons_888888_256x240.png");
}

#seselpages .ui-state-default {
    background: url("/lib/js/ui/themes/base/images/ui-bg_glass_75_e6e6e6_1x400.png") repeat-x scroll 50% 50% #E6E6E6;
    border: medium none;
    color: #555555;
    font-weight: normal;
}




/* меню-1 */

#tabs-menu .sortPages {
	border: 1px solid #969696;
    border-radius: 3px 3px 3px 3px;
    background: url("/system/main/editor/images/editor_60.png") repeat-x scroll 0 -38px #696969;
    font-family: arial;
    width: auto;
	padding: 0;
	margin-top:5px;
}

#tabs-menu .sortPages li {
    background: url("/system/main/editor/images/trsepmenu.png") repeat-x scroll 0 0 transparent;
	margin: 0 0 1px 0px !important;
	padding: 4px 0;
	border: 0 none !important;
    border-bottom: 1px dotted transparent;
    border-top: 1px dotted transparent;
    border-left-width: 0 !important;
    border-right-width: 0 !important;
    display: block;
    font-weight: normal !important;
}

#tabs-menu .sortPages li span {
    background: url("/system/main/editor/images/edit_page.png") no-repeat scroll 0 4px transparent;
    color: #343434;
    font-size: 10px;
    margin: 0 0 0 13px;	
    display: block;
    line-height: 1.4em;
    outline: medium none;
    padding: 0.3em 1.8em;
    text-decoration: none;	
}

#tabs-menu .sortPages li.ui-sortable-helper {
	background: none repeat-x scroll 0 0 #DDDDDD;
}

#tabs-menu .sortPages li.ui-sortable-placeholder {
    font-size: 1px !important;
	height: 2px !important;
	overflow: hidden;
	border-bottom: 2px solid #FF0000 !important;
	padding: 0px !important; 
}

#tabs-menu .sortPages li ul.menuWrapper {
	border: 0px dotted #AAAAAA !important;
	background-color: transparent !important;
	padding: 0 0 0 12px;
}

/* меню-2 */

#tabs-menu .sortMainPages {
	border: 1px solid #969696;
    border-radius: 3px 3px 3px 3px;
    background: url("/system/main/editor/images/editor_60.png") repeat-x scroll 0 -38px #696969;
    font-family: arial;
    width: auto;
	padding: 0;
	margin-top:5px;
}

#tabs-menu .sortMainPages li {
    background: url("/system/main/editor/images/trsepmenu.png") repeat-x scroll 0 0 transparent;
	margin: 0 0 1px 0px !important;
	padding: 4px 0;
	border: 0 none !important;
    border-bottom: 1px dotted transparent;
    border-top: 1px dotted transparent;
    border-left-width: 0 !important;
    border-right-width: 0 !important;
    display: block;
    font-weight: normal !important;
}

#tabs-menu .sortMainPages li span {
    background: url("/system/main/editor/images/edit_page.png") no-repeat scroll 0 4px transparent;
    color: #343434;
    font-size: 10px;
    margin: 0 0 0 13px;	
    display: block;
    line-height: 1.4em;
    outline: medium none;
    padding: 0.3em 1.8em;
    text-decoration: none;	
}

#tabs-menu .sortMainPages li.ui-sortable-helper {
	background: none repeat-x scroll 0 0 #DDDDDD;
}

#tabs-menu .sortMainPages li.ui-sortable-placeholder {
    font-size: 1px !important;
	height: 2px !important;
	overflow: hidden;
	border-bottom: 2px solid #FF0000 !important;
	padding: 0px !important; 
}

#tabs-menu .sortMainPages li ul.menuWrapper {
	border: 0px dotted #AAAAAA !important;
	background-color: transparent !important;
	padding: 0 0 0 12px;
}

/* меню-3 */

#tabs-menu ul#sortbase {
	border: 1px solid #969696 !important;
    border-radius: 3px 3px 3px 3px;
    background: url("/system/main/editor/images/editor_60.png") repeat-x scroll 0 -38px #696969;
    font-family: arial;
    width: auto;
	padding: 0;
	margin-top:5px;
}

#tabs-menu ul#sortbase li {
    background: url("/system/main/editor/images/trsepmenu.png") repeat-x scroll 0 0 transparent;
	margin: 0 0 1px 0px !important;
	padding: 4px 0;
	border: 0 none !important;
    border-bottom: 1px dotted transparent;
    border-top: 1px dotted transparent;
    border-left-width: 0 !important;
    border-right-width: 0 !important;
    display: block;
    font-weight: normal !important;
}

#tabs-menu ul#sortbase li span {
    background: url("/system/main/editor/images/edit_page.png") no-repeat scroll 0 4px transparent;
    color: #343434;
    font-size: 10px;
    margin: 0 0 0 13px;	
    display: block;
    line-height: 1.4em;
    outline: medium none;
    padding: 0.3em 1.8em;
    text-decoration: none;	
}

#tabs-menu ul#sortbase li.ui-sortable-helper {
  background: none repeat-x scroll 0 0 #DDDDDD;
}

#tabs-menu ul#sortbase li.ui-sortable-placeholder {
    font-size: 1px !important;
	height: 2px !important;
	overflow: hidden;
	border-bottom: 2px solid #FF0000 !important;
	padding: 0px !important; 
}

#tabs-menu ul#sortbase li ul.menuWrapper {
	border: 0px dotted #AAAAAA !important;
	background-color: transparent !important;
	padding: 0 0 0 12px;
}

/* меню-4 */

#pageMenuSort ul#sortbase {
	border: 1px solid #969696;
    border-radius: 3px 3px 3px 3px;
    background: url("/system/main/editor/images/editor_60.png") repeat-x scroll 0 -38px #696969;
    font-family: arial;
    width: auto;
	padding: 0;
	margin-top:5px;
}

#pageMenuSort ul#sortbase li {
    display: block;
    background: url("/system/main/editor/images/trsepmenu.png") repeat-x scroll 0 0 transparent;
	margin: 0 0 1px 0px !important;
	padding: 4px 0;
	border: 0 none !important;
    border-bottom: 1px dotted transparent;
    border-top: 1px dotted transparent;
    border-left-width: 0 !important;
    border-right-width: 0 !important;
    font-weight: normal !important;
}

#pageMenuSort ul#sortbase li span {
    background: url("/system/main/editor/images/edit_page.png") no-repeat scroll 0 4px transparent;
    color: #343434;
    font-size: 10px;
    margin: 0 0 0 13px;	
    display: block;
    line-height: 1.4em;
    outline: medium none;
    padding: 0.3em 1.8em;
    text-decoration: none;
}

#pageMenuSort ul.menuWrapper {
	border: 0px dotted #AAAAAA !important;
	background-color: transparent !important;
	padding: 0 0 0 12px;
}

#pageMenuSort ul.menuWrapper li.ui-sortable-placeholder {
	border-bottom: 2px solid #FF0000 !important;
	padding: 0px !important; 
	background: none repeat-x scroll 0 0 transparent !important;
	font-size: 1px !important;
	height: 1px !important;
}

/* конец меню */




#filePages-menu {
	border: 1px solid #969696;
    border-radius: 3px 3px 3px 3px;
    background: url("/system/main/editor/images/editor_60.png") repeat-x scroll 0 -38px #696969;
    font-family: arial;
    height: 400px;
    left: 85px;
    top: 39px !important;
    width: 400px;
    z-index: 1000;
}

#filePages-menu li {
    background: url("/system/main/editor/images/trsepmenu.png") repeat-x scroll 0 0 transparent;
	margin: 0 0 1px 0;
	border-top: 0 !important;
	border-bottom: 0 !important;
	border: 0 !important;
	padding: 4px 0;
	} 

#filePages-menu li.ui-state-hover {
    background: none repeat-x scroll 0 0 #dddddd;
	border-top: 0 !important;
	border-bottom: 0 !important;
	border: 0 !important;
}

#filePages-menu li.ui-selectmenu-item-selected {
    background: none repeat-x scroll 0 0 #dddddd;
}


#filePages-menu li a {
    background: url("/system/main/editor/images/edit_page.png") no-repeat scroll 0 4px transparent;
    color: #343434;
    font-size: 10px;
    margin: 0 0 0 13px;	
    display: block;
    line-height: 1.4em;
    outline: medium none;
    padding: 0.3em 1.8em;
    text-decoration: none;	
} 

#filePages-menu li a span.ui-selectmenu-item-header {
	font-size: 12px;
	color: #343434;
    font-weight: bold;
	margin-bottom: 0;
} 

.group-header.ui-widget-header,.editallbox {
    background: url("/system/main/editor/images/ose_103.gif") no-repeat scroll 0 50% #F1F8FF;
    border: 1px solid #85A8C6;
    border-radius: 4px 4px 4px 4px;
    height: 23px;
    margin: -26px 0 0 -2px;
    padding: 0 8px;
    position: absolute;
    visibility: visible;
}

.ui-widget-header { cursor: move; }
.se-edit-record,.se-edit-content { cursor: pointer; }
.se-add-record { visibility: hidden; cursor: pointer; }
.itemRecordHeader { display:none; visibility: hidden; border: 1 solid #404040; cursor: move; }
#parttitle { border: 1px solid #FF0000; }

.groupItem {
	border-radius: 3px 3px 3px 3px;
    margin: 0 0 15px;
    /*box-shadow: 0 0 0 3px #CCCCCC;*/
	/*border: 3px solid #404040;*/
}	

.groupItem .ui-widget-header {
	visibility: hidden;
}

.ui-dialog {
	background: url("/system/main/editor/images/editor_60.png") repeat-x scroll 0 -3px #696969;
	border-radius: 8px 8px 8px 8px;
    box-shadow: 0 0 0 3px #CCCCCC; 
	border: 3px solid #404040;
}

#tabs-part {
	position: relative;
    top: 1px;
	padding: 0;
	margin: 0;
}

#tabs-menu, #tabs-rec {
	position: relative;
	padding: 0;
	margin: 0;
}

#tabs-part .ui-widget-header, #tabs-menu .ui-widget-header, #tabs-rec .ui-widget-header {
	background: none repeat scroll 0 0 transparent;
}


.ui-tabs {
    top: -5px;
}

.ui-tabs, .ui-dialog, #dialog-modal-part, .ui-dialog-titlebar {
    padding-top: 0 !important;
	margin-top: 0 !important;
}

.ui-dialog .ui-dialog-title {
    color: #D9D9D9; 
    position: absolute;
    top: 7px;
	float: left;
    margin: 0.1em 16px 0.1em 0;
}

.ui-tabs .ui-tabs-nav {
    padding: 0 !important;
	margin-top: 5px;
	line-height: 1;
	vertical-align: bottom;
}

.ui-tabs .ui-tabs-nav li {
    margin-top: 0 !important;
	vertical-align: bottom;
}

.ui-tabs .ui-tabs-nav li a {
  padding: 0.3em 1em;
  outline: none;
}

.ui-tabs .ui-tabs-nav li.ui-tabs-selected {
    background: none repeat scroll 0 0 #4C4C4C;
    margin-bottom: 0;
    padding-bottom: 0;
    margin: 0;
    padding: 0; 	
	border:0;
	display:inline-block;
	float:left;
	top: -3px;
}

.ui-tabs .ui-tabs-nav li.ui-tabs-selected a {
    height: 23px;
    margin: 0;
    top: 4px;	
}


#ui-dialog-title-dialog-modal-part { display: none; }

.ui-dialog .ui-dialog-titlebar { 
    background: none repeat scroll 0 0 transparent;
    right: 0;
    width: auto;
	height: 24px;
}


.ui-dialog .ui-dialog-titlebar-close {
    position: absolute;
    right: 4px;
	float: right;
	top: 15px;
	cursor: pointer;
    margin-right: 3px;
	background-image: url("/system/main/editor/images/editor_65.png");
    background-position: 0 50%;
    height: 19px;
    width: 19px;
    background-repeat: no-repeat;
    display: block;
    overflow: hidden;
	padding: 0;
	z-index: 11;
}




.ui-dialog .ui-icon-closethick {
	background-image: url("/system/main/editor/images/editor_65.png") !important;
}

.ui-dialog .ui-dialog-titlebar-close.ui-state-hover {
	border:0;
}

.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited {
    color: #CCCCCC;
    text-decoration: none;
}

.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
	background: none repeat scroll 0 0 transparent;
    border: 0;
    color: #CCCCCC;
    font-weight: normal;
	font-size: 13px;
}

.ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited {
    color: #ffffff;
	font-weight: bold;
    text-decoration: none;
}

.ui-state-active a {
    background: url("/system/main/editor/images/editor_76.png") no-repeat scroll 50% 100% transparent;
    height: 21px;
    margin-top: -4px;
    padding-bottom: 14px;
    position: relative;
    top: 4px;
}

.ui-dialog .ui-dialog-buttonpane button {
    background: url("/system/main/editor/images/editor_but_87.png") repeat-x scroll 0 0 #a6a6a6;
    color: #403F3F;
	font-size: 11px;
	font-family: arial;
	 border-radius: 3px 3px 3px 3px;
}

.ui-button-text-only .ui-button-text {
    padding: 3px 9px;
    display: block;
    line-height: 1.4;
}
	
	
	
.EdbuttonSend {
    display: inline-block;
    position: relative;
    background: url("/system/main/editor/images/editor_but_87.png") repeat-x scroll 0 0 #A6A6A6;
    border-radius: 3px 3px 3px 3px;
    font-family: arial;
    font-size: 11px;
    font-weight: normal;
    cursor: pointer;
    margin: 0.5em 0.4em 0.5em 0;
    border: 0 none;
    text-align: center;
    text-decoration: none !important;
    line-height: 1.4;
    padding: 3px 9px;
    color: #403F3F;
}
	

.editorlink {
    display: inline-block;
    float: left;
    height: 32px;
    margin: 10px 0 0 0;
    vertical-align: middle;
}

.editorhtitle { margin: 7px 0px 0 0; display: inline-block; white-space: nowrap; }
.editorbtn { float:left; margin: 0px; }

.se-edit-content-head {
    display: inline-block;
    margin: 0 0 0 28px;
    vertical-align: middle;
	font: 11px tahoma;
}


.groupWrapper .cico-a {
    margin-bottom: 0px; /*27px*/
	cursor: pointer;
}

.cico-t {
	margin: 0 3px;
}

.se-edit-content-head .cico-e {
    position: relative;
    top: 3px;
	margin-left: 4px;
}


.bgcshadow {
    border-radius: 6px;
    margin: 0;
	border: 3px solid #404040;
}

.group-header {
    height: 20px;
    padding-bottom: 4px;
    padding-left: 0.2em;
    padding-top: 2px;
}

.group-header .ui-icon { display: none; float: right; }
.group-content { padding: 0.4em; }

.ui-sortable-placeholder { border: 1px dashed #000000; visibility: visible !important; height: 50px !important; max-height:20px; }
.ui-sortable-placeholder:hover { border: 1px solid #ff0000; }
.ui-sortable-placeholder * { visibility: hidden; }
	
.groupMenu1 .itemMenu
{
	line-height: 20px;
	background-color: #6aafff;
	color: #000;
	padding: 0 10px;
	cursor: move;
	font-weight: bold;
	font-size: 12px;
	height: 20px;
	position: relative;
}

.groupItem .group-header1
{
	line-height: 28px;
	background-color: #6aafff;
	border-top: 2px solid #3D4749;
	color: #000;
	padding: 0 10px;
	cursor: move;
	font-weight: bold;
	font-size: 16px;
	height: 28px;
	position: relative;
}
.groupItem .group-header1 a
{
	float: right;
	right: 10px;
	color: #ffffff;
	top: 0px;
	font-weight: normal;
	font-size: 11px;
	text-decoration: none;
}
.editorhtitle { 
	display: none; 
}

#semttable td {
	width:30px;
}

</style>

<div style="width: 100%; height: 63px; position: static;"></div>
<div style="width: 100%; background-repeat: repeat-x; background-image: url('/system/main/editor/images/editor_bg.png'); height: 63px; position: fixed; top: 0pt; left: 0pt; overflow: hidden; white-space: nowrap; z-index: 999;">

<table cellspacing="0" cellpadding="0" border="0" id="semttable" width="100%">
<tr valign="top" align="left">

<td>
<div style="float: left; height: 42px; max-width: 100px; min-width: 30px; width: 1%;"></div>
</td>

<td>
<?php if(intval($_SESSION['siteediteditor'])): ?>
	<a href="?editor_on_off&<?php echo time(); ?>" class="editorlink" style="margin-right:16px;" id="editor_view"><img border="0" title="Просмотр сайта (убрать иконки управления)" src="/system/main/editor/images/editor_view.png" class="editorbtn"><!--span class="editorhtitle">Просмотр</span--></a>
<?php else: ?>
	<a href="?editor_on_off&<?php echo time(); ?>" class="editorlink" style="margin-right:16px;" id="editor_view"><img border="0" title="Режим редактора (показать иконки)" src="/system/main/editor/images/editor_view_off.png" class="editorbtn"><!--span class="editorhtitle">Просмотр</span--></a>
<?php endif; ?>
</td>

<td width="180px">
<span id="seselpages">
	<select name="filePages" id="filePages" class="customicons">
<?php
	$__data = seData::getInstance();
	$pageslist = array();
	foreach($__data->pages as $page){
		$selected = (strval($page['name']) == $__data->getPageName()) ? 'selected':'';
		//$pageslist[] = array('name'=>strval($page['name']), 'title'=>$page->title);
		echo '<option value="'.strval($page['name']).'" class="podcast" '.$selected.'>'.$page->title.'|'.strval($page['name']).'</option>';
}
?>
</select></span>
</td>	



<td>	
<a href="javascript:getEditWindow('editpage_addcontent');" class="editorlink" id="editor_addpage"><img border="0" title="Добавить страницу" src="/system/main/editor/images/editor_addpage.png" class="editorbtn"><span class="editorhtitle">Добавить страницу</span></a>
</td>

<td>
<a href="javascript:getEditWindow('editpage_content');" class="editorlink" id="editor_editpage"><img border="0" title="Редактировать страницу" src="/system/main/editor/images/editor_editpage.png" class="editorbtn"><span class="editorhtitle">Изменить страницу</span></a>
</td>
<td>
<a href="javascript:getEditWindow('editpage_header');" class="editorlink" id="editpage_header"><img border="0" title="META-теги для поисковых систем" src="/system/main/editor/images/editor_seo.png" class="editorbtn"><span class="editorhtitle">SEO</span></a>
</td>

<td>
<a href="javascript:getEditWindow('editpage_delete', '<?php echo $__data->getPageName() ?>');" class="editorlink" style="margin-right:22px;" id="editor_delpage"><img border="0" title="Удалить страницу" src="/system/main/editor/images/editor_delpage.png" class="editorbtn"><span class="editorhtitle">SEO</span></a>
</td>

<!-- td>
<a href="javascript:getEditWindow('editpage_css');" class="editorlink" style="margin-right:5px;" id="editor_css"><img border="0" title="CSS (стили)" src="/system/main/editor/images/editor_css.png" class="editorbtn"><span class="editorhtitle">CSS</span></a>
</td -->
<td>
<a href="javascript:getEditWindow('editpage_contacts');" class="editorlink" style="margin-right:20px;" id="editor_contacts"><img border="0" title="Контактная информация" src="/system/main/editor/images/editor_vars.png" class="editorbtn"><span class="editorhtitle">Контактная информация</span></a>
</td>

<!-- td>
<a href="javascript:getEditWindow('editpage_option');" class="editorlink" style="margin-right:3px;" id="editor_options"><img border="0" title="Настройки проекта" src="/system/main/editor/images/editor_options.png" class="editorbtn"><span class="editorhtitle">CSS</span></a>
</td>
<td>
<a href="javascript:getEditWindow('editpage_color');" class="editorlink" style="margin-right:16px;" id="editor_color"><img border="0" title="Цвет блоков управления сайтом" src="/system/main/editor/images/editor_color.png" class="editorbtn"><span class="editorhtitle">CSS</span></a>
</td-->

<td>
<a href="javascript:getEditWindow('editpage_menu');" class="editorlink" id="editor_menu" style="margin-right:20px;"><img border="0" title="Редактор меню" src="/system/main/editor/images/editor_menu.png" class="editorbtn"><span class="editorhtitle">Редактор меню</span></a>
</td>

<td align="right" style="width:auto;">
<a href="javascript:getEditWindow('editpage_save');" class="editorlink" style="margin-right:7px;margin-top: 11px;float: right;" id="editor_save"><img border="0" title="Сохранить изменения на сайте" src="/system/main/editor/images/editor_public.png" class="editorbtn"><span class="editorhtitle">SEO</span></a>
</td>
<td style="width:auto;">
<a href="javascript:getEditWindow('editpage_cancel');" class="editorlink" style="margin-left:-3px; margin-right:5px;margin-top:11px;" id="editor_cancel"><img border="0" title="Отменить редактирование сайта" src="/system/main/editor/images/editor_cancel_2.png" class="editorbtn"><span class="editorhtitle">SEO</span></a>
</td>

<td align="right">
<a id="editor_se" class="editorlink" style="text-decoration:none; margin-right:10px;width:84px;" href="http://siteedit.ru"><img border="0" class="editorbtn" src="/system/main/editor/images/editor_se.png" title="SiteEdit - система управления сайтом"><span style="display: inline-block; left:-5px; position: relative; text-decoration: underline; top:8px; color:#093dff; font-family:tahoma; font-size:11px;" class="editorhtitle1">siteedit.ru</span></a>
</td>
<td>	
<a href="?logout" class="editorlink" style="margin: 8px 20px 0px 0px;" id="editor_exit"><img border="0" title="Выход" src="/system/main/editor/images/editor_exit.png" class="editorbtn"><span class="editorhtitle">Выход</span></a>
</td>

</tr>
</table>
<div id="selivehint"><span id="sepastlivehint"></span></div>
</div>



<script type="text/javascript">			
			var addressFormatting = function(text){
			var newText = text;
			var findreps = [
				{find:/^([^\|]+)\|/g, rep: '<span class="ui-selectmenu-item-header">$1</span>'}
			];
			
			for(var i in findreps){
				newText = newText.replace(findreps[i].find, findreps[i].rep);
			}
			return newText;
		}

		$('select#filePages').change(function(){
			document.location.href='/'+$(this).val()+'/';
		});
		$('select#filePages').selectmenu({
				style:'dropdown',
				menuWidth: 400,
				maxHeight: 400,
				format: addressFormatting			
		});



// $(document).ready(function() { }); 


  var elem = $("#semttable .editorlink");
   
  elem.live("mouseenter", function(ev)
   {
			var objlt = $("img.editorbtn", this);
	var title = objlt.attr("title");
				objlt.attr("title", "");
   var hint = $("#sepastlivehint");
	   hint.hide("fast");
	   hint.html(title);
	   
   var left = Math.floor($(this).offset().left + $(this).width()*0.5);
   var leftsecond = Math.floor(left - hint.width()*0.5);
	if(leftsecond <= 0)
	   leftsecond = 20;
 
	   hint.css({"left": left + "px"}).show().animate({"left": leftsecond + "px"}, "slow");	   
 
	elem.live("mouseleave", function()
	  {
	  hint.empty();
	  objlt.attr("title", title);
	  });
   });
 

		
</script>