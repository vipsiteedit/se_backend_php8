<div class="content" id="cont_news" <?php echo $section->style ?>>
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
    <?php echo $__data->linkAddRecord($section->id) ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="object" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
            <h4 class="objectTitle">
                <?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                <span id="dataType_date"><?php echo $record->field ?>&nbsp;</span>
                <?php if($section->parametrs->param6!='Y'): ?>
                    <a class="objectTitleTxt" href="<?php echo $record->link_detail ?>">
                <?php else: ?>
                    <span class="objectTitleTxt">
                <?php endif; ?>
                <?php echo $record->title ?>
                <?php if($section->parametrs->param6!='Y'): ?>
                    </a>
                <?php else: ?>
                    </span>
                <?php endif; ?>
            </h4> 
            <?php if(!empty($record->image)): ?>
                <a href="<?php echo $record->link_detail ?>">
                    <img border="0" class="objectImage" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                </a>
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div>
            <?php endif; ?>
            <?php if($section->parametrs->param6=='Y'): ?>
                <?php if(!empty($record->text)): ?>
                    <a id="newslink" href="<?php echo $record->link_detail ?>"><?php echo $section->parametrs->param2 ?></a>
                <?php endif; ?>
            <?php endif; ?>
        </div> 
    
<?php endforeach; ?>




    <arhiv:link>
        <a id="linkArchive" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/arhiv/" ?>"><?php echo $section->parametrs->param3 ?></a>
    </arhiv:link>
</div>
