<if:[param8]=='Y'>
    <header:css>
     [lnk:fancybox2/jquery.fancybox.css]
    </header:css>
    <header:js>
     [js:jquery/jquery.min.js]
     [js:fancybox2/jquery.fancybox.pack.js]
        <script type="text/javascript">
            $(document).ready(function() {
                $("a.gallery[part.id]").fancybox();
            });
        </script>
    </header:js>
</if>
<div class="content cont_txt"[contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span> 
        </[part.title_tag]> 
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage"[part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
    </noempty>
    <noempty:part.text>
        <div class="contentText"[part.style_text]>[part.text]</div> 
    </noempty>
<wrapper>[*addobj]
    <div class="classNavigator">
        [SE_PARTSELECTOR]
    </div>
<repeat:records>
    <div class="object record-item" [objedit]>[*edobj]
        <noempty:record.title>
            <[record.title_tag] class="objectTitle">
                <span class="objectTitleTxt record-title">[record.title]</span> 
            </[record.title_tag]> 
        </noempty>
        <noempty:record.image>
            <if:[param8]=='Y'><a class="gallery[part.id]" rel="group" title="" href="[record.image]"></if>
                <div class="objectImage record-pimage">
                    <img class="objectImg" border="0" src="[record.image_prev]" border="0" alt="[record.image_alt]" title="[record.image_title]">
                </div>
            <if:[param8]=='Y'></a></if>
        </noempty>
        <noempty:record.note>
            <div class="objectNote record-note">[record.note]</div> 
        </noempty>
        <noempty:record.text>
            <a class="linkNext" href="[record.link_detail]#show[part.id]_[record.id]">[param1]</a> 
        </noempty>
    </div> 
</repeat:records>   
    <div class="classNavigator">
        [SE_PARTSELECTOR]
    </div>
</wrapper>

{SHOW}
    <div class="content cont_txt" id="view">
    <if:[param4]=='Y'>
        <a name="show[part.id]_[record.id]"></a>
    </if>
    <div class="record-item" [objedit]>
        <noempty:record.title>
            <[part.title_tag] class="objectTitle record-title">
                <span class="contentTitleTxt">[record.title]</span> 
            </[part.title_tag]> 
        </noempty>
        <noempty:record.image>
            <if:[param8]=='Y'><a class="gallery" rel="group" title="" href="[record.image_prev]"></if>
                <div class="objimage record-image">
                    <img class="objectImage" alt="[record.image_alt]" src="[record.image]" border="0">
                </div> 
            <if:[param8]=='Y'></a></if>
        </noempty>
    <if:[param3]=='Y'>
        <noempty:record.note>
            <div class="objectNote record-note">[record.note]</div> 
        </noempty>
    </if>
    <noempty:record.text>
        <div class="objectText record-text">[record.text]</div>
    </noempty> 
    <if:[param5]=="Y">
        <SERV> 
            <header:js>[js:jquery/jquery.min.js]</header:js>
            <script type="text/javascript" src="<se>http:</se>//yandex.st/share/share.js" charset="utf-8"></script>
            <script type="text/javascript">
                var inptxt = $(".cont_txt .contentTitleTxt").html(); 
                new Ya.share({
                    'element': 'ya_share1',
                    'elementStyle': {
                        'type': 'button',
                        'linkIcon': true,   
                        'border': false,
                        'quickServices': ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj']
                    },
                    'popupStyle': {
                        'copyPasteField': true
                    },
                        'description': inptxt,
                        onready: function(ins){
                            $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf=[param6]\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
                    }
                });
            </script>
        </SERV>
        <div id="ya_share1" style="margin: 10px 0;">
            <SE>
                <img src="[this_url_modul]kont.png">
            </SE>
        </div>
    </if>
    <input class="buttonSend" onclick="document.location.href='[thispage.link]';" type="button" value="[param2]">
    </div>
    </div> 
{/SHOW}
</div>       
