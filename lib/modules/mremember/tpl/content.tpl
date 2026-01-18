<div class="content contRemember" <?php echo $section->style ?>>
<?php if(!empty($section->title)): ?>
  <h3 class="contentTitle"<?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></h3>
<?php endif; ?>
<?php if(!empty($section->image)): ?>
  <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
<?php endif; ?>
<?php if(!empty($section->text)): ?>
  <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
<?php endif; ?>
<div class="warning sysedit"><?php echo $error_message ?></div>
  <form style="margin:0px;" action="" method="post">
    <div class="obj name">
      <label><?php echo $section->language->lang004 ?></label>
      <div><input size="30" name="name" value="<?php echo $name ?>"></div>
    </div>
    <div class="lTitle"><?php echo $section->language->lang005 ?></div>
    <div class="obj email">
      <label><?php echo $section->language->lang006 ?></label>
      <div><input size="30" name="email" value="<?php echo $email ?>"></div>
    </div>
    <div class="forgetPass"><?php echo $section->language->lang011 ?></div>
    <div class="buttonArea"> 
      <input class="buttonSend" name="GoToRemember" type="submit"  value="<?php echo $section->language->lang007 ?>">
    </div>
  </form>
</div>
