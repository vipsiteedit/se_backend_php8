<if:[param45]!='Yes'>
    <div id="ya_share1" style="margin: 10px 0;">
        <SE>
            <img src="[module_url]kont.png">
        </SE>
    </div>
    <SERV>
        <footer:js>[js:jquery/jquery.min.js]
        <script type="text/javascript" src="http://yandex.st/share/share.js" charset="utf-8"></script>
        [subpage name=sub4]
        <script type="text/javascript">
            var note = $("div#view div.objectText").html();
            note = note.substring(0, 150);
            new Ya.share({
                element: 'ya_share1',
                elementStyle: {type: 'button', linkIcon: true, border: false, quickServices: ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj'] },
                popupStyle: {'copyPasteField': true},
                description: note,
                onready: function(ins){
                        $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf=[param44]\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
                }
            });
            switchTxt("div.addComment div.name input", 'article', '[param36]');
        </script>
        </footer:js>
    </SERV>
</if>
<div class="guestList">
    <if:{$comments_count}!=0>
        <div class="passert">
            <div class="commentTitle">[lang033]</div>
            <a name="comments"></a>
            <repeat:comments name=comm>
                <div class="comment comment[comm.id]" data-id="[comm.id]">
                    <div class="date">[comm.date]</div>
                    <div class="name">
                        <a href=<SERV>"mailto:[comm.email]"</SERV><SE>"#"</SE> >[comm.name]</a>
                    </div>
                    <div class="text">[comm.comment]</div>
                    <if:[comm.access]==1>
                        <div class="comm_del">
                            <form style="margin:0px;" action="[link.subpage=sub3]" method="post">
                                <input type="hidden" name="comment_id" value="[comm.id]">
                                <input type="hidden" name="object" value="{$_object}">
                                <input class="buttonSend DelCommPre" type="submit" value="[lang025]" name="DelCommPre">
                            </form>
                        </div>      
                    </if>
                </div>
            </repeat:comments> 
            <if:[param42]=='Yes'>
                {$ARTICLE_PAGES}
            </if>
        </div>
    </if>      
    <div class="addComment">
        <form style="margin:0px;" action="" method="POST">
            <div class="blockError">
                [$COMMENTS]
                <if:{$error_message}!=''>
                    <div class="errorMessage">{$error_message}</div>
                </if>  
            </div>
            <div class="title">[lang034]</div>
            <div class="obj name">
                <label for="name">[lang021]</label>
                <div>
                    <input id="name" title="[lang010]" value="{$name}" name="name" required>
                </div>
            </div>
            <div class="obj email">                     
                <label for="email">[lang022]</label>
                <div>
                    <input id="email" title="[lang011]" name="email" value="{$email}" required>
                </div>
            </div>
            <div class="obj note">
                <label for="note">[lang023]</label>
                <div>
                    <textarea id="note" title="[lang023]" name="note" rows="7" cols="60" required>{$note}</textarea>
                </div>
            </div>
            <if:[param15]=="Yes">
                <div class="antiSpam">
                    <div  class="tablrow">
                        <img id="pin_img" src="<SERV>{$capcha}</SERV><SE>[system.path]img\cardimage.gif</SE>">
                        <label>[lang026]</label>
                        <input class="inp inppin {$errstpin}" {$glob_err_stryle} name="pin" maxlength="5" title="[lang026]" value="" autocomplete="off" required>
                    </div>  
                </div>
            </if>
            <div class="buttonAreaEdit">
                <input name="GoTo" type=<SE>"button"</SE><serv>"submit"</serv> value="[lang024]" class="buttonSend">
            </div>
        </form>
    </div>
</div>
<if:{$comments_count}!=0>
    <footer:js>
    <script>
        $('.guestList .comment input.DelCommPre').on('click', function(e){
            e.preventDefault();
            var id = $(this).closest('div.comment').attr('data-id');
            if (confirm('[lang016]')) {
                $.post("?article[part.id]", {id: ''+id}, function(data){
                    $('.guestList .comment'+data).remove();
                });
            }
        });
        
    </script>
    </footer:js>
</if>
