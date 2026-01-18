<div class="<if:[param36]=='n'>container<else>container-fluid</if>">
<div class="content shopgroups" [contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle">
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img class="contentImage" alt="[part.image_alt]" src="[part.image]" border="0">
    </noempty>
    <noempty:part.text>
        <div class="contentText">[part.text]</div>
    </noempty>
    <div class="contentBody">
        <if:[param33]!='N'>
            [subpage name=path]
        </if>
        <serv>
        <empty:{$hidden}>
            <div class="groupContent row">
                <if:({$shopcatgr}=={$basegroup})>
                    [subpage name=groups]
                <else>
                    [subpage name=general]
                </if>
            </div>
        </empty>
        </serv>
        <se>
            <div class="groupContent row">                  
                [subpage name=segeneral]
            </div>
        </se>
    </div>
</div>
</div>
