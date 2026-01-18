<footer:js>
    [js:jquery/jquery.min.js]
    <script type="text/javascript" src="[module_url]swiper.jquery.min.js"></script>
    [include_js({id: <?php echo $section->id ?>,
            autoplay: <?php if(strval($section->parametrs->param45)=="true"): ?><?php echo $section->parametrs->param44 ?><?php else: ?>0<?php endif; ?>,
            autoplayStopOnLast: <?php echo $section->parametrs->param47 ?>,
            autoplayDisableOnInteraction: <?php echo $section->parametrs->param46 ?>,
            speed: <?php echo $section->parametrs->param48 ?>,
            fp_spaceBetween: <?php echo $section->parametrs->param49 ?>,
            tp_slidesPerView: <?php echo $section->parametrs->param50 ?>,
            tp_spaceBetween: <?php echo $section->parametrs->param55 ?>,
            fp_effect: '<?php echo $section->parametrs->param51 ?>'})]
</footer:js>
<?php if(strval($section->parametrs->param2)!='d'): ?><div class="<?php if(strval($section->parametrs->param2)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
<section class="content text-public view" id="view">
    <?php if($imagelistcount): ?>
   <div class="photo_line-main">
    <div class="swiper-container gallery-top photo_line-full_image_gallery">
        <div class="swiper-wrapper photo_line-full_image_wrapper">
            <?php foreach($section->imagelist as $img): ?>
            <div class="swiper-slide photo_line-full_image_slide" style="background-image:url(<?php echo $img->image ?>)">
                <?php if(($img->title!='') || ($img->note!='')): ?><div class="photo_line-full_image_desc">
                <?php if(!empty($img->title)): ?><<?php echo $section->parametrs->param56 ?> class="photo_line-full_image_title"><?php echo $img->title ?></<?php echo $section->parametrs->param56 ?>><?php endif; ?>
                <?php if(!empty($img->note)): ?><div class="photo_line-full_image_text"><?php echo $img->note ?></div><?php endif; ?>
                </div><?php endif; ?>
            </div>
            
<?php endforeach; ?>
        </div>
        <?php if(strval($section->parametrs->param52)=='true'): ?>
        <div class="photo_line-full_image_btn_area">
        <div class="photo_line-full_image_btn_prev swiper-button-prev swiper-button"></div>
        <div class="photo_line-full_image_btn_next swiper-button-next swiper-button"></div>
        </div>
        <?php endif; ?>
        
    </div>
    <?php if($imagelistcount>1): ?>
    <div class="swiper-container gallery-thumbs photo_line-thumbs_gallery">
        <div class="swiper-wrapper photo_line-thumbs_gallery_wrapper">
            <?php foreach($section->imagelist as $img): ?>
            <div class="swiper-slide photo_line-thumbs_gallery_slide" style="background-image:none">
                <?php if(!empty($img->image_prev)): ?><img class="photo_line-thumbs_image" src="<?php echo $img->image_prev ?>" border="0" alt="<?php echo $img->image_alt ?>"><?php endif; ?>
            </div>
            
<?php endforeach; ?>
        </div>
   
        <?php if(strval($section->parametrs->param53)=='true'): ?>
        <div class="photo_line-thumbs_gallery_btn_area">
        <div class="photo_line-thumbs_gallery_btn_prev swiper-button-prev"></div>
        <div class="photo_line-thumbs_gallery_btn_next swiper-button-next"></div>
        </div>
        <?php endif; ?>
        
        <?php if(strval($section->parametrs->param54)=='true'): ?>
        <div class="photo_line-thumbs_gallery_scrollbar swiper-scrollbar"></div>
        <?php endif; ?>
    </div><?php endif; ?>
  </div>
  <?php endif; ?>
   <div class="contentBody">
        <a class="backLink" href="<?php echo $__data->getLinkPageName() ?>"><?php echo $section->language->lang012 ?></a> 
        <?php if(strval($section->parametrs->param56)!='N'): ?>
        <<?php echo $section->parametrs->param56 ?> class="objectTitle">
            <span class="objectTitleTxt"><?php echo $news_title ?></span>
        </<?php echo $section->parametrs->param56 ?>>
        <?php endif; ?>
        
        <!--div id="objimage"> 
            <?php echo $news_img ?>
        </div-->
        <div class="objectText">
            <?php echo $news_text ?>
        </div>
   </div>
   
    
    <?php if(strval($section->parametrs->param31)=='Y'): ?>
        
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
        
        <div id="ya_share1" style="margin: 10px 0;">
            
        </div>
    <?php endif; ?>
    <input class="buttonSend" onclick="window.history.back(-1);" type="button" value="<?php echo $section->language->lang013 ?>">
</section>
<?php if(strval($section->parametrs->param2)!='d'): ?></div><?php endif; ?>
