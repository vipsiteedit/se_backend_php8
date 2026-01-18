<div class="content contArt" <?php echo $section->style ?>>
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
    <?php $__data->recordsWrapperStart($section->id) ?><?php echo $__data->linkAddRecord($section->id) ?>
    <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
        <div class="object">
            <?php if(!empty($record->title)): ?>
                <<?php echo $record->title_tag ?> class="objectTitle record-title"><?php echo $record->title ?></<?php echo $record->title_tag ?>>
            <?php endif; ?>
            <?php if(!empty($record->image)): ?>
            <div class="objectImage record-pimage">
                <img border="0" class="objectPImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
            </div>
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote record-note"><?php echo $record->note ?></div>
            <?php endif; ?>
            <div class="links">
                <a class="artMore" href="<?php echo $record->link_detail ?>"><?php echo $section->language->lang019 ?></a>
                <div class="comments">
                    <a class="artLnkdscs" href="<?php echo $record->link_detail ?>#comments"><?php echo $section->language->lang020 ?> <span class="mycomments">(<span class="digit"><?php echo $record->comments ?></span>)</span></a>
                </div>
            </div>
        </div>
    
<?php endforeach; ?>
    <?php $__data->recordsWrapperEnd() ?>


    
</div>
