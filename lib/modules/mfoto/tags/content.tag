<header:js>
    <script type="text/javascript" src="[this_url_modul]share42.js"></script>
</header:js>  
<style type="text/css"> 
    .cont_photo #share42 {
        display: inline-block;
        padding: 6px 0 0 6px;
        background: #FFF;
        border: 1px solid #E9E9E9;
        border-radius: 4px;
    }
    .cont_photo #share42:hover {
        background: #F6F6F6;
        border: 1px solid #D4D4D4;
        box-shadow: 0 0 5px #DDD;
    }
    .cont_photo #share42 a {opacity: 0.5;}
    .cont_photo #share42:hover a {opacity: 0.7}
    .cont_photo #share42 a:hover {opacity: 1}
</style>
<div class="content cont_photo"[contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img alt="[part.image_alt]" title="[part.image_title]" border="0" class="contentImage"[part.style_image] src="[part.image]">
    </noempty>
    <noempty:part.text>
        <div class="contentText"[part.style_text]>[part.text]</div>
    </noempty> 
    <div class="classNavigator">
        [SE_PARTSELECTOR]
    </div>
    <wrapper>[*addobj]
        <repeat:records>
            <div class="photo record-item" [objedit]>[*edobj]
                <div class="photoBriefImg" itemscope itemtype="http://schema.org/ImageObject">
                    <noempty:record.image>
                        <div class="objectImage record-pimage">
                            <a href="[record.link_detail]#show[part.id]_[record.id]">
                                <img alt="[record.image_alt]" title="[record.image_title]" src="[record.image_prev]" border="0" class="photoPrev" itemprop="contentUrl">
                            </a>
                        </div> 
                    </noempty>
                </div> 
                <a class="linkText record-link" href="[record.link_detail]#show[part.id]_[record.id]" itemprop="name">[record.title]</a>
            </div> 
        </repeat:records>
    </wrapper>

{SHOW}
        <div id="view">
            <div class="content cont_photo photoDetailed record-item" itemscope itemtype="http://schema.org/ImageObject" [objedit]>
                <noempty:record.title>
                    <[part.title_tag] class="objectTitle record-title">
                        <span class="objectTitleTxt" itemprop="name">[record.title]</span>
                    </[part.title_tag]>
                </noempty>
                <noempty:record.image>
                    <div class="objectImageBlock record-image" id="objimage">
                        <img class="objectImage" title="[record.image_title]" alt="[record.image_alt]" src="[record.image]" border="0">
                    </div>
                </noempty>
                <noempty:record.note>
                    <div class="objectNote record-note" itemprop="description">[record.note]</div>
                </noempty>
                <noempty:record.text>
                    <div class="objectText record-text" itemprop="description">[record.text]</div>
                </noempty>
                <if:[param3]=="Y">
                    <div style="display:none">
                        <input type="text" id="inptxt" name="inptxt" value="[record.text]">
                    </div>
                    <script>
                        var inptxt = document.getElementById('inptxt').value;
                    </script>
                    <SERV>
                        <header:js>[js:jquery/jquery.min.js]</header:js>
                        <script type="text/javascript" src="http://yandex.st/share/share.js" charset="utf-8"></script>
                        <script type="text/javascript">
                            new Ya.share({
                                'element': 'ya_share1',
                                'elementStyle': {
                                    'type': 'button',
                                    'linkIcon': true,   //
                                    'border': false,
                                    'quickServices': ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj']
                                },
                                'popupStyle': {
                                    'copyPasteField': true
                                },
                                'title': '[record.title]',
                                'description': inptxt,
                                onready: function(ins){
                                    $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf=[param4]\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
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
                <input class="buttonSend" onclick="document.location='[thispage.link]';" type="button" value="[param2]">
            </div> 
        </div>
{/SHOW}
</div> 
