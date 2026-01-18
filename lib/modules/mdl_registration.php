<?php
//BeginLib
if (!function_exists('Mail_CheckMail')){
function Mail_CheckMail($name)
{
        if (preg_match("/[0-9a-z_\-]+@([0-9a-z_\-^\.]+\.[a-z]{2,4})$/i", $name, $matches))
 //           if (getmxrr($matches[1], $arr))
            return true;
   return false;
}}   
if (!function_exists('Mail_UserRegistration')){
function Mail_UserRegistration($section, $req)
{
      $_email = $req['email'];
      $_login = $req['login'];
      $_passw = $req['passw'];
      $_last_name = $req['last_name'];
      $_first_name = $req['first_name'];
      $mail_text =$section->params[19]->value;
      if ($frommail=='Y') {
       $mail_text .=$section->params[20]->value.": ";
       $authkod=substr(md5($_login."USymlQpSK"),0,16);
       $mail_text .='<a href="http://'.$_SERVER['HTTP_HOST'];
       $mail_text .="/system/onauth.php?id=".$authkod."&login=".$_login."\">{$section->params[21]->value}</a>";
      };
      $headers ="From: ".trim($_SERVER['HTTP_HOST'])." <".$section->params[0]->value.">\n";
      $headers .="X-Sender: <".$section->params[0]->value.">\n";
      $headers .="X-Mailer: ".$_SERVER['HTTP_HOST']."\n";
      $headers .="X-Priority: 3\n";
      $headers .="Content-Type: text/html; charset=utf-8\n";
      $headers .="Content-Transfer-Encoding: 8bit\n";
      $headers .="Return-Part: <$_email>\n";
      $mail_text.=$section->params[22]->value;
      $mail_text = str_replace("\\r\\n", "<br>", $mail_text);
      $mail_text = str_replace("[USERNAME]", "$_last_name $_first_name", $mail_text);
      $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
      $mail_text = str_replace("[USERLOGIN]", se_db_output($_login), $mail_text);
      $mail_text = str_replace("[USERPASSWORD]", se_db_output($_passw), $mail_text);
      $mail_text = str_replace("[USEREMAIL]", se_db_output($_email), $mail_text);
      mail($_email, "USER REGISTRATION", $mail_text,$headers);
      if (!empty($section->params[0]->value)){
        $headers ="From: ".trim($_SERVER['HTTP_HOST'])." <".$parametrtext[0].">\n";
        $headers .="X-Sender: <$_email>\n";
        $headers .="X-Mailer: CMS EDGESTILE SiteEdit\n";
        $headers .="X-Priority: 3\n";
        $headers .="Content-Type: text/plain; charset=utf-8\n";
        $headers .="Content-Transfer-Encoding: 8bit\n";
        $headers .="Return-Part: <$_email>\n";
        $mail_text =$section->params[23]->value;
        $mail_text = str_replace("\\r\\n", "<br>", $mail_text);
        $mail_text = str_replace("[USERNAME]", "$_last_name $_first_name", $mail_text);
        $mail_text = str_replace("[SITENAME]", $_SERVER['HTTP_HOST'], $mail_text);
        $mail_text = str_replace("[USERLOGIN]", se_db_output($_login), $mail_text);
        $mail_text = str_replace("[USERPASSWORD]", se_db_output($_passw), $mail_text);
        $mail_text = str_replace("[USEREMAIL]", se_db_output($_email), $mail_text);
        $mail_text = str_replace("\\r\\n", "<br>", $mail_text);
        mail($section->params[0]->value, "USER REGISTRATION", $mail_text,$headers);
      }
}}
//EndLib
function module_registration($razdel, $section = null)
{
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "[%adminmail%]";
if (empty($section->params[1]->value)) $section->params[1]->value = "1";
if (empty($section->params[2]->value)) $section->params[2]->value = " ";
if (empty($section->params[3]->value)) $section->params[3]->value = "N";
if (empty($section->params[4]->value)) $section->params[4]->value = "home.html";
if (empty($section->params[5]->value)) $section->params[5]->value = "На главную страницу";
if (empty($section->params[6]->value)) $section->params[6]->value = "(Логин используется для авторизации. Используйте буквы латинского алфавита и цифры)";
if (empty($section->params[7]->value)) $section->params[7]->value = "(Придумайте сложный для подбора пароль, состоящий из букв латинского алфавита и цифр)";
if (empty($section->params[8]->value)) $section->params[8]->value = "(Укажите реальный и действующий e-mail адрес)";
if (empty($section->params[9]->value)) $section->params[9]->value = "Ваш Логин:";
if (empty($section->params[10]->value)) $section->params[10]->value = "Пароль:";
if (empty($section->params[11]->value)) $section->params[11]->value = "Повторите пароль:";
if (empty($section->params[12]->value)) $section->params[12]->value = "E-mail:";
if (empty($section->params[13]->value)) $section->params[13]->value = "Ваше имя:";
if (empty($section->params[14]->value)) $section->params[14]->value = "Фамилия:";
if (empty($section->params[15]->value)) $section->params[15]->value = "Регистрация";
if (empty($section->params[16]->value)) $section->params[16]->value = "Не все поля заполнены, повторите ввод!";
if (empty($section->params[17]->value)) $section->params[17]->value = "Не указан пароль, повторите ввод!";
if (empty($section->params[18]->value)) $section->params[18]->value = "Пароли не совпадают, повторите ввод!";
if (empty($section->params[19]->value)) $section->params[19]->value = "Уважаемый(ая) [USERNAME]\r\nВы зарегистрировались на сайте [SITENAME]\r\n\r\nВаши авторизационные данные:\r\nЛогин: [USERLOGIN]\r\nПароль: [USERPASSWORD]\r\n\r\n ";
if (empty($section->params[20]->value)) $section->params[20]->value = "Для активизации доступа нажмиите на ссылку";
if (empty($section->params[21]->value)) $section->params[21]->value = "Подтверждение регистрации";
if (empty($section->params[22]->value)) $section->params[22]->value = "С Уважением, \r\nАдминистрация сайта [SITENAME]";
if (empty($section->params[23]->value)) $section->params[23]->value = "Уважаемый Администратор сайта [SITENAME]!\r\nУ Вас зарегистрировался пользователь [USERNAME]\r\nЕго регистрационные данные:\r\nЛогин: [USERLOGIN]\r\nE-mail: [USEREMAIL]";
if (empty($section->params[24]->value)) $section->params[24]->value = "Этот логин уже существует, выберите другое!";
if (empty($section->params[25]->value)) $section->params[25]->value = "Поздравляем! Регистрация прошла успешно.";
if (empty($section->params[26]->value)) $section->params[26]->value = " На Ваш почтовый адрес выслана ссылка для активизации.";
if (empty($section->params[27]->value)) $section->params[27]->value = "Введите число с картинки:";
if (empty($section->params[28]->value)) $section->params[28]->value = "<FONT size=4 color=red>Неверное число</FONT>";
if (empty($section->params[29]->value)) $section->params[29]->value = "Я принимаю";
if (empty($section->params[30]->value)) $section->params[30]->value = "license.html";
if (empty($section->params[31]->value)) $section->params[31]->value = "пользовательское соглашение";
if (empty($section->params[32]->value)) $section->params[32]->value = "Не указанно, что вы прочли лицензию. Повторите ввод";
if (empty($section->params[33]->value)) $section->params[33]->value = "&nbsp; &#8594; &nbsp;";
if (empty($section->params[34]->value)) $section->params[34]->value = "dannye.html";
if (empty($section->params[35]->value)) $section->params[35]->value = "N";
if (empty($section->params[36]->value)) $section->params[36]->value = "Контрольный вопрос";
if (empty($section->params[37]->value)) $section->params[37]->value = "Введите контрольное слово";
if (empty($section->params[38]->value)) $section->params[38]->value = "(Напишите контрольное слово, оно будет нужно для восстановления доступа к сайту при потере пароля)";
if (empty($section->params[39]->value)) $section->params[39]->value = "Шаг 1 ";
if (empty($section->params[40]->value)) $section->params[40]->value = "Регистрация";
if (empty($section->params[41]->value)) $section->params[41]->value = "Шаг 2";
if (empty($section->params[42]->value)) $section->params[42]->value = "Мои данные";
if (empty($section->params[43]->value)) $section->params[43]->value = "Y";
global $error_message,$_last_name,$_first_name,$_email,$_login,$_passw, $_otvet,$_confpassw,$_sub,$SESSION_VARS;
global $text_license, $anti_spam, $shag_text;
$frommail = $section->params[3]->value; 
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
$sid = session_id();
$error_message ='';
$text_license=' '.$section->params[29]->value.' <a href="'.$section->params[30]->value.'" target="blank">'.$section->params[31]->value.'</a>';
//Анти-спам картинка
$anti_spam='<TR class="tableRow" id="tableRowOdd">
<TD><b class="title">'.$section->params[27]->value.'<FONT COLOR="#FF0000">*</FONT></b></TD>
<TD id="rstrok"><table cellSpacing="0" cellPadding="3" border="0"><tr><td><img class="ml_secimg" style="width:150px; height:30px" src="/lib/cardimage.php?session='.$sid.'&amp;'.time().'"></td>
<td vlaign="top">&nbsp;<input id="field_pin" maxlength="5" size="5" name="pin" type="text" class="ml_txtpin" title="'.$section->params[27]->value.'">
</td></tr></table></TD>
</TR>';
if (isRequest('GoToAuthor'))
{
  getRequestList(&$__request, '', 3);
  getRequestList(&$__request, 'email');
  //Проверка на безопасность
  //Окончание проверки
  $fl_err = false;
  if (empty($__request['login']) || !Mail_CheckMail($__request['email'])
  || empty($__request['first_name'])) 
  {
    $error_message = $section->params[16]->value;
    $fl_err = true;
  }
  if (empty($__request['passw'])) 
  {
    $error_message = $section->params[17]->value;
    $fl_err = true;
  }
  if ($__request['passw'] != $__request['confpassw']) 
  {
    $error_message = $section->params[18]->value;
    $fl_err = true;
  }
  $ch_license = $section->params[43]->value;
  if (($ch_license == 'Y') && empty($__request['license'])) 
  {
    $error_message = $section->params[32]->value;
    $fl_err = true;
  }
  require_once getcwd()."/lib/card.php";
  if (!checkcard(getRequest('pin', 1)))
  {
    $error_message = $section->params[28]->value;
    $fl_err = true;
  }
  if (!$fl_err)
  {
      /*
        $aresult=se_db_query("SHOW COLUMNS FROM `author` WHERE FIELD='a_otvet'");
        $rows = se_db_num_rows($aresult);
        if ($rows == 0)
            se_db_query("ALTER TABLE `author` ADD `a_otvet` VARCHAR( 255 );");
     */
    if ($frommail == 'Y') 
    {
        $enable = 'N'; 
    }
    else 
    {
        $enable='Y';
    }
    $user = new seUser();
    $user->where("a_login='?'", $__request['login'])->fetchOne();
    if (!$user->id)
    {
        $user->login = $__request['login'];
        $user->last_name = $__request['last_name'];
        $user->first_name = $__request['first_name'];
        $user->email = $__request['email'];
        $user->a_group = intval($section->params[1]->value);
        $user->a_admin = $section->params[2]->value;
        $user->password = md5($__request['passw']);
        $user->reg_date = date('Y-m-d');
        $user->a_otvet = $__request['otvet'];
        $user->a_enable = $enable;
        if ($iser_id = $user->save())
        {
            Mail_UserRegistration($section, $__request);
            if ($frommail=='N') {
                check_session(true); // Удаляем старую сессию
                $SESSION_VARS['AUTH_PW']    = md5($__request['passw']);
                $SESSION_VARS['AUTH_USER']  = $__request['login'];
                $SESSION_VARS['IDUSER']     = $iser_id;
                $SESSION_VARS['USER']       = $__request['first_name'];
                $SESSION_VARS['GROUPUSER']  = $section->params[1]->value;
                $SESSION_VARS['ADMINUSER']  = $section->params[2]->value;
                check_session(false);
            }
            header("Location: /".$_page."/".$razdel."/sub1/email/".$__request['email'].'/');
        }
    } else $error_message = $section->params[24]->value;
  }
}
//EndRazdel
}
else{
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==1)){
//BeginSubPage1
     if ($frommail=='Y') $error_message='<h4 id="error_message"><center>'.$section->params[25]->value.'
     <br>'.$section->params[26]->value.'</center></h4></br>';
     else $error_message='<h4 id="error_message"><center>'.$section->params[25]->value.'<br></center></h4></br>';
//EndSubPage1
}
}
//EndSubPages
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"cont_auth\"[part.style]>
<noempty:part.title><h3 class=\"contentTitle\"[part.style_title]><span class=\"contentTitleTxt\">[part.title]</span> </h3> </noempty>
<noempty:part.image><a href=\"[part.image]\" target=\"_blank\"></noempty>
<noempty:part.image><img alt=\"[part.image_alt]\" border=\"0\" class=\"contentImage\"[part.style_image] src=\"[part.image]\"></a></noempty>
<noempty:part.text><div class=\"contentText\"[part.style_text]>[part.text]</div></noempty>
<div id=\"cont_authBlock\">
    <table border=\"0\" cellPadding=\"3\" cellSpacing=\"0\">
    <tbody class=\"tableBody\">
@if(\"{$section->params[35]->value}\"==\"Y\"){
    <tr>
        <td>&nbsp;</td>
        <td> 
        <table width=\"100%\" cellspacing=\"0\">
        <tr>
            <td width=\"100%\">
                <font size=\"-1\"><strnog> {$section->params[39]->value}</strnog> : {$section->params[40]->value} {$section->params[33]->value} <a href=\"{$section->params[34]->value}\">{$section->params[41]->value}: {$section->params[42]->value}</a> </font> 
            </td>
        </tr> 
        </table>
        </td> 
    </tr> 
}
    <tr><form id=\"Go\" action=\"\" method=\"post\">
        <td>&nbsp;</td>
        <td> <font color=\"red\" size=\"4\">$error_message</font> </td>
    </tr> 
    <tr class=\"tableRow\" id=\"tableRowOdd\">
        <td> <b class=\"title\">{$section->params[9]->value}<FONT COLOR='#FF0000'>*</font></b></td> 
        <td id=\"rstrok\"><input id=\"field\" name=\"login\" notnull=\"1\" title=\"{$section->params[9]->value}\" value=\"$_login\"> </td>
    </tr>
    <tr> 
        <td> &nbsp;</td>
        <td><em class=\"texts\">{$section->params[6]->value}</em></td>
    </tr> 
    <tr class=\"tableRow\" id=\"tableRowOdd\">
        <td><b class=\"title\">{$section->params[10]->value}<FONT COLOR='#FF0000'>*</font> </b></td>
        <td id=\"rstrok\"><input id=\"field\" type=\"password\" class=\"parolsize\" size=\"20\" name=\"passw\" notnull=\"1\" title=\"{$section->params[10]->value}\"></td> 
    </tr> 
    <tr>
        <td>&nbsp;</td><td><em class=\"texts\">{$section->params[7]->value}</em></td>
    </tr> 
    <tr class=\"tableRow\" id=\"tableRowOdd\">
        <td><b class=\"title\">{$section->params[11]->value}<FONT COLOR='#FF0000'>*</font></b></td> 
        <td id=\"rstrok\"><input type=\"password\" id=\"field\" class=\"parolsize\" size=\"20\" name=\"confpassw\" notnull=\"1\" title=\"{$section->params[11]->value}\"></td> 
    </tr> 
    <tr class=\"tableRow\" id=\"tableRowOdd\">
        <td> <b class=\"title\">{$section->params[12]->value}<FONT COLOR='#FF0000'>*</font> </b> </td> 
        <td id=\"rstrok\"><input id=\"field\" name=\"email\" notnull=\"1\" title=\"{$section->params[12]->value}\" value=\"$_email\"></td> 
    </tr> 
    <tr>
        <td> &nbsp;</td> <td>  <em class=\"texts\">{$section->params[8]->value}</em></td>
    </tr> 
    <tr class=\"tableRow\" id=\"tableRowOdd\">
        <td><b class=\"title\">{$section->params[13]->value}<FONT COLOR='#FF0000'>*</font></b></td> 
        <td id=\"rstrok\"><input id=\"field\" name=\"first_name\" notnull=\"1\" title=\"{$section->params[13]->value}\" value=\"$_first_name\"></td>
    </tr> 
    <tr class=\"tableRow\" id=\"tableRowOdd\">
        <td><b class=\"title\">{$section->params[14]->value}</b></td> 
        <td id=\"rstrok\"><input id=\"field\" name=\"last_name\" notnull=\"1\" title=\"{$section->params[14]->value}\" value=\"$_last_name\"></td>
    </tr> 
    <tr class=\"tableRow\" id=\"tableRowOdd\">
        <td> <b class=\"title\">{$section->params[37]->value}</b></td> 
        <td id=\"rstrok\"><input id=\"field\" name=\"otvet\" notnull=\"1\" title=\"{$section->params[37]->value}\" value=\"$_otvet\" ></td>
    </tr> 
    <tr>
        <td>&nbsp;</td><td><em class=\"texts\">{$section->params[38]->value}</em></td>
    </tr> 
@if('{$section->params[43]->value}'=='Y'){ 
    <tr class=\"tableRow\" id=\"tableRowOdd\">
        <td><b class=\"title\">&nbsp;</b></td> 
        <td id=\"rstrok\"><input TYPE='checkbox' NAME='license' VALUE='checked'>$text_license
        </td>
    </tr> 
} 
    $anti_spam
    <tr><td colspan=\"2\">&nbsp;</td></tr> 
    <tr>
        <td>&nbsp;</td> 
        <td> 
            <table cellPadding=\"0\" cellSpacing=\"0\" border=\"0\" align=\"left\">
            <tr> 
                <td><input name=\"GoToAuthor\" type=\"submit\" value=\"{$section->params[15]->value}\" class=\"buttonSend\"></td> 
                <td width=\"40\">&nbsp;</td> 
            </tr>
            </table> 
        </td></form>
    </tr> 
    </tbody>
    </table> 
</div> 
<serv><script src=\"/system/main/notnull.js\"></script></serv>
</div> 
<!-- =============== END CONTENT ============= -->";
$__module_subpage[1]['form'] = "<DIV class=content id=cont_auth_end>
<H4 id=\"error_message\">$error_message</H4>
<DIV id=\"sendbut\"><a id=regback href=\"{$section->params[4]->value}\">{$section->params[5]->value}</a></DIV>
</DIV>
";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};