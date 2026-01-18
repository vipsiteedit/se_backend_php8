<div class="content cont_rattxt" >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </<?php echo $section->title_tag ?>> 
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div> 
    <?php endif; ?>
     
    <?php if($accesslevel==3): ?>
        <div id="divclear">
            <form style="margin:0px;" method="post">
                <input class="buttonSend buttonClear" type="submit" value="<?php echo $section->language->lang004 ?>" name="clear">
            </form> 
        </div> 
    <?php endif; ?>
    <?php echo $__data->linkAddRecord($section->id) ?>                
    <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    <?php $__data->recordsWrapperStart($section->id) ?>
        <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>
       
            <div class="object record-item"<?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                <?php if(!empty($record->title)): ?>
                    <<?php echo $record->title_tag ?> class="objectTitle record-title">
                        <span class="objectTitleTxt"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span> 
                    </<?php echo $record->title_tag ?>> 
                <?php endif; ?>
                <?php if(!empty($record->image)): ?>
                    <img border="0" class="objectImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                <?php endif; ?>
                <?php if(!empty($record->note)): ?>
                    <div class="objectNote record-note"><?php echo $record->note ?></div> 
                <?php endif; ?>
                <?php if(!empty($record->text)): ?>
                    <div id="link">
                        <a href="<?php echo $record->link_detail ?>"><?php echo $section->parametrs->param9 ?></a> 
                    </div> 
                <?php endif; ?>
                <div id="objFooter">
                    
                        <form style="margin:0px;" action="#<?php echo $record->link ?>" method="post">
                    
                    <span id="ratingTitle"><?php echo $section->language->lang002 ?>:</span> 
                    <span id="obj_rating"><?php echo $record->rating ?></span>
                    <input type="hidden" name="ratingraz" value="<?php echo $section->id ?>"> 
                    <input type="hidden" name="ratingobj" value="<?php echo $record->id ?>"> 
                    <input type="submit" id="VoteBut" class="buttonSend" name="goRating" value="<?php echo $section->language->lang003 ?>">
                    
                        </form>
                     
                </div> 
            </div> 
        
<?php endforeach; ?>
    <?php $__data->recordsWrapperEnd() ?>


</div> 
