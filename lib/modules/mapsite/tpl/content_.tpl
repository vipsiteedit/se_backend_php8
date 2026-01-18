<div class="content" id="con_mapsite"<?php echo $section->style ?>>
<?php if($section->title!=''): ?><h3 class="contentTitle"<?php echo $section->style_title ?>><?php echo $section->title ?></h3> <?php endif; ?>
<?php if($section->image!=''): ?><img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>"><?php endif; ?>
<?php if($section->text!=''): ?><div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div> <?php endif; ?>
<div id="listLinks">
<?php foreach($__data->getPages() as $page): ?><?php if(intval($page->indexes)): ?>
<h4 class="mapline"><a class="<?php echo "maplinks".$page->level ?>" href="<?php echo seMultiDir()."/".$page['name']."/" ?>"><?php echo $page->title ?></a></h4>

<?php endif; ?><?php endforeach; ?>
</div> 
</div> 
