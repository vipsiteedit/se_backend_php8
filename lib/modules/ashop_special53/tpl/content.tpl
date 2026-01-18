<footer:js>
[js:jquery/jquery.min.js]     
<?php if(strval($section->parametrs->param46)=='Y' || strval($section->parametrs->param33)=='rotate'): ?>
<?php if(strval($section->parametrs->param46)=='Y'): ?>
[js:ui/jquery.ui.min.js]
<?php endif; ?>
[include_js({id:<?php echo $section->id ?>, p32:'<?php echo $section->parametrs->param32 ?>',p46:'<?php echo $section->parametrs->param46 ?>'})]
<?php if(strval($section->parametrs->param33)=='rotate'): ?>
[js:jquery/jquery.carousel.js]
<script>
$('#partRotate<?php echo $section->id ?> .rtContainer').Carousel({
    position: "<?php echo $section->parametrs->param34 ?>",
    visible: <?php echo $section->parametrs->param35 ?>,
    rotateBy: <?php echo $section->parametrs->param36 ?>,
    speed: <?php echo $section->parametrs->param37 ?>,
    direction: <?php echo $section->parametrs->param42 ?>,
    btnNext: '#partRotate<?php echo $section->id ?> #nextRotate',
    btnPrev: '#partRotate<?php echo $section->id ?> #prevRotate',      
    auto: <?php echo $section->parametrs->param38 ?>,      
    delay: <?php echo $section->parametrs->param39 ?>,
    dirAutoSlide:<?php echo $section->parametrs->param40 ?>,
    margin: 0      
}); 
</script>
<?php endif; ?><?php endif; ?>   
</footer:js>
<div class="content contSpecialGoods" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?> 
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </<?php echo $section->title_tag ?>> 
    <?php endif; ?> 
    <?php if(!empty($section->image)): ?> 
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
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
