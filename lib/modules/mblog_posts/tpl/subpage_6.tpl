<?php if($show!=0): ?>
    <div class="addcommmenttt comments_ins">
        <?php if($usrName==""): ?> 
            <div class="obj name">
                <label class="title" for="name"><?php echo $section->parametrs->param33 ?></label>
                <div class="field">
                    <input class="inputs" id="name" name="name<?php echo $idlink ?>"  maxlength="255">
                </div> 
            </div>
        <?php endif; ?>
        <div class="comments_ins_title"><?php echo $section->parametrs->param8 ?></div>  
        <textarea class="comments_ins_text" rows="3" cols="commentsinstext" name="commentsinstext<?php echo $idlink ?>"></textarea>
        <div class="antispam"> 
            <img class="pin_img" src="/lib/cardimage.php?session=<?php echo $sid ?>&<?php echo $tim ?>">
            <div class="titlepin"><?php echo $section->parametrs->param11 ?></div>
            <input class="inp inppin" name="pin<?php echo $idlink ?>" maxlength="5" value="" autocomplete="off">
        </div>
        <div class="buttons">
            <input class="buttonSend goButton" name="GoTonewbbs" type="submit" value="<?php echo $section->parametrs->param8 ?>">
            <input class="buttonSend delform" name="delform" onclick="$('.addcommmenttt').remove();" type="button" value="<?php echo $section->parametrs->param61 ?>">
        </div>
        <input type=hidden name="iddopcomvv" value="<?php echo $idlink ?>"> 
    </div>
<?php endif; ?>
