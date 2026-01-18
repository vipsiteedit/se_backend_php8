<div class="content" id="cont_news_lent" <?php echo $section->style ?>>
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </h3> 
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div> 
    <?php endif; ?>
    <br clear="all">
    <div class="pnavigation"><?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?></div> 
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="object" >
            <h4 class="objectTitle">
                <span id="dataType_date"><?php echo $record->field ?><!--[objectdate]-->&nbsp;</span> 
                <span class="objectTitleTxt"><?php echo $record->title ?><!--[objecttitle]--></span> 
            </h4> 
            <?php if(!empty($record->image)): ?>
                <a href="<?php echo $record->link_detail ?>">
                    <img border="0" class="objectImage" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                </a> 
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div> 
            <?php endif; ?>
            <?php if(!empty($record->text)): ?>
                <a id="newslink" href="<?php echo $record->link_detail ?>"><?php echo $section->parametrs->param2 ?></a> 
            <?php endif; ?>
        </div> 
    
<?php endforeach; ?>
    


    <br clear="all">
    <div class="pnavigation"><?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?></div> 
</div> 
