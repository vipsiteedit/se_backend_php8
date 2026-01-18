<div class="content shopSearchList" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?> 
        <h3 class="contentTitle" <?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span> </h3> 
    <?php endif; ?> 
    <?php if(!empty($section->image)): ?> 
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?> 
    <?php if(!empty($section->text)): ?> 
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div> 
    <?php endif; ?> 
    <?php if(file_exists($__MDL_ROOT."/php/subpage_2.php")) include $__MDL_ROOT."/php/subpage_2.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_2.tpl")) include $__data->include_tpl($section, "subpage_2"); ?>
    <?php if($goods_count!=0): ?>
        <div class="selSearchRezult">
            
            <div class="nameHeader">(<?php echo $section->language->lang049 ?>&nbsp;&nbsp;<?php echo $goods_count ?>)</div>
        </div>
        <div class="searchRezult">
            <?php echo $MANYPAGE ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_3.php")) include $__MDL_ROOT."/php/subpage_3.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_3.tpl")) include $__data->include_tpl($section, "subpage_3"); ?>
        </div>
    <?php else: ?>
        <?php if($search!=0): ?>
            <div class="selSearchRezult">
                <div class="block_message"><?php echo $section->language->lang046 ?></div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div> 
