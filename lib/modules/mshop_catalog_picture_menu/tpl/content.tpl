<footer:js>
[js:jquery/jquery.min.js]

[include_js({action:'<?php echo $section->parametrs->param15 ?>'})]

</footer:js>
<div class="content shopGrouppicMenu" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->title ?>" border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="groupList">
        <?php if(file_exists($__MDL_ROOT."/php/subpage_menu.php")) include $__MDL_ROOT."/php/subpage_menu.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_menu.tpl")) include $__data->include_tpl($section, "subpage_menu"); ?>
        
    </div>
</div> 
