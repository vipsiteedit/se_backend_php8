<header:css>
[include_css]
</header:css>
<if:[param1]!='d'>
<div class="<if:[param1]=='n'>container<else>container-fluid</if>">
</if>
<div class="footer-container row"[contedit]>
    <div class="b_social_media col-sm-3 col-xs-6 clearfix">
    <repeat:records>
        <a class="b_social_media-link" href="[record.field]" data-sm-id="[record.title]" style="background-image: url('[record.image]');"></a>
    </repeat:records>
    </div>
    <div class="b_footer_info col-sm-6 col-xs-12 clearfix">
        <div class="b_copyright" data-seedit="copyright"><span style="font-weight:400;">©&nbsp;</span>[site.copyright]</div>
        <div class="b_footer_text">[part.title]</div>
    </div>
    <div class="b_payment_system col-sm-3 col-xs-6 clearfix <if:[param2]=='y'>show-statistic</if>">
        <if:[param2]=='y'>
           <div class="footer-statistic" data-seedit="statistic">
               [site.statistic]
           </div>
        </if>
        <div class="footer-technology">
            <span>
                Работает на технологии SiteEdit<br>
                Разработано в <a target="_blank" href="http://art.siteedit.ru">art.siteedit.ru</a>
            </span>
        </div>
    </div> 
</div>
<if:[param1]!='d'>
</div>
</if>
