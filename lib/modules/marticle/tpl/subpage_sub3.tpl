<div class="content cont_sub3">
    <form style="margin:0px;" action="" method="post">
        <input type="hidden" name="comment_id" value="<?php echo $id ?>">
        <br><label><?php echo $section->language->lang016 ?></label>                  
        <input class="buttonSend" type="submit" value="<?php echo $section->language->lang017 ?>" name="DelComm">
        <input type="hidden" name="object" value="<?php echo $_object ?>">
        <input class="buttonSend" type="submit" value="<?php echo $section->language->lang018 ?>" name="DelCommNo">   
    </form>
</div>
