<header:js>
 [include_css]
</header:js>
<div class="<if:[param22]=='center'>content cont_mail_a cont_mail_a_center<else><if:[param22]=='inline'>content cont_mail_a cont_mail_a_inline<else>content cont_mail_a</if></if> <if:[param22]=='fixed'>container</if> part[part.id]"[contedit]>
 <noempty:part.title>
 <[part.title_tag] class="contentTitle"><span class="contentTitleTxt">[part.title]</span></[part.title_tag]>
 </noempty>
 <noempty:part.image><img alt="[part.image_alt]" title="[part.image_title]" border="0px" class="contentImage" src="[part.image]"></noempty>
 <noempty:part.text><div class="contentText">[part.text]</div> </noempty>
 <form action="" name="form" method="post">
  <div class="err">
   {$globalerr}<SE><span class="sysedit"> [param14]</span></SE>
  </div>
  <div class="field">
    <div class="caption">[param16]:</div>
   <input name="name" type="text" {$glob_err_stryle} value="{$name}" class="inpfield {$errstname}">
   <div class="err">{$nameerr}<SE><span class="sysedit"> [param6]</span></SE>
  </div>
 </div>
 
 
 <div class="field">
 <div class="caption">[param17]:</div>
  <input name="mail" type="text" {$glob_err_stryle} value="[$email]" class="inpfield {$errstmail}">
  <div class="err">[$emailerr]<SE><span class="sysedit"> [param8]</span></SE></div>
 </div>
 <div class="field">
  <div class="caption">[param18]:</div>
  <div class="blockarea"> <textarea cols="38" rows="6" class="inp {$errstnote} textarea" name="note" {$glob_err_stryle}>{$note}</textarea> </div>
  <div class="err">{$noteerr}<SE><span class="sysedit"> [param9]</span></SE> </div>
 </div>
 <if:[param3]=='Yes'>
 <div class="captcha">
     <div class="captcha_block">
        {$anti_spam}                
     </div>
 </div>
 </if>
 [subpage name=license]
 <if:{$errorlicense}!=''>
    <div class="error<SE> sysedit</SE>">
        <span>{$errorlicense}</span>
    </div>
 </if>
 <div class="field field-btn">
 <input class="btn buttonSend" id="Send" name="Send" {$glob_err_stryle} value="[param2]" [#"type="submit""][se."type="button" onClick="document.location.href='[link.subpage=send]';""] ></div>
</form>
<SE><br clear="all"><div <if:[param1]!=''>class="sysedit"</if>
style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black; "><b> [lang001]<br> [lang002] [param1]</b> </div></SE>
</div>
