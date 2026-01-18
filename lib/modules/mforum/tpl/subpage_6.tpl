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
<?php if($showpage!=0): ?>
<?php if($error_exists!=0): ?>
<div id='message_warning'>
<?php echo $error ?>
</div>
<div id='butlayer'>
<input class='buttonSend' id='btBack' type='button' onclick='javascript:history.go(-1)' 
    value='<?php echo $section->language->lang126 ?>'>
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
<!--06-->
