
<div class="photoDetailed" id="show_photo">
<?php if(!empty($record->title)): ?><h4 class="objectTitle"><span class="objectTitleTxt"><?php echo $record->title ?></span></h4> <?php endif; ?>
<?php if(!empty($record->image)): ?><img class="objectImage" title="<?php echo $record->image_alt ?>" alt="<?php echo $record->image_alt ?>" src="<?php echo $record->image ?>" border="0"><?php endif; ?>
<?php if(!empty($record->note)): ?><div class="objectNote"><?php echo $record->note ?></div> <?php endif; ?>
<?php if(!empty($record->text)): ?><div class="objectText"><?php echo $record->text ?></div> <?php endif; ?>
<input class="buttonSend" onclick="window.history.back();" type="button" value="<?php echo $section->parametrs->param2 ?>">
</div> 
