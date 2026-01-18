<footer:js>
[js:jquery/jquery.min.js]
[include_js({p10: '<?php echo $section->parametrs->param10 ?>'})]
</footer:js>
<div class="content shopmini"<?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>> 
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div> 
    <?php endif; ?>
    <?php $__data->recordsWrapperStart($section->id) ?><?php echo $__data->linkAddRecord($section->id) ?>
    <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="object record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?> ><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
            <?php if(!empty($record->title)): ?>
                <<?php echo $record->title_tag ?> class="objectTitle record-title"><?php echo $record->title ?></<?php echo $record->title_tag ?>> 
            <?php endif; ?>
            <?php if(!empty($record->image)): ?>
            <div class="objectImage">
                <img class="objectImg" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>" onclick="document.location.href='<?php echo $record->link_detail ?>'">
            </div>
            <?php endif; ?>
            <div class="objectCode">
                <span class="objectCodeTitle"><?php echo $section->parametrs->param7 ?></span>
                <span class="objectCodeVal record-text1"><?php echo $record->text1 ?></span>
            </div>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div> 
            <?php endif; ?>
            <?php if(strval($section->parametrs->param9)=="Y"): ?>
                <?php if(!empty($record->text)): ?>
                    <a class="linkNext" href="<?php echo $record->link_detail ?>"><?php echo $section->parametrs->param1 ?></a> 
                <?php endif; ?>
            <?php endif; ?>
            <div class="specprice">
                <span class="specpriceVal record-field" data-price="<?php echo $record->field ?>"><?php echo $record->field ?></span>
                <span class="specpriceTitle"><?php echo $section->parametrs->param6 ?></span>            
            </div>                                                              
            <form style="margin:0px;" method="POST">
            <input type="hidden" name="addcartspecial" value="<?php echo $record->id ?>">
                <input type="hidden" name="partid" value="<?php echo $section->id ?>">
                <input class="buttonSend send" type="submit"  value="<?php echo $section->parametrs->param3 ?>">
            </form> 
        </div>                                
    
<?php endforeach; ?>
    <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    <?php $__data->recordsWrapperEnd() ?>


</div>      
