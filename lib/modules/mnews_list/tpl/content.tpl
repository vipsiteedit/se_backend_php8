<div class="content cont_news_lent" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </<?php echo $section->title_tag ?>> 
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>" border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div> 
    <?php endif; ?>
    <?php $__data->recordsWrapperStart($section->id) ?><?php echo $__data->linkAddRecord($section->id) ?>
    <div class="pnavigation up"><?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?></div> 
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="object record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
            <<?php echo $record->title_tag ?> class="objectTitle"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                <?php if(!empty($record->field)): ?>
                    <span id="dataType_date"><?php echo $record->field ?></span> 
                <?php endif; ?>
                <span class="objectTitleTxt record-title"><?php echo $record->title ?></span> 
            </<?php echo $record->title_tag ?>> 
            <?php if(!empty($record->image)): ?>
                <a href="<?php echo $record->link_detail ?>">
                    <img border="0" class="objectImage record-pimage" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                </a> 
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote record-note"><?php echo $record->note ?></div> 
            <?php endif; ?>
            <?php if(!empty($record->text)): ?>
                <a id="newslink" href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>"><?php echo $section->language->lang001 ?></a> 
            <?php endif; ?>
        </div> 
    
<?php endforeach; ?>
    <div class="pnavigation down"><?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?></div> 
    <?php $__data->recordsWrapperEnd() ?>


</div> 
