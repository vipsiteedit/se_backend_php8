<?php
//BeginLib
//EndLib
function module_bbs($razdel, $section = null)
{
   getRequestList($__request, 'page,sub');
   getRequestList($__request, 'razdel', 1);
   $_page = $__request['page'];
   $_razdel = $__request['razdel'];
   $_sub = $__request['sub'];
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "30";
if (empty($section->params[1]->value)) $section->params[1]->value = "1";
if (empty($section->params[2]->value)) $section->params[2]->value = "125";
if (empty($section->params[3]->value)) $section->params[3]->value = "Добавить";
if (empty($section->params[4]->value)) $section->params[4]->value = "100";
if (empty($section->params[5]->value)) $section->params[5]->value = "300";
if (empty($section->params[6]->value)) $section->params[6]->value = "&nbsp;»&nbsp;";
if (empty($section->params[7]->value)) $section->params[7]->value = "Изменить";
if (empty($section->params[8]->value)) $section->params[8]->value = "Выберите город:";
if (empty($section->params[9]->value)) $section->params[9]->value = "Выбрать";
if (empty($section->params[10]->value)) $section->params[10]->value = "Вернуться назад";
if (empty($section->params[11]->value)) $section->params[11]->value = "дата:";
if (empty($section->params[12]->value)) $section->params[12]->value = "имя:";
if (empty($section->params[13]->value)) $section->params[13]->value = "город:";
if (empty($section->params[14]->value)) $section->params[14]->value = "e-mail:";
if (empty($section->params[15]->value)) $section->params[15]->value = "url:";
if (empty($section->params[16]->value)) $section->params[16]->value = "Добавить объявление:";
if (empty($section->params[17]->value)) $section->params[17]->value = "Изменить объявление:";
if (empty($section->params[18]->value)) $section->params[18]->value = "Имя:";
if (empty($section->params[19]->value)) $section->params[19]->value = "Город:";
if (empty($section->params[20]->value)) $section->params[20]->value = "EMail:";
if (empty($section->params[21]->value)) $section->params[21]->value = "URL:";
if (empty($section->params[22]->value)) $section->params[22]->value = "Краткая информация:";
if (empty($section->params[23]->value)) $section->params[23]->value = "Текст объявления:";
if (empty($section->params[24]->value)) $section->params[24]->value = "Загрузить рисунок:";
if (empty($section->params[25]->value)) $section->params[25]->value = "Удалить запись?";
if (empty($section->params[26]->value)) $section->params[26]->value = "Добавить";
if (empty($section->params[27]->value)) $section->params[27]->value = "Сохранить";
if (empty($section->params[28]->value)) $section->params[28]->value = "365";
if (empty($section->params[29]->value)) $section->params[29]->value = "Не заполнено поле Имя";
if (empty($section->params[30]->value)) $section->params[30]->value = "Не заполнено поле Город";
if (empty($section->params[31]->value)) $section->params[31]->value = "Не корректный e-mail";
if (empty($section->params[32]->value)) $section->params[32]->value = "Не заполнено поле Краткая информация";
if (empty($section->params[33]->value)) $section->params[33]->value = "Не заполнено поле Текст объявления";
if (empty($section->params[34]->value)) $section->params[34]->value = "Телефон";
if (empty($section->params[35]->value)) $section->params[35]->value = "Не верно введен телефон";
if (empty($section->params[36]->value)) $section->params[36]->value = "телефон:";
global $selector, $steplist, $_page, $obj, $object_extern, $_object, $_razdel,$SESSION_VARS;
global $obj_show, $_GoTo, $_sub, $errortext, $_phone, $_name, $_town, $_email, $_url, $_short,$titlepage;
global $_text, $name, $town, $email, $url, $short, $text, $_part, $_townselected, $picture, $fulltext;
global $raz_obj, $_id, $id, $_del, $BBS_MOD,$_img,$obj_alt,$bbseditlink, $ID_AUTHOR,$_desc, $MANYPAGE;
global $id_author, $GR_AUTHOR, $group_num, $bbs_add_link;
$_object = getRequest('object', 1);
$arr = se_db_query("SHOW FIELDS FROM `bbs` WHERE field = 'phone'"); 
$group_num = intval($section->params[1]->value);
if (se_db_num_rows($arr) == 0)
{
    se_db_query("ALTER TABLE `bbs` ADD `phone` Char(15)");
}
if (!isset($_desc)) $_desc = $_page;
$_desc=se_db_input($_desc);
$nObj=$section->params[0]->value;
$nChars=$section->params[2]->value;
$pagen = $section->params[0]->value;
$edkey=$section->params[6]->value;
$edtitle=$section->params[7]->value;
$townselected=substr(htmlspecialchars($_townselected, ENT_QUOTES), 0, 30);
if (!empty($townselected)) $townSQL="AND town='$townselected'"; else $townSQL="";
//BeginSubPages
if (($razdel != $__request['razdel']) || empty($__request['sub'])){
//BeginRazdel
//если объявления резрещено создавать, то создаем
if ($section->params[28]->value>0)
{
    $dates = date('Y-m-d',time()-$section->params[28]->value*86400);
    se_db_delete("bbs","`date`<'$dates'");
};
if ($GR_AUTHOR >= $group_num) 
{
    $bbs_add_link = "<a id=\"newMsg\" title=\"{$section->params[3]->value}\" href=\"[@subpage1]\">{$section->params[3]->value}</a>";
}
//Выводим объекты
 $selector='<OPTION value=\"\"> ---- </OPTION>\n';
//выбираем города из базы
$rt=se_db_query("SELECT SQL_CACHE SQL_SMALL_RESULT  DISTINCT `town` FROM `bbs` WHERE `page`='$_page' ORDER BY `town`;");
while ($townlist=se_db_fetch_array($rt)) {
  if ($townlist['town']==$townselected) $selector.='<OPTION value="'.$townlist['town'].'" selected>'.$townlist['town']."</OPTION>\n";
  else $selector.="<OPTION value=\"".$townlist['town']."\"".">".$townlist['town']."</OPTION>\n";
}
    if (!empty($_GET['sheet'])) $sheet = htmlspecialchars($_GET['sheet'], ENT_QUOTES); else $sheet = "1";
    if (intval($pagen)==0) {
        $limitpage = "";   $limit = " ";
    }else {
        if ((!empty($sheet))&&($sheet > 1)) $limitpage = "LIMIT ".($pagen*$sheet-$pagen).",".$pagen;
        else $limitpage = "LIMIT ".$pagen;
    }
 $sql="SELECT SQL_CALC_FOUND_ROWS `id`, LEFT(`text`,600) as `text`, `short`, `img`, `date`, `name`,`town`,`email`, `url` , `id_author`, `phone`
  FROM bbs
  WHERE `page`='$_desc' $townSQL
  ORDER BY id DESC $limitpage;";
  if (function_exists('se_db_found_rows')) {
    $ro = se_db_query($sql,60);
    $cnrow=se_db_found_rows($ro);
  } else {
    $ro = se_db_query($sql);
    list($cnrow) = se_db_fetch_row(se_db_query("SELECT FOUND_ROWS()"));
  }
    if (intval($pagen)>0)
      $MANYPAGE = str_replace("%alt%","{$section->params[21]->value}",se_divpages($cnrow, $pagen));
 $width_prew="{$section->params[4]->value}"; if ($width_prew==0) $width_prew=100;
//перемещам указатель на нужную запись
$dat=array();
$i=0;
while ($msg = se_db_fetch_array($ro)) {
  if ((($msg['id_author'] == $ID_AUTHOR) or ($GR_AUTHOR == 3)) && (!isset($_object)))
    $bbseditlink="<a id=editbbs style=\"text-decoration:none;\" title=\"$edtitle\" href=\"[@subpage2]?id=$msg[id]\">$edkey</a>";
  else $bbseditlink="";
  $dat[$i] = $msg;
  $dat[$i]['date'] = date('d.m.Y', strtotime($msg['date']));
  $dat[$i]['title'] = $bbseditlink.$msg['short'];
  if (!empty($msg['img'])) {
    list($imgname,$imgext)=explode(".",$msg['img']);
    $imgprev="/images/bbs/".$imgname.".".$imgext;
    $dat[$i]['image']='<IMG alt="'.se_db_output($msg['short']).'" border="0" class="objectImage" src="'.$imgprev.'" width="'.$width_prew.'">';
  } else $dat[$i]['image'] = '';
  $dat[$i]['note'] = substr(nl2br($msg['text']), 0, $nChars);
  if (strlen($msg['text']) > $nChars) $dat[$i]['note'] .= "...";
  $dat[$i]['text'] = nl2br($msg['text']);
  $i++;
}
  se_show_fields($section, $dat);
//EndRazdel
}
else{
if(($razdel == $__request['razdel']) && !empty($__request['sub']) && ($__request['sub']==1)){
//BeginSubPage1
if ($GR_AUTHOR < $group_num) return;   
    if (isset($_GoTo)) {
      require_once("lib/lib_images.php"); //Присоединяем графическую библиотеку
      $resmax = se_db_query("SELECT SQL_SMALL_RESULT max(id) AS obid FROM bbs");
      $rmax   = mysql_fetch_array($resmax);
      $maxid  = @$rmax['obid']+1;
      $width_prew = "{$section->params[4]->value}"; 
      if ($width_prew==0)
      {
      $width_prew=100;
      }
      $width = "{$section->params[5]->value}"; if ($width==0) $width=350;
      $img = se_set_image_prev($width_prew,$width,"bbs",$maxid);
//собираем данные из формы
      $name = substr(htmlspecialchars($_name, ENT_QUOTES), 0, 50);
      $town = substr(htmlspecialchars($_town, ENT_QUOTES), 0, 30);
      $email = substr(htmlspecialchars($_email, ENT_QUOTES), 0, 30);
      $url = substr(htmlspecialchars($_url, ENT_QUOTES), 0, 50);
      $short = substr(htmlspecialchars($_short, ENT_QUOTES), 0, 150);
      $text = substr(htmlspecialchars($_text, ENT_QUOTES), 0, 2000);
      $phone = substr(htmlspecialchars($_phone, ENT_QUOTES), 0, 15); 
//проверка на инъекции
      $name = se_db_input(se_db_output($name));
      $town = se_db_input(se_db_output($town));
      $email = se_db_input(se_db_output($email));
      $url = se_db_input(se_db_output($url));
      $short = se_db_input(se_db_output($short));
      $text = se_db_input(se_db_output($text));
      $phone = se_db_input(se_db_output($phone));
      //Предполагаем, что все введеные данные корректны
      $flag=true;
      //обрабатываем name
      if (empty($name)) {
        $flag=false;
        $errortext="{$section->params[29]->value}";
      }
      //обрабатываем town
      if (empty($town) && $flag) {
        $flag=false;
        $errortext="{$section->params[30]->value}";
      }
      //обрабатываем телефон
      if (isset($phone)) 
      {
       if (!is_numeric($phone))
       {
       $errortext="{$section->params[35]->value}";
       }
      }
      //обрабатываем e-mail
      if ($flag && !preg_match("/[0-9a-zA-Z]([0-9a-zA-Z\-\_]+\.)*[0-9a-zA-Z]*@[a-zA-Z0-9]*([0-9a-zA-Z\-\_]+\.)*[0-9a-zA-Z]+\.[a-zA-Z]{2,6}$/i", $email) && !empty($email)) {
        $flag=false;
        $errortext="{$section->params[31]->value}";
      }
      //обрабатываем url
      if (substr($url, 0, 7)=="http://" && !empty($url)) $url=substr($url, 7);
      //обрабатываем краткий текст
      if ($flag && empty($short)) {
        $flag=false;
        $errortext="{$section->params[32]->value}";
      }
      //обрабатываем текст
      if ($flag && empty($text)) {
        $flag=false;
        $errortext="{$section->params[33]->value}";
      }
      if (!$flag) {  //если есть ошибки, то отправляем в космос
        $_razdel=$razdel;
        $_sub=1;
      }
      else {
        //добавляем запись
        $date=date("Y-m-d");
        se_db_query(
        "INSERT INTO `bbs`(id_author,page, date, name, town, email, url, short, text, img, phone)
        VALUES('$ID_AUTHOR','$_desc', '$date', '$name', '$town', '$email', '$url', '$short', '$text', '$img', '$phone');"
        );
         Header("Location: http://".$_SERVER['HTTP_HOST']."/".$_page."?".time());
      }
    }
//EndSubPage1
} else
if(($razdel == $__request['razdel']) && !empty($__request['sub']) && ($__request['sub']==2)){
//BeginSubPage2
if ($GR_AUTHOR < $group_num) return;   
  $id=intval($_id);
  if (isset($_GoTo)) { //изменяем запись
   require_once("lib/lib_images.php"); //Присоединяем графическую библиотеку
   $width_prew="{$section->params[4]->value}"; if ($width_prew==0) $width_prew=100;
   $width="{$section->params[5]->value}"; if ($width==0) $width=350;
    if ((empty($_img)) or (is_uploaded_file($_FILES['userfile']['tmp_name']))){ 
      $img=se_set_image_prev($width_prew,$width,"bbs",$id);
    } else $img=$_img;
    $del=htmlspecialchars($_del, ENT_QUOTES);
    if (isset($del) && $del) { //удаляем запись
      se_db_query("DELETE QUICK FROM bbs WHERE id='$id';");
     Header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."?".time());
    }
    $name=substr(htmlspecialchars($_name, ENT_QUOTES), 0, 50);
    $town=substr(htmlspecialchars($_town, ENT_QUOTES), 0, 30);
    $email=substr(htmlspecialchars($_email, ENT_QUOTES), 0, 30);
    $url=substr(htmlspecialchars($_url, ENT_QUOTES), 0, 50);
    $short=substr(htmlspecialchars($_short, ENT_QUOTES), 0, 150);
    $text=substr(htmlspecialchars($_text, ENT_QUOTES), 0, 2000);
    $phone=substr(htmlspecialchars($_phone, ENT_QUOTES), 0, 15);
//проверка на инъекции
      $name=se_db_input(se_db_output($name));
      $town=se_db_input(se_db_output($town));
      $email=se_db_input(se_db_output($email));
      $url=se_db_input(se_db_output($url));
      $short=se_db_input(se_db_output($short));
      $text=se_db_input(se_db_output($text));
      $phone=se_db_input(se_db_output($phone));
    se_db_query("UPDATE bbs
      SET id_author='$ID_AUTHOR', name='$name', town='$town', email='$email', phone='$phone', url='$url', short='$short', text='$text', img='$img', phone='$phone'
      WHERE id='$id';");
    Header("Location: http://".$_SERVER['HTTP_HOST']."/".$_page."?".time());
   }
  $ro=se_db_query("SELECT *
    FROM bbs
    WHERE id='$_id';");
  $msg=se_db_fetch_array($ro);
//проверка на инъекции
      $name=se_db_output($name);
      $town=se_db_output($town);
      $email=se_db_output($email);
      $url=se_db_output($url);
      $short=se_db_output($short);
      $text=se_db_output($text);
      $phone=se_db_output($phone);
  $name=$msg['name'];
  $town=$msg['town'];
  $phone=$msg['phone'];
  $email=$msg['email'];
  $url=$msg['url'];
  $short=$msg['short'];
  $text=$msg['text'];
  $id=$msg['id'];
  $_img=$msg['img'];
//EndSubPage2
} else
if(($razdel == $__request['razdel']) && !empty($__request['sub']) && ($__request['sub']==3)){
//BeginSubPage3
$rt=se_db_query("SELECT SQL_CACHE *
  FROM bbs
  WHERE id='".se_db_input($_object)."';");
while ($msg=se_db_fetch_array($rt)) {
  $titlepage=se_db_output($msg['short']);
  if (!empty($msg['img'])){
  $imgfull='/images/bbs/'.$msg['img'];  
  $picture='<DIV id="objimage">
<IMG class="objectImage" alt="'.$titlepage.'" src="'.$imgfull.'" border="0"></div>';
  }
 $fulltext=se_db_output($msg['text']); 
}
//EndSubPage3
}
}
//EndSubPages
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"cont_desk\" [part.style]>
<noempty:part.title><h3 class=\"contentTitle\"[part.style_title]><span class=\"contentTitleTxt\">[part.title]</span> </h3> </noempty>
<noempty:part.image><img border=\"0\" class=\"contentImage\"[contentstyle_img] src=\"[part.image]\" alt=\"[part.image_alt]\"></noempty>
<noempty:part.text><div class=\"contentText\"[part.style_text]>[part.text]</div> </noempty>
$bbs_add_link
<div id=\"Cnri\">
    <form action=\"/$_page\" method=\"get\">
        <table border=\"0\">
        <tr> 
            <td class=\"title\">{$section->params[8]->value} </td> 
            <td class=\"CnriBox\"><select class=\"CnriSel\" id=\"inField\" name=\"townselected\">$selector</select> </td> 
            <td> <input class=\"buttonSend\" type=\"submit\" value=\"{$section->params[9]->value}\"></td> 
        </tr> 
        </table> 
    </form> <br> [part.text]
</div> 
$MANYPAGE
".se_record_list($section, "
<div class=\"desks\">
    <h4 class=\"objectTitle\"><label id=\"obtitle\">[@col_title]</label></h4> 
    [@col_image]
    <div class=\"info\"><a id=\"shorttext\" href=\"[@subpage3]object/[@col_id]/\">[@col_note]</a></div> 
    <table class=\"contact\" border=\"0\">
    <tr><td>
        <div id=\"objbbs\"><div id=\"namebbs\">{$section->params[11]->value}</div><b id=\"dateStyle\">[@col_date]</b></div> 
        <div id=\"objbbs\"><div id=\"namebbs\">{$section->params[12]->value}</div><b id=\"authordata\">[@col_name]</b></div> 
        <div id=\"objbbs\"><div id=\"namebbs\">{$section->params[13]->value}</div><b id=\"townbbs\">[@col_town]</b></div> 
        <div id=\"objbbs\"><div id=\"namebbs\">{$section->params[36]->value}</div><b id =\"phone\">[@col1_phone]</b></div> 
        <div id=\"objbbs\"><div id=\"namebbs\">{$section->params[14]->value}</div><b id =\"email\">[@col_email]</b></div> 
        <div id=\"objbbs\">
            <div id=\"namebbs\">{$section->params[15]->value}</div> 
            <a id =\"url\" href=\"http://[@col_url]\" target=\"blank\">[@col_url]</a>
        </div> 
    </td></tr>
    </table> 
</div> 
")."
$MANYPAGE
</div> 
<!-- =============== END CONTENT ============= -->";
$__module_content['show'] = "
";
$__module_subpage[1]['group'] = "[$group_num]";
$__module_subpage[1]['form'] = "<div class=\"content\" id=\"cont_edesk\"><h3 class=\"contentTitle\">{$section->params[16]->value}</h3> 
<FONT color=\"#FF0000\" id=\"errortext\">$errortext</font> 
<FORM action=\"\" method=\"post\" enctype=\"multipart/form-data\">
<table border=\"0\" cellPadding=\"0\" cellSpacing=\"0\" class=\"tableTable\">
<tbody class=\"tableBody\">
<tr> 
  <td class=\"title\"><b id=\"titlename\">{$section->params[18]->value}</b> </td> 
    <td class=\"field\"><input class=\"field_name\" id=\"inField\" name=\"name\" value=\"$name\" maxlength=\"50\">
    </td> </tr> 
<tr> 
<td class=\"title\"><b id=\"titletown\">{$section->params[19]->value}</b> </td> 
<td class=\"field\"><input class=\"field_town\" id=\"inField\" name=\"town\" value=\"$town\" maxlength=\"30\">
</td> </tr> 
<tr> 
  <td class=\"title\"><b id=\"titlephone\">{$section->params[34]->value}</b> </td> 
  <td class=\"field\"><input class=\"field_phone\" id=\"inField\" name=\"phone\" value=\"$phone\" maxlength=\"15\">
  </td> </tr> 
<tr> 
  <td class=\"title\"><b id=\"titleemail\">{$section->params[20]->value}</b> </td> 
  <td class=\"field\"><input class=\"field_email\" id=\"inField\" name=\"email\" value=\"$email\" maxlength=\"30\">
  </td> </tr> 
<tr> 
  <td class=\"title\"><b id=\"titleurl\">{$section->params[21]->value}</b> </td> 
  <td class=\"field\"><input class=\"field_url\" id=\"inField\" name=\"url\" value=\"$url\" maxlength=\"50\">
  </td> </tr> 
<tr> 
  <td class=\"title\"><b id=\"titleshort\">{$section->params[22]->value}</b> </td> 
  <td class=\"field\"><input class=\"field_short\" id=\"inField\" name=\"short\" value=\"$short\" maxlength=\"50\">
  </td> </tr> 
<tr> 
    <td class=\"title\"><b id=\"titletext\">{$section->params[23]->value}</b> </td> 
    <td colSpan=\"2\" class=\"field\"><textarea class=\"field_text\" id=\"arField\" name=\"text\" rows=\"5\" cols=\"10\">$text</textarea> 
    </td> </tr> 
    <tr> <td colspan=\"2\">&nbsp;</td> </tr> 
<tr> 
  <td class=\"title\"><b id=\"titleimg\">{$section->params[24]->value}</b> </td> 
  <td class=\"field\"><input id=\"add_img\" type=\"file\" name=\"userfile[]\"></td> 
</tr> 
<tr> <td colspan=\"2\">&nbsp;</td> </tr> 
</tbody> </table> <div id=\"groupButton\"><input id=\"goButton\" class=\"buttonSend\" name=\"GoTo\" type=\"submit\" value=\"{$section->params[26]->value}\">
<input id=\"backButton\" class=\"buttonSend\" onclick=\"document.location = '/".$_page."'\" type=\"button\" value=\"{$section->params[10]->value}\"></div> 
</form> </div> ";
$__module_subpage[2]['group'] = "[$group_num]";
$__module_subpage[2]['form'] = "<DIV class=\"content\" id=\"cont_edesk\">
<H3 class=\"contentTitle\">{$section->params[17]->value}</H3>
<FONT color=\"#FF0000\" id=\"errortext\">$errortext</FONT>
<FORM style=\"margin:0px;\" action=\"\" method=\"post\" enctype=\"multipart/form-data\">
<TABLE border=\"0\" cellPadding=\"0\" cellSpacing=\"0\" class=\"tableTable\">
<TBODY class=\"tableBody\">
<TR>
  <TD class=\"title\"><b id=\"titlename\">{$section->params[18]->value}</b></TD>
    <TD class=\"field\"><INPUT class=\"field_name\" id=\"inField\" name=\"name\" value=\"$name\" maxlength=\"50\">
    </TD></TR>
<TR>
<TD class=\"title\"><b id=\"titletown\">{$section->params[19]->value}</b></TD>
<TD class=\"field\"><INPUT class=\"field_town\" id=\"inField\" name=\"town\" value=\"$town\" maxlength=\"30\">
</TD></TR>
<TR>
  <TD class=\"title\"><b id=\"titlephone\">{$section->params[34]->value}</b></TD>
  <TD class=\"field\"><INPUT class=\"field_phone\" id=\"inField\" name=\"phone\" value=\"$phone\" maxlength=\"15\">
  </TD></TR>
<TR>
  <TD class=\"title\"><b id=\"titleemail\">{$section->params[20]->value}</b></TD>
  <TD class=\"field\"><INPUT class=\"field_email\" id=\"inField\" name=\"email\" value=\"$email\" maxlength=\"30\">
  </TD></TR>
<TR>
  <TD class=\"title\"><b id=\"titleurl\">{$section->params[21]->value}</b></TD>
  <TD class=\"field\"><INPUT class=\"field_url\" id=\"inField\" name=\"url\" value=\"$url\" maxlength=\"50\">
  </TD></TR>
<TR>
  <TD class=\"title\"><b id=\"titleshort\">{$section->params[22]->value}</b></TD>
  <TD class=\"field\"><INPUT class=\"field_short\" id=\"inField\" name=\"short\" value=\"$short\" maxlength=\"50\">
  </TD></TR>
<TR>
    <TD class=\"title\" id=\"ftar\"><b id=titletext>{$section->params[23]->value}</b></TD>
    <TD colSpan=\"2\" class=\"field\"><TEXTAREA class=\"field_text\" id=\"arField\" name=\"text\" rows=\"5\" cols=\"10\">$text</TEXTAREA>
    </TD></TR>
<TR>
  <TD class=\"title\"><b id=\"titleimg\">{$section->params[24]->value}</b></TD>
  <TD class=\"field\"><input id=\"add_img\" type=\"file\" name=\"userfile[]\"></td>
</TR>
<TR><td colspan=\"2\">&nbsp;</td></TR>
<TR>
    <td class=\"title\"><b id=\"titledel\">{$section->params[25]->value}</b></td><td class=\"field\"><INPUT type=\"checkbox\" name=\"del\" value=\"true\" id=\"chField\">
    </TD></TR>
</TBODY></TABLE><div id=\"groupButton\"><INPUT id=\"goButton\" class=\"buttonSend\" name=\"GoTo\" type=\"submit\" value=\"{$section->params[27]->value}\">
<INPUT id=\"backButton\" class=\"buttonSend\" onclick=\"document.location = '/".$_page."'\" type=\"button\" value=\"{$section->params[10]->value}\"></div>
<INPUT type=\"hidden\" name=\"id\" value=\"$id\"> 
<INPUT type=\"hidden\" name=\"img\" value=\"$_img\"> 
</FORM>
</DIV>";
$__module_subpage[3]['form'] = "<DIV class=\"content\" id=\"show\">
<DIV class=\"object\">
<H4 class=\"objectTitle\">$titlepage
</H4>
$picture
<DIV class=\"objectText\">$fulltext
</DIV></DIV>
<INPUT class=\"buttonSend\" onclick=\"document.location = '/".$_page."'\" type=\"button\" value=\"{$section->params[10]->value}\">
</DIV>
";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};