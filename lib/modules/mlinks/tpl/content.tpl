<div class="content cont_link"<?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <a href="<?php echo $section->image ?>" target="_blank">
            <img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>">
        </a> 
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php echo $__data->linkAddRecord($section->id) ?>
    <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
<?php $__data->recordsWrapperStart($section->id) ?>    
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

    <table border="0" cellpadding="0" cellspacing="0" class="tableTable record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
        <tbody class="tableBody">
                <tr class="tableHeader" vAlign="top">
                    <td class="tableRow" colspan="2"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                        <a class="linkobject" href="<?php echo $record->field ?>" target="_blank">
                            <?php echo $record->title ?>
                        </a>&nbsp;
                    </td>
                </tr> 
                <tr>
                    <td width="95" vAlign="top" id="bfield">
                        <?php if(!empty($record->image)): ?> 
                            <a class="linkobjectImg" href="<?php echo $record->field ?>" target="_blank">
                                <img src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" border="0">
                            </a> 
                        <?php endif; ?>
                        <span class="note"><?php echo $record->note ?></span>&nbsp;
                    </td> 
                    <td vAlign="top" id="tfield"><span><?php echo $record->text ?></span>&nbsp;</td> 
                </tr> 
        </tbody> 
    </table> 
    
<?php endforeach; ?>
<?php $__data->recordsWrapperEnd() ?>
</div> 
