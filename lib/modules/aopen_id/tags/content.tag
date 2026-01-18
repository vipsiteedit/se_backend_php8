<header:js>
<serv>
    <script src="//loginza.ru/js/widget.js" type="text/javascript"></script>
</serv>
<SE>
<script type="text/javascript"> 
    function logins(val){
        if(val=='s'){
            $('.loginblock').hide();    
            $('.logoutblock').show();
        }
        if(val == 'a'){
            $('.loginblock').show();
            $('.logoutblock').hide();
        }        
    };
</script> 
</SE>
</header:js>
<div class="content openid" [part.style][contedit]>
    <if:{$enter}=="logout">
        <div class='logoutblock row'>
            <div class='title col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <img class='img-responsive img-rounded title_img' src='<if:($lists_photo)>{$lists_photo}<else>[module_url]nofoto.gif</if>'>
            </div>
            <div class='invitation col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                [param14]<span class='username'>[NAMEUSER]</span>
            </div>
            <if:{$seUserGroup}!=3>
                    <div class='soc_link_a col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <a href='{$url}'>[param18]</a>
                        <if:($extra_avatar)>
                            <a href='?id_account'>[param26]</a>
                        </if>
                    </div>
                    <if:{$extra_avatar}>
                        <div class='extra_images col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span class='extra_title'>[param13]</span>
                            {$extra_avatar}
                        </div>
                    </if>
            </if>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 lnkout">
                <a class='links' href='?logout'>[param15]</a>
            </div>
        </div>
    </if>
    <if:{$enter}=='login'>
        <div class='loginblock row'>
            <if:(([param23]=='f')||([param23]=='t'))>
                <div class='openIdlogin col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                    <form action='<se>[thispage.link]</se>' method='post'>
                        <div class="form-group">
                            <span class='title'>[param3]</span>
                        </div>
                        <div class="form-group">
                            <input type='hidden' value='true' name='authorize'>
                        </div>
                        <div class="form-group">
                            <input class='authorlogin' name='authorlogin' value='' placeholder='[param21]'>
                        </div>
                        <div class="form-group">
                            <input class='authorpassw' type='password' name='authorpassword' value='' placeholder='[param22]'>
                        </div>
                        <if:([param24]=='y')>
                            <div class="checkbox authorSave">
                                <label >
                                    <input id='authorSaveCheck' type='checkbox' value='1' name='authorSaveCheck'>
                                    [param19]
                                </label>
                            </div>
                        </if>
                        <button type="submit" class="btn btn-default loginsend" name='GoToAuthor' <se>onclick="logins('s'); return false;"</se> >[param10]</button>
                    </form>
                    <if:([param25]=='y')>
                        <a class='links regi' href='{$reg_link}'>[param20]</a>
                    </if>
                    <if:([param32]!='')>
                        <a class='links remem' href='{$restore_link}'>[param33]</a>
                    </if>
                </div>
            </if>
            <if:(([param23]=='s')||([param23]=='t'))>
                <div class='openIdBlock col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <if:([param6]=='s')>
                    <div class='loginblocktxt'>[param2]</div>
                    <a href='{$url}' class='loginzain'  <se>onclick="logins('s'); return false;"</se> >
                        <img class='imgs' src='[module_url]social_add.png'/>
                    </a>
                <else>
                    <div class='loginblocktxt'>[param2]</div>
                    <a href='{$url}' class='loginzain'  <se> onclick="logins('s'); return false;"</se> >[param1]</a>
                </if>
                </div>
            </if>
        </div>    
<se>
        <div class='logoutblock row' style="display:none;">
            <div class='title col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <img class='img-responsive img-rounded title_img' src='[module_url]nofoto.gif'>
            </div>
            <div class='invitation col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                [param14]<span class='username'>[NAMEUSER]</span>
            </div>
            <if:{$seUserGroup}!=3>
                    <div class='soc_link_a col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <a href='{$url}'>[param18]</a>
                        <a href='?id_account'>[param26]</a>
                    </div>
                    <if:{$extra_avatar}>
                        <div class='extra_images col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span class='extra_title'>[param13]</span>
                            <img class='icon_img' src='[this_url_modul]facebook.png'>
                            <img class='icon_img' src='[this_url_modul]twitter.png'>
                            <img class='icon_img' src='[this_url_modul]vkontakte.png'>
                            <img class='icon_img' src='[this_url_modul]google.png'>
                            <img class='icon_img' src='[this_url_modul]mailruapi.png'>
                            <img class='icon_img' src='[this_url_modul]odnoklassniki.png'>
                        </div>
                    </if>
            </if>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 lnkout">
                <a class='links' href='#'  onclick="logins('a');">[param15]</a>
            </div>
        </div>
</se>
    </if>
</div>    
<se>        
<BR class="sysedit"><a class="sysedit" href='[link.subpage=1]'>Субстраница 1</a>
<BR class="sysedit"><a class="sysedit" href='[link.subpage=2]'>Субстраница 2</a>
</se>
