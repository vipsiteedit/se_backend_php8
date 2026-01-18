<div class="content" id="rss" [contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span> 
        </[part.title_tag]> 
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <repeat:objects>
        <div class="object">
            <span class="objectTitle">
                <a href="[record.link]">
                    [record.title] <!-- span class="objectTitleTxt">[record.title]</span -->
                </a>
            </span> 
            <span class="dataType_date">[record.pubdate]</span> 
            <noempty:record.image>
                <img border="0" class="objectImage" src="[record.image]" width="100" height="100" alt="[record.image_alt]">
            </noempty>
            <span class="objectNote">[record.note]</span>
        </div> 
    </repeat:objects> 
</div> 
