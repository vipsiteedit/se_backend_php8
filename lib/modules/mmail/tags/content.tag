<div class="content cont_mail" [contentstyle] [contedit]>
<noempty:part.title><h3 class="contentTitle" [part.style_title]><span class="contentTitleTxt">[part.title]</span></h3> </noempty>
<noempty:part.image><img alt="[part.image_alt]" border="0px" class="contentImage" [part.style_image] src="[part.image]"></noempty>
<noempty:part.text><div class="contentText" [part.style_text]>[part.text]</div> </noempty>
<form action="" name="form" method="post">
    <div class="err">
        {$globalerr}<SE><span class="sysedit"> [param14]</span></SE> 
    </div> 
    <table border="0" class="tableTable" width="600">
        <tbody> 
            <tr> 
                <td width="30%" class="tabletitle"><div class="divtitle">[param16]</div>
                </td> 
                <td class="tablefield"> 
                    <input name="name" type="text" maxlength="20" {$glob_err_stryle} value="{$name}" class="inpfield {$errstname}">
                    <div class="err">{$nameerr}<SE><span class="sysedit"> [param6]</span></SE> </div> 
                </td> 
            </tr> 
            <tr> 
                <td class="tabletitle"><div class="divtitle">[param17]</div> 
                </td> 
                <td class="tablefield">
                    <input name="mail" type="text" maxlength="30" {$glob_err_stryle} value="[$email]" class="inpfield {$errstmail}">
                    <div class="err">[$emailerr]<SE><span class="sysedit"> [param8]</span></SE></div> 
                </td> 
            </tr> 
            <tr> 
                <td colspan="2" class="tablearrea">
                    <div class="texttitle">[param18]</div> 
                    <div class="blockarea"> <textarea cols="38" rows="6" class="inp {$errstnote} textarea" name="note" {$glob_err_stryle}>{$note}</textarea> </div> 
                    <div class="err">{$noteerr}<SE><span class="sysedit"> [param9]</span></SE> </div> 
                </td> 
            </tr> 
            
            <if:[param3]=='Yes'>
                <tr> 
                    <td colspan="2" class="tablrow">
                        {$anti_spam}
                    </td> 
                </tr> 
            </if>
            <tr><td colspan="2">
            [subpage name=license]
            </td></tr>
            <if:{$errorlicense}!=''>
                <div class="error<SE> sysedit</SE>">
                    <span>{$errorlicense}</span>
                </div>
            </if>
            
            <tr> 
                <td colspan="2" class="tablerow">
                <input class="buttonSend" id="Send" name="Send" {$glob_err_stryle} value="[param2]" [#"type="submit""][se."type="button" onClick="document.location.href='[@subpage1]';""] ></td> 
            </tr> 
        </tbody> 
    </table> 
</form> 
<SE><br clear="all"><div <if:[param1]!=''>class="sysedit"</if>
style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black; "><b> [lang001]<br> [lang002] [param1]</b> </div></SE>
</div> 
