<div class="blockContactLine blockContactName">
    <div class="blockRegLabel">
        <label for="reg_fio"><?php echo $section->language->lang031 ?></label><?php if($user_id==0): ?><span class="required">*</span><?php endif; ?>
    </div>
    <div class="blockRegInput">                
        <input value="<?php echo $contact_name ?>" <?php if($user_id!=0): ?>disabled<?php endif; ?> type="text" class="inputCartContact <?php if($error_name!=''): ?>inputCartError<?php endif; ?>" id="reg_fio" name="contact_name" >
        <?php if($error_name!=''): ?>
        <div class="regCartError "><?php echo $error_name ?></div>
        <?php endif; ?>
    </div>
</div>
<div class="blockContactLine blockContactEmail">
    <div class="blockRegLabel">
        <label for="reg_email">E-mail</label><span class="required">*</span>
    </div>
    <div class="blockRegInput">
        <input type="text" value="<?php echo $contact_email ?>" class="inputCartContact <?php if($error_email!=''): ?>inputCartError<?php endif; ?>" id="reg_email" name="contact_email">
        <?php if($error_email!=''): ?>
        <div class="regCartError "><?php echo $error_email ?></div>
        <?php endif; ?>
    </div>
</div>
<div class="blockContactLine blockContactPhone">
    <div class="blockRegLabel">
        <label for="reg_phone"><?php echo $section->language->lang032 ?></label><?php if($section->parametrs->param10=='Y'): ?><span class="required">*</span><?php endif; ?>
    </div>
    <div class="blockRegInput">
        <input type="text" value="<?php echo $contact_phone ?>" class="inputCartContact <?php if($error_phone!=''): ?>inputCartError<?php endif; ?>" id="reg_phone" name="contact_phone">
        <?php if($error_phone!=''): ?>
        <div class="regCartError "><?php echo $error_phone ?></div>
        <?php endif; ?>
    </div>            
</div>
<div class="blockContactLine blockContactPostIndex" <?php if($select_delivery['addr']==false): ?> style="display:none;"<?php endif; ?>>
    <div class="blockRegLabel">
        <label for="reg_post_index"><?php echo $section->language->lang016 ?></label><?php if($section->parametrs->param11=='Y'): ?><span class="required">*</span><?php endif; ?>
    </div>
    <div class="blockRegInput">
        <input type="text" value="<?php echo $contact_post_index ?>" class="inputCartContact <?php if($error_index!=''): ?>inputCartError<?php endif; ?>" id="contact_post_index" name="contact_post_index" >
        <?php if($error_index!=''): ?>
        <div class="regCartError "><?php echo $error_index ?></div>
        <?php endif; ?>
    </div> 
</div>
<div class="blockContactLine blockContactAddress"<?php if($select_delivery['addr']==false): ?> style="display:none;"<?php endif; ?>>
    <div class="blockRegLabel">
        <label for="reg_address"><?php echo $section->language->lang033 ?></label><span class="required">*</span>
    </div>
    <div class="blockRegInput">
        <textarea class="inputCartContact <?php if($error_address!=''): ?>inputCartError<?php endif; ?>" id="reg_address" name="contact_address"><?php echo $contact_address ?></textarea>
        <?php if($error_address!=''): ?>
        <div class="regCartError "><?php echo $error_address ?></div>
        <?php endif; ?>
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
<div id="requiredMessage"><?php echo $section->language->lang035 ?></div>
