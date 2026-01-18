<header:js>
[lnk:rouble/rouble.css]
<?php if(strval($section->parametrs->param327)=='Y'): ?>
[lnk:fancybox2/jquery.fancybox.css]  
<?php endif; ?>
</header:js>
<footer:js>
[js:jquery/jquery.min.js]
<?php if(strval($section->parametrs->param327)=='Y'): ?>
[js:jquery/jquery.mousewheel.js]
<?php if(strval($section->parametrs->param319)=='Y'): ?> 
[js:jquery/jcarousellite.js]
<?php endif; ?>
<?php if(strval($section->parametrs->param311)=='Y'): ?> 
[js:jquery/zoomsl.min.js]
<?php endif; ?>
[js:fancybox2/jquery.fancybox.pack.js] 
<?php endif; ?>

<script type="text/javascript" src="[module_url]mshop_vitrine_big51_new.js"></script>
<script type="text/javascript"> mshop_vitrine_big51_new_execute({
    ajax_url: '?ajax<?php echo $section->id ?>',
    param321: '<?php echo $section->parametrs->param321 ?>',
    param307: '<?php echo $section->parametrs->param307 ?>',
    param308: '<?php echo $section->parametrs->param308 ?>',
    param309: '<?php echo $section->parametrs->param309 ?>',
    param233: '<?php echo $section->parametrs->param233 ?>'
});</script>
</footer:js>
<div class="content e_shopvit" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
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
            <?php if(file_exists($__MDL_ROOT."/php/subpage_goodlist.php")) include $__MDL_ROOT."/php/subpage_goodlist.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_goodlist.tpl")) include $__data->include_tpl($section, "subpage_goodlist"); ?>
            <div class="blockPanel">
                <div class="vitrineSort" style="display:inline-block;">
                    <form style="margin:0px;" method="post" action="">
                        <label class="vitrineSortLabel" for="sortOrderby"><?php echo $section->language->lang025 ?></label>
                        <select class="vitrineSortSelect" id="sortOrderby" name="sortOrderby">
                            <?php echo $sort_options ?>
                        </select>
                        <label class="vitrineSortDirLabel" for="sortDir"><?php echo $section->language->lang026 ?></label>
                        <select class="vitrineSortDirSelect" id="sortDir" name="sortDir">
                            <?php echo $sort_direction ?>
                        </select>
                    </form>
                </div>
            <?php if(strval($section->parametrs->param183)=='Y'): ?>
                <div class="changeView">
                    <form method="post" action="" style="margin:0px;">
                        <?php if(strval($section->parametrs->param184)=='v'): ?>
                            <input class="buttonSend vitrina" type="submit" name="typevitrine" value="<?php echo $section->language->lang034 ?>" title="<?php echo $section->language->lang034 ?>" disabled>
                            <input class="buttonSend table" type="submit" name="typetable" value="<?php echo $section->language->lang035 ?>" title="<?php echo $section->language->lang035 ?>">
                        <?php else: ?>
                            <input class="buttonSend vitrina" type="submit" name="typevitrine" value="<?php echo $section->language->lang034 ?>" title="<?php echo $section->language->lang034 ?>"> 
                            <input class="buttonSend table" type="submit" name="typetable" value="<?php echo $section->language->lang035 ?>" title="<?php echo $section->language->lang035 ?>" disabled>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endif; ?>
            </div>
            <?php if(!empty($SE_NAVIGATOR)): ?>
                <div class="goodsNavigator top"><?php echo $SE_NAVIGATOR ?></div>
            <?php endif; ?>
            <div class="goodsGoods <?php if(strval($section->parametrs->param184)=='v'): ?> vitrina<?php else: ?> table<?php endif; ?> <?php if(strval($section->parametrs->param85)=='Y'): ?>notAjaxCart<?php endif; ?>">
                <?php if(strval($section->parametrs->param184)=='v'): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_vitrine.php")) include $__MDL_ROOT."/php/subpage_vitrine.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_vitrine.tpl")) include $__data->include_tpl($section, "subpage_vitrine"); ?>
                <?php else: ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_table.php")) include $__MDL_ROOT."/php/subpage_table.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_table.tpl")) include $__data->include_tpl($section, "subpage_table"); ?>
                <?php endif; ?>
            </div>
            <?php if(!empty($SE_NAVIGATOR)): ?>
                <div class="goodsNavigator bottom"><?php echo $SE_NAVIGATOR ?></div>
            <?php endif; ?>    
        <?php else: ?>
            <div class="noGoodsIntable"><?php echo $section->language->lang023 ?></div>
        <?php endif; ?>
        
        <?php if(strval($section->parametrs->param323)=='Y' && $footer_text!=''): ?>
            <?php if(!empty($noindex)): ?><!--noindex--><?php endif; ?>
            <div class="sg_footer_text">
                <?php echo $footer_text ?>
            </div>
            <?php if(!empty($noindex)): ?><!--/noindex--><?php endif; ?>
        <?php endif; ?>
    </div>
</div>
