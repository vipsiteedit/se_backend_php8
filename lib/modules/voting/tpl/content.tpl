<div class="content" id="cont_golos">
    <?php if($section->title!=''): ?>
        <a name="<?php echo $section->id ?>"></a>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php echo $__data->linkAddRecord($section->id) ?>
    <form action="" method="post" style="margin:0px;">
        <div class="object"[buttonstyle]>
            <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>
    
                <h4 class="objectTitle"<?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                    <input TYPE="RADIO" name="voting_radio" value="<?php echo $record->id ?>">
                    <font color="<?php echo $record->field ?>"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></font>
                </h4> 
            
<?php endforeach; ?>
            <?php echo $VOTINGSHOW ?>
            <div id="areaButton">
                <input name="GoTo_VOTING" class="buttonSend buttonVoting" <?php echo $voting->buttonstyle ?> type=submit value="<?php echo $section->parametrs->param6 ?>">
                <input name="GoTo_SHOW" class="buttonSend buttonResult" <?php echo $voting->buttonstyle ?> type=submit value="<?php echo $section->parametrs->param7 ?>">
            </div> 
            <input type="hidden" name="razdel" value="<?php echo $section->id ?>">
        </div>
    </form> 
     
     
</div> 
