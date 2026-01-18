<div class="content" id="menuLink" >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
<?php echo $__data->linkAddRecord($section->id) ?>
    <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="object" <?php echo $__data->editItemRecord($section->id, $record->id) ?>><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
            <?php if(!empty($record->image)): ?>
                <a href=<?php if($record->field!=''): ?>"<?php echo $record->field ?>"<?php else: ?>"#"<?php endif; ?>>
                    <img border="0" class="objectImage" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>">
                </a>
            <?php endif; ?>
            <a href=<?php if($record->field!=''): ?>"<?php echo $record->field ?>"<?php else: ?>"#"<?php endif; ?> class=<?php if($record->field==(isset($_page) ? $_page : '')): ?>"link linkActive"<?php else: ?>"link"<?php endif; ?>>
                <?php echo $record->title ?>
            </a>
        </div> 
    
<?php endforeach; ?>
</div> 
