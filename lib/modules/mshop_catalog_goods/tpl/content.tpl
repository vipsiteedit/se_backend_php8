<?php if(file_exists($__MDL_ROOT."/php/subpage_7.php")) include $__MDL_ROOT."/php/subpage_7.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_7.tpl")) include $__MDL_ROOT."/tpl/subpage_7.tpl"; ?>
<div class="content e_shopvit" <?php echo $section->style ?>>
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>                         
    <?php if($section->image!=''): ?>
        <img class="contentImage" alt="<?php echo $section->image_alt ?>" src="<?php echo $section->image ?>" border="0" <?php echo $section->style_image ?>>
    <?php endif; ?>                              
    <?php if($section->text!=''): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="goodsContent">
        <?php if($goodscount!=0): ?>
            <?php if($section->parametrs->param183=='Y'): ?>
                <div class="changeView">
                    <form method="post" action="" style="margin:0px;">
                        <?php if($section->parametrs->param184=='v'): ?>
                            <input class="buttonSend vitrina" type="submit" name="typetable" value="<?php echo $section->parametrs->param186 ?>">
                        <?php else: ?>
                            <input class="buttonSend table" type="submit" name="typevitrine" value="<?php echo $section->parametrs->param185 ?>">
                        <?php endif; ?>
                    </form>
                </div>
            <?php endif; ?>  
            <?php if($flag): ?>
                <div class="addLinkGood ">
                    <a class="LinkGood" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub12/" ?>"><?php echo $section->parametrs->param282 ?></a>
                </div>
                <div class="addLinkGroup ">
                    <a class="LinkGroup" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub15/" ?>"><?php echo $section->parametrs->param315 ?></a>
                </div>
            <?php endif; ?>
            <div class="goodsNavigator top">
                <?php if($otherPg!=0): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_8.php")) include $__MDL_ROOT."/php/subpage_8.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_8.tpl")) include $__MDL_ROOT."/tpl/subpage_8.tpl"; ?>
                <?php else: ?>
                    <?php echo $SE_NAVIGATOR ?>
                <?php endif; ?> 
            </div>
            <div class="goodsGoods<?php if($section->parametrs->param184=='v'): ?> vitrina<?php else: ?> table<?php endif; ?>">
                <?php if($section->parametrs->param184=='v'): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_3.php")) include $__MDL_ROOT."/php/subpage_3.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_3.tpl")) include $__MDL_ROOT."/tpl/subpage_3.tpl"; ?>
                <?php else: ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_4.php")) include $__MDL_ROOT."/php/subpage_4.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_4.tpl")) include $__MDL_ROOT."/tpl/subpage_4.tpl"; ?>
                <?php endif; ?>
            </div>
            <div class="goodsNavigator bottom">
                <?php if($otherPg!=0): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_8.php")) include $__MDL_ROOT."/php/subpage_8.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_8.tpl")) include $__MDL_ROOT."/tpl/subpage_8.tpl"; ?>
                <?php else: ?>
                    <?php echo $SE_NAVIGATOR ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="noGoodsIntable"><?php echo $section->parametrs->param272 ?></div>
        <?php endif; ?>
        
        <div class="sg_footer_text">
            <?php echo $footer_text ?>
        </div>
    </div>
</div>
