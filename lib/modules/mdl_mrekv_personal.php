<?php
function module_mrekv_personal($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mrekv_personal';
 else $__MDL_URL = 'modules/mrekv_personal';
 $__MDL_ROOT = dirname(__FILE__).'/mrekv_personal';
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
  $errorlabel=1;
  if ((isset($_SESSION['ID_AUTH'])) && ($_SESSION['ID_AUTH']>0)) 
  {
     $id_author = $_SESSION['ID_AUTH'];
  } else 
  {
     $id_author = seuserid();
  }   
 
 $persons = new sePerson();
 $persons->find($id_author);
 if (isset($_POST['GoToPers'])){
     if ($id_author == 0) {
         $rekv_message = $section->language->lang030; 
     }else{
         $b_year     = getRequest("b_year", 1);
         $b_month    = getRequest("b_month", 1);
         $b_day      = getRequest("b_day", 1);
 
        $errorlabel = 1; 
        $fl = true;    //флаг того, что дата заполнялась
        $ok_mess = ''; //данные изменены успешно
        if((empty($b_year))&&(empty($b_month))&&(empty($b_day))){
             $fl = false;
        }
        
        if (!(($b_day<32)&&($b_day>0)) && $fl) {
         $b_day='';
         $errorlabel=0;
         $rekv_message=$section->language->lang003;
        }
        
        if (!preg_match("/^\d+$/i",$b_day) && $fl && ($rekv_message == '')) {
         $b_day='';
         $errorlabel=0;
         $rekv_message=$section->language->lang003;
        }
        
         if (!preg_match("/^\d+$/i",$b_month ) && $fl && ($rekv_message == '')) {
         $b_month ='';
         $errorlabel=0;
         $rekv_message=$section->language->lang002;
        }
        
       if (!(($b_month<13)&&($b_month>0)) && $fl && ($rekv_message == '')) {
         $b_month ='';
         $errorlabel=0;
         $rekv_message=$section->language->lang002;
        }
        
        
       if (!empty($b_year) && $fl && ($rekv_message == ''))
         while (strlen($b_year)<4) {
             $b_year = "19".$b_year;
         }
         
        if (!preg_match("/^\d{4}$/i",$b_year) && $fl && ($rekv_message == '')) {
         $b_year='';
         $errorlabel=0;
         $rekv_message=$section->language->lang001;
        }
               
        if (!(($b_year<date('Y'))&&($b_year>1900)) && $fl && ($rekv_message == '')) {
         $b_year='';
         $errorlabel=0;
         $rekv_message=$section->language->lang001;
        }
         
        if($fl){
         if (!empty($b_month))
             while (strlen($b_month)<2)$b_month="0".$b_month;
         if (!empty($b_day))
             while (strlen($b_day)<2) $b_day="0".$b_day;
         if (!empty($b_year))
             $b_date = $b_year . '-' . $b_month . '-' . $b_day;
         } else $b_date = NULL; 
          
         
         $persons->last_name = getRequest("last_name",3);
         $persons->first_name = getRequest("first_name",3);
         $persons->sec_name =  getRequest("sec_name",3);
         $persons->birth_date = $b_date;
         $persons->email= getRequest("email", 3);
         $persons->doc_ser = getRequest("doc_ser");
         $persons->doc_num = getRequest("doc_num");
         $persons->doc_registr = getRequest("doc_registr",3);
         $persons->post_index = getRequest("post_index");
         $persons->country_id = getRequest("country",1);
         $persons->state_id = getRequest("state",1);
         $persons->town_id = getRequest("city", 1);
         $persons->addr = getRequest("addr", 3);
         $persons->phone = getRequest("phone",3);
         $persons->icq = getRequest("icq");
         $persons->sex = getRequest("sex");
         
         if($rekv_message == ''){
             if (($persons->save()) && ($errorlabel==1))
                 $ok_mess = $section->language->lang022;
         }     
     }
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
 //BeginSubPage1
 $__module_subpage['1']['admin'] = "";
 $__module_subpage['1']['group'] = 0;
 $__module_subpage['1']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='1' && file_exists($__MDL_ROOT . "/tpl/subpage_1.tpl")){
	include $__MDL_ROOT . "/php/subpage_1.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_1");
	$__module_subpage['1']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPage1
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}