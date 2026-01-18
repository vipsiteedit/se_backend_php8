
<div class="content" id="view">
<?php if(!empty($record->title)): ?><h4 class="objectTitle"><span class="objectTitleTxt"><?php echo $record->title ?></span></h4> <?php endif; ?>
<?php if(!empty($record->image)): ?><div id="objimage"><img id="objectImage" alt="<?php echo $record->image_alt ?>" src="<?php echo $record->image ?>" border="0"></div> 
<?php endif; ?>
<?php if(!empty($record->note)): ?><div class="objectNote"><?php echo $record->note ?></div> <?php endif; ?>
<div class="objectText"><?php echo $record->text ?></div> 
<input type="button" id="butfirst" class="buttonSend" onclick="document.location='<?php echo seMultiDir()."/".$_page."/" ?>';" value="<?php echo $section->parametrs->param4 ?>">
</div> 
