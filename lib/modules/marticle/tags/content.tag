<div class="content contArt" [part.style][contedit]>
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
    <wrapper>[*addobj]
    [SE_PARTSELECTOR]
    <repeat:records>
        [*edobj]
        <div class="object">
            <noempty:record.title>
                <[record.title_tag] class="objectTitle record-title">[record.title]</[record.title_tag]>
            </noempty>
            <noempty:record.image>
            <div class="objectImage record-pimage">
                <img border="0" class="objectPImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
            </div>
            </noempty>
            <noempty:record.note>
                <div class="objectNote record-note">[record.note]</div>
            </noempty>
            <div class="links">
                <a class="artMore" href="[record.link_detail]">[lang019]</a>
                <div class="comments">
                    <a class="artLnkdscs" href="[record.link_detail]#comments">[lang020] <span class="mycomments">(<span class="digit"><SE>81</SE>[record.comments]</span>)</span></a>
                </div>
            </div>
        </div>
    </repeat:records>
    </wrapper>

{SHOW}
    <div class="content contArt">
        <div id="view">
            <noempty:record.title>
                <[part.title_tag] class="objectTitle record-title">[record.title]</[part.title_tag]>
            </noempty>
            <noempty:record.image>
                <div id="objimage record-image">
                    <img border="0" class="objectImage" src="[record.image]" alt="[record.image_alt]" title="[record.image_title]">
                </div>
            </noempty>
            <noempty:record.note>
                <div class="objectNote record-note">[record.note]</div>
            </noempty>
            <div class="objectText record-text">[record.text]</div>
            <div class="buttonArea">
                <input class="buttonSend" onclick='location.href="[thispage.link]"' type="button" value="[lang027]">
            </div>
           [subpage name=sub2]
        </div>
    </div>
{/SHOW}
    <SE>
        <div style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black; " class="sysedit">
            [lang012]
        </div>
    </SE>
</div>
