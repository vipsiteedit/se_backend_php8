
    <div class="content cont_rattxt view record-item" id="view"<?php echo $__data->editItemRecord($section->id, $record->id) ?>>
        <?php if(!empty($record->title)): ?>
            <<?php echo $section->title_tag ?> class="objectTitle">
                <span class="objectTitleTxt record-title"><?php echo $record->title ?></span>
            </<?php echo $section->title_tag ?>> 
        <?php endif; ?>
        <?php if(!empty($record->image)): ?>
            <div class="objimage record-image" id="objimage">
                <img class="objectImage" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>" src="<?php echo $record->image ?>" border="0">
            </div> 
        <?php endif; ?>
        <?php if(!empty($record->note)): ?>
            <div class="objectNote record-note"><?php echo $record->note ?></div> 
        <?php endif; ?>
        <div class="objectText record-text"><?php echo $record->text ?></div> 
            <input type="button" id="butfirst" class="buttonSend" onclick="document.location='<?php echo $__data->getLinkPageName() ?>';" value="<?php echo $section->language->lang001 ?>">
        </div> 
