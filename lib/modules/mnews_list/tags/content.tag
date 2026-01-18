<div class="content cont_news_lent" [contentstyle][contedit]>
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
    <wrapper>[*addobj]
    <div class="pnavigation up">[SE_PARTSELECTOR]</div> 
    <repeat:records>
        <div class="object record-item" [objedit]>
            <[record.title_tag] class="objectTitle">[*edobj]
                <noempty:record.field>
                    <span id="dataType_date">[record.field]</span> 
                </noempty>
                <span class="objectTitleTxt record-title">[record.title]</span> 
            </[record.title_tag]> 
            <noempty:record.image>
                <a href="[record.link_detail]">
                    <img border="0" class="objectImage record-pimage" src="[record.image_prev]" border="0" alt="[record.image_alt]" title="[record.image_title]">
                </a> 
            </noempty>
            <noempty:record.note>
                <div class="objectNote record-note">[record.note]</div> 
            </noempty>
            <noempty:record.text>
                <a id="newslink" href="[record.link_detail]#show[part.id]_[record.id]">[lang001]</a> 
            </noempty>
        </div> 
    </repeat:records>
    <div class="pnavigation down">[SE_PARTSELECTOR]</div> 
    </wrapper>

{SHOW}
        <div class="content record-item" id="view" [contentstyle]>
            <noempty:record.title>
                <[part.title_tag] class="contentTitle">
                    <span class="contentTitleTxt record-title">[record.title]</span>
                </[part.title_tag]> 
            </noempty>
            <noempty:record.image>
                <div class="objimage record-image" id="objimage">
                    <img class="objectImage" alt="[record.image_alt]" title="[record.image_title]" src="[record.image]" border="0">
                </div> 
            </noempty>
            <noempty:record.note>
                <div class="objectNote record-note">[record.note]</div> 
            </noempty>      
            <div class="objectText record-text">[record.text]</div> 
            <input class="buttonSend" onclick="document.location='[thispage.link]';" type="button" value="[lang002]">
        </div> 
    
{/SHOW}
</div> 
