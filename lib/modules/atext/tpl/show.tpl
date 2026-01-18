
        <footer:js>
        <?php if(strval($section->parametrs->param5)=="Y"): ?>
        [js:jquery/jquery.min.js]
        <script type="text/javascript" src="http://yandex.st/share/share.js" charset="utf-8"></script>
        [include_js({p6: '<?php echo $section->parametrs->param6 ?>'})]
        <?php endif; ?>
        </footer:js>
        <?php if(strval($section->parametrs->param8)!='d'): ?><div class="cont-text-container <?php if(strval($section->parametrs->param8)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
            <article class="content cont-text part<?php echo $section->id ?> show">
                <?php if(strval($section->parametrs->param4)=='Y'): ?>
                    <a name="show<?php echo $section->id ?>_<?php echo $record->id ?>"></a>
                <?php endif; ?>                                                                        
                <div class="record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                    <?php if(!empty($record->title)): ?>
                        <<?php echo $section->title_tag ?> class="objectTitle object-title">
                            <span class="contentTitleTxt" data-record="title"><?php echo $record->title ?></span> 
                        </<?php echo $section->title_tag ?>> 
                    <?php endif; ?>
                    <?php if(!empty($record->image)): ?>
                        <div class="objimage record-image">
                            <img class="objectImage" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>" src="<?php echo $record->image ?>" border="0">
                        </div> 
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param3)=='Y'): ?>
                        <?php if(!empty($record->note)): ?>
                            <div class="objectNote record-note"><?php echo $record->note ?></div> 
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if(!empty($record->text)): ?>
                        <div class="objectText record-text"><?php echo $record->text ?></div>
                    <?php endif; ?> 
                    <?php if(strval($section->parametrs->param5)=="Y"): ?>
                        <div id="ya_share1" style="margin: 10px 0;">
                            
                        </div>
                    <?php endif; ?>
                    <input class="buttonSend button-send btn btn-success" onclick="document.location.href='<?php echo $__data->getLinkPageName() ?>';" type="button" value="<?php echo $section->parametrs->param2 ?>">
                </div>
            </article>                           
        <?php if(strval($section->parametrs->param8)!='d'): ?></div><?php endif; ?>
        
