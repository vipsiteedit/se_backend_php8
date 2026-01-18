
        <div class="content" id="cont_news_show" <?php echo $section->style ?>>
            <?php if(!empty($record->title)): ?>
                <h3 class="contentTitle">
                    <span class="contentTitleTxt"><?php echo $record->title ?></span>
                </h3> 
            <?php endif; ?>
            <?php if(!empty($record->image)): ?>
                <div class="objimage">
                    <img class="objectImage" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>" src="<?php echo $record->image ?>" border="0">
                </div> 
            <?php endif; ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div> 
            <?php endif; ?>      
            <div class="objectText"><?php echo $record->text ?></div> 
            <input class="buttonSend" onclick="window.history.back();" type="button" value="<?php echo $section->parametrs->param3 ?>">
        </div> 
    
