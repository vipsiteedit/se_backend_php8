<header:css>
[include_css]
</header:css>
<?php if(strval($section->parametrs->param1)!='d'): ?>
<div class="<?php if(strval($section->parametrs->param1)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
<section class="content b_trust_us" >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle">
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </<?php echo $section->title_tag ?>> 
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div> 
    <?php endif; ?>
<div class="b_trust_us-objects_block">
<?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

    <div class="object b_trust_us-object" <?php echo $__data->editItemRecord($section->id, $record->id) ?>><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
        <?php if(!empty($record->image)): ?>
            <div class="b_trust_us-object_image_block">
                <img class="object b_trust_us-object_image" border="0" src="<?php echo $record->image_prev ?>" border="0" alt="<?php echo $record->image_alt ?>">
            </div>
        <?php endif; ?>
        <div class="b_trust_us-object_content">
        <?php if(!empty($record->title)): ?>
            <<?php echo $record->title_tag ?> class="object b_trust_us-object_title">
                <span class="object b_trust_us-object_title_text"><?php echo $record->title ?></span>
            </<?php echo $record->title_tag ?>> 
        <?php endif; ?>
        <?php if(!empty($record->note)): ?>
            <div class="object b_trust_us-object_text"><?php echo $record->note ?></div>
        <?php endif; ?>
        </div>
    </div>

<?php endforeach; ?>
</div>
</section><?php if(strval($section->parametrs->param1)!='d'): ?>
</div><?php endif; ?>
