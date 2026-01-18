<div class="content valutKurs" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php echo $__data->linkAddRecord($section->id) ?>
    <div class="date">
        <span class="text"><?php echo $section->parametrs->param6 ?></span>
        <span class="date">
            <?php echo $curs_date ?>
        </span>
    </div>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
        <div class="object">
            <?php if(!empty($record->image)): ?>
                <img class="objectImage" alt="[object_alt]" src="<?php echo $record->image_prev ?>" border="0">
            <?php endif; ?>
            <div class="objKurs">
                <span class="objectTitle"><?php echo $record->title ?></span>
                <span class="seporator"><?php echo $section->parametrs->param5 ?></span>
                <span class="kursValue"><?php echo $record->text1 ?></span>
            </div>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div>
            <?php endif; ?>
        </div>
    
<?php endforeach; ?>
</div>
