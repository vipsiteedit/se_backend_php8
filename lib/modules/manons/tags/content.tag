<div class="content art_bank <if:[param2]=='a'>container<else>container-fluid</if>"[contentstyle] [contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage"[part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
    </noempty>
    <noempty:part.text>
        <div class="contentText"[part.style_text]>[part.text]</div>
    </noempty>
    <div class="contentBody"> 
        [*addobj]
        [SE_PARTSELECTOR]
        <div class="records-container">
            <repeat:records>
                <div class="object record-item" [objedit]>[*edobj]
                    <a href=<if:record.field!=''>"[record.field]"<else>"#"</if> <if:[param3]=='N'>rel="nofollow"</if> <if:record.text1!=''>target="[record.text1]"</if> class=<if:record.field==[thispage.name]>"link linkActive record-field"<else>"link record-field"</if>>
                        <[record.title_tag]>[record.title]</[record.title_tag]>
                    </a>
                   <noempty:record.image>
                        <a class="record-image-link" href=<if:record.field!=''>"[record.field]"<else>"#"</if> <if:[param3]=='N'>rel="nofollow"</if> <if:record.text1!=''>target="[record.text1]"</if>>
                            <img class="objectImage record-pimage" src="[record.image_prev]" border="0" alt="[record.image_alt]" title="[record.image_title]">
                        </a>
                    </noempty>
                    <noempty:record.note>
                        <div class="objectNote record-note">
                            [record.note]
                        </div>
                    </noempty>        
                </div> 
            </repeat:records>
        </div>
    </div>
</div> 
