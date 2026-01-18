<?php
function module_mrekv_company($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mrekv_company';
 else $__MDL_URL = 'modules/mrekv_company';
 $__MDL_ROOT = dirname(__FILE__).'/mrekv_company';
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
 if (isRequest('referer')){
    $refer = getRequest('referer', 3);
    if ($refer == ''){
         $refer = $_SERVER['HTTP_REFERER'];
    } 
 } else {
     $refer = '';
 }
 $warning_message="";
 if(seUserId()!=0){
     $dis = '';
 } else {
     $dis = 'disabled';
     $warning_message=$section->language->lang012;
 }
 $table = new seTable();
 
 $lang = se_getlang();
 
 //se_db_query("alter table user_rekv add updated_at timestamp");
 if ((isset($_SESSION['ID_AUTH'])) && ($_SESSION['ID_AUTH']>0)){
    $id_author = $_SESSION['ID_AUTH'];
 } else {
     $id_author = seUserId();//$ID_AUTHOR;
 }
 //se_db_connect();
 
 if (isRequest('GoToRekv')) { 
 /*
     if (se_db_is_item("user_urid","`id_author`='$id_author'"))
         $act='update';
     else 
         $act='insert';
      $err=(!se_db_perform("user_urid",
  array('id_author'=>$id_author,
        'company' =>$_plant,
        'director'=>$_director,
        'tel'=>$_tel,
        'fax'=>$_fax,
        'uradres'=>$_uradres),$act,"`id_author`='$id_author'"));
 //*/
     $table->from("user_urid","uu");
     $table->find($id_author);
     $table->id = $id_author;  
     $table->company   = getRequest('plant', 3);//$_plant;
     $table->director  = getRequest('director', 3);//$_director;
     $table->tel       = getRequest('tel',3);//$_tel;
     $table->uradres   = getRequest('uradres', 3);
     $table->fax       = getRequest('fax', 3);//$_fax; 
     $table->save();
     $err = !($table->find($id_author));
 //*
     if (!$err) {
         $table->from("user_rekv_type","urt");
         $table->select();
         $table->addWhere("`lang`='?'",$lang);
         $query = $table->getList();
        
         if(!empty($query)) 
             foreach($query as $item) {//while ($item=se_db_fetch_array($query)) {
                 $code=$item['code'];
                 $rekv = new seTable("user_rekv","uu");
                 $rekv->addWhere("rekv_code='?'",$code);
                 $rekv->andWhere("id_author='?'",$id_author);
                 $rekv->andWhere("lang='?'",$lang);
                 $rekv->fetchOne();
                 $rekv->lang      = $lang;
                 $rekv->id_author = $id_author;
                 $rekv->rekv_code = $code;
                 $rekv->value     = getRequest($code, 3);
                 $rekv->save();
                 unset($rekv);
             }
    }
    if ($err) $warning_message=$section->language->lang010;
    else $warning_message=$section->language->lang011;
 };
 
 unset($table);

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

