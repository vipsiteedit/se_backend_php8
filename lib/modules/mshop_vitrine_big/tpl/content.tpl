<?php if(file_exists($__MDL_ROOT."/php/subpage_goodlist.php")) include $__MDL_ROOT."/php/subpage_goodlist.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_goodlist.tpl")) include $__data->include_tpl($section, "subpage_goodlist"); ?>
<div class="content e_shopvit" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>                         
    <?php if(!empty($section->image)): ?>
        <img class="contentImage" alt="<?php echo $section->image_alt ?>" src="<?php echo $section->image ?>" border="0" <?php echo $section->style_image ?>>
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="goodsContent">
        <?php if($goodscount!=0): ?>
            <?php if($section->parametrs->param183=='Y'): ?>
                <div class="changeView">
                    <form method="post" action="" style="margin:0px;">
                        <?php if($section->parametrs->param184=='v'): ?>
                            <input class="buttonSend vitrina" type="submit" name="typetable" value="<?php echo $section->language->lang039 ?>">
                        <?php else: ?>
                            <input class="buttonSend table" type="submit" name="typevitrine" value="<?php echo $section->language->lang038 ?>">
                        <?php endif; ?>
                    </form>
                </div>
            <?php endif; ?>      
            <div class="goodsNavigator top">
                    <?php echo $SE_NAVIGATOR ?>
            </div>
            <div class="goodsGoods<?php if($section->parametrs->param184=='v'): ?> vitrina<?php else: ?> table<?php endif; ?>">
                <?php if($section->parametrs->param184=='v'): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_vitrine.php")) include $__MDL_ROOT."/php/subpage_vitrine.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_vitrine.tpl")) include $__data->include_tpl($section, "subpage_vitrine"); ?>
                <?php else: ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_table.php")) include $__MDL_ROOT."/php/subpage_table.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_table.tpl")) include $__data->include_tpl($section, "subpage_table"); ?>
                <?php endif; ?>
            </div>
            <div class="goodsNavigator bottom">
                    <?php echo $SE_NAVIGATOR ?>
            </div>
        <?php else: ?>
            <div class="noGoodsIntable"><?php echo $section->language->lang014 ?></div>
        <?php endif; ?>
        
        <?php if($footer_text!=''): ?>
            <div class="sg_footer_text">
                <?php echo $footer_text ?>
            </div>
        <?php endif; ?>
    </div>
</div>
