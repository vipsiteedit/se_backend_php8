<div class="content shopgroups" data-type="[part.type]" data-id="[part.id]"[contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img class="contentImage" alt="[part.image_alt]" src="[part.image]" border="0" [part.style_image]>
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <div class="contentBody">
        <if:[param33]!='N'>
            [subpage name=path]
        </if>
        <empty:{$hidden}>
            <div class="groupContent">
                <if:({$shopcatgr}=={$basegroup})>
                    [subpage name=groups]
                <else>
                    [subpage name=general]
                </if>
                <if:[param31]!='N'>
                    [subpage name=brands]
                </if>
            </div>
        </empty>
    </div>
</div>
