<header:js>
    <script type="text/javascript" src="[this_url_modul]share42.js"></script>
</header:js>  
<style type="text/css"> 
    .cont_photo #share42 {
        display: inline-block;
        padding: 6px 0 0 6px;
        background: #FFF;
        border: 1px solid #E9E9E9;
        border-radius: 4px;
    }
    .cont_photo #share42:hover {
        background: #F6F6F6;
        border: 1px solid #D4D4D4;
        box-shadow: 0 0 5px #DDD;
    }
    .cont_photo #share42 a {opacity: 0.5;}
    .cont_photo #share42:hover a {opacity: 0.7}
    .cont_photo #share42 a:hover {opacity: 1}
</style>
<div class="content cont_photo"<?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>" border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?> 
    <div class="classNavigator">
        <?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
    </div>
    <?php $__data->recordsWrapperStart($section->id) ?><?php echo $__data->linkAddRecord($section->id) ?>
        <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

            <div class="photo record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                <div class="photoBriefImg" itemscope itemtype="http://schema.org/ImageObject">
                    <?php if(!empty($record->image)): ?>
                        <div class="objectImage record-pimage">
                            <a href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>">
                                <img alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>" src="<?php echo $record->image_prev ?>" border="0" class="photoPrev" itemprop="contentUrl">
                            </a>
                        </div> 
                    <?php endif; ?>
                </div> 
                <a class="linkText record-link" href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>" itemprop="name"><?php echo $record->title ?></a>
            </div> 
        
<?php endforeach; ?>
    <?php $__data->recordsWrapperEnd() ?>


</div> 
