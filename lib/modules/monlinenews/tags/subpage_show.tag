<if:([param38]!='d' && [param38]!='')>
<div class="<if:[param38]=='n'>container<else>container-fluid</if>"></if>
<section class="content contOnNews view" id="view"> 
    <div class="contentBody">
        <a class="backLink" href="[thispage.link]">[lang012]</a> 
        <h1 class="objectTitle">
            <span class="objectTitleTxt">{$news_title}</span>
        </h1>
        <div id="objimage"> 
            {$news_img}
        </div>
        <div class="objectText">
            {$news_text}
        </div>
    </div>
    <if:[param31]=='Y'>
        <SERV>
            <header:js>[js:jquery/jquery.min.js]</header:js>
            <script type="text/javascript" src="http://yandex.st/share/share.js" charset="utf-8"></script>
            <script type="text/javascript">
                new Ya.share({
                    element: 'ya_share1',
                    elementStyle: {type: 'button', linkIcon: true, border: false, quickServices: ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj'] },
                    popupStyle: {'copyPasteField': true},
                    onready: function(ins){
                        $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf=[param34]\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
                    }
                });
            </script>
        </SERV>
        <div id="ya_share1" style="margin: 10px 0;">
            <SE>
                <img src="[module_url]kont.png">
            </SE>
        </div>
    </if>
    <input class="buttonSend" onclick="window.history.back(-1);" type="button" value="[lang013]">
</section><if:([param38]!='d' && [param38]!='')>
</div></if>
