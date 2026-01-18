<!-- Subpage 1. Подробно о товаре и сопутствующие товары с аналогами-->
<?php if(file_exists($__MDL_ROOT."/php/subpage_7.php")) include $__MDL_ROOT."/php/subpage_7.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_7.tpl")) include $__MDL_ROOT."/tpl/subpage_7.tpl"; ?> 
<header:js>
<script type="text/javascript" src="[module_url]share42.js"></script>
</header:js>  
<style type="text/css"> 
#share42 {
  display: inline-block;
  padding: 6px 0 0 6px;
  background: #FFF;
  border: 1px solid #E9E9E9;
  border-radius: 4px;
}
#share42:hover {
  background: #F6F6F6;
  border: 1px solid #D4D4D4;
  box-shadow: 0 0 5px #DDD;
}
#share42 a {opacity: 0.5;}
#share42:hover a {opacity: 0.7}
#share42 a:hover {opacity: 1}
</style>

<script type="text/javascript">
$(document).ready(function(){
    $(".goodsDetRating").append('<span class="goodsDetVotesTitle"><?php echo $section->parametrs->param267 ?></span><a href="" class="goodsDetUp" title="<?php echo $section->parametrs->param260 ?>"><?php echo $section->parametrs->param262 ?></a><span class="goodsDetVotes"></span><a href="" class="goodsDetDown" title="<?php echo $section->parametrs->param261 ?>"><?php echo $section->parametrs->param263 ?></a><div class="goodsDetRatingErr"></div>');
    $(".goodsDetRating > .goodsDetRatingErr").hide();
    
    $.post("?ajax<?php echo $razdel ?>", {initvote: "1"}, onInitVotesSuccess);
    function onInitVotesSuccess(data) {  
        $(".goodsDetRating > .goodsDetVotes").text(data);    
        return false;
    }
    
    function errorReportingAuth() {
        $(".goodsDetRating > .goodsDetRatingErr").text("<?php echo $section->parametrs->param264 ?>");        
        $(".goodsDetRating > .goodsDetRatingErr").show();    
        setTimeout(function(){ $(".goodsDetRating > .goodsDetRatingErr").hide(); return false;}, 3000);
        return false;
    }
    
    function errorReportingDouble() {
        $(".goodsDetRating > .goodsDetRatingErr").text("<?php echo $section->parametrs->param265 ?>");        
        $(".goodsDetRating > .goodsDetRatingErr").show();    
        setTimeout(function(){ $(".goodsDetRating > .goodsDetRatingErr").hide(); return false;}, 3000);
        return false;
    }
                                                              
    function onAjaxSuccess(data) {  
        if (data=="err_auth") { 
            errorReportingAuth();
        } else
        if (data=="err_double_vote") { 
            errorReportingDouble();
        }
        else {
            $(".goodsDetRating > .goodsDetVotes").text(data);    
        }
        return false;
    }
    
    $(".goodsDetRating > .goodsDetUp").click(function(){
        $.post("?ajax<?php echo $razdel ?>", {vote: "1"}, onAjaxSuccess);
        return false;
    });
    
    $(".goodsDetRating > .goodsDetDown").click(function(){
        $.post("?ajax<?php echo $razdel ?>", {vote: "-1"}, onAjaxSuccess);
        return false;
    });
})
</script>
    
<div class="content e_shopvit" > 
    <div class="goodsContentDet">
<!-- Путь по каталогу -->
        <?php if($section->parametrs->param246=='Y'): ?>
            <div class="goodsPath">
                <a class="goodsPathRoot" href="<?php echo seMultiDir()."/".$_page."/" ?>"><?php echo $section->parametrs->param234 ?></a><?php echo $cat_path ?>
            </div>
        <?php endif; ?>
        <div class="goodsDetail">
            
            <h4 class="goodsDetTitle"><?php echo $price_fields_name ?></h4>
            <?php if($section->parametrs->param126=='Y'): ?>
                <h4 class="goodsDetArticle"><?php echo $section->parametrs->param22 ?>&nbsp;<?php echo $price_fields_code ?></h4>
            <?php endif; ?>
<!-- Изображение -->
            <div class="goodsLinkPhoto">
                <img class="goodsPhoto" src="<?php echo $img_block ?>" border="0" title="<?php echo $price_img_alt ?>" alt="<?php echo $price_img_alt ?>">
                
            </div> 
            <div id="objimage"> 
            <?php echo $shop_img ?>
            </div>
<!-- Рейтинг -->
            <?php if($section->parametrs->param266=='Y'): ?>
                <div class="goodsDetRating">
                    
                </div>
            <?php endif; ?>
<!-- Производитель -->
            <?php if($section->parametrs->param129=='Y'): ?>
                <?php if($price_fields_manufacturer!=''): ?>
                    <div class="manuf">
                        <label class="manuftitle"><?php echo $section->parametrs->param135 ?></label>
                        <span class="manufvar"><?php echo $price_fields_manufacturer ?>&nbsp;</span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
<!-- Наличие -->
            <?php if($section->parametrs->param128=='Y'): ?>
                <div class="presence">
                    <span class="goodsDetCountNaim"><?php echo $section->parametrs->param136 ?></span>
                    <span class="goodsDetCountStyle" id="count_<?php echo $razdel ?>_<?php echo $id_goods ?>"><?php echo $price_fields_count ?></span>
                </div>
            <?php endif; ?>
            <div class="goodsDetPriceBox">
                <form name="formtop" style="margin:0px;" method="post" action="">
                    <a name="f1"></a>
<!-- Цена -->
                    <div class="price">
                        <span class="goodsDetPriceNaim"><?php echo $section->parametrs->param121 ?></span>
                        <span class="goodsDetPriceStyle "><?php echo $price_fields_price ?></span>
                    </div>
                    <div class="goodsDetButtonBox">
                        <input type="hidden" name="addcart" value="<?php echo $price_fields_id ?>">
                        <a class="buttonSend back" href="javascript:window.history.back()"><?php echo $section->parametrs->param11 ?></a>
                    </div>
                </form>
            </div>
<!-- Краткое описание -->
            <?php if($section->parametrs->param133!='N'): ?>
                <div class="goodsDetNote"><?php echo $price_fields_note ?></div>
            <?php endif; ?>
            <?php if($morephoto!=''): ?>
                <?php if(file_exists($__MDL_ROOT."/php/subpage_2.php")) include $__MDL_ROOT."/php/subpage_2.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_2.tpl")) include $__MDL_ROOT."/tpl/subpage_2.tpl"; ?>
            <?php endif; ?>
 
 <!-- полный текст -->
 <div class="goodsDetText"><?php echo $price_fields_text ?></div>           
<!-- Социальные кнопки -->

            <script type="text/javascript" src="http://yandex.st/share/share.js" charset="utf-8"></script>
            <script type="text/javascript">
                new Ya.share({
                    element: 'ya_share1',
                    elementStyle: {type: 'button', linkIcon: true, border: false, quickServices: ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj'] },
                    popupStyle: {'copyPasteField': true},
                    onready: function(ins){
                            $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf=<?php echo $section->parametrs->param333 ?>\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
                    }
                });
            </script>

            <div id="ya_share1" style="margin: 10px 0;" class="SocialBtn">
            
            </div>
        </div>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_9.php")) include $__MDL_ROOT."/php/subpage_9.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_9.tpl")) include $__MDL_ROOT."/tpl/subpage_9.tpl"; ?>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_10.php")) include $__MDL_ROOT."/php/subpage_10.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_10.tpl")) include $__MDL_ROOT."/tpl/subpage_10.tpl"; ?>
    </div>
</div>
