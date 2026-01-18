
    <div class="content" id="cont_news" <?php echo $section->style ?>>
        <div id="view">
            <?php if(!empty($record->title)): ?>
                <h4 class="objectTitle">
                    <span class="objectTitleTxt"><?php echo $record->title ?></span>
                </h4>
            <?php endif; ?>
            <?php if(!empty($record->image)): ?>
                <div class="objimage">
                    <img class="objectImage" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>" src="<?php echo $record->image ?>" border="0">
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param5=='Y'): ?>
                <?php if(!empty($record->note)): ?>
                    <div class="objectNote"><?php echo $record->note ?></div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="objectText"><?php echo $record->text ?></div> 
            <input class="buttonSend" onclick="window.history.back();" type="button" value="<?php echo $section->parametrs->param4 ?>">
        </div> 
    </div> 
