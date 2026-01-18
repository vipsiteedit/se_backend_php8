<?php if($show!=0): ?>
    <div class="addcommmenttt comments_ins">
        <?php if($usrName==""): ?> 
            <div class="obj name">
                <label class="title" for="name"><?php echo $section->language->lang050 ?></label>
                <div class="field">
                    <input class="inputs" id="name" name="name<?php echo $idlink ?>"  maxlength="255" placeholder="<?php echo $section->language->lang050 ?>">
                </div> 
            </div>
        <?php endif; ?>
        <div class="comments_ins_title"><?php echo $section->language->lang079 ?></div>  
        <textarea class="comments_ins_text" rows="3" cols="commentsinstext" name="commentsinstext<?php echo $idlink ?>"  placeholder="<?php echo $section->language->lang081 ?>"></textarea>
        <!--div class="antispam"> 
            <img class="pin_img" src="/lib/cardimage.php?session=<?php echo $sid ?>&<?php echo $tim ?>">
            <div class="titlepin"><?php echo $section->language->lang034 ?></div>
            <input class="inp inppin" name="pin<?php echo $idlink ?>" maxlength="5" value="" autocomplete="off">
        </div-->
        <div class="buttons">
            <input class="buttonSend goButton" name="GoTonewbbs" type="submit" value="<?php echo $section->language->lang032 ?>">
            <input class="buttonSend delform" name="delform" onclick="$('.addcommmenttt').remove(); $('.comments_ins').show();" type="button" value="<?php echo $section->language->lang037 ?>">
        </div>
        <input type=hidden name="iddopcomvv" value="<?php echo $idlink ?>"> 
    </div>
<?php endif; ?>
