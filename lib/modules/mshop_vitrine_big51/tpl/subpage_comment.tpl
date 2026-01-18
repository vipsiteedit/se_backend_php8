<h3 class="titleHead">
    <span><?php echo $section->language->lang018 ?></span>
    <span style="color:grey;"><?php echo $count_comment ?></span>
</h3>
<div class="content">
<div class="comment">
<?php if($comm=='0'): ?>
    <div class="no_comment"><?php echo $section->language->lang041 ?></div>
<?php else: ?>
    <table class="tableTable tableComment" cellSpacing="0" cellPadding="0" width="100%">
        <?php foreach($section->comments as $comm): ?>
            <tr class="tableRow <?php echo $comm->style ?>">
                <td> 
                    <div class="headComment">
                        <div class="comm_date"><?php echo $comm->date ?></div> 
                        <div class="comm_titlename"><?php echo $comm->name ?></div> 
                    </div>
                    <div class="comm_txt"><?php echo $comm->comment ?> </div>
                    <?php if(!empty($comm->adminnote)): ?>
                        <div class="comm_admnote"><?php echo $section->language->lang040 ?> <?php echo $comm->adminnote ?></div>
                    <?php endif; ?>
                </td>
            </tr> 
        
<?php endforeach; ?>
    </table>
    
<?php endif; ?>
    <?php if($user_group!=0): ?>
        <div class="addComment">
            <form style="margin:0px;" method="post" action="">
                <?php if($error_comm_message!=''): ?>
                    <div class="error"><?php echo $error_comm_message ?></div>
                <?php endif; ?>
                <div class="title"><?php echo $section->language->lang036 ?></div>
                <textarea class="areatext" title="<?php echo $section->language->lang036 ?>" name="comm_note" rows="7"></textarea>
                <input class="buttonSend" type="submit" value="<?php echo $section->language->lang037 ?>" name="GoToComment">
            </form> 
        </div>
    <?php else: ?>
        <div class="comm_info"><?php echo $section->language->lang038 ?></div>
    <?php endif; ?>
    
</div>
</div>
