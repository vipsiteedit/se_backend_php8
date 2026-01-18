<header:css>
[include_css]
</header:css>
<if:[param1]!='d'>
<div class="<if:[param1]=='n'>container<else>container-fluid</if>"></if>
<section class="content b_trust_us" [contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle">
            <span class="contentTitleTxt">[part.title]</span> 
        </[part.title_tag]> 
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage"[part.style_image] src="[part.image]" alt="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText">[part.text]</div> 
    </noempty>
<div class="b_trust_us-objects_block">
<repeat:records>
    <div class="object b_trust_us-object" [objedit]>[*edobj]
        <noempty:record.image>
            <div class="b_trust_us-object_image_block">
                <img class="object b_trust_us-object_image" border="0" src="[record.image_prev]" border="0" alt="[record.image_alt]">
            </div>
        </noempty>
        <div class="b_trust_us-object_content">
        <noempty:record.title>
            <[record.title_tag] class="object b_trust_us-object_title">
                <span class="object b_trust_us-object_title_text">[record.title]</span>
            </[record.title_tag]> 
        </noempty>
        <noempty:record.note>
            <div class="object b_trust_us-object_text">[record.note]</div>
        </noempty>
        </div>
    </div>
</repeat:records>
</div>
</section><if:[param1]!='d'>
</div></if>
