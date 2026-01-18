<footer:js>
[js:jquery/jquery.min.js]
[lnk:fancybox3/jquery.fancybox.min.css]    
[js:fancybox3/jquery.fancybox.min.js] 
[include_js({})]
</footer:js>
<div class="content photoAlbumAdapt <if:[param12]=='n'>container</if>" [contedit]>
    <a name="sm[part.id]"></a>
    [subpage name=head]
    <wrapper>[*addobj]
        <repeat:records>
            <div class="obj record-item" [objedit]>
                [*edobj]
                <div class="photoPreview" itemscope itemtype="http://schema.org/ImageObject">
                    <noempty:record.image>
                        <a data-fancybox="image[part.id]" class="photoLink slide-show" href="[record.link_detail]#show[part.id]_[record.id]" rel="image[part.id]<if:[param11]=='N'> nofollow</if>">
                            <img alt="[record.image_alt]" title="[record.image_title]" src="[record.image_prev]" border="0" class="previewImg" itemprop="contentUrl" />
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
    <div class="content photoAlbumAdapt [razdel]" [contentstyle][contedit]>
        <div class="photoDetailed" id="view" itemscope itemtype="http://schema.org/ImageObject">
            <noempty:record.title>
                <[part.title_tag] class="objectTitle">
                    <span class="objectTitleTxt" itemprop="name">[record.title]</span>
                </[part.title_tag]>
            </noempty>
            <noempty:record.image>
                <img class="objectImage" title="[record.image_title]" alt="[record.image_alt]" src="[record.image]" border="0" itemprop="contentUrl">
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
