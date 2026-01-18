<footer:js>
[js:jquery/jquery.min.js]
[include_js({
    part_id: '<?php echo $section->id ?>',
    url_end: '<?php echo $url_end ?>',
    param4: '<?php echo $section->parametrs->param4 ?>',
    param9: '<?php echo $section->parametrs->param9 ?>',
    param13: '<?php echo $section->parametrs->param13 ?>',
    multilink: '<?php echo $multilink ?>'
})]
</footer:js>
<div class="content shopGrouppic part<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->title ?>" border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="groupList">
        <?php echo $PRICEMENU ?>
        
    </div>
</div> 
