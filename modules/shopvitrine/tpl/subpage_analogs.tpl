<?php if(!empty($pricelist)): ?>
    <?php if(file_exists($__MDL_ROOT."/php/subpage_goodlist.php")) include $__MDL_ROOT."/php/subpage_goodlist.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_goodlist.tpl")) include $__data->include_tpl($section, "subpage_goodlist"); ?>
    <div class="analogGoods <?php if(strval($section->parametrs->param317)=='V'): ?> vitrina<?php endif; ?><?php if(strval($section->parametrs->param317)=='T'): ?> tables<?php endif; ?><?php if(strval($section->parametrs->param317)=='L'): ?> list<?php endif; ?>">
        <h3 class="analogTitle"><span><?php echo $section->language->lang029 ?></span></h3>
        <?php if(strval($section->parametrs->param317)=='T'): ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_table.php")) include $__MDL_ROOT."/php/subpage_table.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_table.tpl")) include $__data->include_tpl($section, "subpage_table"); ?>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param317)=='V'): ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_vitrine.php")) include $__MDL_ROOT."/php/subpage_vitrine.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_vitrine.tpl")) include $__data->include_tpl($section, "subpage_vitrine"); ?>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param317)=='L'): ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_list.php")) include $__MDL_ROOT."/php/subpage_list.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_list.tpl")) include $__data->include_tpl($section, "subpage_list"); ?>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param317)=='Y'): ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_special.php")) include $__MDL_ROOT."/php/subpage_special.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_special.tpl")) include $__data->include_tpl($section, "subpage_special"); ?>
        <?php endif; ?>
    </div>  
<?php endif; ?>
