<div class="content cont_photo_rating" <?php echo $section->style ?>>
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
     
    <?php if($GR_AUTHOR==3): ?>
        <div id="divclear">
            <form style="margin:0px;" method="post">
                <input class="buttonSend buttonClear" type="submit" value="<?php echo $section->language->lang003 ?>" name="clear">
            </form> 
        </div>
    <?php endif; ?>
    <?php echo $__data->linkAddRecord($section->id) ?>
    <div class="classNavigator">
    <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    </div>
    <?php $__data->recordsWrapperStart($section->id) ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="photo photoBrief record-item"<?php echo $__data->editItemRecord($section->id, $record->id) ?>>
            <div id="photoBriefImg">
                <?php if(!empty($record->image)): ?>
                    <a href="<?php echo $record->link_detail ?>#<?php echo $record->link ?>" style="text-decoration:none;">
                        <img alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>" src="<?php echo $record->image_prev ?>" border="0" id="photoPrev" class="objectImage">
                    </a>
                <?php endif; ?>
                <?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                <a class="linkstitle" href="<?php echo $record->link_detail ?>"><?php echo $record->title ?></a>
            </div> 
            <div id="objFooter">
                    <form style="margin:0px;" action="<?php echo $__data->getLinkPageName() ?>#<?php echo $record->link ?>" method="post">
                        <div id="ratingTitle"><?php echo $section->language->lang002 ?>:</div>
                        <div id="obj_rating"><?php echo $record->rating ?></div> 
                        <input type="hidden" name="ratingraz" value="<?php echo $section->id ?>"> 
                        <input type="hidden" name="ratingobj" value="<?php echo $record->id ?>"> 
                        <input type="submit" class="buttonSend" name="goRating" value="<?php echo $section->language->lang001 ?>">
                    </form>
            </div> 
        </div> 
    
<?php endforeach; ?>
    <?php $__data->recordsWrapperEnd() ?>


</div> 
