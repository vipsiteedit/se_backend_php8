<?php if(!empty($section->title)): ?><h3 class="contentTitle" <?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></h3><?php endif; ?>
<?php if(!empty($section->image)): ?><img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>"><?php endif; ?>
<?php if(!empty($section->text)): ?><div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div><?php endif; ?>
