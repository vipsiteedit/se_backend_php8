<header:js>
<link rel="stylesheet" type="text/css" href="/lib/js/lightbox/lightbox.css"></link>
[js:jquery/jquery.min.js]
[js:lightbox/jquery.lightbox.js]
</header:js>
<script type="text/javascript">
$(document).ready(function(){ 
    $("#photo a").lightBox({  
        overlayBgColor: '#FFF', 
        overlayOpacity: 0.6,    
        imageLoading:   '/lib/js/lightbox/lightbox-ico-loading.gif', 
        imageBtnPrev:   '/lib/js/lightbox/foto_arrow_left.gif', 
        imageBtnNext:   '/lib/js/lightbox/foto_arrow_right.gif', 
        imageBtnClose:  '/lib/js/lightbox/foto_close.gif', 
        imageBlank:     '/lib/js/lightbox/lightbox-blank.gif'  
     });  
});      
</script>  
<div class="content" id="con_photo"<?php echo $section->style ?>>
<?php if($section->title!=''): ?><h3 class="contentTitle"<?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></h3><br clear="all"><?php endif; ?>
<?php if($section->image!=''): ?><a href="<?php echo $section->image ?>" target="_blank"><img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>"></a> <?php endif; ?>
<?php if($section->text!=''): ?><div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div><?php endif; ?>
<br clear="all"><?php echo $__data->linkAddRecord($section->id) ?>
<?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
<div id="photo">

<?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

<div class="photo" id="photoBrief" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
<div class="photo" id="photoBriefImg">
<?php if(!empty($record->image)): ?><a rel="lightbox-foto" href="<?php echo $record->image ?>" title="<?php echo $record->image_alt ?>"><IMG  alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>" src="<?php echo $record->image_prev ?>" border="0" id="photoPrev"></a><?php endif; ?>
<br>
<?php echo $__data->linkEditRecord($section->id, $record->id,'') ?></div>
</div>

<?php endforeach; ?>
</div>
</div>
