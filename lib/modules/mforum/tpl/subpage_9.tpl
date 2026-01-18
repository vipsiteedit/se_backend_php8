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
<form method='get' action='<?php echo seMultiDir()."/".$_page."/".$razdel."/sub10/" ?>'>
<h3 class='forumTitle' id='srch_Title'><?php echo $section->language->lang009 ?></h3>
<table class='tableForum' id='tableSrch'>
<tbody class='tableBody'>
<tr>
<td class='title' id='srch_titleWords'>
<div id='srch_Words'><?php echo $section->language->lang049 ?></div>
</td>
<td class='field' id='srch_fieldWords'>
<div id='srch_FPh'>
<input id='srch_inpWords' type='text' class='inputForum' name='text' value='<?php echo $what ?>'>
</div>
</td>
</tr>
<tr>
<td class='title' id='srch_titleInMess'>
<div id='srch_InMess'><?php echo $section->language->lang050 ?></div>
</td>
<td class='field' id='srch_fieldInMess'>
<div id='srch_Mess'>
<!--input id='srch_inpInMess' name='user' value=''-->
<select id='srch_inpInMess' name='user'>
<option value=0>--<?php echo $section->language->lang051 ?>--</option>
<?php foreach($section->users as $user): ?>
<option value=<?php echo $user->id ?>><?php echo $user->nick ?></option>

<?php endforeach; ?>
</select>
</div>
</td>
</tr>
<tr>
<td class='title' id='srch_titleWhere'>
<div id='srch_Where'><?php echo $section->language->lang052 ?></div>
</td>
<td class='field' id='srch_fieldWhere'>
<div id='srch_FPlc'>
<select id='srch_slcWhere' name='forums[]' size='6' multiple='multiple'>
<option value='all' selected='selected'>&raquo; <?php echo $section->language->lang053 ?></option>
<?php foreach($section->forum_sel as $fsel): ?>
<option value=<?php echo $fsel->id ?>><?php echo $fsel->name ?></option>

<?php endforeach; ?>
</select>
</div>
</td>
</tr>
<tr>
<td class='title' id='srch_titleInterv'>
<div id='srch_Interv'><?php echo $section->language->lang054 ?></div>
</td>
<td class='field' id='srch_fieldInterv'>
<div id='srch_SrcTim'>
<select id=srch_slcInterv name='time'>
<option value='<?php echo $time1 ?>'><?php echo $section->language->lang055 ?></option>
<option value='<?php echo $time7 ?>'><?php echo $section->language->lang056 ?></option>
<option value='<?php echo $time30 ?>'><?php echo $section->language->lang057 ?></option>
<option value='<?php echo $time60 ?>'><?php echo $section->language->lang058 ?></option>
<option value='<?php echo $time90 ?>'><?php echo $section->language->lang059 ?></option>
<option value='<?php echo $time180 ?>'><?php echo $section->language->lang060 ?></option>
<option value='<?php echo $time365 ?>'><?php echo $section->language->lang061 ?></option>
<option value='0' selected='selected'><?php echo $section->language->lang062 ?></option>
</select>
</div>
</td>
</tr>
<tr>
<td class='title' id='srch_titleResAs'>
<div id='srch_ResAs'><?php echo $section->language->lang063 ?></div>
</td>
<td class='field' id='srch_fieldResAs'>
<div id='srch_Reslt'>
<input id='srch_rdResAs' type='radio' name='result_type' value='topics' checked='checked'>
<label id='srch_lbRS' for='result_topics'><?php echo $section->language->lang064 ?></label>
<br>
<input id='srch_rdResAs' type='radio' name='result_type' value='messages'>
<label id='srch_lbRS' for='result_msg'><?php echo $section->language->lang065 ?></label>
</div>
</td>
</tr>
<tr>
<td colspan=2 align='center'>
<input class='buttonSend' id='srch_btnStart' type='submit' name='doGo' value='<?php echo $section->language->lang124 ?>'>
</td>
</tr>
</tbody>
</table>
</form>
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
<!--09-->
