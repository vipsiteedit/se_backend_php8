<?php if($footer_text!=''): ?>
<div class="content shopgroupinfo" <?php echo $section->style ?>>
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>                         
    <?php if($section->image!=''): ?>
        <img class="contentImage" alt="<?php echo $section->image_alt ?>" src="<?php echo $section->image ?>" border="0" <?php echo $section->style_image ?>>
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody">
            <?php echo $footer_text ?>
    </div>
</div><?php endif; ?>
