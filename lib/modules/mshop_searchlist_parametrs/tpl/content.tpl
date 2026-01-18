<?php if($showSearchBlock): ?>
<div class="content shopSearchList" <?php echo $section->style ?>> 
<?php if($section->title!=''): ?> 
  <h3 class="contentTitle" <?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span> </h3> 
<?php endif; ?> 
<?php if($section->image!=''): ?> 
  <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
<?php endif; ?> 
<?php if($section->text!=''): ?> 
  <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div> 
<?php endif; ?> 
<?php if(file_exists($__MDL_ROOT."/php/subpage_1.php")) include $__MDL_ROOT."/php/subpage_1.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_1.tpl")) include $__MDL_ROOT."/tpl/subpage_1.tpl"; ?>   
</div>
<?php endif; ?>
