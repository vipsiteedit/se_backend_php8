<div class="content contSearch" <?php echo $section->style ?>>
    <div class="contentTitle" <?php echo $section->style_title ?>>
        <span class="contentTitleTxt"><?php echo $section->title ?></span>
        <b class="searchString">"<?php echo $SEARCH_TITLE ?>"</b>
    </div>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="searchWarn"><?php echo $SEARCH_WARN ?></div>
    <div class="blockResult">
        <div class="countRec">
            <?php if($SEARCH_COUNTS!=0): ?>
                <?php echo $section->language->lang012 ?><b class="countRecNum"><?php echo $SEARCH_COUNTS ?></b>
            <?php endif; ?>
        </div>
        <div class="blockObjResult">
            <?php echo $SEARCH_CONTENT ?>
            
        </div>
        <div class="steplist">
            <?php echo $steplist ?>
            
        </div>
    </div>
    
</div>
