<div class="content cont_faq"<?php echo $section->style ?>>
    <a name="r<?php echo $section->id ?>"></a> 
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div id="linkBox">
        <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

            <a id="linkTitle" href="#r<?php echo $section->id ?>_<?php echo $record->id ?>"><?php echo $record->title ?></a>
        
<?php endforeach; ?>
    </div> 
    <?php $__data->recordsWrapperStart($section->id) ?><?php echo $__data->linkAddRecord($section->id) ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="object record-item"<?php echo $__data->editItemRecord($section->id, $record->id) ?>>
            <<?php echo $record->title_tag ?> class="objectTitle">
                <a name="r<?php echo $section->id ?>_<?php echo $record->id ?>"></a> 
                <span class="objectTitleTxt record-title"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span>
            </<?php echo $record->title_tag ?>>
            <?php if(!empty($record->image)): ?>
            <div class="objectImage record-pimage">
                <a target="_blank" href="<?php echo $record->image ?>">
                    <img border="0" class="objectPImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>">
                </a>
            </div>
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote record-note"><?php echo $record->note ?></div>
            <?php endif; ?>
            <?php if(!empty($record->text)): ?>
                <div class="objectText record-rext"><?php echo $record->text ?></div>
            <?php endif; ?>
            <a class="go_up" title="<?php echo $section->language->lang001 ?>" href="#r<?php echo $section->id ?>"><?php echo $section->language->lang001 ?></a>
        </div> 
    
<?php endforeach; ?>
    <?php $__data->recordsWrapperEnd() ?>
</div> 
