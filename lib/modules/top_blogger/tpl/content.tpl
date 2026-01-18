<header:css>
    [lnk:bootstrap/css/bootstrap.min.css]
</header:css>
<header:js>
    [js:jquery/jquery.min.js]
    [js:bootstrap/bootstrap.min.js]
    <script type="text/javascript" src="[module_url]engine.js"></script>    
</header:js>
<div class="content topblogger" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="workspace row">
        <?php if(file_exists($__MDL_ROOT."/php/subpage_lists.php")) include $__MDL_ROOT."/php/subpage_lists.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_lists.tpl")) include $__data->include_tpl($section, "subpage_lists"); ?>
        <div class="debut row">
           <a href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subdebuted/" ?>" class="btn btn-link debut_a"><?php echo $section->language->lang001 ?></a>
        </div>
    </div>
</div>
