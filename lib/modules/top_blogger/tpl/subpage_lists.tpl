        <div class="bloggerlist row">
            <?php foreach($section->bloggers as $blogger): ?>
                <div class="blogger">
                    <span class="blogger_avatar">
                        <img class="blogger_avatar_img" border='0' src="<?php echo $blogger->avatar ?>">
                    </span>
                    <span class="info">
                        <span class="blogger_fio">
                            <?php if($section->parametrs->param2!=''): ?>
                                <a href="<?php echo seMultiDir()."/".$section->parametrs->param2."/" ?>user/<?php echo $blogger->uid ?>/"><?php echo $blogger->fio ?></a>
                            <?php else: ?>
                                <?php echo $blogger->fio ?>
                            <?php endif; ?>
                        </span>
                        <span class="blogger_post">
                            <span class="blogger_post_count"><?php echo $blogger->countpost ?></span>
                            <span class="blogger_post_title"><?php echo $section->language->lang002 ?></span>
                        </span>
                        <span class="blogger_subscribe">
                            <span class="blogger_subscribe_count" id="topblogger_blogger_subscribe_count_<?php echo $blogger->id ?>"><?php echo $blogger->countsibscribe ?></span>
                            <span class="blogger_subscribe_title"><?php echo $section->language->lang003 ?></span>
                        </span>
          
                        <?php if(($user_group>0)&&($blogger->uid>0)): ?>
    
                            <span class="blogger_subcribe">
                                <input type="button" class="buttonSend btn btn-default blogger_subcribe_inp" value="<?php echo $section->language->lang004 ?>" onclick="setSubscribe('<?php echo $blogger->uid ?>');">
                            </span>
    
                        <?php endif; ?>
    
                    </span>
                </div>
            
<?php endforeach; ?>
        </div>
    
