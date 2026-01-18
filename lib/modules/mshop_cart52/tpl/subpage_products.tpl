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
                    <a href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?><?php echo $record->link ?>"><img src="<?php echo $record->img ?>" style="<?php echo $record->img_style ?>" alt=""></a>
                </td>                                         
                <td class="itemInfoGoodsCart">
                    <a class="linkname" href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?><?php echo $record->link ?>"><?php echo $record->name ?></a>
                    <?php if(!empty($record->paramsname)): ?>
                        <div class="cartitem_params"><?php echo $record->paramsname ?></div>
                    <?php endif; ?>
                    <div class="cartitem_article">
                        <?php echo $section->language->lang052 ?> <span><?php echo $record->article ?></span>
                    </div>
                    <div class="cartitem_presence">
                        <?php echo $section->language->lang053 ?> <span><?php echo $record->presence_count ?></span>
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
                    <a href="<?php echo seMultiDir()."/".$_page."/" ?>delcartname/<?php echo $record->key ?>/" class="
                     btnDeleteItem" title="<?php echo $section->language->lang025 ?>">&nbsp;</a>
                </td>
            </tr>
        
<?php endforeach; ?>
        
        <tr id="trTotalOrder">
            <td colspan="3">
                <noscript>
                    <div id="noScriptBlockButton">
                        <input type="submit" class="buttonSend" id="btnClearCart" name="cart_clear" value="<?php echo $section->language->lang026 ?>">
                        <input type="submit" class="buttonSend" id="btnReloadCart" name="cart_reload" value="<?php echo $section->language->lang027 ?>">
                    </div>
                </noscript>
            </td>
            <td colspan="2" id="tdTotalGoods">
                <div id="discountGoods">
                    <?php echo $section->language->lang028 ?> <span class="cartPriceSum"><?php echo $total_sum_discount ?></span>
                </div>
                <div id="summGoods">
                    <?php echo $section->language->lang029 ?> <span class="cartPriceSum"><?php echo $total_sum_goods ?></span>
                </div>
                <div id="weightGoods">
                    <?php echo $section->language->lang079 ?> <span class="cartPriceSum"><?php echo $total_weight_goods ?></span>
                </div>
                <div id="volumeGoods">
                    <?php echo $section->language->lang080 ?> <span class="cartPriceSum"><?php echo $total_volume_goods ?></span>
                </div>
            </td>
        </tr>         
    </tbody> 
</table>                
