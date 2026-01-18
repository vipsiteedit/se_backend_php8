<?php if(!empty($pricelist)): ?>
    <div class="analogGoods">
        <h3 class="analogTitle"><span><?php echo $section->language->lang029 ?></span></h3>
        <?php if(file_exists($__MDL_ROOT."/php/subpage_special.php")) include $__MDL_ROOT."/php/subpage_special.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_special.tpl")) include $__data->include_tpl($section, "subpage_special"); ?>
    </div>  
<?php endif; ?>
