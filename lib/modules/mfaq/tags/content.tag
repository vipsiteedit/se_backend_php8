<div class="content cont_faq"[part.style][contedit]>
    <a name="r[part.id]"></a> 
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage"[part.style_image] src="[part.image]" alt="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText"[part.style_text]>[part.text]</div>
    </noempty>
    <div id="linkBox">
        <repeat:records>
            <a id="linkTitle" href="#r[part.id]_[record.id]">[record.title]</a>
        </repeat:records>
    </div> 
    <wrapper>[*addobj]
    <repeat:records>
        <div class="object record-item"[objedit]>
            <[record.title_tag] class="objectTitle">
                <a name="r[part.id]_[record.id]"></a> 
                <span class="objectTitleTxt record-title">[*edobj][record.title]</span>
            </[record.title_tag]>
            <noempty:record.image>
            <div class="objectImage record-pimage">
                <a <SERV>target="_blank"</SERV> href="[record.image]">
                    <img border="0" class="objectPImage" src="[record.image_prev]" alt="[record.image_alt]">
                </a>
            </div>
            </noempty>
            <noempty:record.note>
                <div class="objectNote record-note">[record.note]</div>
            </noempty>
            <noempty:record.text>
                <div class="objectText record-rext">[record.text]</div>
            </noempty>
            <a class="go_up" title="[lang001]" href="#r[part.id]">[lang001]</a>
        </div> 
    </repeat:records>
    </wrapper>
</div> 
