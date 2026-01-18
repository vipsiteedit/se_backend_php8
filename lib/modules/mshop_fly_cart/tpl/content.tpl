<header:js>
[lnk:rouble/rouble.css]
</header:js>
<footer:js>
[js:jquery/jquery.min.js]
<?php if(strval($section->parametrs->param17)=='Y'): ?>
[js:ui/jquery.ui.min.js]
<?php endif; ?>

<script type="text/javascript" src="[module_url]mshop_fly_cart.js"></script>
<script type="text/javascript"> mshop_fly_cart_execute({
    id: <?php echo $section->id ?>,
    p11: '<?php echo $section->language->lang020 ?>',
    p16: '<?php echo $section->parametrs->param16 ?>',
    p17: '<?php echo $section->parametrs->param17 ?>',
    p19: '<?php echo $section->parametrs->param19 ?>',
    p4: '<?php echo $section->parametrs->param4 ?>',
    p20: '<?php echo $section->parametrs->param20 ?>',
    curr: '<?php echo $pricemoney ?>'
});</script>
</footer:js>
<div class="content contFlyCart" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
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
