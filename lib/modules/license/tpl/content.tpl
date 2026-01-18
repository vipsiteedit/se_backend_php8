<footer:js>
[js:jquery/jquery.min.js]
[lnk:fancybox2/jquery.fancybox.css] 
[js:fancybox2/jquery.fancybox.pack.js]
[include_js()]
</footer:js>
<div class="content contLicense textLicense" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?> style="display:none;">
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"><span class="contentTitleTxt"><?php echo $section->title ?></span></<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
</div>
