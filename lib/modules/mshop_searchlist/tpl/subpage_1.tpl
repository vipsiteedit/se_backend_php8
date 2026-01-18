<!-- Subpage 1. Подробно о товаре и сопутствующие товары с аналогами-->
<div class="content shopSearchList" > 
    <div class="goodsContentDet">
        <!--div class="goodsPath">
            < SERV><?php echo $cat_path ?>< /SERV>
            < SE>
            <span class="goodsPathRoot"><?php echo $section->language->lang001 ?></a>
            <span class="goodsPathSepar">/</span>
            <span class="goodsLinkPath"><?php echo $section->language->lang002 ?></a>
            <span class="goodsPathSepar">/</span>
            <span class="goodsActivePath">Merida Matts TFS 300-V</span>
            < /SE>
        </div-->
        <!-- The good details -->
        <div class="goodsDetail">
            <h4 class="goodsDetTitle"><?php echo $price_fields['name'] ?></h4>
            <div class="goodsLinkPhoto">
                <?php echo $img_block ?>
                 
            </div>
            <div class="top">
                <div class="goodsDetPriceBox">
                    <span class="goodsDetPriceNaim"><?php echo $section->parametrs->param24 ?></span>
                    <span class="goodsDetPriceStyle"><?php echo $price_fields['price'] ?></span>
                </div>
                <div class="goodsDetButtonBox">
                    <a name="f1"></a>
                    <form style="margin:0px;" method="post">
                        <?php if($price_fields['buyshow']!=0): ?>
                            <input type="hidden" name="addcartcount" value="1">
                            <input type="hidden" name="addcart" value="<?php echo $price_fields['id'] ?>">
                            <a class="buttonSend addcart" onclick="this.parentNode.submit();" href="#f1"><?php echo $section->language->lang041 ?></a>
                        <?php endif; ?>
                        <a class="buttonSend back" href="javascript:window.history.back()"><?php echo $section->parametrs->param11 ?></a>
                    </form>
                </div>
            </div>
            <div class="goodsDetNote">
                <?php echo $price_fields['note'] ?>
                   
            </div>
            <div class="goodsDetText">
                <?php echo $price_fields['text'] ?>
                  
            </div>
        </div>
<?php if($analogscount!=0): ?> 
        <!-- The good analogs -->
        <div class="goodsAnalogs goods">
            <div class="goodsAnalogsHat"><?php echo $section->language->lang042 ?></div>
            <?php if(file_exists($__MDL_ROOT."/php/subpage_3.php")) include $__MDL_ROOT."/php/subpage_3.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_3.tpl")) include $__data->include_tpl($section, "subpage_3"); ?>
        </div>
<?php endif; ?>
    </div>
</div>
