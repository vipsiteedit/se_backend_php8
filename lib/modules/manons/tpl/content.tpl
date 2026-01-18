<div class="content art_bank <?php if(strval($section->parametrs->param2)=='a'): ?>container<?php else: ?>container-fluid<?php endif; ?>"<?php echo $section->style ?> >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody"> 
        <?php echo $__data->linkAddRecord($section->id) ?>
        <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
        <div class="records-container">
            <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

                <div class="object record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                    <a href=<?php if($record->field!=''): ?>"<?php echo $record->field ?>"<?php else: ?>"#"<?php endif; ?> <?php if(strval($section->parametrs->param3)=='N'): ?>rel="nofollow"<?php endif; ?> <?php if($record->text1!=''): ?>target="<?php echo $record->text1 ?>"<?php endif; ?> class=<?php if($record->field==$_page): ?>"link linkActive record-field"<?php else: ?>"link record-field"<?php endif; ?>>
                        <<?php echo $record->title_tag ?>><?php echo $record->title ?></<?php echo $record->title_tag ?>>
                    </a>
                   <?php if(!empty($record->image)): ?>
                        <a class="record-image-link" href=<?php if($record->field!=''): ?>"<?php echo $record->field ?>"<?php else: ?>"#"<?php endif; ?> <?php if(strval($section->parametrs->param3)=='N'): ?>rel="nofollow"<?php endif; ?> <?php if($record->text1!=''): ?>target="<?php echo $record->text1 ?>"<?php endif; ?>>
                            <img class="objectImage record-pimage" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($record->note)): ?>
                        <div class="objectNote record-note">
                            <?php echo $record->note ?>
                        </div>
                    <?php endif; ?>        
                </div> 
            
<?php endforeach; ?>
        </div>
    </div>
</div> 
