<div class="content forum">
<?php if(file_exists($__MDL_ROOT."/php/subpage_19.php")) include $__MDL_ROOT."/php/subpage_19.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_19.tpl")) include $__MDL_ROOT."/tpl/subpage_19.tpl"; ?>
<?php if($enable==1): ?>
<div id="message_warning"><?php echo $section->language->lang131 ?></div>
<?php else: ?>
<?php if($enable==2): ?>
<div id="message_warning"><?php echo $section->language->lang132 ?>&nbsp;<?php echo $haltView ?>&nbsp;<?php echo $section->language->lang133 ?></div>
<?php else: ?>
<?php if(file_exists($__MDL_ROOT."/php/subpage_21.php")) include $__MDL_ROOT."/php/subpage_21.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_21.tpl")) include $__MDL_ROOT."/tpl/subpage_21.tpl"; ?>
<!--Начало тела страницы-->
<?php if($uid==0): ?>
<div id=message_warning><?php echo $section->language->lang025 ?></div>
<?php else: ?>
<?php if($error_exists!=0): ?>
<div id='message_warning'>
<?php echo $error ?>
</div>
<div id='butlayer'>
<input class='buttonSend' id='btBack' type='button' onclick='javascript:history.go(-1)' 
    value='<?php echo $section->language->lang126 ?>'>
</div>
<?php else: ?>
<!-- ------------------------- -->
<h3 class=forumTitle id='Mypvt_Title'><?php echo $section->language->lang026 ?></h3>
<table class='tableForum' id='tablePvt'>
<tbody class='tableBody'>
<form method='post' action="?sid=<?php echo $sid ?>" enctype='multipart/form-data'>
<tr>
<td class='title' id='pvt_titleNick'>
<div id='pvt_Nick'><?php echo $section->language->lang027 ?></div>
</td>
<td class='field' id='pvt_fieldNick'>
<div id='pvt_UserNick'>
<input type='text' class='inputForum' id='pvt_inpNick' name='nick' value='<?php echo $nick ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleRealName'>
<div id='pvt_RealName'><?php echo $section->language->lang028 ?></div>
</td>
<td class='field' id='pvt_fieldRealName'>
<div id='pvt_UserName'>
<input type='text' class='inputForum' id='pvt_inpName' name='realname' value='<?php echo $realname ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleLocation'>
<div id='pvt_Location'><?php echo $section->language->lang029 ?></div>
</td>
<td class='field' id='pvt_fieldLocation'>
<div id='pvt_UserLocation'>
<input type='text' class='inputForum' id='pvt_inpLocation' name='location' value='<?php echo $location ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleStatus'>
<div id='pvt_Status'><?php echo $section->language->lang030 ?></div>
</td>
<td class='field' id='pvt_fieldStatus'>
<div id='pvt_UserStatus'><?php echo $status ?></div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleMessages'>
<div id='pvt_Messages'><?php echo $section->language->lang031 ?></div>
</td>
<td class='field' id='pvt_fieldMessages'>
<div id='pvt_UserMess'>
<a id='pvt_linkUserMess' 
    href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub10/" ?>?user=<?php echo $uid ?>&forums[]=all&text=&result_type=messages&time=0">
<?php echo $msg_count ?>
</a>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titlePersonalMessages'>
<div id='pvt_PersonalMessages'><?php echo $section->language->lang032 ?></div>
</td>
<td class='field' id='pvt_fieldPersonalMessages'>
<div id='pvt_UserPersonalMess'>
<a id='pvt_linkUserPersonalMess' 
    href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?user=<?php echo $uid ?>">
<?php echo $msg_personalcount ?>
</a>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titlePutPersMessages'>
<div id='pvt_PutPersMessages'><?php echo $section->language->lang033 ?></div>
</td>
<td class='field' id='pvt_fieldPutPersMessages'>
<div id='pvt_UserPutPersMess'>
<a id='pvt_linkUserPutPersMess' 
    href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?puser=<?php echo $uid ?>">
<?php echo $msg_putpcount ?>
</a>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleRegDate'>
<div id='pvt_RegDate'><?php echo $section->language->lang034 ?></div>
</td>
<td class='field' id='pvt_fieldRegDate'>
<div id='pvt_UserRgDt'><?php echo $registered ?></div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleLastVisit'>
<div id='pvt_LastVisit'><?php echo $section->language->lang035 ?></div>
</td>
<td class='field' id='pvt_fieldLastVisit'>
<div id='pvt_UserLsVst'><?php echo $last ?></div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleEmail'>
<div id='pvt_Email'>e-mail:</div>
</td>
<td class='field' id='pvt_fieldEmail'>
<div id='pvt_UserMail'>
<input type='text' class='inputForum' id='pvt_inpMail' name='email' value='<?php echo $email ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleICQ'>
<div id=pvt_ICQ>ICQ UIN:</div>
</td>
<td class='field' id='pvt_fieldICQ'>
<div id='pvt_UserICQ'>
<input type='text' class='inputForum' id='pvt_inpICQ' name='icq' value='<?php echo $icq ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleURL'>
<div id='pvt_URL'>URL:</div>
</td>
<td class='field' id='pvt_fieldURL'>
<div id='pvt_UserURL'>
<input type='text' class='inputForum' id='pvt_inpURL' name='url' value='<?php echo $url ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleJobTitle'>
<div id='pvt_JobTitle'><?php echo $section->language->lang036 ?></div>
</td>
<td class='field' id='pvt_fieldJobTitle'>
<div id='pvt_UserJobTitle'>
<input type='text' class='inputForum' id='pvt_inpJobTitle' name='jobtitle' value='<?php echo $jobtitle ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleInterests'>
<div id='pvt_Interests'><?php echo $section->language->lang037 ?></div>
</td>
<td class='field' id='pvt_fieldInterests'>
<div id='pvt_UserInterests'>
<input type='text' class='inputForum' id='pvt_inpInterests' name='interests' value='<?php echo $interests ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleAvatar'>
<div id='pvt_Avatar'><?php echo $section->language->lang038 ?></div>
</td>
<td class='field' id='pvt_fieldAvatar'>
<?php if($img_exists!=0): ?>
<div id='pvt_UserAvtr'>
<img id='pvt_AvtImg' src="/modules/forum/images/<?php echo $img ?>">
<div id='pvt_AvtDown'>
<?php echo $section->language->lang123 ?>&nbsp;<?php echo $imgsz0 ?>x<?php echo $imgsz1 ?>px<br>
<a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub6/" ?>?delete&sid=<?php echo $sid ?>' id='pvt_linkDel'><?php echo $section->language->lang039 ?></a>
</div>
</div>
<?php else: ?>
-
<?php endif; ?>
</td>
</tr>
<tr>
<td class='title' id='pvt_titleDesript'>
<div id='pvt_Desript'><?php echo $section->language->lang040 ?></div>
</td>
<td class='field' id='pvt_fieldDesript'> 
<div id='pvt_UserDscr'>
<textarea class='areaForum' id='pvt_AreaDscr' name='origin'><?php echo $origin ?></textarea>
</div>
</td>
</tr>
<tr>
<td colspan=2>
<div id='pvt_LoadAvatar'>
<span id='loadimginfo'><?php echo $section->language->lang041 ?></span>
<input id='pvt_inpLoad' type='file' name='userfile'>
<input class='buttonSend' id='pvt_btnload' type='submit' name='upload' value='<?php echo $section->language->lang042 ?>'>
</div>
</td>
</tr>
<tr>
<td colspan=2>
<div id='pvt_ServicesButtons'>
<input class='buttonSend' id='pvt_btnSave' type='submit' name='doGo' value='<?php echo $section->language->lang043 ?>'>
<input class='buttonSend' id='pvt_btnUndo' type='reset' value='<?php echo $section->language->lang044 ?>'>
<input class='buttonSend' id='btBack' type='button' onclick='javascript:history.go(-1)' 
    value='<?php echo $section->language->lang126 ?>'>
</div>
</td>
</tr>
</form>
</tbody>
</table>
<!-- ------------------------- -->
<?php endif; ?>
<?php endif; ?>
<!--Конец тела страницы-->
<div id=footinfo><?php echo $section->language->lang143 ?>&nbsp;
<span id="allusers"><b><?php echo $section->language->lang144 ?>:</b>&nbsp;<?php echo $all_users ?></span>
<span id="regusers"><b><?php echo $section->language->lang145 ?>:</b>&nbsp;<?php echo $reg_users ?></span>
<?php if($reg_users!=0): ?>
(<?php foreach($section->regusers as $reguser): ?>
<a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $reguser->id ?>' class='reguser'><?php echo $reguser->name ?></a><?php echo $reguser->notend ?>

<?php endforeach; ?>)
<?php endif; ?>
<span id="guestusers"><b><?php echo $section->language->lang146 ?>:</b>&nbsp;<?php echo $guest ?></span>
<?php if($allrobots!=0): ?>
<b id='main_Robots'><?php echo $section->language->lang147 ?>:&nbsp;<?php echo $allrobots ?>&nbsp;
(<?php foreach($section->robots as $robot): ?>
<?php echo $robot->name ?><?php echo $robot->notend ?>

<?php endforeach; ?>)</b>
<?php endif; ?>
</div>
<?php endif; ?>
<?php endif; ?>
<?php if(file_exists($__MDL_ROOT."/php/subpage_20.php")) include $__MDL_ROOT."/php/subpage_20.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_20.tpl")) include $__MDL_ROOT."/tpl/subpage_20.tpl"; ?>
</div>
<!--05-->
