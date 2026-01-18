<?php if($pricelist): ?>
<div class="b_special_simple" data-type="<?php echo $section->type ?>">
    <div class="contentBody bodySpecialGoods<?php echo $section->id ?>">
        <div id="partBlock<?php echo $section->id ?>" class="blockGoods b_special_simple-object_area">
            <?php $__list = 'specobjects'.$record->id; foreach($section->$__list as $prod): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_blockproduct.php")) include $__MDL_ROOT."/php/subpage_blockproduct.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_blockproduct.tpl")) include $__data->include_tpl($section, "subpage_blockproduct"); ?>
            <?php endforeach; ?>
        </div> 
    </div>
</div>
<?php endif; ?>
