<table border="0" cellPadding="0" cellSpacing="0" class="tableTable tableListGoods" width="100%">
    <thead class="tableHead">
        <tr class="tableHeader"> 
            <th colspan="2" class="headItemName"><?php echo $section->language->lang023 ?></th> 
            <th class="headItemCount"><?php echo $section->language->lang005 ?></th>
            <th class="headItemSum"><?php echo $section->language->lang006 ?></th>
            <th class="headItemDelete">&nbsp;</th>
        </tr>
    </thead>     
    <tbody class="tableBody">            
        <?php foreach($section->objects as $record): ?>
            <tr class="tableRow itemCart" data-id="<?php echo $record->key ?>">
                <td class="itemImageCart">
                    <a href="<?php echo $record->link ?>"><img src="<?php echo $record->img ?>" style="<?php echo $record->img_style ?>" alt=""></a>
                </td>                                         
                <td class="itemInfoGoodsCart">
                    <a class="linkname" href="<?php echo $record->link ?>"><?php echo $record->name ?></a>
                    <?php if(!empty($record->paramsname)): ?>
                        <div class="cartitem_params"><?php echo $record->paramsname ?></div>
                    <?php endif; ?>
                    <div class="cartitem_article">
                        <?php echo $section->language->lang052 ?> <span><?php echo $record->article ?></span>
                    </div>
                    <?php if(strval($section->parametrs->param28)=='Y'): ?>
                    <div class="cartitem_presence">
                        <?php echo $section->language->lang053 ?> <span><?php echo $record->presence_count ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param27)=='Y'): ?>
                    <div class="presence">
                            <label class="presenceLabel"><ml>Срок поставки:</ml></label>
                            <?php if(!empty($record->signal_dt)): ?>
                            <span class="presenceValue" itemprop="delivery">
                            <span class="signal" style="background: <?php echo $record->signal_dt ?>;"></span><?php echo $record->delivery_time ?></span>
                        <?php endif; ?>
                        <?php if(empty($record->signal_dt)): ?>
                            <span class="presenceValue" itemprop="delivery">по запросу</span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="cartitem_presence">
                        <ml>Срок поставки</ml> <span><?php echo $record->presence_count ?></span>
                    </div>
                    <div class="cartitem_price">
                        <span class="itemPriceTitle"><?php echo $section->language->lang024 ?></span>
                        <?php if(!empty($record->discount)): ?>
                            <span class="itemOldPrice"><?php echo $record->oldprice ?></span>
                        <?php endif; ?>
                        <span class="itemNewPrice"><?php echo $record->newprice ?></span>
                    </div>
                </td>  
                <td class="itemCountCart">
                    <div class="cartitem_count">
                        <a href="#" class="buttonSend decCountItem" data-action="decrement" style="display:none;">-</a>
                        <input class="cartitem_inputcn" type="text" name="countitem[<?php echo $record->key ?>]" value="<?php echo $record->count ?>" size="3" data-step="<?php echo $record->step ?>">
                        <a href="#" class="buttonSend incCountItem" data-action="increment" style="display:none;">+</a>
                        <span class="measure"><?php echo $record->measure ?></span>
                    </div>
                </td>
                <td class="itemSumCart">
                    <span class="summBlock"><?php echo $record->newsum ?></span>
                </td>
                <td class="itemDeleteCart">
                    <a href="<?php echo $__data->getLinkPageName() ?>delcartname/<?php echo $record->key ?>/" class="
                     btnDeleteItem" title="<?php echo $section->language->lang025 ?>">&nbsp;</a>
                </td>
            </tr>
        
<?php endforeach; ?>
        
        <tr id="trTotalOrder">
            <td colspan="3">
                    <div id="noScriptBlockButton">
                        <input type="submit" class="buttonSend" id="btnClearCart" name="cart_clear" value="<?php echo $section->language->lang026 ?>">
                        <!--input type="submit" class="buttonSend" id="btnReloadCart" name="cart_reload" value="<?php echo $section->language->lang027 ?>"-->
                    </div>
            </td>
            <td colspan="2" id="tdTotalGoods">
                <div id="discountGoods">
                    <?php echo $section->language->lang028 ?> <span class="cartPriceSum"><?php echo $total_sum_discount ?></span>
                </div>
                <div id="summGoods">
                    <?php echo $section->language->lang029 ?> <span class="cartPriceSum"><?php echo $total_sum_goods ?></span>
                </div>
                <?php if(!empty($total_weight_goods)): ?>
                <div id="weightGoods">
                    <?php echo $section->language->lang079 ?> <span class="cartPriceSum"><?php echo $total_weight_goods ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($total_volume_goods)): ?>
                <div id="volumeGoods">
                    <?php echo $section->language->lang080 ?> <span class="cartPriceSum"><?php echo $total_volume_goods ?></span>
                </div>
                <?php endif; ?>
            </td>
        </tr>         
    </tbody> 
</table>    
<?php if(strval($section->parametrs->param26)=='Y'): ?>            
    <?php if(file_exists($__MDL_ROOT."/php/subpage_related.php")) include $__MDL_ROOT."/php/subpage_related.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_related.tpl")) include $__data->include_tpl($section, "subpage_related"); ?>
<?php endif; ?>
