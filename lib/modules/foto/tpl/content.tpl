<div class="content" id="cont_photo"<?php echo $section->style ?>>
<?php if($section->title!=''): ?><h3 class="contentTitle"<?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span> </h3> <br clear="all"><?php endif; ?>
<?php if($section->image!=''): ?><a href="<?php echo $section->image ?>" target="_blank"><img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>"></a> <?php endif; ?>
<?php if($section->text!=''): ?><div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div> <?php endif; ?> 
<br clear="all">
<?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
<?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

<div class="photo" id="photoBrief" >
<div class="photo" id="photoBriefImg">
<?php if(!empty($record->image)): ?><a href="<?php echo $record->link_detail ?>"><img alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>" src="<?php echo $record->image_prev ?>" border="0" id="photoPrev"></a> <?php endif; ?>
<br> 
<a id="links" href="<?php echo $record->link_detail ?>"><?php echo $record->title ?></a> </div> 
</div> 

<?php endforeach; ?>


</div> 
