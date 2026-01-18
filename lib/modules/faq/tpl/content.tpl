<div class="content" id="cont_faq"<?php echo $section->style ?>>
    <a name="r<?php echo $section->id ?>"></a> 
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div id="linkBox">
        <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

            <a id="linkTitle" href="#r<?php echo $section->id ?>_<?php echo $record->id ?>"><?php echo $record->title ?></a>
        
<?php endforeach; ?>
    </div> 
    <br clear="all">
    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="object">
            <h4 class="objectTitle">
                <a name="r<?php echo $section->id ?>_<?php echo $record->id ?>"></a> 
                <span class="objectTitleTxt"><?php echo $record->title ?></span>
            </h4>
            <?php if(!empty($record->image)): ?>
                <a target="_blank" href="<?php echo $record->image ?>">
                    <img border="0" class="objectImage" id="objprevImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>">
                </a>
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div>
            <?php endif; ?>
            <?php if(!empty($record->text)): ?>
                <div class="objectText"><?php echo $record->text ?></div>
            <?php endif; ?>
            <a class="go_up" title="<?php echo htmlspecialchars($section->parametrs->param2) ?>" href="#r<?php echo $section->id ?>"><?php echo $section->parametrs->param2 ?></a>
        </div> 
    
<?php endforeach; ?>
</div> 
