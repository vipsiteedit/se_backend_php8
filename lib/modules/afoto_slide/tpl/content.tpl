<footer:js>
[js:jquery/jquery.min.js]
[lnk:fancybox3/jquery.fancybox.min.css]    
[js:fancybox3/jquery.fancybox.min.js] 
[include_js({})]
</footer:js>
<div class="content photoAlbumAdapt <?php if(strval($section->parametrs->param12)=='n'): ?>container<?php endif; ?>" >
    <a name="sm<?php echo $section->id ?>"></a>
    <?php if(file_exists($__MDL_ROOT."/php/subpage_head.php")) include $__MDL_ROOT."/php/subpage_head.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_head.tpl")) include $__data->include_tpl($section, "subpage_head"); ?>
    <?php $__data->recordsWrapperStart($section->id) ?><?php echo $__data->linkAddRecord($section->id) ?>
        <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

            <div class="obj record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                <?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                <div class="photoPreview" itemscope itemtype="http://schema.org/ImageObject">
                    <?php if(!empty($record->image)): ?>
                        <a data-fancybox="image<?php echo $section->id ?>" class="photoLink slide-show" href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>" rel="image<?php echo $section->id ?><?php if(strval($section->parametrs->param11)=='N'): ?> nofollow<?php endif; ?>">
                            <img alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>" src="<?php echo $record->image_prev ?>" border="0" class="previewImg" itemprop="contentUrl" />
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($record->title)): ?>
                        <a class="textLink <?php if(strval($section->parametrs->param11)=='N'): ?>slide-show<?php endif; ?>" href="<?php echo $record->link_detail ?>#show<?php echo $section->id ?>_<?php echo $record->id ?>" itemprop="name" <?php if(strval($section->parametrs->param11)=='N'): ?>rel="nofollow"<?php endif; ?>><?php echo $record->title ?></a>
                    <?php endif; ?>
                    <span style="display: none" itemprop="description"><?php echo $record->note ?></span>
                </div>
            </div>
        
<?php endforeach; ?>
    <?php $__data->recordsWrapperEnd() ?>


<?php echo SE_PARTSELECTOR($section->id,count($section->objects),$section->objectcount, getRequest('item',1), getRequest('sel',1)) ?>
<?php if(method_exists($__data, 'editorAddPhotos')) echo $__data->editorAddPhotos($section); ?>
</div> 
