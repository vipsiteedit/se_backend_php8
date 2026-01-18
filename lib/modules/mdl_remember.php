<?php
//BeginLib
//EndLib
function module_remember($razdel, $section = null)
{
   $__module_subpage = array();
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "[%adminmail%]";
if (empty($section->params[1]->value)) $section->params[1]->value = "home";
if (empty($section->params[2]->value)) $section->params[2]->value = "Главная";
if (empty($section->params[3]->value)) $section->params[3]->value = "Уважаемый(ая) [USERNAME]\r\nВы зарегистрировались на сайте [SITENAME]\r\n\r\nВаши авторизационные данные:\r\nЛогин: [USERLOGIN]\r\nПароль: [USERPASSWORD]\r\n";
if (empty($section->params[4]->value)) $section->params[4]->value = "Логин";
if (empty($section->params[5]->value)) $section->params[5]->value = "и";
if (empty($section->params[6]->value)) $section->params[6]->value = "e-Mail";
if (empty($section->params[7]->value)) $section->params[7]->value = "Новый пароль будет выслан вам по e-mail,  который вы оставили при регистрации.";
if (empty($section->params[8]->value)) $section->params[8]->value = "Отправить пароль";
if (empty($section->params[9]->value)) $section->params[9]->value = "Не верно указаны данные!";
if (empty($section->params[10]->value)) $section->params[10]->value = "Уважаемый Админ!\r\nВаш клиент [USERNAME]запросил восстановление пароля, который был отправлен на [USEREMAIL]";
if (empty($section->params[11]->value)) $section->params[11]->value = "Указанный адресат не найден!";
if (empty($section->params[12]->value)) $section->params[12]->value = "На Ваш почтовый адрес [USEREMAIL] выслан новый пароль.";
global $error_message,$_email;
$error_message ="";
if (isset($_POST['GoToAuthor'])){
  se_db_connect();
  $user = new seUser();
  $person= new sePerson();
  $name=se_db_output(@$_POST['name']);
  $email=se_db_output(@$_POST['email']);
   if (empty($name) || empty($email)) { 
        $error_message=$section->params[9]->value;
   }
   else { 
  $admemail=$section->params[0]->value;
  $user->where("username='?'",$name);
  $user->fetchOne();
  if ($user->id) {
  $person->find($user->id);
  if ($person->email == $email){
     $newpassw=substr(md5(time()),0,6);
     $user->tmppassw = md5($newpassw);
     $user->save();
     $_login=se_db_output($user->username);
     $_passw=$newpassw;
     $_last_name=se_db_output($person->last_name);
     $_first_name=se_db_output($person->first_name);
     $_email=se_db_output($person->email);
     $mail_text = $section->params[3]->value;
     $mail_text = str_replace("\\r\\n", "<br>", $mail_text);
     $mail_text = str_replace("[USERNAME]", "$_last_name $_first_name", $mail_text);
     $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
     $mail_text = str_replace("[USERLOGIN]", se_db_output($_login), $mail_text);
     $mail_text = str_replace("[USERPASSWORD]", se_db_output($_passw), $mail_text);
     $mail_text = str_replace("[USEREMAIL]", se_db_output($_email), $mail_text);
     $headers ="Content-Type: text/html; charset=utf-8\n";
     $headers .="From: ".trim($_SERVER['HTTP_HOST'])." <".$admemail.">\n";
     $headers .="X-Sender: <".$admemail.">\n";
     $headers .="X-Mailer: PHP\n";
     $headers .="X-Priority: 3\n";
     $headers .="Content-Transfer-Encoding: 8bit\n";
     $headers .="Return-Part: $admemail\n";
     $headers .="Content-Type: text/html; charset=utf-8\n";
     $mail_text = str_replace("\\r\\n", "<br>", $mail_text);
     mail(trim($_email), "USER REGISTRATION", $mail_text,$headers,'-f'.$admemail);
        $headers ="From: ".trim($_SERVER['HTTP_HOST'])." <".$admemail.">\n";
        $headers .="X-Sender: $admemail\n";
        $headers .="X-Mailer: PHP\n";
        $headers .="X-Priority: 3\n";
        $headers .="Content-Type: text/plain; charset=utf-8\n";
        $headers .="Content-Transfer-Encoding: 8bit\n";
        $headers .="Return-Part: $admemail\n";
        $mail_text =$section->params[10]->value;
        $mail_text = str_replace("\\r\\n", "\r\n", $mail_text);
        $mail_text = str_replace("[USERNAME]", "$_last_name $_first_name", $mail_text);
        $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
        $mail_text = str_replace("[USERLOGIN]", se_db_output($_login), $mail_text);
        $mail_text = str_replace("[USERPASSWORD]", se_db_output($_passw), $mail_text);
        $mail_text = str_replace("[USEREMAIL]", se_db_output($_email), $mail_text);
        if (mail(trim($admemail), "USER REMEMBER", $mail_text,$headers,'-f'.$admemail)
        or mail(trim($admemail), "USER REMEMBER", $mail_text,$headers))
            header("Location: "._HOST_."/$_page/$razdel/sub1/?email=".$_email);
  } else $error_message=$section->params[11]->value;
}
}
}
if (isset($_sub)) {
     $textmess=str_replace("[USEREMAIL]", se_db_output($_email),$section->params[12]->value);
     $error_message="<h4><center><FONT color=#008080>$textmess</FONT></center></h4></br>";
}; 
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
//EndRazdel
}
else{
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==1)){
//BeginSubPage1
//EndSubPage1
}
}
//EndSubPages
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<DIV class=\"content\" id=\"cont_remem\"[part.style]>
<noempty:part.title><h3 class=\"contentTitle\"[part.style_title]><span class=\"contentTitleTxt\">[part.title]</span></h3></noempty>
<noempty:part.image><A href=\"[part.image]\" target=\"_blank\"></noempty>
<noempty:part.image><IMG alt=\"[part.image_alt]\" border=\"0\" class=\"contentImage\"[part.style_image] src=\"[part.image]\"></A></noempty>
<noempty:part.text><div class=\"contentText\"[part.style_text]>[part.text]</div></noempty>
<font color=red id=\"warning\">$error_message</font>
<TABLE cellSpacing=\"0\" cellPadding=\"0\" style=\"width:17\" border=\"0\" class=\"tableTable\">
  <TBODY>
  <FORM style=\"margin:0px;\" action=\"\" method=\"post\">
  <TR>
    <TD class=\"verdana11\" style=\"height:22\">
      <TABLE cellSpacing=\"0\" cellPadding=\"0\" style=\"width:100%;\" bgColor=\"#faf8f8\" border=\"0\">
        <TBODY calss=\"tableBody\">
        <TR class=\"tableRow\">
          <TD align=\"right\" class=\"title\">{$section->params[4]->value}&nbsp;</TD>
          <TD class=\"rstrok\"><INPUT class=\"contentForm\" size=\"30\" name=\"name\"></TD></TR>
        <TR class=\"tableRow\">
          <TD align=\"center\" colSpan=\"2\"><FONT color=\"#990000\" id=\"ltitle\">{$section->params[5]->value}</FONT></TD></TR>
        <TR class=\"tableRow\">
          <TD align=\"right\" class=\"title\">{$section->params[6]->value}&nbsp;</TD>
          <TD class=\"rstrok\"><INPUT class=\"contentForm\" size=\"30\" name=\"email\"></TD></TR>
      </TBODY></TABLE></TD></TR>
  <TR>
    <TD class=\"forget_pass\" style=\"height:52\">{$section->params[7]->value}</TD></TR>
  <TR>
    <TD align=\"center\" style=\"height:56\">
      <TABLE cellSpacing=\"1\" cellPadding=\"0\" bgColor=\"#cccccc\" border=\"0\">
        <TBODY>
        <TR>
          <TD class=\"buttonSend\" onmouseover=\"this.className='dbut'\"
          onmouseout=\"this.className='ubut'\" vAlign=\"top\" noWrap align=\"center\"
          bgColor=\"#ffffff\"><INPUT class=\"red_uin\" onmouseover=\"this.className='red_din'\"
          name=\"GoToAuthor\" type=\"submit\"           
          title=\"{$section->params[8]->value}\" style=\"width: 120px;\" onmouseout=\"this.className='red_uin'\" type=\"submit\" align=\"right\" value=\"{$section->params[8]->value}\" ></TD></TR>
      </TBODY>
     </TABLE></TD></TR></FORM>
</TBODY></TABLE>
</DIV>
<!-- =============== END CONTENT ============= -->";
$__module_subpage[1]['form'] = "<DIV class=content id=cont_remem_end>
<H4 id=\"error_message\">$error_message</H4>
<DIV id=\"sendbut\"><a id=regback href=\"{$section->params[1]->value}.html\">{$section->params[2]->value}</a></DIV>
</DIV>
";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};