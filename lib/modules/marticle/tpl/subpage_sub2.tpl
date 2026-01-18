<?php if(strval($section->parametrs->param45)!='Yes'): ?>
    <div id="ya_share1" style="margin: 10px 0;">
        
    </div>
    
        <footer:js>[js:jquery/jquery.min.js]
        <script type="text/javascript" src="http://yandex.st/share/share.js" charset="utf-8"></script>
        <?php if(file_exists($__MDL_ROOT."/php/subpage_sub4.php")) include $__MDL_ROOT."/php/subpage_sub4.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_sub4.tpl")) include $__data->include_tpl($section, "subpage_sub4"); ?>
        <script type="text/javascript">
            var note = $("div#view div.objectText").html();
            note = note.substring(0, 150);
            new Ya.share({
                element: 'ya_share1',
                elementStyle: {type: 'button', linkIcon: true, border: false, quickServices: ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj'] },
                popupStyle: {'copyPasteField': true},
                description: note,
                onready: function(ins){
                        $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf=<?php echo $section->parametrs->param44 ?>\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
                }
            });
            switchTxt("div.addComment div.name input", 'article', '<?php echo $section->parametrs->param36 ?>');
        </script>
        </footer:js>
    
<?php endif; ?>
<div class="guestList">
    <?php if($comments_count!=0): ?>
        <div class="passert">
            <div class="commentTitle"><?php echo $section->language->lang033 ?></div>
            <a name="comments"></a>
            <?php foreach($section->comments as $comm): ?>
                <div class="comment comment<?php echo $comm->id ?>" data-id="<?php echo $comm->id ?>">
                    <div class="date"><?php echo $comm->date ?></div>
                    <div class="name">
                        <a href="mailto:<?php echo $comm->email ?>" ><?php echo $comm->name ?></a>
                    </div>
                    <div class="text"><?php echo $comm->comment ?></div>
                    <?php if($comm->access==1): ?>
                        <div class="comm_del">
                            <form style="margin:0px;" action="<?php echo seMultiDir()."/".$_page."/".$razdel."/subsub3/" ?>" method="post">
                                <input type="hidden" name="comment_id" value="<?php echo $comm->id ?>">
                                <input type="hidden" name="object" value="<?php echo $_object ?>">
                                <input class="buttonSend DelCommPre" type="submit" value="<?php echo $section->language->lang025 ?>" name="DelCommPre">
                            </form>
                        </div>      
                    <?php endif; ?>
                </div>
            
<?php endforeach; ?> 
            <?php if(strval($section->parametrs->param42)=='Yes'): ?>
                <?php echo $ARTICLE_PAGES ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>      
    <div class="addComment">
        <form style="margin:0px;" action="" method="POST">
            <div class="blockError">
                <?php echo $COMMENTS ?>
                <?php if($error_message!=''): ?>
                    <div class="errorMessage"><?php echo $error_message ?></div>
                <?php endif; ?>  
            </div>
            <div class="title"><?php echo $section->language->lang034 ?></div>
            <div class="obj name">
                <label for="name"><?php echo $section->language->lang021 ?></label>
                <div>
                    <input id="name" title="<?php echo $section->language->lang010 ?>" value="<?php echo $name ?>" name="name" required>
                </div>
            </div>
            <div class="obj email">                     
                <label for="email"><?php echo $section->language->lang022 ?></label>
                <div>
                    <input id="email" title="<?php echo $section->language->lang011 ?>" name="email" value="<?php echo $email ?>" required>
                </div>
            </div>
            <div class="obj note">
                <label for="note"><?php echo $section->language->lang023 ?></label>
                <div>
                    <textarea id="note" title="<?php echo $section->language->lang023 ?>" name="note" rows="7" cols="60" required><?php echo $note ?></textarea>
                </div>
            </div>
            <?php if(strval($section->parametrs->param15)=="Yes"): ?>
                <div class="antiSpam">
                    <div  class="tablrow">
                        <img id="pin_img" src="<?php echo $capcha ?>">
                        <label><?php echo $section->language->lang026 ?></label>
                        <input class="inp inppin <?php echo $errstpin ?>" <?php echo $glob_err_stryle ?> name="pin" maxlength="5" title="<?php echo $section->language->lang026 ?>" value="" autocomplete="off" required>
                    </div>  
                </div>
            <?php endif; ?>
            <div class="buttonAreaEdit">
                <input name="GoTo" type="submit" value="<?php echo $section->language->lang024 ?>" class="buttonSend">
            </div>
        </form>
    </div>
</div>
<?php if($comments_count!=0): ?>
    <footer:js>
    <script>
        $('.guestList .comment input.DelCommPre').on('click', function(e){
            e.preventDefault();
            var id = $(this).closest('div.comment').attr('data-id');
            if (confirm('<?php echo $section->language->lang016 ?>')) {
                $.post("?article<?php echo $section->id ?>", {id: ''+id}, function(data){
                    $('.guestList .comment'+data).remove();
                });
            }
        });
        
    </script>
    </footer:js>
<?php endif; ?>
