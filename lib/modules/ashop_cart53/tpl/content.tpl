<header:js>
[lnk:rouble/rouble.css]
</header:js>
<footer:js>
[js:jquery/jquery.min.js]
[include_js({'id':<?php echo $section->id ?>, 'link':'<?php echo $__data->getLinkPageName() ?>','p16':'<?php echo $section->parametrs->param16 ?>',curr: '<?php echo $baseCurr ?>'})]
</footer:js>
<div class="content contShopCartNew" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
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
            <form id="cartGoodsForm" style="margin:0px;" name="cartgoodsform" action="" method="post">     
                <div class="blockCartContent" id="blockCartGoods">
                    <div class="blockCartTitle">
                        <span><?php echo $section->language->lang039 ?></span>
                    </div>
                    <div class="blockCartList">
                        <?php if(file_exists($__MDL_ROOT."/php/subpage_products.php")) include $__MDL_ROOT."/php/subpage_products.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_products.tpl")) include $__data->include_tpl($section, "subpage_products"); ?>
                    </div>
                </div>
                <div class="continueShoppingArea">
                    <a class="continueShopping btn btn-link" href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?>"><?php echo $section->language->lang072 ?></a>
                </div>
                <?php if(strval($section->parametrs->param8)=='Y'): ?>
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
                
                <div class="blockCartContent" id="blockCartContact">
                    <div class="blockCartTitle">
                        <span><?php echo $section->language->lang045 ?></span>
                    </div>
                    <div class="blockCartList">
                        <?php if(file_exists($__MDL_ROOT."/php/subpage_contacts.php")) include $__MDL_ROOT."/php/subpage_contacts.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_contacts.tpl")) include $__data->include_tpl($section, "subpage_contacts"); ?>
                    </div> 
                </div>
                                      
                <div class="blockCartContent" id="blockCartDelivery">
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_selectregion.php")) include $__MDL_ROOT."/php/subpage_selectregion.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_selectregion.tpl")) include $__data->include_tpl($section, "subpage_selectregion"); ?>
                    <div class="blockCartTitle">
                        <span class="deliveryTitle"><?php echo $section->language->lang044 ?></span>
                        <span id="selectedUserRegion"><?php echo $section->language->lang068 ?><a class="userRegionName" href="#"><?php echo $region_city ?></a></span>
                    </div> 
                    <div class="blockCartList">
                        <?php if(file_exists($__MDL_ROOT."/php/subpage_deliveries.php")) include $__MDL_ROOT."/php/subpage_deliveries.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_deliveries.tpl")) include $__data->include_tpl($section, "subpage_deliveries"); ?>    
                    </div>
                    <div class="blockContactAddress"<?php if(empty($addr)): ?> style="display:none;"<?php endif; ?>>
                        <div class="blockRegLabel">
                            <label for="reg_address"><?php echo $section->language->lang033 ?></label><span class="required">*</span>
                        </div>
                        <div class="blockRegInput">
                            <textarea style="width:600px;height:100px;" class="inputCartContact <?php if(!empty($error_address)): ?>inputCartError<?php endif; ?>" id="reg_address" name="contact_address"><?php echo $contact_address ?></textarea>
                            <div class="regCartError "<?php if(empty($error_address)): ?> style="display:none;"<?php endif; ?>><?php echo $error_address ?></div>
                        </div> 
                    </div>
                </div>     
                
                <?php if(strval($section->parametrs->param9)=='Y'): ?>
                    <?php if(file_exists($__MDL_ROOT."/php/subpage_payments.php")) include $__MDL_ROOT."/php/subpage_payments.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_payments.tpl")) include $__data->include_tpl($section, "subpage_payments"); ?>
                <?php endif; ?>
                
                <?php if(file_exists($__MDL_ROOT."/php/subpage_license.php")) include $__MDL_ROOT."/php/subpage_license.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_license.tpl")) include $__data->include_tpl($section, "subpage_license"); ?>
                
                <div class="blockCartContent" id="blockCartTotalSum">
                    <span id="totalSumTitle"><?php echo $section->language->lang047 ?></span> 
                    <span id="totalSumPrice"><?php echo $sum_total_order ?></span>        
                </div>
                
                <div class="blockCartContent blockButtonOrder">
                    <button class="buttonSend" id="test_order" name="place_order"><?php echo $section->language->lang048 ?></button>
                </div>
            </form>
        </div>
        <div id="emptyCartGoods" <?php if($total['count']!=0): ?>style="display:none;"<?php endif; ?>>
            <div id="emptyCart"><?php echo $section->language->lang049 ?></div>
            <a class="linkGoShopping" href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?>"><?php echo $section->language->lang050 ?></a>
        </div>
    </div>
</div>
