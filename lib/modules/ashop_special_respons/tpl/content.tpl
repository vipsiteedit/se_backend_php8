<footer:js>
[js:jquery/jquery.min.js]
[module_js:responsive_tabs-jquery.responsive_tabs.min.js]
<script >
$('.responsive_tabs-id_<?php echo $section->id ?>').responsiveTabs({
    accordionTabElement: '<div class="responsive_tabs-accordion_title"></div>'
});
</script>
<?php if(strval($section->parametrs->param46)=='Y'): ?>
[js:ui/jquery.ui.min.js]
[include_js({id:<?php echo $section->id ?>, p32:'<?php echo $section->parametrs->param32 ?>',p46:'<?php echo $section->parametrs->param46 ?>'})]
<?php endif; ?>   
</footer:js>
<header:css>
<?php if(strval($section->parametrs->param51) == 'c'): ?>
    <style>
        .responsive_tabs-id_<?php echo $section->id ?> .b_special_simple-object_image {
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;     
            max-width: -webkit-calc(100% - 40px);
            max-height: -webkit-calc(100% - 40px);
            max-width: calc(100% - 40px);
            max-height: calc(100% - 40px);
        }       
    </style>
<?php endif; ?>
</header:css>
<?php if(strval($section->parametrs->param1)!='d'): ?>
<div class="<?php if(strval($section->parametrs->param1)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
<section class="special-responsive responsive_tabs responsive_tabs-id_<?php echo $section->id ?>" >
<?php if(!empty($section->title)): ?><<?php echo $section->title_tag ?> class="content-title contentTitle" <?php echo $section->style_title ?>>
  <span class="content-title-txt contentTitleText"><?php echo $section->title ?></span></<?php echo $section->title_tag ?>>
<?php endif; ?>
<?php if(!empty($section->image)): ?>
  <img border="0" class="content-image contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
<?php endif; ?>
<?php if(!empty($section->text)): ?>
  <div class="content-text contentText"><?php echo $section->text ?></div>
<?php endif; ?>
    <ul class="responsive_tabs-tab_list">
        <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <li class="responsive_tabs-tab_list_item"><a href="#tab-<?php echo $section->id ?>_<?php echo $record->id ?>" class="responsive_tabs-tab_list_link"><?php echo $record->title ?></a></li>
        
<?php endforeach; ?>
    </ul>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

    <div id="tab-<?php echo $section->id ?>_<?php echo $record->id ?>" class="responsive_tabs-tab_content"><?php if(file_exists($__MDL_ROOT."/php/subpage_special.php")) include $__MDL_ROOT."/php/subpage_special.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_special.tpl")) include $__data->include_tpl($section, "subpage_special"); ?></div>
    
<?php endforeach; ?>
</section><?php if(strval($section->parametrs->param1)!='d'): ?>
</div><?php endif; ?>
