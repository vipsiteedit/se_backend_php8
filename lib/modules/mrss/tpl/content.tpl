<div class="content" id="rss" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span> 
        </<?php echo $section->title_tag ?>> 
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php foreach($section->objects as $record): ?>
        <div class="object">
            <span class="objectTitle">
                <a href="<?php echo $record->link ?>">
                    <?php echo $record->title ?> <!-- span class="objectTitleTxt"><?php echo $record->title ?></span -->
                </a>
            </span> 
            <span class="dataType_date"><?php echo $record->pubdate ?></span> 
            <?php if(!empty($record->image)): ?>
                <img border="0" class="objectImage" src="<?php echo $record->image ?>" width="100" height="100" alt="<?php echo $record->image_alt ?>">
            <?php endif; ?>
            <span class="objectNote"><?php echo $record->note ?></span>
        </div> 
    
<?php endforeach; ?> 
</div> 
