<div class="content" id="menuLink" [contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage"[part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText"[part.style_text]>[part.text]</div>
    </noempty>
[*addobj]
    [SE_PARTSELECTOR]
    <repeat:records>
        <div class="object" [objedit]>[*edobj]
            <noempty:record.image>
                <a href=<if:record.field!=''>"[record.field]"<else>"#"</if>>
                    <img border="0" class="objectImage" src="[record.image_prev]" border="0" alt="[record.image_alt]">
                </a>
            </noempty>
            <a href=<if:record.field!=''>"[record.field]"<else>"#"</if> class=<if:record.field==[thispage.name].html>"link linkActive"<else>"link"</if>>
                [record.title]
            </a>
        </div> 
    </repeat:records>
</div> 
