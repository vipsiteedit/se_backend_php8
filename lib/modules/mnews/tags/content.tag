<div class="content cont_news <if:[param11]=='a'>container<else>container-fluid</if>" [contentstyle][contedit]>
<if:[param7]=='N'><noindex></if>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img alt="[part.image_alt]" title="[part.image_title]" border="0" class="contentImage" [part.style_image] src="[part.image]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <div class="contentBody"> 
        <div class="records-container">
            [*addobj]
            <repeat:records>
                <div class="object record-item" [objedit]>
                    <[record.title_tag] class="objectTitle">
                        [*edobj]
                        <noempty:record.field>
                            <span id="dataType_date">[record.field]&nbsp;</span>
                        </noempty>
                        <noempty:record.title>
                            <if:[param9]=='N'><noindex></if>
                                <a class="objectTitleTxt" href="[record.link_detail]<if:[param10]=='Y'>#show[part.id]_[record.id]</if>" <if:[param9]=='N'>rel="nofollow"</if> >[record.title]</a>
                            <if:[param9]=='N'></noindex></if>
                        </noempty>
                    </[record.title_tag]> 
                    <noempty:record.image>
                        <if:[param9]=='N'><noindex></if>
                            <a href="[record.link_detail]" class="record-image-link" <if:[param9]=='N'>rel="nofollow"</if> >
                                <img class="objectImage record-pimage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]" border="0">
                            </a>
                        <if:[param9]=='N'></noindex></if>
                    </noempty>
                    <noempty:record.note>
                        <div class="objectNote record-note">[record.note]</div>
                    </noempty>
                    <if:[param6]=='Y'>
                        <noempty:record.text>
                            <if:[param9]=='N'><noindex></if>
                                <a class="newslink" href="[record.link_detail]<if:[param10]=='Y'>#show[part.id]_[record.id]</if>" <if:[param9]=='N'>rel="nofollow"</if> >[param2]</a>
                            <if:[param9]=='N'></noindex></if>
                        </noempty>
                    </if>
                </div> 
            </repeat:records>
        </div>
        

{SHOW}
        <if:[param8]=='N'><noindex></if>
            <div class="content cont_news cont_news_view record-item <if:[param11]=='a'>container<else>container-fluid</if>" [objedit] [part.style]>
                <if:[param10]=='Y'>
                    <a name="show[part.id]_[record.id]"></a>
                </if>
                <div id="view">
                    <noempty:record.title>
                        <[record.title_tag] class="objectTitle">
                            <span class="objectTitleTxt record-title">[record.title]</span>
                        </[record.title_tag]>
                    </noempty>
                    <noempty:record.image>
                        <div class="objimage record-image">
                            <img class="objectImage" src="[record.image]" alt="[record.image_alt]" title="[record.image_title]" border="0">
                        </div>
                    </noempty>
                    <if:[param5]=='Y'>
                        <noempty:record.note>
                            <div class="objectNote record-note">[record.note]</div>
                        </noempty>
                    </if>
                    <div class="objectText record-text">[record.text]</div> 
                    <input class="buttonSend" onclick="document.location = '[thispage.link]';" type="button" value="[param4]">
                </div> 
            </div> 
        <if:[param8]=='N'></noindex></if>
        
{/SHOW}

{ARHIV}
        <div class="content cont_news_arhiv <if:[param11]=='a'>container<else>container-fluid</if>" id="arh_news">
            <[part.title_tag] class="contentTitle" [part.style_title]>
                <span class="contentTitleTxt">[part.title]</span>
            </[part.title_tag]> 
            [SE_PARTSELECTOR]
            <ul> 
                <repeat:records>
                    <li class="arhivTitle">
                        <a id="links" href="[record.link_detail]">[record.field] [record.title]</a>
                    </li>
                </repeat:records>
            </ul> 
            <input class="buttonSend" onclick="document.location = '[thispage.link]';" type="button" value="[param4]">
        </div>
        
{/ARHIV}
        <arhiv:link>
            <a id="linkArchive" href="[arhiv.link]">[param3]</a>
        </arhiv:link>
    </div>
    <if:[param7]=='N'></noindex></if>
</div>
