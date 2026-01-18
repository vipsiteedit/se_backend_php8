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
<a class='menuitem' href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>?id=<?php echo $aid ?>' id='forums'><?php echo $fArea ?></a>
</div>
<?php if($thispage!=0): ?>
<?php if($message_exists!=0): ?>
<div id='message_warning'><?php echo $_message ?></div>
<?php else: ?>
<div id='mdr_ThNum'>
<?php echo $section->language->lang081 ?>:&nbsp;
<span id='countTh'><?php echo $allTopic ?></span>
</div>
<?php if($ipages!=0): ?>
<div id='steplist'>|
<?php foreach($section->ipages as $ipage): ?>
<?php if($ipage->status==1): ?>
<b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
<?php else: ?>
<a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub16/" ?>?id=<?php echo $ext_id ?>&part=<?php echo $ipage->ipage ?>' id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
<?php endif; ?>

<?php endforeach; ?>
</div>
<?php endif; ?>
<form method='POST'>
<h3 class='forumTitle' id='mdr_Title'><?php echo $section->language->lang012 ?> &quot;<?php echo $forumName ?>&quot;</h3>
<table class='tableForum' id='mdr_Table'>
<tbody class='tableBody'>
<tr>
<td class='title' id='mdr_titleCl'>&nbsp;</td>
<td class='title' id='mdr_titleTh'>
<div id='mdr_tTh'>
<?php echo $section->language->lang067 ?>
</div>
</td>
<td class='title' id='mdr_titleSt'>
<div id='mdr_tSt'>
<?php echo $section->language->lang082 ?>
</div>
</td>
<td class='title' id='mdr_titleVis'>
<div id='mdr_tVis'>
<?php echo $section->language->lang083 ?>
</div>
</td>
<td class='title' id='mdr_titleCrt'>
<div id='mdr_tCrt'>
<?php echo $section->language->lang071 ?>
</div>
</td>
<td class='title' id='mdr_titleUpd'>
<div id='mdr_tUpd'>
<?php echo $section->language->lang072 ?>
</div>
</td>
</tr>
<?php foreach($section->themes as $theme): ?>
<tr>
<td class='field' id='mdr_fieldCl'>
<div id='mdr_fCl'>
<input type='checkbox' name='checked[]' value='<?php echo $theme->id ?>' id='mdr_chbSel'>
</div>
</td>
<td class='field' id='mdr_fieldTh'>
<div id='mdr_fTh'><?php echo $theme->name ?></div>
</td>
<td class='field' id='mdr_fieldSt'>
<?php if($theme->enable!=0): ?>
<div id='mdr_fStOn'><?php echo $section->language->lang084 ?></div>

<?php else: ?>
<div id='mdr_fStOff'><?php echo $section->language->lang085 ?></div>
<?php endif; ?>
</td>
<td class='field' id='mdr_fieldVis'>
<?php if($theme->visible!=0): ?>
<div id='mdr_fVisOn'><?php echo $section->language->lang086 ?></div>

<?php else: ?>
<div id='mdr_fVisOff'><?php echo $section->language->lang087 ?></div>
<?php endif; ?>
</td>
<td class='field' id='mdr_fieldCrt'>
<div id='main_date'><?php echo $theme->date ?></div>
</td>
<td class='field' id='mdr_fieldUpd'>
<div id='main_date'><?php echo $theme->dateNew ?></div>
</td>
</tr>

<?php endforeach; ?>
<tr>
<td colspan=6>
<div id='mdr_SrvBtns'>
<input type='submit' class='buttonSend' id='mdr_bOp' name='doOpen' value='<?php echo $section->language->lang088 ?>'>
<input type='submit' class='buttonSend' id='mdr_bCls' name='doClose' value='<?php echo $section->language->lang089 ?>'>
<input type='submit' class='buttonSend' id='mdr_bOn' name='doOn' value='<?php echo $section->language->lang090 ?>'>
<input type='submit' class='buttonSend' id='mdr_bOff' name='doOff' value='<?php echo $section->language->lang091 ?>'>
<input type='submit' class='buttonSend' id='mdr_bUp' name='doUp' value='<?php echo $section->language->lang092 ?>'>
<input type='submit' class='buttonSend' id='mdr_bDown' name='doDown' value='<?php echo $section->language->lang093 ?>'>
<input type='submit' class='buttonSend' id='mdr_bDel' name='doDel' value='<?php echo $section->language->lang039 ?>' 
    onclick='return confirmDel();'>
</div>
<script>
    function confirmDel() {
        var is_confirmed = confirm('<?php echo $section->language->lang094 ?>');
        return is_confirmed;
    }
</script>
</td>
</tr>
</tbody>
</table>
</form>
<?php if($ipages!=0): ?>
<div id='steplist'>|
<?php foreach($section->ipages as $ipage): ?>
<?php if($ipage->status==1): ?>
<b id='currentpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</b>|
<?php else: ?>
<a href='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub16/" ?>?id=<?php echo $ext_id ?>&part=<?php echo $ipage->ipage ?>' id='otherpart'>&nbsp;<?php echo $ipage->ipage ?>&nbsp;</a>|
<?php endif; ?>

<?php endforeach; ?>
</div>
<?php endif; ?>
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
<!--16-->
