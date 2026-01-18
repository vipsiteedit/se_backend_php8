<footer:js>
    [js:jquery/jquery.min.js]
    <script type="text/javascript" src='[this_url_modul]password_strength_plugin.js'></script>
    <script type="text/javascript">
        $(document).ready( function() {
        $(".password_adv").passStrength({
            shortPass:      "reg_shortPass",
            badPass:        "reg_badPass",
            goodPass:       "reg_goodPass",
            strongPass:     "reg_strongPass",
            baseStyle:      "reg_testresult",
            samePassword:   "reg_samePassword",
            pshortPass:  '[lang028]',
            pbadPass: '[lang029]',
            pgoodPass: '[lang030]',
            pstrongPass: '[lang031]',
            psamePassword: '[lang032]',
            userid:     "#login",
            messageloc:     1
            });
        });           
    </script>
</footer:js>
<div class="content contAuthCheskPass container" [part.style][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]><span class="contentTitleTxt">[part.title]</span></[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img alt="[part.image_alt]" title="[part.image_alt]" border="0" class="contentImage" [part.style_image] src="[part.image]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <div class="contAuthBlock">
        <form id="Go" action="" method="post" style="margin:0px">
            <div class="errorText">
                <se>
                    <span class="sysedit">
                </se>
                [$error_message]
                <se>
                    [lang007]</span>
                </se>
            </div>
            <div class="obj login">
                <label for="login">[lang012]<span>*<span></label>
                <div class="area">
                    <input id="login" name="username" title="[lang012]" value="{$_username}"><em>[lang015]</em>
                </div>
            </div>
            <div class="obj passw">
                <label for="passw">[lang011]<span>*<span></label>
                <div class="area">
                    <input id="passw" class="password_adv" type="password" size="20" name="passw" title="[lang011]">
                        <se>
                            <span class="sysedit">
                                <div class="reg_testresult">
                                    <div class="reg_shortPass"> [lang028]</div>
                                    <div class="reg_badPass"> [lang029]</div>
                                    <div class="reg_goodPass"> [lang030] </div>
                                    <div class="reg_strongPass"> [lang031]</div>
                                    <div class="reg_samePassword">[lang032]</div>
                                </div>
                            </span>
                        </se>
                    <em>[lang014]</em>
                </div>
            </div>
 
            <div class="obj confpassw">
                <label for="confpassw">[lang010]<span>*<span></label>
                <div class="area">
                    <input type="password" id="confpassw" size="20" name="confpassw" title="[lang010]">
                </div>
            </div>
            <div class="obj email">
                <label for="email">[lang009]<span>*<span></label>
                <div class="area">
                    <input id="email" name="email" title="[lang009]" value="{$_email}"><em>[lang013]</em>
                </div>
            </div>
            <div class="obj firstName">
                <label for="firstName">[lang004]<span>*<span></label>
                <div class="area">
                    <input id="firstName" name="first_name" title="[lang004]" value="{$_first_name}">
                </div>
            </div>
            <div class="obj lastName">
                <label for="lastName">[lang003]<span>*<span></label>
                <div class="area">
                    <input id="lastName" name="last_name" title="[lang003]" value="{$_last_name}">
                </div>
            </div>
            <div class="obj phone">
                <label for="phone">[lang033]<span>*<span></label>
                <div class="area">
                    <input id="phone" name="phone" title="[lang033]" value="{$phone}">
                </div>
            </div>
            <if:[param46]=='Y'>
                <div class="license">
                    <input id="license" type="checkbox" name="license" checked>
                    <label for="license">[lang024]&nbsp;<a href=<SE>"#"</SE><SERV>"[param33]" target="_blank"</SERV>>[lang025]</a></label>
                </div>
            </if>
            <div class="antiSpam">
                {$anti_spam}
            </div>
            [subpage name=license]
            <div class="buttonArea">
                <input name="GoToAuthor" <serv>type="submit"</serv> <se>type="button" onclick="document.location.href='[@subpage1]';"</se> value="[lang005]" class="buttonSend">
            </div>
        </form>
    </div>
    <se>
        <br class="sysedit">
        <div style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black;" class="sysedit">
            [lang001]
            <br>
            [lang002] 
            <a href="[param33]">[param33]</a>
        </div>
    </se>
</div>
