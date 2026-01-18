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
<h3 class='forumTitle' id='area_AreaName'><?php echo $area ?></h3>
<table class='tableForum' id='table_showAreas'>
<tbody class='tableBody'>
<tr>
<td colspan=2 class='title' id='title_ShowForumName'>
<div id='area_ShowForumName'><?php echo $section->language->lang068 ?></div>
</td>
<td class='title' id='title_ShowThemMount'>
<div id='area_ShowThemMount'><?php echo $section->language->lang140 ?></div>
</td>
<td class='title' id='title_ShowModerName'>
<div id='area_ShowModerName'><?php echo $section->language->lang148 ?></div>
</td>
</tr>
<?php foreach($section->forums as $forum): ?>
<tr>
<td class='field' id='statustd'>
<div id="main_<?php echo $forum->StatusID ?>">&nbsp;</div>
</td>
<td class='field' id='field_ShowForumName'>
<div id='area_ForumName'>
<a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub2/" ?>?id=<?php echo $forum->id ?>" id='area_LinkForum'><?php echo $forum->name ?></a>
<div id='area_ShDescr'><?php echo $forum->description ?></div>
</div>
</td>
<td class='field' id='field_ShowThemMount'>
<div id='area_ThemMount'><?php echo $forum->count ?></div>
</td>
<td class='field' id='field_ShowModerName'>
<div id='area_ModerName'>
<a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub7/" ?>?id=<?php echo $forum->uid ?>" id='area_LinkModer'><?php echo $forum->nick ?></a>
</div>
</td>
</tr>

<?php endforeach; ?>
</tbody>
</table>
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
<!--01-->
