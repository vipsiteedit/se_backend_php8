<div class="content cont_photo_rating" [contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img alt="[part.image_alt]" title="[part.image_title]" border="0" class="contentImage" [part.style_image] src="[part.image]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <SE>
        <div id="divclear">
            <form style="margin:0px;" method="post">
                <input class="buttonSend buttonClear" type="button" value="[lang003]" name="clear">
            </form> 
        </div>            
    </SE> 
    <if:{$GR_AUTHOR}==3>
        <div id="divclear">
            <form style="margin:0px;" method="post">
                <input class="buttonSend buttonClear" type="submit" value="[lang003]" name="clear">
            </form> 
        </div>
    </if>
    [*addobj]
    <div class="classNavigator">
    [SE_PARTSELECTOR]
    </div>
    <wrapper>
    <repeat:records>
        <div class="photo photoBrief record-item"[objedit]>
            <div id="photoBriefImg">
                <noempty:record.image>
                    <a href="[record.link_detail]#[record.link]" style="text-decoration:none;">
                        <img alt="[record.image_alt]" title="[record.image_title]" src="[record.image_prev]" border="0" id="photoPrev" class="objectImage">
                    </a>
                </noempty>
                [*edobj]
                <a class="linkstitle" href="[record.link_detail]">[record.title]</a>
            </div> 
            <div id="objFooter">
                    <form style="margin:0px;" action="[thispage.link]#[record.link]" method="post">
                        <div id="ratingTitle">[lang002]:</div>
                        <div id="obj_rating"><SE>0</SE>[record.rating]</div> 
                        <input type="hidden" name="ratingraz" value="[part.id]"> 
                        <input type="hidden" name="ratingobj" value="[record.id]"> 
                        <input type="<serv>submit</serv><se>button</se>" class="buttonSend" name="goRating" value="[lang001]">
                    </form>
            </div> 
        </div> 
    </repeat:records>
    </wrapper>

{SHOW}
        <div class="content cont_photo_rating photoDetailed record-item"[objedit] id="view">
            <noempty:record.title>
                <[part.title_tag] class="objectTitle">
                    <span class="objectTitleTxt record-title">[record.title]</span>
                </[part.title_tag]>
            </noempty>
            <noempty:record.image>
                <div class="objimage record-image" id="objimage">
                    <img class="fullImage objectImage" alt="[record.image_alt]" title="[record.image_title]" src="[record.image]" border="0">
                </div>
            </noempty>
            <noempty:record.note>
                <div class="objectNote record-note">[record.note]</div>
            </noempty>
            <noempty:record.text>
                <div class="objectText record-text">[record.text]</div>
            </noempty>
<if:[param9]=='Y'>
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
                    element: 'ya_share1',
                    elementStyle: {type: 'button', linkIcon: true, border: false, quickServices: ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj'] },
                    popupStyle: {'copyPasteField': true},
                    'title': '[record.title]',
                    'description': inptxt,
                    onready: function(ins){
                        $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf=[param10]\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
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
            <input class="buttonSend" onclick="document.location = '[thispage.link]'" type="button" value="[lang004]">
        </div> 
                          
{/SHOW}
</div> 
