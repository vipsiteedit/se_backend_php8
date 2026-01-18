<footer:js>
[js:jquery/jquery.min.js]  
<?php if(strval($section->parametrs->param11)=='Y'): ?>
[js:jquery/jquery.stickytableheaders.min.js]
<?php endif; ?>

<script type="text/javascript" src="[module_url]mshop_compare.js"></script>
<script type="text/javascript"> mshop_compare_execute({});</script>
</footer:js>
 <div class="content contShopCompare" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
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
    <div class="contentBody">
        <?php if(file_exists($__MDL_ROOT."/php/subpage_compare.php")) include $__MDL_ROOT."/php/subpage_compare.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_compare.tpl")) include $__data->include_tpl($section, "subpage_compare"); ?>
    </div>
</div>
