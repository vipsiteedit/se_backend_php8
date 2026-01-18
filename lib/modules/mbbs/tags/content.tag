<div class="content contDesk" [contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]><span class="contentTitleTxt">[part.title]</span></[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <if:{$access_add}==true>
        <a class="newMsg" title="[lang011]" href="[link.subpage=1]">[lang011]</a> 
    </if>
    <div class="Cnri">
        <SERV>
            <form style="margin:0px;" action="" method="post">
        </SERV>
        <div class="title">[lang013]</div> 
        <div class="CnriBox">
            <select class="CnriSel" name="townselected">
                <option value=""> ---- </option>
                <repeat:selecttown name=town>
                    <option value="[town.town]" [town.selected]>[town.town]</option>
                </repeat:selecttown>
            </select>
        </div> 
        <div class="buttonArea">
            <input class="buttonSend" type="submit" value="[lang014]">
        </div>
        <SERV>
            </form>
        </SERV>
    </div>
    {$MANYPAGE}
    <repeat:messags name=record>
        <div class="object">
            <[record.title_tag] class="objectTitle">
                <span class="objectTitleTxt">[record.title]</span>
            </[record.title_tag]>
            <noempty:record.image>
                <a href="[link.subpage=3][se."?"]id/[record.id]/"><img border="0" class="objectImage" src="[record.image]" alt="[record.alt]" width="[param4]"></a>
            </noempty>
            <div class="objectNote">
                <a class="shortText" href="[link.subpage=3][se."?"]id/[record.id]/">[record.note]</a>
            </div>
            <div class="contact">
                <div class="obj date">
                    <div class="name">[lang016]</div>
                    <b class="text">[record.date]</b>
                </div>
                <div class="obj authorName">
                    <div class="name">[lang017]</div>
                    <b class="text">[record.name]</b>
                </div>
                <div class="obj town">
                    <div class="name">[lang018]</div>
                    <b class="text">[record.town]</b>
                </div>
                <div class="obj phone">
                    <div class="name">[lang039]</div>
                    <b class="text">[record.phone]</b>
                </div>
                <div class="obj email">
                    <div class="name">[lang019]</div>
                    <a class="text" href="mailto:[record.email]">[record.email]</a>
                </div>
                <div class="obj url">
                    <div class="name">[lang020]</div>
                    <a class="text" href="http://[record.url]">[record.url]</a>
                </div>
            </div>
            <if:[record.access]==true>
                <div class="footEditBlock">
                    <a class="buttonSend editbbs" style="text-decoration:none;" title="[lang012]" href="[link.subpage=2][se."?"]id/[record.id]/">[lang012]</a>
                </div> 
            </if>  
        </div>
    </repeat:messags>
    {$MANYPAGE}
    <SE>
        <br class="sysedit">
        <div style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black; " class="sysedit">
            <span>[lang010]</span>
        </div>
    </SE>
</div> 
