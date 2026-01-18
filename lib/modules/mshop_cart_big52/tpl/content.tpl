<header:js>
[js:jquery/jquery.min.js]
<script type="text/javascript">
var param16 = '<?php echo $section->parametrs->param16 ?>',
    ajax_url = '?ajax<?php echo $section->id ?>',
    page_link = '<?php echo seMultiDir()."/".$_page."/" ?>';
</script>
<script type="text/javascript" src="[module_url]engine.js"></script>
</header:js>
<div class="content contShopCartNew" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="contentBody" style="position:relative;">       
        <div id="notEmptyCartGoods" <?php if($total['count']==0): ?>style="display:none;"<?php endif; ?>>
            <div <?php if($error_message==''): ?>style="display:none;"<?php endif; ?> id="blockMessageWarning">
                <span><?php echo $error_message ?></span>
            </div>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_selectregion.php")) include $__MDL_ROOT."/php/subpage_selectregion.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_selectregion.tpl")) include $__data->include_tpl($section, "subpage_selectregion"); ?>
            <form id="cartGoodsForm" style="margin:0px;" name="cartgoodsform" action="" method="post">     
                <div class="blockCartContent" id="blockCartGoods">
                    <div class="blockCartTitle">
                        <span><?php echo $section->language->lang039 ?></span>
                    </div>
                    <div class="blockCartList">
                        <?php if(file_exists($__MDL_ROOT."/php/subpage_goods_list.php")) include $__MDL_ROOT."/php/subpage_goods_list.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_goods_list.tpl")) include $__data->include_tpl($section, "subpage_goods_list"); ?>
                    </div>
                </div>
                <div class="continueShoppingArea">
                    <a class="continueShopping" href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?>"><?php echo $section->language->lang072 ?></a>
                </div>
                <?php if($section->parametrs->param8=='Y'): ?>
                <div class="blockCartContent" id="blockCouponDiscount">
                    <div id="blockCouponApply">
                        <span id="couponTitle"><?php echo $section->language->lang040 ?></span>
                        <input id="inputCoupon" type="text" name="code_coupon" value="<?php if($find_coupon): ?><?php echo $code_coupon ?><?php endif; ?>">
                        <input type="button" class="buttonSend" id="btnApplyCoupon" title="<?php echo $section->language->lang041 ?>" value="<?php echo $section->language->lang042 ?>">
                        <div id="noteCoupon">
                            <?php if($find_coupon): ?><?php echo $note_coupon ?><?php else: ?><?php echo $section->language->lang043 ?><?php endif; ?>
                        </div>
                    </div>
                    <div id="blockSumCoupon">
                        <?php if($find_coupon): ?>
                            -<?php echo $sum_coupon ?>
                        <?php endif; ?>
                    </div>
                </div> 
                <?php endif; ?>        
                
                <div class="blockCartContent" id="blockCartDelivery">
                    <div class="blockCartTitle">
                        <span class="deliveryTitle"><?php echo $section->language->lang044 ?></span>
                        <span id="selectedUserRegion"><?php echo $section->language->lang068 ?><a id="linkSelectRegion" href="#"><?php echo $region_city ?></a></span>
                    </div> 
                    <div class="blockCartList">
                        <?php if(file_exists($__MDL_ROOT."/php/subpage_delivery_list.php")) include $__MDL_ROOT."/php/subpage_delivery_list.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_delivery_list.tpl")) include $__data->include_tpl($section, "subpage_delivery_list"); ?>    
                    </div>
                </div>
                
                <div class="blockCartContent" id="blockCartContact">
                    <div class="blockCartTitle">
                        <span><?php echo $section->language->lang045 ?></span>
                    </div>
                    <div class="blockCartList">
                        <?php if(file_exists($__MDL_ROOT."/php/subpage_contact.php")) include $__MDL_ROOT."/php/subpage_contact.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_contact.tpl")) include $__data->include_tpl($section, "subpage_contact"); ?>
                    </div> 
                </div>
                
                <?php if($section->parametrs->param9=='Y'): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_payment_list.php")) include $__MDL_ROOT."/php/subpage_payment_list.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_payment_list.tpl")) include $__data->include_tpl($section, "subpage_payment_list"); ?>
                <?php endif; ?>
                
                <div class="blockCartContent" id="blockCartTotalSum">
                    <span id="totalSumTitle"><?php echo $section->language->lang047 ?></span> 
                    <span id="totalSumPrice"><?php echo $sum_total_order ?></span>        
                </div>
                
                <div class="blockCartContent blockButtonOrder">
                <?php if($section->parametrs->param17=='Y'): ?>
                    <input type="submit" class="buttonSend" id="test_order" name="test_order" value="<?php echo $section->language->lang048 ?>">
                <?php else: ?>
                    <input type="hidden" id="test_order" name="test_order" value="true">
                    <input type="submit" class="buttonSend" id="test_order" name="confirm_order" value="<?php echo $section->language->lang048 ?>">
                <?php endif; ?>
                </div>
            </form>
        </div>
        <div id="emptyCartGoods" <?php if($total['count']!=0): ?>style="display:none;"<?php endif; ?>>
            <div id="emptyCart"><?php echo $section->language->lang049 ?></div>
            <a class="linkGoShopping" href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?>"><?php echo $section->language->lang050 ?></a>
        </div>
    </div>
</div>
