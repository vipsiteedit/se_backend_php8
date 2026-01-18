[subpage name=scripts]
<div class="content photoAlbumSplash" [contedit]>
    <a name="sm[part.id]"></a>
    [subpage name=head]
    <wrapper>[*addobj]
        <repeat:records>
            <div class="obj record-item" [objedit]>
                [*edobj]
                <div class="photoPreview" itemscope itemtype="http://schema.org/ImageObject">
                    <noempty:record.image>
                        <a class="photoLink slide-show" href="[record.link_detail]#show[part.id]_[record.id]" <if:[param11]=='N'>rel="nofollow"</if>>
                            <img alt="[record.image_alt]" title="[record.image_alt]" src="[record.image_prev]" border="0" class="previewImg" itemprop="contentUrl" />
                        </a>
                    </noempty>
                    <noempty:record.title>
                        <a class="textLink <if:[param11]=='N'>slide-show</if>" href="[record.link_detail]#show[part.id]_[record.id]" itemprop="name" <if:[param11]=='N'>rel="nofollow"</if>>[record.title]</a>
                    </noempty>
                    <span style="display: none" itemprop="description">[record.note]</span>
                </div>
            </div>
        </repeat:records>
    </wrapper>

{SHOW}
    <div class="content photoAlbumSplash [razdel]" [contentstyle][contedit]>
        <div class="photoDetailed" id="view" itemscope itemtype="http://schema.org/ImageObject">
            <noempty:record.title>
                <h4 class="objectTitle">
                    <span class="objectTitleTxt" itemprop="name">[record.title]</span>
                </h4>
            </noempty>
            <noempty:record.image>
                <img class="objectImage" title="[record.image_alt]" alt="[record.image_alt]" src="[record.image]" border="0" itemprop="contentUrl">
            </noempty>
            <noempty:record.note>
                <div class="objectNote">[record.note]</div>
            </noempty>
            <noempty:record.text>
                <div class="objectText" itemprop="description">[record.text]</div>
            </noempty> 
            <a class="buttonSend" href="[thispage.link]">[param2]</a>     
        </div>
    </div>
{/SHOW}
[SE_PARTSELECTOR]
<SERV>[addphotos]</SERV>
</div> 
<se>
<div class="sysedit" style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black; "><ml>На картинке показывается область загрузки. Она видна постоянно только в программе для удобства настройки по дизайну.</ml></div>
</se>
