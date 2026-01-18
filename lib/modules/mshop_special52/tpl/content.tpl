<header:js>
[lnk:rouble/rouble.css]
</header:js>
<footer:js>
[js:jquery/jquery.min.js]     
<?php if(strval($section->parametrs->param46)=='Y' || strval($section->parametrs->param33)=='rotate'): ?>
<?php if(strval($section->parametrs->param46)=='Y'): ?>
[js:ui/jquery.ui.min.js]
<?php endif; ?>
<?php if(strval($section->parametrs->param33)=='rotate'): ?>
[js:jquery/jquery.carousel.js]
<?php endif; ?>

<script type="text/javascript" src="[module_url]mshop_special52.js"></script>
<script type="text/javascript"> mshop_special52_execute({
    id : <?php echo $section->id ?>, 
    p32: '<?php echo $section->parametrs->param32 ?>', 
    p33: '<?php echo $section->parametrs->param33 ?>', 
    p34: '<?php echo $section->parametrs->param34 ?>',
    p35: '<?php echo $section->parametrs->param35 ?>',
    p36: '<?php echo $section->parametrs->param36 ?>',
    p37: '<?php echo $section->parametrs->param37 ?>',
    p42: '<?php echo $section->parametrs->param42 ?>',
    p38: '<?php echo $section->parametrs->param38 ?>',
    p39: '<?php echo $section->parametrs->param39 ?>',
    p40: '<?php echo $section->parametrs->param40 ?>',
    p46: '<?php echo $section->parametrs->param46 ?>'
});</script>
<?php endif; ?>   
</footer:js>
<div class="content contSpecialGoods" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
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
<?php if($pricelist): ?>
    <div class="contentBody bodySpecialGoods<?php echo $section->id ?>">
    <?php if(strval($section->parametrs->param33)=='rotate'): ?>
        <div id="partRotate<?php echo $section->id ?>" class="rotateGoods">
        <a href="javascript:void(0);" id="prevRotate">&nbsp;</a>  
        <div class="rtContainer">
            <ul class="rtRotate" style="margin:0;padding:0;list-style:none;">
            <?php foreach($section->objects as $record): ?>
                <li style="float:left;">
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_blockproduct.php")) include $__MDL_ROOT."/php/subpage_blockproduct.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_blockproduct.tpl")) include $__data->include_tpl($section, "subpage_blockproduct"); ?>
                </li>
            
<?php endforeach; ?> 
            </ul>
        </div>
        <a href="javascript:void(0);" id="nextRotate">&nbsp;</a>
        </div>
    <?php endif; ?>
    <?php if(strval($section->parametrs->param33)=='block'): ?>
        <div id="partBlock<?php echo $section->id ?>" class="blockGoods">
            <?php foreach($section->objects as $record): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_blockproduct.php")) include $__MDL_ROOT."/php/subpage_blockproduct.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_blockproduct.tpl")) include $__data->include_tpl($section, "subpage_blockproduct"); ?>
            
<?php endforeach; ?>    
        </div>
    <?php endif; ?> 
    </div>
<?php endif; ?>
</div>  
