 <div class="content contLastNews" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php foreach($section->objects as $record): ?>
        <div class="object">
            <h4 class="objectTitle">
                <span class="dataType_date"><?php echo $record->date ?></span>
                <a class="textTitle" href="<?php echo $record->shownews ?>"><?php echo $record->title ?></a>
            </h4>
            <?php if(!empty($record->image_prev)): ?>
                <a href="<?php echo $record->shownews ?>">
                    <img border="0" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->title ?>" class="objectImage">
                </a>
            <?php endif; ?>        
            <div class="objectNote"><?php echo $record->text ?></div> 
            <?php if(strval($section->parametrs->param9)=='Y'): ?>
                <span class="newslinks"><?php echo $record->link ?></span>
            <?php endif; ?>
        </div>
    
<?php endforeach; ?>
    <a class="linkNews" href="<?php echo $site ?><?php echo seMultiDir()."/".$section->parametrs->param2."/" ?>"><?php echo $section->parametrs->param8 ?></a>
    
</div>
