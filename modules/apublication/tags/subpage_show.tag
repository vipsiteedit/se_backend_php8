<footer:js>
    [js:jquery/jquery.min.js]
    <script type="text/javascript" src="[module_url]swiper.jquery.min.js"></script>
    [include_js({id: [part.id],
            autoplay: <if:[param45]=="true">[param44]<else>0</if>,
            autoplayStopOnLast: [param47],
            autoplayDisableOnInteraction: [param46],
            speed: [param48],
            fp_spaceBetween: [param49],
            tp_slidesPerView: [param50],
            tp_spaceBetween: [param55],
            fp_effect: '[param51]'})]
</footer:js>
<if:[param2]!='d'><div class="<if:[param2]=='n'>container<else>container-fluid</if>"></if>
<section class="content text-public view" id="view">
    <if:({$imagelistcount})>
   <div class="photo_line-main">
    <div class="swiper-container gallery-top photo_line-full_image_gallery">
        <div class="swiper-wrapper photo_line-full_image_wrapper">
            <repeat:imagelist name=img>
            <div class="swiper-slide photo_line-full_image_slide" style="background-image:url([img.image])">
                <if:(([img.title]!='') || ([img.note]!=''))><div class="photo_line-full_image_desc">
                <noempty:img.title><[param56] class="photo_line-full_image_title">[img.title]</[param56]></noempty>
                <noempty:img.note><div class="photo_line-full_image_text">[img.note]</div></noempty>
                </div></if>
            </div>
            </repeat:imagelist>
        </div>
        <if:[param52]=='true'>
        <div class="photo_line-full_image_btn_area">
        <div class="photo_line-full_image_btn_prev swiper-button-prev swiper-button"></div>
        <div class="photo_line-full_image_btn_next swiper-button-next swiper-button"></div>
        </div>
        </if>
        
    </div>
    <if:({$imagelistcount}>1)>
    <div class="swiper-container gallery-thumbs photo_line-thumbs_gallery">
        <div class="swiper-wrapper photo_line-thumbs_gallery_wrapper">
            <repeat:imagelist name=img>
            <div class="swiper-slide photo_line-thumbs_gallery_slide" style="background-image:none">
                <noempty:img.image_prev><img class="photo_line-thumbs_image" src="[img.image_prev]" border="0" alt="[img.image_alt]"></noempty>
            </div>
            </repeat:imagelist>
        </div>
   
        <if:[param53]=='true'>
        <div class="photo_line-thumbs_gallery_btn_area">
        <div class="photo_line-thumbs_gallery_btn_prev swiper-button-prev"></div>
        <div class="photo_line-thumbs_gallery_btn_next swiper-button-next"></div>
        </div>
        </if>
        
        <if:[param54]=='true'>
        <div class="photo_line-thumbs_gallery_scrollbar swiper-scrollbar"></div>
        </if>
    </div></if>
  </div>
  </if>
   <div class="contentBody">
        <a class="backLink" href="[thispage.link]">[lang012]</a> 
        <if:[param56]!='N'>
        <[param56] class="objectTitle">
            <span class="objectTitleTxt">{$news_title}</span>
        </[param56]>
        </if>
        
        <!--div id="objimage"> 
            {$news_img}
        </div-->
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
</section>
<if:[param2]!='d'></div></if>
