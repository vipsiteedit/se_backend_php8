<h3 class="titleHead" id="comments">
    <span><?php echo $section->language->lang018 ?></span>
    <span class="countComments"><?php echo $count_comment ?></span>
</h3>
<div class="content">
    <div class="comment">
    <?php if($count_comment==0): ?>
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
    <?php if($user_group!=0 || strval($section->parametrs->param217) == 'C'): ?>
        <div class="addComment">
            <form method="post" action="#comments">
                <?php if(!empty($error_comm_message)): ?>
                    <div class="error"><span class="errorcomment"><?php echo $error_comm_message ?></span></div>
                <?php endif; ?> 
                <div class="addText form-group">
                    <label class="title" for="comment-text"><?php echo $section->language->lang036 ?></label>
                    <textarea id="comment-text" class="areatext form-control" title="<?php echo $section->language->lang036 ?>" name="comm_text" rows="7" required><?php if(!empty($comment_text)): ?><?php echo $comment_text ?><?php endif; ?></textarea>
                </div>
                <?php if($user_group==0): ?>
                    <div class="addUserName form-group">
                        <label for="comment-user"><?php echo $section->language->lang105 ?></label>
                        <input id="comment-user" class="form-control" type="text" name="comm_user" value="<?php if(!empty($comment_user)): ?><?php echo $comment_user ?><?php endif; ?>" required>
                    </div>
                    <div class="addCaptcha form-group">
                        <?php echo $anti_spam ?>
                    </div>
                <?php endif; ?>                
                <input class="buttonSend btn btn-default" type="submit" value="<?php echo $section->language->lang037 ?>" name="GoToComment">
            </form> 
        </div>
    <?php else: ?>
        <div class="comm_info"><?php echo $section->language->lang038 ?></div>
    <?php endif; ?>
    
</div>
</div>
