<footer:js>
[js:jquery/jquery.min.js]
[js:ui/jquery.ui.min.js]
[include_js({id: '<?php echo $section->id ?>', p9: '<?php echo $section->parametrs->param9 ?>', p10: '<?php echo $section->parametrs->param10 ?>', p11: '<?php echo $section->parametrs->param11 ?>'})]
</footer:js>
<div class="content accordion"<?php echo $section->style ?> id="id<?php echo $section->id ?>">
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php $__data->recordsWrapperStart($section->id) ?><?php echo $__data->linkAddRecord($section->id) ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
        <div class="object"<?php echo $__data->editItemRecord($section->id, $record->id) ?>>
            <<?php echo $record->title_tag ?> class="objectTitle">
                <?php if(strval($section->parametrs->param14)=='N'): ?>
                    <noindex>
                <?php endif; ?>
                    <a class="objectTitleTxt" href="#" <?php if(strval($section->parametrs->param14)=='N'): ?> rel="nofollow" <?php endif; ?> ><?php echo $record->title ?></a>
                <?php if(strval($section->parametrs->param14)=='N'): ?>
                    </noindex>
                <?php endif; ?>
            </<?php echo $record->title_tag ?>>
            <div class="contentBlock">
            <?php if(!empty($record->image)): ?>
                <img border="0" class="objectImage" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div>
            <?php endif; ?>
            <?php if(!empty($record->text)): ?>
                <?php if(strval($section->parametrs->param13)=='N'): ?>
                    <noindex>
                <?php endif; ?>
                    <a class="linkNext" href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>" <?php if(strval($section->parametrs->param13)=='N'): ?> rel="nofollow" <?php endif; ?>><?php echo $section->parametrs->param1 ?></a>
                <?php if(strval($section->parametrs->param13)=='N'): ?>
                    </noindex>
                <?php endif; ?>
            <?php endif; ?>
            </div>
        </div> 
    
<?php endforeach; ?>
    <?php $__data->recordsWrapperEnd() ?>   


</div>       
