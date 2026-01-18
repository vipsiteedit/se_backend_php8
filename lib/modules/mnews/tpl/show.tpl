
        <?php if(strval($section->parametrs->param8)=='N'): ?><noindex><?php endif; ?>
            <div class="content cont_news cont_news_view record-item <?php if(strval($section->parametrs->param11)=='a'): ?>container<?php else: ?>container-fluid<?php endif; ?>" <?php echo $__data->editItemRecord($section->id, $record->id) ?> <?php echo $section->style ?>>
                <?php if(strval($section->parametrs->param10)=='Y'): ?>
                    <a name="show<?php echo $section->id ?>_<?php echo $record->id ?>"></a>
                <?php endif; ?>
                <div id="view">
                    <?php if(!empty($record->title)): ?>
                        <<?php echo $record->title_tag ?> class="objectTitle">
                            <span class="objectTitleTxt record-title"><?php echo $record->title ?></span>
                        </<?php echo $record->title_tag ?>>
                    <?php endif; ?>
                    <?php if(!empty($record->image)): ?>
                        <div class="objimage record-image">
                            <img class="objectImage" src="<?php echo $record->image ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>" border="0">
                        </div>
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param5)=='Y'): ?>
                        <?php if(!empty($record->note)): ?>
                            <div class="objectNote record-note"><?php echo $record->note ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="objectText record-text"><?php echo $record->text ?></div> 
                    <input class="buttonSend" onclick="document.location = '<?php echo $__data->getLinkPageName() ?>';" type="button" value="<?php echo $section->parametrs->param4 ?>">
                </div> 
            </div> 
        <?php if(strval($section->parametrs->param8)=='N'): ?></noindex><?php endif; ?>
        
