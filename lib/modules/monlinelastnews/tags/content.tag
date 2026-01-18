 <div class="content contLastNews" [contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <repeat:objects>
        <div class="object">
            <h4 class="objectTitle">
                <span class="dataType_date">[record.date]</span>
                <a class="textTitle" href="<SERV>[record.shownews]</SERV><SE>[param2].html</SE>">[record.title]</a>
            </h4>
            <noempty:record.image_prev>
                <a href="<SERV>[record.shownews]</SERV><SE>[param2].html</SE>">
                    <img border="0" src="[record.image_prev]" alt="[record.title]" class="objectImage">
                </a>
            </noempty>        
            <div class="objectNote">[record.text]</div> 
            <if:[param9]=='Y'>
                <span class="newslinks">[record.link]</span>
            </if>
        </div>
    </repeat:objects>
    <a class="linkNews" href="<SERV>{$site}</SERV>[param2].html">[param8]</a>
    <SE>
        <p style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black; " class="sysedit">Не забудьте указать в "Дополнительных настройках" ссылку на страницу новостей. Указанная ссылка: <a href="[param2].html">[param2].html</a> </p>
    </SE>
</div>
