<div class="blockContactLine blockContactName">
    <div class="blockRegLabel">
        <label for="reg_fio"><?php echo $section->language->lang031 ?></label><?php if($user_id==0): ?><span class="required">*</span><?php endif; ?>
    </div>
    <div class="blockRegInput">                
        <input value="<?php echo $contact_name ?>" <?php if($user_id!=0): ?>disabled<?php endif; ?> type="text" class="inputCartContact <?php if(!empty($error_name)): ?>inputCartError<?php endif; ?>" id="reg_fio" name="contact_name" >
        <div class="regCartError "<?php if(empty($error_name)): ?> style="display:none;"<?php endif; ?>><?php echo $error_name ?></div>
    </div>
</div>
<div class="blockContactLine blockContactEmail">
    <div class="blockRegLabel">
        <label for="reg_email">E-mail</label><span class="required">*</span>
    </div>
    <div class="blockRegInput">
        <input type="text" value="<?php echo $contact_email ?>" class="inputCartContact<?php if(!empty($error_email)): ?> inputCartError<?php endif; ?>" id="reg_email" name="contact_email">
        <div class="regCartError "<?php if(empty($error_email)): ?> style="display:none;"<?php endif; ?>><?php echo $error_email ?></div>
    </div>
</div>
<div class="blockContactLine blockContactPhone">
    <div class="blockRegLabel">
        <label for="reg_phone"><?php echo $section->language->lang032 ?></label><?php if(strval($section->parametrs->param10)=='Y'): ?><span class="required">*</span><?php endif; ?>
    </div>
    <div class="blockRegInput">
        <input type="text" value="<?php echo $contact_phone ?>" class="inputCartContact<?php if(!empty($error_phone)): ?> inputCartError<?php endif; ?>" id="reg_phone" name="contact_phone">
        <div class="regCartError "<?php if(empty($error_phone)): ?> style="display:none;"<?php endif; ?>><?php echo $error_phone ?></div>
    </div>            
</div>
<div class="blockContactLine blockContactPostIndex">
    <div class="blockRegLabel">
        <label for="reg_post_index"><?php echo $section->language->lang016 ?></label><?php if(strval($section->parametrs->param11)=='Y'): ?><span class="required">*</span><?php endif; ?>
    </div>
    <div class="blockRegInput">
        <input id="test" type="text" value="<?php echo $contact_post_index ?>" class="inputCartContact<?php if(!empty($error_index)): ?> inputCartError<?php endif; ?>" id="contact_post_index" name="contact_post_index" >
        <div class="regCartError "<?php if(empty($error_index)): ?> style="display:none;"<?php endif; ?>><?php echo $error_index ?></div>
    </div> 
</div>
<div class="blockContactLine blockContactComment">
    <div class="blockRegLabel">
        <label for="reg_comment"><?php echo $section->language->lang034 ?></label>
    </div>
    <div class="blockRegInput">
        <textarea class="inputCartContact" id="reg_comment" name="contact_comment"><?php echo $contact_comment ?></textarea>
    </div> 
</div>
<?php if(strval($section->parametrs->param19)=='Y'): ?>
    <?php if(file_exists($__MDL_ROOT."/php/subpage_userfields.php")) include $__MDL_ROOT."/php/subpage_userfields.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_userfields.tpl")) include $__data->include_tpl($section, "subpage_userfields"); ?>
<?php endif; ?>
<div id="requiredMessage"><?php echo $section->language->lang035 ?></div>
<?php if(strval($section->parametrs->param17)=='Y' && $user_id==0): ?>
    <?php if(file_exists($__MDL_ROOT."/php/subpage_requisite.php")) include $__MDL_ROOT."/php/subpage_requisite.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_requisite.tpl")) include $__data->include_tpl($section, "subpage_requisite"); ?>
<?php endif; ?>
