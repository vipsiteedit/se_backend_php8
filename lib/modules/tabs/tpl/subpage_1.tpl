<div class="obj"<?php echo $__data->editItemRecord($section->id, $record->id) ?>><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>

    <?php if(!empty($record->text1)): ?><h4 class="objectTitle"><span class="objectTitleTxt"><?php echo $record->text1 ?></span></h4><?php endif; ?>
    <?php if(!empty($record->image)): ?><img border="0" class="objectImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>"><?php endif; ?>
    <?php if(!empty($record->note)): ?><div class="objectNote"><?php echo $record->note ?></div><?php endif; ?>
    <?php if(!empty($record->text)): ?><div class="objectText"><?php echo $record->text ?></div><?php endif; ?>
</div>
