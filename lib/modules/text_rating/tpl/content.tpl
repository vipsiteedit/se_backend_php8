<div class="content" id="cont_rattxt" >
<?php if($section->title!=''): ?><h3 class="contentTitle" <?php echo $section->style_title ?>>
 <span class="contentTitleTxt"><?php echo $section->title ?></span> </h3> <?php endif; ?>
<?php if($section->image!=''): ?><img border="0" class="contentImage" src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>"><?php endif; ?>
<?php if($section->text!=''): ?><div class="contentText"><?php echo $section->text ?></div> <?php endif; ?>
 
<?php if($accesslevel==3): ?>
<div id="divclear">
<form style="margin:0px;" method="post">
<input class="buttonSend buttonClear" type="submit" value="<?php echo $section->parametrs->param7 ?>" name="clear">
</form> 
</div> 
<?php endif; ?>
<br clear="all"><?php echo $__data->linkAddRecord($section->id) ?>
<?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
<?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>
       
<div class="object"<?php echo $__data->editItemRecord($section->id, $record->id) ?>>
<?php if(!empty($record->title)): ?><h4 class="objectTitle"><span class="objectTitleTxt"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span> </h4> <?php endif; ?>
<?php if(!empty($record->image)): ?><img border="0" class="objectImage" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>"><?php endif; ?>
<?php if(!empty($record->note)): ?><div class="objectNote"><?php echo $record->note ?></div> <?php endif; ?>
<?php if(!empty($record->text)): ?><div id="link"><a href="<?php echo $record->link_detail ?>"><?php echo $section->parametrs->param3 ?></a> </div> <?php endif; ?>
 <div id="objFooter">
<form style="margin:0px;" action="<?php echo seMultiDir()."/".$_page."/" ?>#<?php echo $record->link ?>" method="post">
<b id="ratingTitle"><?php echo $section->parametrs->param5 ?>:</b> <b id="obj_rating"><?php echo $record->rating ?></b>
<input type="hidden" name="ratingraz" value="<?php echo $section->id ?>"> 
<input type="hidden" name="ratingobj" value="<?php echo $record->id ?>"> 
<input type="submit" id="VoteBut" class="buttonSend" name="goRating" value="<?php echo $section->parametrs->param6 ?>"></form> 
</div> 
</div> 

<?php endforeach; ?>


</div> 
