<?php

if (!isset($_GET['id'])) {  //Показываем всех

  $ru = se_db_query("
    SELECT id, nick, enabled, registered
    FROM forum_users
    WHERE enabled='Y'
    ORDER BY nick;"
  );

  $forum_echo.= "<h3 class=forumTitle id=users_Title>Пользователи форума</h3>";
  $forum_echo.= "<h4 id=users_Count>На Форуме зарегистрировано пользователей: ".se_db_num_rows($ru)."</h4>";

  //Страницы
  if (isset($_GET['part']))
    $ext_part=htmlspecialchars($_GET['part'], ENT_QUOTES);
  else
    $ext_part=1;

  $n=ceil(se_db_num_rows($ru)/50);
  if ($ext_part>$n) $ext_part=$n;
  if ($ext_part<1) $ext_part=1;

  if (50<se_db_num_rows($ru)) {
    $forum_echo.= "<div id=steplist>|";
    for($i=1; $i<=$n; $i++)
      if ($i==$ext_part)
        $forum_echo.= "<b id=currentpart> $i </b>|";
      else
        $forum_echo.= "<a href='?act=showuser&part=$i' id=otherpart> $i </a>|";
    $forum_echo.= "</div>";
  }
  se_db_data_seek($ru, ($ext_part-1)*50);

  $forum_echo.= "<table class=tableForum id=table_showUsers><tbody class=tableBody>";
  $forum_echo.= "<tr><td class=title id=users_titleNick><div id=users_Nick>Ник</div></td>
                 <td class=title id=users_titleRegDate><div id=users_RegDate>Дата регистрации</div></td></tr>";

//  while ($user=se_db_fetch_array($ru)) {
  for ($i=1; ($i<=50) && ($user=se_db_fetch_array($ru)); $i++) {

    $nick="<a id=users_linkUserName href='?act=showuser&id=".$user['id']."'>".htmlspecialchars($user['nick'])."</a>";
    $date=date("d", $user['registered'])." ".$month_R[date("m", $user['registered'])].date(" Y года, H:i", $user['registered']);
    $forum_echo.= "<tr><td class=field id=users_fieldUserName><div id=users_UserName>$nick</div></td>
                   <td class=field id=users_fieldNumber><div id=users_Number>$date</div></td></tr>";
  }
  $forum_echo.= "</tbody></table>";
  $forum_echo.= "<div id=users_forLink><a href=\"javascript:history.go(-1)\" id=users_linkBack>Назад</a></div>";

}

else {  //Показываем одного

  $ext_id=htmlspecialchars($_GET['id'], ENT_QUOTES);
  if (!is_numeric($ext_id)) require "hack.php";

  $ru = se_db_query("
  SELECT forum_users.id, forum_users.id_status, forum_users.nick, forum_users.realname, forum_users.location,
         forum_users.email, forum_users.icq, forum_users.url, forum_users.img, forum_users.jobtitle,
         forum_users.enabled, forum_users.registered, forum_users.last, forum_users.origin, forum_users.interests,
         forum_status.id as sid, forum_status.name as status
  FROM forum_users, forum_status
  WHERE forum_users.id='$ext_id' AND forum_users.id_status=forum_status.id;"
  );

  $user=se_db_fetch_array($ru);

  $nick=stripslashes(htmlspecialchars($user['nick']));
  $realname=stripslashes(htmlspecialchars($user['realname']));
  $location=stripslashes(htmlspecialchars($user['location']));
  $jobtitle=stripslashes(htmlspecialchars($user['jobtitle']));
  $interests=stripslashes(htmlspecialchars($user['interests']));
  $status=stripslashes(htmlspecialchars($user['status']));
  $email=stripslashes(htmlspecialchars($user['email']));
  $icq=stripslashes(htmlspecialchars($user['icq']));
  $url=stripslashes(htmlspecialchars($user['url']));
  $registered=date("d", $user['registered'])." ".$month_R[date("m", $user['registered'])].date(" Y года в H:i", $user['registered']);
  $last=date("d", $user['last'])." ".$month_R[date("m", $user['last'])].date(" Y года в H:i", $user['last']);
  if ($user['img']=="") $img="Отсутствует"; else $img="<img id=user_AvtImg src=/modules/forum/images/".$user['img'].">";
  $origin=stripslashes(htmlspecialchars($user['origin'], ENT_QUOTES));
  $origin=str_replace("\n", "<br>", $origin);


    //Заменяем тэги
  $trans = array("[b]" => "<b>", "[/b]" => "</b>",
               "[em]" => "<em>", "[/em]" => "</em>",
               "[u]" => "<u>", "[/u]" => "</u>",
               "[ul]" => "<ul>", "[/ul]" => "</ul>",
               "[ol]" => "<ol>", "[/ol]" => "</ol>",
               "[quote]" => "<div id=quote>", "[/quote]" => "</div>");

  $origin=strtr($origin, $trans);

  //Заменяем url
  $origin=eregi_replace("\[a +href=([^]]+)\]([^]]+)\[/a\]", '<a id=user_linkSite href="\\1">\\2</a>', $origin);

  //Заменяем mailto
  $origin=eregi_replace("\[mailto=([^]]+)\]([^]]+)\[/mailto\]", '<a id=user_linkEmail href="mailto:\\1">\\2</a>', $origin);

  //Заменяем img
  $origin=eregi_replace("\[img +src=([^]]+)\]", '<img src="\\1">', $origin);

  //Заменяем смайлики
  $origin=eregi_replace("\[smile([[:digit:]]+)\]", "<img src='$smilesURL/smile\\1.gif'>", $origin);

  //Заменяем цвет
  $origin=preg_replace("/\[color *= *(#[\d|A-F|a-f]+)\]([^\]]+)\[\/color\]/i", "<font color='$1'>$2</font>", $origin);

  $ra = se_db_query("
  SELECT id
  FROM forum_msg
  WHERE id_users='$ext_id'"
  );

  $allmsg="<a id=user_linkUserMess href='?act=rsearch&user=$nick&forums[]=all&text=&result_type=messages&time=0'>".se_db_num_rows($ra)."</a>";
  // $email = '<a href=\"mailto:'.$email.'\">'.$email.'</a>';
  // $url   = '<a href=\"http://'.$url.'\">'.$url.'</a>';

  $forum_echo.="
  <h3 class=forumTitle id=user_Title>Данные пользователя<?=$nick?></h3>
  <form action='?act=my' method='post'>
  <table class=tableForum id=tablePvt><tbody class=tableBody>
  <tr>
  	<td class=title id=pvt_titleNick>
    	<div id=pvt_Nick>Ник:</div>
    </td>
    <td class=field id=pvt_fieldNick>
    	<div id=pvt_UserNick>$nick</div>
    </td>
  </tr>
  <tr>
  	<td class=title id=pvt_titleRealName>
    	<div id=pvt_RealName>Реальное имя:</div>
    </td>
    <td class=field id=pvt_fieldRealName>
    	<div id=pvt_UserName>$realname&nbsp;</div>
    </td>
  </tr>
  <tr>
  	<td class=title id=pvt_titleLocation>
    	<div id=pvt_Location>Местонахождение:</div>
    </td>
    <td class=field id=pvt_fieldLocation>
    	<div id=pvt_UserLocation>$location&nbsp;</div>
    </td>
  </tr>
  <tr>
  	<td class=title id=pvt_titleStatus>
    	<div id=pvt_Status>Статус:</div>
    </td>
    <td class=field id=pvt_fieldStatus>
    	<div id=pvt_UserStatus>$status&nbsp;</div>
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
    	<div id=pvt_UserRgDt>$registered&nbsp;</div>
        </td>
    </tr>
  <tr>
  	<td class=title id=pvt_titleLastVisit>
    	<div id=pvt_LastVisit>Последнее посещение:</div>
    </td>
    <td class=field id=pvt_fieldLastVisit>
    	<div id=pvt_UserLsVst>$last&nbsp;</div>
    </td>
  </tr>
  <tr>
  	<td class=title id=pvt_titleEmail>
    	<div id=pvt_Email>e-mail:</div>
    </td>
    <td class=field id=pvt_fieldEmail>
    	<div id=pvt_UserMail>$email&nbsp;</div>
    </td>
  </tr>
  <tr>
  	<td class=title id=pvt_titleICQ>
    	<div id=pvt_ICQ>ICQ UIN:</div>
    </td>
    <td class=field id=pvt_fieldICQ>
    	<div id=pvt_UserICQ>$icq&nbsp;</div>
    </td>
  </tr>
  <tr>
  	<td class=title id=pvt_titleURL>
    	<div id=pvt_URL>URL:</div>
    </td>
    <td class=field id=pvt_fieldURL>
    	<div id=pvt_UserURL>$url&nbsp;</div>
    </td>
  </tr>
  <tr>
  	<td class=title id=pvt_titleJobTitle>
    	<div id=pvt_JobTitle>Род занятий:</div>
    </td>
    <td class=field id=pvt_fieldJobTitle>
    	<div id=pvt_UserJobTitle>$jobtitle&nbsp;</div>
    </td>
  </tr>
  <tr>
  	<td class=title id=pvt_titleInterests>
    	<div id=pvt_Interests>Интересы:</div>
    </td>
    <td class=field id=pvt_fieldInterests>
    	<div id=pvt_UserInterests>$interests&nbsp;</div>
    </td>
  </tr>
  <tr>
  	<td class=title id=pvt_titleAvatar>
    	<div id=pvt_Avatar>Изображение:</div>
    </td>
    <td class=field id=pvt_fieldAvatar>
    	<div id=pvt_UserAvtr>$img&nbsp;</div>
    </td>
  </tr>
  <tr>
  	<td class=title id=pvt_titleDesript>
    	<div id=pvt_Desript>Подпись:</div>
    </td>
    <td class=field id=pvt_fieldDesript>
    	<div id=pvt_UserDscr>$origin&nbsp;</div>
    </td>
  </tr>
  </tbody></table>";

  //Страницы
   if (50<se_db_num_rows($ru)) {
    $forum_echo.= "<div id=steplist>|";
    for($i=1; $i<=$n; $i++)
      if ($i==$ext_part)
        $forum_echo.= "<b id=currentpart> $i </b>|";
      else
        $forum_echo.= "<a href='?act=showuser&part=$i' id=otherpart> $i </a>|";
    $forum_echo.= "</div>";
  }

  $forum_echo.= "<div id=user_forLink><a href=\"javascript:history.go(-1)\" id=user_linkBack>Назад</a></div>";
}


?>