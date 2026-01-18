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
<?php if($thispage!=0): ?>
<?php if($_message_exists!=0): ?>
<div id='message_warning'>
<?php echo $_message ?>
</div>
<div id='butlayer'>
<input class='buttonSend' id='btBack' type='button' 
    onclick='document.location="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?id=<?php echo $topic ?>";' value="<?php echo $section->language->lang126 ?>">
</div>
<?php else: ?>
<form name='mailform' id='mailform' method='POST'>
<h3 class='forumTitle' id='mess_Title'>
<?php echo $section->language->lang076 ?>
</h3>
<table class='tableForum' id='table_PvtMesg'>
<tbody class='tableBody'>
<tr>
<td class='title' id='msg_titleFrom'>
<div id='msg_tForm'>
<?php echo $section->language->lang077 ?>
</div>
</td>
<td class='field' id='msg_fieldFrom'>
<div id='msg_fFrom'>
<?php echo $nick ?>
<input class='inputForum' id='msg_inpFrom' type='text' value='<?php echo $mailfrom ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='msg_titleTo'>
<div id='msg_tTo'>
<?php echo $section->language->lang017 ?>
</div>
</td>
<td class='field' id='msg_fieldTo'>
<div id='msg_fTo'>
<?php echo $userto ?>
</div>
</td>
</tr>       
<tr>
<td class='title' id='msg_titleTheme'>
<div id='msg_tTheme'>
<?php echo $section->language->lang067 ?>
</div>
</td>
<td class='field' id='msg_fieldTheme'>
<div id='msg_fTheme'>
<input class='inputForum' id='msg_inpTheme' type='text' value='<?php echo $subject ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='msg_titleMessage'>
<div id='msg_tMessage'>
<?php echo $section->language->lang078 ?>
</div>
</td>
<td class='field' id='msg_fieldMessage'>
<div id='msg_fMessage'>
<textarea class='areaForum' id='msg_arMessage' name='message'></textarea>
</div>
</td>
</tr>
<tr>
<td>
<div id='msg_ServiceButns'>
<input class='buttonSend' id='msg_btSend' name='doGo' type='submit' value=<?php echo $section->language->lang079 ?>>
<input class='buttonSend' id='msg_btClear' type='reset' value=<?php echo $section->language->lang080 ?>>
</div>
<input type='hidden' name='topicid' value='<?php echo $ext_topic ?>'>
<input type='hidden' name='userfrom' value='<?php echo $nick ?>'>
<input type='hidden' name='mailfrom' value='<?php echo $mailfrom ?>'>
<input type='hidden' name='idto' value='<?php echo $ext_id ?>'>
<input type='hidden' name='subject' value='<?php echo $subject ?>'>
</td>
</tr>
</tbody>
</table>
</form>
<script>
    document.all.mailform.msg_arMessage.focus();
</script>
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
<!--15-->
