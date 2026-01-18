<div class="content cont_golos">
    <?php if(!empty($section->title)): ?>
        <a name="<?php echo $section->id ?>"></a>
        <<?php echo $section->title_tag ?> class="contentTitle">
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php echo $__data->linkAddRecord($section->id) ?>
    <form action="" method="post" style="margin:0px;">
        <div class="object" [buttonstyle] >
            <?php $__data->recordsWrapperStart($section->id) ?>
                <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>
    
                    <span class="objectTitle record-item"<?php echo $__data->editItemRecord($section->id, $record->id) ?>>
                        <input TYPE="RADIO" name="voting_radio" value="<?php echo $record->id ?>"> 
                        <?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                        <span><?php echo $record->title ?></span>
                    </span> 
                
<?php endforeach; ?>
            <?php $__data->recordsWrapperEnd() ?>
            <?php echo $VOTINGSHOW ?>
            <div class="areaButton">
                <input name="GoTo_VOTING" class="buttonSend buttonVoting" <?php echo $voting->buttonstyle ?> type=submit value="<?php echo $section->language->lang001 ?>">
                <input name="GoTo_SHOW" class="buttonSend buttonResult" <?php echo $voting->buttonstyle ?> type=submit value="<?php echo $section->language->lang002 ?>">
            </div> 
            <input type="hidden" name="razdel" value="<?php echo $section->id ?>">
        </div>
    </form> 
 
 
</div> 
