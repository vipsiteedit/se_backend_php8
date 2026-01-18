<?php if(strval($section->parametrs->param8)=='Y'): ?>
    <header:css>
     [lnk:fancybox2/jquery.fancybox.css]
    </header:css>
    <header:js>
     [js:jquery/jquery.min.js]
     [js:fancybox2/jquery.fancybox.pack.js]
        <script type="text/javascript">
            $(document).ready(function() {
                $("a.gallery<?php echo $section->id ?>").fancybox();
            });
        </script>
    </header:js>
<?php endif; ?>
<div class="content cont_txt"<?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </<?php echo $section->title_tag ?>> 
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div> 
    <?php endif; ?>
<?php $__data->recordsWrapperStart($section->id) ?><?php echo $__data->linkAddRecord($section->id) ?>
    <div class="classNavigator">
        <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    </div>
<?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

    <div class="object record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
        <?php if(!empty($record->title)): ?>
            <<?php echo $record->title_tag ?> class="objectTitle">
                <span class="objectTitleTxt record-title"><?php echo $record->title ?></span> 
            </<?php echo $record->title_tag ?>> 
        <?php endif; ?>
        <?php if(!empty($record->image)): ?>
            <?php if(strval($section->parametrs->param8)=='Y'): ?><a class="gallery<?php echo $section->id ?>" rel="group" title="" href="<?php echo $record->image ?>"><?php endif; ?>
                <div class="objectImage record-pimage">
                    <img class="objectImg" border="0" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                </div>
            <?php if(strval($section->parametrs->param8)=='Y'): ?></a><?php endif; ?>
        <?php endif; ?>
        <?php if(!empty($record->note)): ?>
            <div class="objectNote record-note"><?php echo $record->note ?></div> 
        <?php endif; ?>
        <?php if(!empty($record->text)): ?>
            <a class="linkNext" href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>"><?php echo $section->parametrs->param1 ?></a> 
        <?php endif; ?>
    </div> 

<?php endforeach; ?>   
    <div class="classNavigator">
        <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    </div>
<?php $__data->recordsWrapperEnd() ?>


</div>       
