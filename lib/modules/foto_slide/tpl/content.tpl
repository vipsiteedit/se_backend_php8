<?php if(file_exists($__MDL_ROOT."/php/subpage_2.php")) include $__MDL_ROOT."/php/subpage_2.php"; ?><?php include "subpage_2.tpl"; ?>
<script type="text/javascript">
contentParam["<?php echo $section->id ?>"]={opacity:<?php echo $section->parametrs->param3 ?>/100, textImage:" <?php echo $section->parametrs->param4 ?>", textOf:" <?php echo $section->parametrs->param5 ?>", close:" <?php echo $section->parametrs->param6 ?>", next:" <?php echo $section->parametrs->param7 ?>", prev:" <?php echo $section->parametrs->param8 ?>", loading:" <?php echo $section->parametrs->param10 ?>"};
</script>
<div class="content photoAlbumSplash <?php echo $section->id ?>" <?php echo $section->style ?>>
<?php if(file_exists($__MDL_ROOT."/php/subpage_1.php")) include $__MDL_ROOT."/php/subpage_1.php"; ?><?php include "subpage_1.tpl"; ?>
<?php $__data->recordsWrapperStart($section->id) ?><?php echo $__data->linkAddRecord($section->id) ?>
<?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

<div class="obj record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
<?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
    <div class="photoPreview">
        <?php if(!empty($record->image)): ?><a class="photoLink" href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>"><img alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>" src="<?php echo $record->image_prev ?>" border="0" class="previewImg" /></a><?php endif; ?>
        <?php if(!empty($record->title)): ?><a class="textLink" href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>"><?php echo $record->title ?></a><?php endif; ?>
    </div>
</div>

<?php endforeach; ?>
<?php $__data->recordsWrapperEnd() ?>


<?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
<!-- [addphotos] -->
</div> 
