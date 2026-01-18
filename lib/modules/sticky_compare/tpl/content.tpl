<footer:css>
[lnk:rouble/rouble.css]
</footer:css>
<footer:js>
[js:jquery/jquery.min.js]
<script src="[this_url_modul]iscroll-probe.min.js"></script>
[include_js({
    id: <?php echo $section->id ?>,
    hide_time: <?php echo $section->parametrs->param11 ?>,
    min_visible_area: <?php echo $section->parametrs->param12 ?> 
})]
</footer:js>
<div class="content sticky_compare<?php if(strval($section->parametrs->param13)=='n'): ?> container<?php else: ?> container-fluid<?php endif; ?>" data-content-type="<?php echo $section->type ?>" data-content-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody">
        <?php if(file_exists($__MDL_ROOT."/php/subpage_2.php")) include $__MDL_ROOT."/php/subpage_2.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_2.tpl")) include $__data->include_tpl($section, "subpage_2"); ?>
    </div>
</div>
