<div class="content cont_news <?php if(strval($section->parametrs->param11)=='a'): ?>container<?php else: ?>container-fluid<?php endif; ?>" <?php echo $section->style ?>>
<?php if(strval($section->parametrs->param7)=='N'): ?><noindex><?php endif; ?>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>" border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody"> 
        <div class="records-container">
            <?php echo $__data->linkAddRecord($section->id) ?>
            <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

                <div class="object record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                    <<?php echo $record->title_tag ?> class="objectTitle">
                        <?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                        <?php if(!empty($record->field)): ?>
                            <span id="dataType_date"><?php echo $record->field ?>&nbsp;</span>
                        <?php endif; ?>
                        <?php if(!empty($record->title)): ?>
                            <?php if(strval($section->parametrs->param9)=='N'): ?><noindex><?php endif; ?>
                                <a class="objectTitleTxt" href="<?php echo $record->link_detail ?><?php if(strval($section->parametrs->param10)=='Y'): ?>#show<?php echo $section->id ?>_<?php echo $record->id ?><?php endif; ?>" <?php if(strval($section->parametrs->param9)=='N'): ?>rel="nofollow"<?php endif; ?> ><?php echo $record->title ?></a>
                            <?php if(strval($section->parametrs->param9)=='N'): ?></noindex><?php endif; ?>
                        <?php endif; ?>
                    </<?php echo $record->title_tag ?>> 
                    <?php if(!empty($record->image)): ?>
                        <?php if(strval($section->parametrs->param9)=='N'): ?><noindex><?php endif; ?>
                            <a href="<?php echo $record->link_detail ?>" class="record-image-link" <?php if(strval($section->parametrs->param9)=='N'): ?>rel="nofollow"<?php endif; ?> >
                                <img class="objectImage record-pimage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>" border="0">
                            </a>
                        <?php if(strval($section->parametrs->param9)=='N'): ?></noindex><?php endif; ?>
                    <?php endif; ?>
                    <?php if(!empty($record->note)): ?>
                        <div class="objectNote record-note"><?php echo $record->note ?></div>
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param6)=='Y'): ?>
                        <?php if(!empty($record->text)): ?>
                            <?php if(strval($section->parametrs->param9)=='N'): ?><noindex><?php endif; ?>
                                <a class="newslink" href="<?php echo $record->link_detail ?><?php if(strval($section->parametrs->param10)=='Y'): ?>#show<?php echo $section->id ?>_<?php echo $record->id ?><?php endif; ?>" <?php if(strval($section->parametrs->param9)=='N'): ?>rel="nofollow"<?php endif; ?> ><?php echo $section->parametrs->param2 ?></a>
                            <?php if(strval($section->parametrs->param9)=='N'): ?></noindex><?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div> 
            
<?php endforeach; ?>
        </div>
        




        <arhiv:link>
            <a id="linkArchive" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/arhiv/" ?>"><?php echo $section->parametrs->param3 ?></a>
        </arhiv:link>
    </div>
    <?php if(strval($section->parametrs->param7)=='N'): ?></noindex><?php endif; ?>
</div>
