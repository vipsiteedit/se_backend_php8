<footer:js>[include_js()]</footer:js>
<header:js>
[lnk:rouble/rouble.css]
</header:js>
<footer:js>
[js:jquery/jquery.min.js]
<?php if(strval($section->parametrs->param17)=='Y'): ?>
[js:ui/jquery.ui.min.js]
<?php endif; ?>
[lnk:fancybox2/jquery.fancybox.css] 
[js:fancybox2/jquery.fancybox.pack.js] 
[include_js({'id':<?php echo $section->id ?>,
    'param4':'<?php echo $section->parametrs->param4 ?>',
    'param11':'<?php echo $section->language->lang020 ?>',
    'param16':'<?php echo $section->parametrs->param16 ?>',
    'param17':'<?php echo $section->parametrs->param17 ?>',
    'param19':'<?php echo $section->parametrs->param19 ?>',
    'param20':'<?php echo $section->parametrs->param20 ?>',
    'param22':'<?php echo $section->parametrs->param22 ?>',
    'curr': '<?php echo $pricemoney ?>',
    'is_local': ''
})]
</footer:js>
<div class="content contFlyCart" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>  
    <div id="fixedCart" class="fixedCart">
        <?php if(file_exists($__MDL_ROOT."/php/subpage_cart.php")) include $__MDL_ROOT."/php/subpage_cart.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_cart.tpl")) include $__data->include_tpl($section, "subpage_cart"); ?>
    </div>
</div>
