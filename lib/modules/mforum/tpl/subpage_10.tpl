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
<?php if($find==0): ?>
<div id=message_warning>
<?php echo $section->language->lang066 ?>
</div>
<div id='butlayer'>
<input class='buttonSend' id='btBack' type='button' onclick='javascript:history.go(-1)' 
    value="<?php echo $section->language->lang126 ?>">
</div>
<?php else: ?>
<?php if($ipages!=0): ?>
<div id='steplist'>|
<?php foreach($section->ipages as $ipage): ?>
<?php if($ipage->status==1): ?>
<b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
<?php else: ?>
<a href="<?php echo $query ?>&part=<?php echo $ipage->ipage ?>" id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
<?php endif; ?>

<?php endforeach; ?>
</div>
<?php endif; ?>
<table class='tableForum' id='found_tableFound'>
<tbody class='tableBody'>
<tr>
<td class='title' id='found_titleTheme'>
<div id='found_Theme'><?php echo $section->language->lang067 ?></div>
</td>
<td class='title' id='found_titleForum'>
<div id='found_Forum'><?php echo $section->language->lang068 ?></div>
</td>
<td class='title' id='found_titleMessages'>
<div id='found_Messages'><?php echo $section->language->lang069 ?></div>
</td>
<td class='title' id='found_titleShowed'>
<div id='found_Showed'><?php echo $section->language->lang070 ?></div>
</td>
<td class='title' id='found_titleCreated'>
<div id='found_Created'><?php echo $section->language->lang071 ?></div>
</td>
<td class='title' id='found_titleRefreshed'>
<div id='found_Refreshed'><?php echo $section->language->lang072 ?></div>
</td>
</tr>
<?php foreach($section->frms as $frm): ?>
<tr>
<td class='field' id='found_fieldTheme'>
<div id='found_nameTheme'>
<a id='found_linkTheme' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?id=<?php echo $frm->id_topic ?>'><?php echo $frm->name ?></a>
</div>
</td>
<td class='field' id='found_fieldForum'>
<div id='found_nameForum'>
<a id='found_linkForum' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>?id=<?php echo $frm->id_forums ?>'><?php echo $frm->forumname ?></a>
</div>
</td>
<td class='field' id='found_fieldMessages'>
<div id='found_MessagesMount'><?php echo $frm->count ?></div>
</td>
<td class='field' id='found_fieldShowed'>
<div id='found_ShowedMount'><?php echo $frm->views ?></div>
</td>
<td class='field' id='found_fieldCreated'>
<div id='main_date'>
<span class=DataUpdate><?php echo $frm->date ?></span>,
<div id=main_autUpdate>
<?php echo $section->language->lang125 ?>
<a class='main_cellAuthorNickCr' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $frm->id_users ?>'><?php echo $frm->author ?></a>
</div>
</div>
</td>
<td class='field' id='found_fieldRefreshed'>
<div id='main_date'>
<span class=DataUpdate><?php echo $frm->dateNew ?></span>,
<div id='main_autUpdate'>
<?php echo $section->language->lang125 ?> 
<a class='main_cellAuthorNickCr' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $frm->id_usersNew ?>'><?php echo $frm->nick ?></a>
</div>
</div>
</td>
</tr>

<?php endforeach; ?>
</tbody>
</table>
<?php if($ipages!=0): ?>
<div id='steplist'>|
<?php foreach($section->ipages as $ipage): ?>
<?php if($ipage->status==1): ?>
<b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
<?php else: ?>
<a href="<?php echo $query ?>&part=<?php echo $ipage->ipage ?>" id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
<?php endif; ?>

<?php endforeach; ?>
</div>
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
<!--10-->
