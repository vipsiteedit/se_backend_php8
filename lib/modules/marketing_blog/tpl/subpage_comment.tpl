                <form id="myComment" style="margin:0px;" action="" method="post" enctype="multipart/form-data">
                    <div class="comments_ins_title"><?php echo $section->language->lang053 ?></div>
                    <?php if(($user_id==0)||(($section->parametrs->param8=='A')&&($user_id==0))): ?>
                        <div class="comments_ins_name">
                            <input type="text" name="guest_name" placeholder="<?php echo $section->language->lang056 ?>" required>
                        </div>
                    <?php endif; ?>
                    <div class="comments_ins_add">  
                        <textarea class="comments_ins_add_text" rows="10" cols="40" name="message" placeholder="<?php echo $section->language->lang054 ?>" required></textarea> 
                        <div class="smile_carot"  onmouseover="$(this).find('.smile_list').show();" onmouseout="$(this).find('.smile_list').hide();"><?php echo $section->language->lang087 ?>
                        <div class="smile_list" style="display:none;">
                            
                            
                            <?php foreach($section->smiles as $smile): ?>
                                <span class="smile_list_fetch" data-abbr="<?php echo $smile->abbr ?>"  style="width: 16px; height: 16px; cursor: pointer;">
                                    <img src="/lib/emotion/<?php echo $smile->img ?>">
                                </span>
                            
<?php endforeach; ?>
                            
                        </div>
                        </div>
                    </div>
                    <?php if($isCapcha==1): ?>
                        <div class="capcha">
                            <img id="pin_img" src="/lib/cardimage.php?session=<?php echo $cid ?>">
                            <div class="titlepin"><?php echo $section->language->lang061 ?></div>
                            <input class="inp inppin " name="pin" maxlength="5" value="" autocomplete="off">
                        </div> 
                    <?php endif; ?>
                    <div class="comments_ins_buttons">
                        <input type='hidden' name='idlink' value='<?php echo $id ?>'>
                        <input type='hidden' name='strCheck' value=''>
                        <input class="buttonSend goButton" name="putComment" type="submit" value="<?php echo $section->language->lang055 ?>">
                    </div>
                </form>
                <script type="text/javascript">
                    $("#myComment").find(".smile_list_fetch").on('click', function(){
                        smile = $(this).attr("data-abbr");
                        insert_text_cursor('message',smile);
                    })
                </script>
