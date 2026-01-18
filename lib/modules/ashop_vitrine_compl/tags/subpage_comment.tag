<h3 class="titleHead" id="comments">
    <span>[lang018]</span>
    <span class="countComments">{$count_comment}</span>
</h3>
<div class="content">
    <div class="comment">
    <if:{$count_comment}==0> 
        <div class="no_comment">[lang041]</div>
    <else>
        <table class="tableTable tableComment" cellSpacing="0" cellPadding="0" width="100%">
            <repeat:comments name=comm>
                <tr class="tableRow [comm.style]">
                    <td> 
                        <div class="headComment">
                            <div class="comm_date">[comm.date]</div> 
                            <div class="comm_titlename">[comm.name]</div> 
                        </div>
                        <div class="comm_txt">[comm.comment] </div>
                        <noempty:comm.adminnote>
                            <div class="comm_admnote">[lang040] [comm.adminnote]</div>
                        </noempty>
                    </td>
                </tr> 
            </repeat:comments>
        </table>
        <SE><div class="no_comment">[lang041]</div></SE>
    </if>
    <if:{$user_group}!=0 || [param217] == 'C'>
        <div class="addComment">
            <form method="post" action="#comments">
                <serv><noempty:{$error_comm_message}></serv>
                    <div class="error"><span class="errorcomment">{$error_comm_message}</span></div>
                <serv></noempty></serv> 
                <div class="addText form-group">
                    <label class="title" for="comment-text">[lang036]</label>
                    <textarea id="comment-text" class="areatext form-control" title="[lang036]" name="comm_text" rows="7" required><noempty:{$comment_text}>{$comment_text}</noempty></textarea>
                </div>
                <serv><if:{$user_group}==0></serv><se><if:[param217]=='C'></se>
                    <div class="addUserName form-group">
                        <label for="comment-user">[lang105]</label>
                        <input id="comment-user" class="form-control" type="text" name="comm_user" value="<noempty:{$comment_user}>{$comment_user}</noempty>" required>
                    </div>
                    <div class="addCaptcha form-group">
                        {$anti_spam}
                    </div>
                </if>                
                <input class="buttonSend btn btn-default" type="<serv>submit</serv><se>button</se>" value="[lang037]" name="GoToComment">
            </form> 
        </div>
    <else>
        <a href="#" class="comm_info msgAuth se-login-modal" data-target="#comments">[lang038]</a>
    </if>
    <SE><div class="comm_info">[lang038]</div></SE>
</div>
</div>
