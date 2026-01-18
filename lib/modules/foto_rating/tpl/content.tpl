<div class="content" id="cont_photo_rating" <?php echo $section->style ?>>
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
     
    <?php if($GR_AUTHOR==3): ?>
        <div id="divclear">
            <form style="margin:0px;" method="post">
                <input class="buttonSend buttonClear" type="submit" value="<?php echo htmlspecialchars($section->parametrs->param8) ?>" name="clear">
            </form> 
        </div>
    <?php endif; ?>
    <br clear="all">
    <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="photo" id="photoBrief">
            <div id="photoBriefImg">
                <?php if(!empty($record->image)): ?>
                    <a href="<?php echo $record->link_detail ?>">
                        <img alt="<?php echo $record->image_alt ?>" src="<?php echo $record->image_prev ?>" border="0" id="photoPrev">
                    </a>
                <?php endif; ?>
                <br>
                <a class="linkstitle" href="<?php echo $record->link_detail ?>"><?php echo $record->title ?></a>
            </div> 
            <div id="objFooter">
                
                    <form style="margin:0px;" action="<?php echo seMultiDir()."/".$_page."/" ?>#<?php echo $record->link ?>" method="post">
                
                        <b id="ratingTitle"><?php echo $section->parametrs->param5 ?>:</b>
                        <b id="obj_rating"><?php echo $record->rating ?></b> 
                        <input type="hidden" name="ratingraz" value="<?php echo $section->id ?>"> 
                        <input type="hidden" name="ratingobj" value="<?php echo $record->id ?>"> 
                        <input type="submit" class="buttonSend" name="goRating" value="<?php echo htmlspecialchars($section->parametrs->param4) ?>"><br>
                
                    </form>
                
            </div> 
        </div> 
    
<?php endforeach; ?>
    


</div> 
