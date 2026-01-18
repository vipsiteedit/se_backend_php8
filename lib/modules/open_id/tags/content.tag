<footer:js>
[js:jquery/jquery.min.js]
<serv>
<script src="//loginza.ru/js/widget.js" type="text/javascript"></script>
</serv>
<SE>
<script type="text/javascript"> 
<!--
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
-->
</script> 
</SE>
</footer:js>
<div class="content openid" [part.style][contedit]>
        {$enter}
        <SE>
        <!-- log in -->
        <div class="loginblock">    
        <if:(([param23]=='t')||([param23]=='f'))>       
            <div class="openIdlogin">
            <form style="margin: 0;" action="[param34]" method="post">
                <span class="title">[param3]</span>
                <input type="hidden" value="true" name="authorize">
                <input class="authorlogin" name="authorlogin" onfocus="if (this.value=='[param21]') this.value='';" onblur="if (this.value=='') this.value='[param21]';" value="[param21]">
                <input class="authorpassw" type="password" name="authorpassword" onfocus="if (this.value=='[param22]') this.value='';" onblur="if (this.value=='') this.value='[param22]';" value="[param22]">
                <input class="buttonSend loginsend" type="button" value="[param10]" name="GoToAuthor"  onclick="logins('s')">
                <if:[param24]=='y'>
                    <div class="authorSave">
                        <input id="authorSaveCheck" type="checkbox" value="1" name="authorSaveCheck"><label for="authorSaveCheck" class="authorSaveWord">[param19]</label>
                    </div>
                </if>
            </form>
            <if:[param25]=='y'>
                <a class="links regi" href="[param20].html">[param20]</a>
            </if>
            <if:[param32]!=''>
                <a class="links remem" href="[param32].html">[param33]</a>
            </if>
            </div>
        </if>
        <if:(([param23]=='s')||([param23]=='t'))>
            <div class="openIdBlock">
              <span class='loginblocktxt'>[param2]</span>
              <if:[param6]=='s'> 
                    <a href="javascript:logins('s')" class='loginzain'><img class="imgs" src='[this_url_modul]social_add.png' border="0"></a>
                <else>
                    <a href="javascript:logins('s')" class='loginzain'>[param1]</a>
                </if>
            </div>
        </if>
        </div>           
        
        <!-- log out -->
        <div class="logoutblock" style="display:none;">
            <span class="title">
                <img src="[this_url_modul]nofoto.gif" class="title_img">
            </span>
            <div class="invitation">[param14]
                <span class="username">Иван Иванов</span>
            </div>
            <div class="soc_link">
                <span class="soc_link_a">
                    <a href="#">[param18]</a>
                    <a href="[link.subpage=p2]">[param26]</a>
                </span>
                <span class='extra_images'><span class="extra_title">[param13]</span>
                    <img class='icon_img' src='[this_url_modul]facebook.png'>
                    <img class='icon_img' src='[this_url_modul]twitter.png'>
                    <img class='icon_img' src='[this_url_modul]vkontakte.png'>
                    <img class='icon_img' src='[this_url_modul]google.png'>
                    <img class='icon_img' src='[this_url_modul]mailruapi.png'>
                    <img class='icon_img' src='[this_url_modul]odnoklassniki.png'>
                </span>
            </div>
            <a class="links" href="javascript:logins('a')">[param15]</a>
        </div>
        </SE>
        
    </div>
<SE>
<BR class="sysedit"><a class="sysedit" href='[link.subpage=p1]'>Субстраница 1</a>
<BR class="sysedit"><a class="sysedit" href='[link.subpage=p2]'>Субстраница 2</a>
</SE>
