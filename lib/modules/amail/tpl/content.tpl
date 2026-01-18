<header:js>
 [include_css]
</header:js>
<div class="<?php if(strval($section->parametrs->param22)=='center'): ?>content cont_mail_a cont_mail_a_center<?php else: ?><?php if(strval($section->parametrs->param22)=='inline'): ?>content cont_mail_a cont_mail_a_inline<?php else: ?>content cont_mail_a<?php endif; ?><?php endif; ?> <?php if(strval($section->parametrs->param22)=='fixed'): ?>container<?php endif; ?> part<?php echo $section->id ?>">
 <?php if(!empty($section->title)): ?>
 <<?php echo $section->title_tag ?> class="contentTitle"><span class="contentTitleTxt"><?php echo $section->title ?></span></<?php echo $section->title_tag ?>>
 <?php endif; ?>
 <?php if(!empty($section->image)): ?><img alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>" border="0px" class="contentImage" src="<?php echo $section->image ?>"><?php endif; ?>
 <?php if(!empty($section->text)): ?><div class="contentText"><?php echo $section->text ?></div> <?php endif; ?>
 <form action="" name="form" method="post">
  <div class="err">
   <?php echo $globalerr ?>
  </div>
  <div class="field">
    <div class="caption"><?php echo $section->parametrs->param16 ?>:</div>
   <input name="name" type="text" <?php echo $glob_err_stryle ?> value="<?php echo $name ?>" class="inpfield <?php echo $errstname ?>">
   <div class="err"><?php echo $nameerr ?>
  </div>
 </div>
 
 
 <div class="field">
 <div class="caption"><?php echo $section->parametrs->param17 ?>:</div>
  <input name="mail" type="text" <?php echo $glob_err_stryle ?> value="<?php echo $email ?>" class="inpfield <?php echo $errstmail ?>">
  <div class="err"><?php echo $emailerr ?></div>
 </div>
 <div class="field">
  <div class="caption"><?php echo $section->parametrs->param18 ?>:</div>
  <div class="blockarea"> <textarea cols="38" rows="6" class="inp <?php echo $errstnote ?> textarea" name="note" <?php echo $glob_err_stryle ?>><?php echo $note ?></textarea> </div>
  <div class="err"><?php echo $noteerr ?> </div>
 </div>
 <?php if(strval($section->parametrs->param3)=='Yes'): ?>
 <div class="captcha">
     <div class="captcha_block">
        <?php echo $anti_spam ?>                
     </div>
 </div>
 <?php endif; ?>
 <?php if(file_exists($__MDL_ROOT."/php/subpage_license.php")) include $__MDL_ROOT."/php/subpage_license.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_license.tpl")) include $__data->include_tpl($section, "subpage_license"); ?>
 <?php if($errorlicense!=''): ?>
    <div class="error">
        <span><?php echo $errorlicense ?></span>
    </div>
 <?php endif; ?>
 <div class="field field-btn">
 <input class="btn buttonSend" id="Send" name="Send" <?php echo $glob_err_stryle ?> value="<?php echo $section->parametrs->param2 ?>" type="submit" ></div>
</form>

</div>
