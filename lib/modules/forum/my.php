<?php

Global $page;

if ($uid==0) {
  $forum_echo.= "<div id=message_warning>Вы не авторизованы!</div>";
  return;
}

if (isset($_POST['doGo'])) {
  $nick=AddSlashes($_POST['nick']);
  $realname=AddSlashes($_POST['realname']);
  $location=AddSlashes($_POST['location']);
  $jobtitle=AddSlashes($_POST['jobtitle']);
  $interests=AddSlashes($_POST['interests']);
  $email=AddSlashes($_POST['email']);
  $icq=AddSlashes($_POST['icq']);
  $url=AddSlashes($_POST['url']);
  $origin=AddSlashes($_POST['origin']);

  $ru = se_db_query("
  SELECT forum_users.id, forum_users.nick
  FROM forum_users
  WHERE forum_users.nick='$nick' AND id<>$uid;"
);

  if (se_db_num_rows($ru)!=0) {
    $forum_echo.= "<div id=message_warning>Пользователь с ником $nick уже зарегистрирован на форуме.";
    $forum_echo.= "<br>Пожалуйста, выберите другой ник.</div>";
    $forum_echo.= "<div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
    return;
  }

  $ru = se_db_query("
  UPDATE forum_users
  SET `nick`='$nick', `realname`='$realname', `location`='$location', `jobtitle`='$jobtitle', `interests`='$interests', `email`='$email', `icq`='$icq', `url`='$url', `origin`='$origin'
  WHERE id=$uid;"
  );
  //echo "<script>document.location.href='javascript:history.go(-2)';</script>";
  //exit();
}


$ru = se_db_query("
  SELECT forum_users.id, forum_users.id_status, forum_users.nick, forum_users.realname, forum_users.location,
         forum_users.jobtitle, forum_users.interests,forum_users.email, forum_users.icq, forum_users.url, forum_users.img,
         forum_users.enabled, forum_users.registered, forum_users.last, forum_users.origin,
         forum_status.id as sid, forum_status.name as status
  FROM forum_users, forum_status
  WHERE forum_users.id='$uid' AND forum_users.id_status=forum_status.id;"
);
//echo se_db_error();
$user=se_db_fetch_array($ru);

/*
if ($user['enabled']) {
  $forum_echo.= "Данный пользователь заблокирован!";
  $forum_echo.= "<div id=butlayer><input class=buttonForum id=btBack type=button onclick='javascript:history.go(-1)' value=\"Назад\"></div>";
  exit();
}
*/

$nick=htmlspecialchars($user['nick']);
$realname=htmlspecialchars($user['realname']);
$location=htmlspecialchars($user['location']);
$jobtitle=htmlspecialchars($user['jobtitle']);
$interests=htmlspecialchars($user['interests']);
$status=htmlspecialchars($user['status']);
$email=htmlspecialchars($user['email']);
$icq=htmlspecialchars($user['icq']);
$url=htmlspecialchars($user['url']);
$registered=date("d", $user['registered'])." ".$month_R[date("m", $user['registered'])].date(" Y года в H:i", $user['registered']);
$last=date("d", $user['last'])." ".$month_R[date("m", $user['last'])].date(" Y года в H:i", $user['last']);
if ($user['img']=="")
  $img="Отсутствует";
else {
  $sz=GetImageSize("modules/forum/images/".$user['img']);

  $img="<img id=pvt_AvtImg src=/modules/forum/images/".$user['img']."?".time().">
  <div id=pvt_AvtDown>Размер: ".$sz[0]."x".$sz[1]."px<br><a href='?act=myimg&delete&sid=$sid' id=pvt_linkDel>Удалить</a></div>";
}
$origin=stripslashes(htmlspecialchars($user['origin']));

$ra = se_db_query("
SELECT id
FROM forum_msg
WHERE id_users='$uid'"
);

$allmsg="<a id=pvt_linkUserMess href='?act=rsearch&user=$nick&forums[]=all&text=&result_type=messages&time=0'>".se_db_num_rows($ra)."</a>";

$forum_echo.="
<h3 class=forumTitle id=pvt_Title>Мои данные</h3>
<form action='?act=my' method='post'>
<table class=tableForum id=tablePvt>
<tbody class=tableBody>
<tr>
	<td class=title id=pvt_titleNick>
	    <div id=pvt_Nick>Ваш ник:</div>
	</td>
    <td class=field id=pvt_fieldNick>
    	<div id=pvt_UserNick>
        <input type=text class=inputForum id=pvt_inpNick name='nick' value='$nick'>
        </div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleRealName>
    	<div id=pvt_RealName>Реальное имя:</div>
    </td>
    <td class=field id=pvt_fieldRealName>
    	<div id=pvt_UserName>
        <input type=text class=inputForum id=pvt_inpName name='realname' value='$realname'>
        </div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleLocation>
    	<div id=pvt_Location>Местонахождение:</div>
    </td>
    <td class=field id=pvt_fieldLocation>
    	<div id=pvt_PvtLocation>
        <input type=text class=inputForum id=pvt_inpLocation name='location' value='$location'>
        </div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleStatus>
    	<div id=pvt_Status>Статус:</div>
    </td>
    <td class=field id=pvt_fieldStatus>
    	<div id=pvt_UserStatus>$status</div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleMessages>
    	<div id=pvt_Messages>Сообщений:</div>
    </td>
    <td class=field id=pvt_fieldMessages>
    	<div id=pvt_UserMess>$allmsg</div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleRegDate>
    	<div id=pvt_RegDate>Регистрация:</div>
    </td>
    <td class=field id=pvt_fieldRegDate>
    	<div id=pvt_UserRgDt>$registered</div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleLastVisit>
    	<div id=pvt_LastVisit>Последнее посещение:</div>
    </td>
    <td class=field id=pvt_fieldLastVisit>
    	<div id=pvt_UserLsVst>$last</div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleEmail>
    	<div id=pvt_Email>e-mail:</div>
    </td>
    <td class=field id=pvt_fieldEmail>
    	<div id=pvt_UserMail>
        <input type=text class=inputForum id=pvt_inpMail name='email' value='$email'>
        </div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleICQ>
    	<div id=pvt_ICQ>ICQ UIN:</div>
    </td>
    <td class=field id=pvt_fieldICQ>
    	<div id=pvt_UserICQ>
        <input type=text class=inputForum id=pvt_inpICQ name='icq' value='$icq'>
        </div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleURL>
    	<div id=pvt_URL>URL:</div>
    </td>
    <td class=field id=pvt_fieldURL>
    	<div id=pvt_UserURL>
        <input type=text class=inputForum id=pvt_inpURL name='url' value='$url'>
        </div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleJobTitle>
    	<div id=pvt_JobTitle>Род занятий:</div>
    </td>
    <td class=field id=pvt_fieldJobTitle>
    	<div id=pvt_PvtJobTitle>
        <input type=text class=inputForum id=pvt_inpJobTitle name='jobtitle' value='$jobtitle'>
        </div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleInterests>
    	<div id=pvt_Interests>Интересы:</div>
    </td>
    <td class=field id=pvt_fieldInterests>
    	<div id=pvt_PvtInterests>
        <input type=text class=inputForum id=pvt_inpInterests name='interests' value='$interests'>
        </div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleAvatar>
    	<div id=pvt_Avatar>Изображение:</div>
    </td>
    <td class=field id=pvt_fieldAvatar>
    	<div id=pvt_UserAvtr>$img</div>
    </td>
</tr>
<tr>
	<td class=title id=pvt_titleDesript>
    	<div id=pvt_Desript>Подпись:</div>
    </td>
    <td class=field id=pvt_fieldDesript>
    	<div id=pvt_UserDscr>
        <textarea class=areaForum id=pvt_AreaDscr name='origin'>$origin</textarea>
    	</div>
    </td>
</tr>
<TR><TD colspan=2>&nbsp;</TD></TR>
<TR><TD colspan=2>
<div id=pvt_ServicesButtons>
<input class=buttonForum id=pvt_btnSave type='submit' name='doGo' value='Сохранить'>
<input class=buttonForum id=pvt_btnUndo type='reset' name='Cancel' value='Отменить изменения'>
</div></form>
</TD></TR>
<TR><TD colspan=2>&nbsp;</TD></TR>
<TR><TD colspan=2>
<div id=pvt_LoadAvatar>
Загрузить изображение
<form action='?act=myimg&sid=$sid' method='post' enctype=multipart/form-data>
<input id=pvt_inpLoad type=file name='userfile'><input class=buttonForum id=pvt_btnload type=submit value='Загрузить'>
</form></div>
</TD></TR>
<TR><TD colspan=2>&nbsp;</TD></TR>
</tbody></table>
";

$forum_echo.= "<div id=pvt_Back><a href='javascript:history.go(-1)' id=pvt_linkBack>Назад</a></div>";
?>