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
<div id="forumPath">
<a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>?id=<?php echo $aid ?>' id='forums'><?php echo $forumArea ?></a>
</div>
<div id='showForumModerator'><?php echo $section->language->lang148 ?>:
<?php if($moderator_exists!=0): ?>
<a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $moduid ?>' id='showForumModeratorNick'><?php echo $moderator ?></a>
<?php else: ?>
<?php echo $section->language->lang149 ?>
<?php endif; ?>
<?php if($ismoderator!=0): ?>
<a id='showMdrLink' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub16/" ?>?id=<?php echo $forumId ?>'><?php echo $section->language->lang150 ?></a>
<?php endif; ?>
</div>
<div id='showForumTopics'><?php echo $section->language->lang140 ?>:&nbsp;<?php echo $themes ?></div>
<a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub4/" ?>?id=<?php echo $forumId ?>&newt" id='showForumNewTopic'><?php echo $section->language->lang151 ?></a>
<?php if($ipages!=0): ?>
<div id='steplist'>|
<?php foreach($section->ipages as $ipage): ?>
<?php if($ipage->status==1): ?>
<b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
<?php else: ?>
<a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>?id=<?php echo $ext_id ?>&part=<?php echo $ipage->ipage ?>" id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
<?php endif; ?>

<?php endforeach; ?>
</div>
<?php endif; ?>
<h3 class='forumTitle' id='titleForumName'><?php echo $forumName ?></h3>
<table class='tableForum' id='table_showForums'>
<tbody class='tableBody'>
<tr>
<td colspan=2 class='title' id='title_ShowForumTopic'>
<div id='show_ShowForumTopic'><?php echo $section->language->lang067 ?></div>
</td>
<td class='title' id='title_ShowForumMsgs'>
<div id='show_ShowForumMsgs'><?php echo $section->language->lang069 ?></div>
</td>
<td class='title' id='title_ShowForumViews'>
<div id='show_ShowForumViews'><?php echo $section->language->lang070 ?></div>
</td>
<td class='title' id='title_ShowForumCreate'>
<div id='show_ShowForumCreate'><?php echo $section->language->lang071 ?></div>
</td>
<td class='title' id='title_ShowForumRefresh'>
<div id='show_ShowForumRefresh'><?php echo $section->language->lang072 ?></div>
</td>
</tr>
<?php if($themes==0): ?>
<tr>
<td colspan=6 id='field_Error' class='field'>
<div id='show_Error'><?php echo $section->language->lang152 ?></div>
</td>
</tr>
<?php else: ?>
<?php foreach($section->themes as $theme): ?>
<tr>
<td class='field' id='statustd'>
<div id="main_<?php echo $theme->StatusID ?>"></div>
</td>
<td class='field' id='field_ShowForumTopic'>
<div id='show_Theme'>
<a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?id=<?php echo $theme->id ?>" id='show_LinkTopic'>
<?php echo $theme->name ?>
</a>
<?php if($theme->parts!=0): ?>
<div id=show_MsgNum><?php echo $section->language->lang153 ?>:
<?php if($theme->parts==2): ?>
<a id='show_NextNum' href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?id=<?php echo $theme->id ?>&part=1">1</a>, ... 
<?php endif; ?>
<?php $__list = 'parts'.$theme->id; foreach($section->$__list as $ipart): ?>
<a id='show_NextNum' href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>?id=<?php echo $theme->id ?>&part=<?php echo $ipart->part ?>"><?php echo $ipart->part ?></a><?php echo $ipart->next ?>

<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</td>
<td class='field' id='field_ShowForumMsgs'>
<div id='show_ThemMount'>
<?php echo $theme->count ?>
</div>
</td>
<td class='field' id='field_ShowForumViews'>
<div id='show_ThemShow'>
<?php echo $theme->views ?>
</div>
</td>
<td class='field' id='field_ShowForumCreate'>
<div id='main_date'>
<?php echo $theme->date ?>,
<div id='main_autUpdate'>
<?php echo $section->language->lang141 ?>: 
<a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $theme->id_users ?>" id='main_cellAuthorNickCr'><?php echo $theme->author ?></a>
</div>
</div>
</td>
<td class='field' id='field_ShowForumRefresh'>
<div id='main_date'>
<?php echo $theme->dateNew ?>,
<div id='main_autUpdate'>
<?php echo $section->language->lang141 ?>: 
<a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $theme->id_usersNew ?>" id='main_cellAuthorNickCr'><?php echo $theme->nick ?></a>
</div>
</div>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
<?php if($ipages!=0): ?>
<div id='steplist'>|
<?php foreach($section->ipages as $ipage): ?>
<?php if($ipage->status==1): ?>
<b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
<?php else: ?>
<a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>?id=<?php echo $ext_id ?>&part=<?php echo $ipage->ipage ?>" id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
<?php endif; ?>

<?php endforeach; ?>
</div>
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
<!--02-->
