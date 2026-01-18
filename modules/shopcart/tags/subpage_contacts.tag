<div class="blockContactLine blockContactName">
    <div class="blockRegLabel">
        <label for="reg_fio">[lang031]</label><if:{$user_id}==0><span class="required">*</span></if>
    </div>
    <div class="blockRegInput">                
        <input value="{$contact_name}" <if:{$user_id}!=0>disabled</if> type="text" class="inputCartContact <noempty:{$error_name}>inputCartError</noempty>" id="reg_fio" name="contact_name" >
        <div class="regCartError <se>sysedit</se>"<empty:{$error_name}> style="display:none;"</empty>>{$error_name}</div>
    </div>
</div>
<div class="blockContactLine blockContactEmail">
    <div class="blockRegLabel">
        <label for="reg_email">E-mail</label><span class="required">*</span>
    </div>
    <div class="blockRegInput">
        <input type="text" value="{$contact_email}" class="inputCartContact<noempty:{$error_email}> inputCartError</noempty>" id="reg_email" name="contact_email">
        <div class="regCartError <se>sysedit</se>"<empty:{$error_email}> style="display:none;"</empty>>{$error_email}</div>
    </div>
</div>
<div class="blockContactLine blockContactPhone">
    <div class="blockRegLabel">
        <label for="reg_phone">[lang032]</label><if:[param10]=='Y'><span class="required">*</span></if>
    </div>
    <div class="blockRegInput">
        <input type="text" value="{$contact_phone}" class="inputCartContact<noempty:{$error_phone}> inputCartError</noempty>" id="reg_phone" name="contact_phone">
        <div class="regCartError <se>sysedit</se>"<empty:{$error_phone}> style="display:none;"</empty>>{$error_phone}</div>
    </div>            
</div>
<div class="blockContactLine blockContactPostIndex">
    <div class="blockRegLabel">
        <label for="reg_post_index">[lang016]</label><if:[param11]=='Y'><span class="required">*</span></if>
    </div>
    <div class="blockRegInput">
        <input id="test" type="text" value="{$contact_post_index}" class="inputCartContact<noempty:{$error_index}> inputCartError</noempty>" id="contact_post_index" name="contact_post_index" >
        <div class="regCartError <se>sysedit</se>"<empty:{$error_index}> style="display:none;"</empty>>{$error_index}</div>
    </div> 
</div>
<div class="blockContactLine blockContactComment">
    <div class="blockRegLabel">
        <label for="reg_comment">[lang034]</label>
    </div>
    <div class="blockRegInput">
        <textarea class="inputCartContact" id="reg_comment" name="contact_comment">{$contact_comment}</textarea>
    </div> 
</div>
<if:[param19]=='Y'>
    [subpage name=userfields]
</if>
<div id="requiredMessage">[lang035]</div>
<if:[param17]=='Y' && {$user_id}==0>
    [subpage name=requisite]
</if>
