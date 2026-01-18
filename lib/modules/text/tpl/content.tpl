<div class="content" id="cont_txt"<?php echo $section->style ?>>
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php echo $__data->linkAddRecord($section->id) ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="object"<?php echo $__data->editItemRecord($section->id, $record->id) ?>>
            <?php if(!empty($record->title)): ?>
                <h4 class="objectTitle">
                    <span class="objectTitleTxt"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span>
                </h4>
            <?php endif; ?>
            <?php if(!empty($record->image)): ?>
                <img border="0" class="objectImage" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>">
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div>
            <?php endif; ?>
            <?php if(!empty($record->text)): ?>
                <a class="linkNext" href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>"><?php echo $section->parametrs->param1 ?></a>
            <?php endif; ?>
        </div> 
    
<?php endforeach; ?>   


</div>       
