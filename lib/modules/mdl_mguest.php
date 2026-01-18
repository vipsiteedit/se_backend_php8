<?php
function module_mguest($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mguest';
 else $__MDL_URL = 'modules/mguest';
 $__MDL_ROOT = dirname(__FILE__).'/mguest';
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
 // echo 898;
 isRequest('edit');
 $thispagelink = seMultiDir() . "/$_page/";
 
 $userAccess = seUserGroup();
 $dateerror = "";
 $__request = array();
 $__request = getRequestList($__request, 'usrmail,usrname,note', VAR_NOTAGS); 
 $remaddr = (!empty($_SERVER['HTTP_X_REAL_IP'])) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
 
 //  для капчи
 $hash = $__data->getPageName().strval($section->id);
 $hash = md5($hash);
 
 $month_name = array (" ", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
 $adminmail = $section->parametrs->param1;
 //coздаем тут всё
 if (!file_exists("data/"))
 {
     mkdir("data",0740);
 }
 
 $filename = "data/m".$_page."_".$razdel.".dat";
 $filenameip = "data/m".$_page."_".$razdel.".ip.dat";
 $sessfile = "data/m".$_page."_".$razdel.".sess.dat";
 $oldfilename = "data/{$_page}_{$razdel}.dat";
 $_time = time();
 //перекодируем старую гостевую в новую
 if ((!file_exists(getcwd().'/'.$filename) || !filesize(getcwd().'/'.$filename)) 
     && file_exists(getcwd().'/'.$oldfilename)) {
    to_utf8($oldfilename, $filename);
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
 //BeginSubPageedit
 $__module_subpage['edit']['admin'] = "";
 $__module_subpage['edit']['group'] = "3";
 $__module_subpage['edit']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='edit' && file_exists($__MDL_ROOT . "/tpl/subpage_edit.tpl")){
	include $__MDL_ROOT . "/php/subpage_edit.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_edit");
	$__module_subpage['edit']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageedit
 //BeginSubPageblock
 $__module_subpage['block']['admin'] = "";
 $__module_subpage['block']['group'] = "3";
 $__module_subpage['block']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='block' && file_exists($__MDL_ROOT . "/tpl/subpage_block.tpl")){
	include $__MDL_ROOT . "/php/subpage_block.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_block");
	$__module_subpage['block']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageblock
 //BeginSubPagenoconfirm
 $__module_subpage['noconfirm']['admin'] = "";
 $__module_subpage['noconfirm']['group'] = 0;
 $__module_subpage['noconfirm']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='noconfirm' && file_exists($__MDL_ROOT . "/tpl/subpage_noconfirm.tpl")){
	include $__MDL_ROOT . "/php/subpage_noconfirm.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_noconfirm");
	$__module_subpage['noconfirm']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagenoconfirm
 //BeginSubPagelicense
 $__module_subpage['license']['admin'] = "";
 $__module_subpage['license']['group'] = 0;
 $__module_subpage['license']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='license' && file_exists($__MDL_ROOT . "/tpl/subpage_license.tpl")){
	include $__MDL_ROOT . "/php/subpage_license.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_license");
	$__module_subpage['license']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPagelicense
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}

