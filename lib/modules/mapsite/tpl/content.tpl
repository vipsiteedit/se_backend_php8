<div class="content" id="con_mapsite"<?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>><?php echo $section->title ?></<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div id="listLinks">
        <?php foreach($__data->getPages() as $map): ?><?php if(intval($map->indexes)): ?>
            <h4 class="mapline">
                <a class="<?php echo "maplinks".$map->level ?>" href="<?php echo seMultiDir()."/".$map['name']."/" ?>"><?php echo $map->title ?></a>
            </h4>
        
<?php endif; ?><?php endforeach; ?>
    </div> 
</div> 
