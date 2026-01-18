<footer:js>[include_js()]</footer:js>
<header:js>
[lnk:rouble/rouble.css]
</header:js>
<footer:js>
[js:jquery/jquery.min.js]
<if:[param17]=='Y'>
[js:ui/jquery.ui.min.js]
</if>
[lnk:fancybox2/jquery.fancybox.css] 
[js:fancybox2/jquery.fancybox.pack.js] 
[include_js({'id':[part.id],
    'param4':'[param4]',
    'param11':'[lang020]',
    'param16':'[param16]',
    'param17':'[param17]',
    'param19':'[param19]',
    'param20':'[param20]',
    'param22':'[param22]',
    'curr': '{$pricemoney}',
    'is_local': '<se>1</se>'
})]
</footer:js>
<div class="content contFlyCart" data-type="[part.type]" data-id="[part.id]" [part.style][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_alt]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>  
    <div id="fixedCart" class="fixedCart">
        [subpage name=cart]
    </div>
</div>
<SE>
<footer:js>
<script type="text/javascript">
$(document).ready(function(){
    $(".testShowAjaxIcon").toggle(function(){
        $('.loaderAjax').show();
        $(this).attr('value','[lang013]');
    }, function(){
        $('.loaderAjax').hide();
        $(this).attr('value','[lang006]');
    });
    
    $(".testEmptyAllGoods").toggle(function(){
        $('.noGoods').show();
        $('.issetGoods, .goodInfo').hide();
        $(this).attr('value','[lang014]');
    }, function(){
        $(".issetGoods, .goodInfo:not(.defHidden)").show();
        $('.noGoods').hide();
        $(this).attr('value','[lang007]');
    });
    
    $(".testShortExtCart").click(function(){
        var ext_cart = $(".fixedCart .extendInfoCart");
        var short_cart = $(".fixedCart .shortInfoCart");
        cart_animate = true;
        if (short_cart.is(':hidden')){
            ext_cart.slideUp(500, function(){
                short_cart.slideDown(500, function(){
                    cart_animate = false;     
                });    
            });    
        }
        else{
            short_cart.slideUp(500, function(){
                ext_cart.slideDown(500, function(){
                    cart_animate = false;     
                });    
            });
        }
        $(".fixedCart .butShowHide").fadeOut(500, function(){
            $(this).toggleClass('showExtCart').fadeIn(500);
        });   
    });
    
});
</script>
</footer:js>
<div class="sysedit" style="border:2px dashed red;background-color:white;color:black">  
<input class="sysedit testEmptyAllGoods" id="" type="button" value="[lang007]">
<BR class="sysedit">
<input class="sysedit testShortExtCart" type="button" title="[lang015]" value="[lang016]">
<BR class="sysedit"><a class="sysedit" href='[link.subpage=examples]' title="[lang011]">[lang012]</a>
</div>
</SE>
