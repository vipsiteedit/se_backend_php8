<table border="0" cellPadding="0" cellSpacing="0" class="tableTable tableListGoods" width="100%">
    <thead class="tableHead">
        <tr class="tableHeader"> 
            <th colspan="2" class="headItemName">[lang023]</th> 
            <th class="headItemCount">[lang005]</th>
            <th class="headItemSum">[lang006]</th>
            <th class="headItemDelete">&nbsp;</th>
        </tr>
    </thead>     
    <tbody class="tableBody">            
        <repeat:objects name=record>
            <tr class="tableRow itemCart" data-id="[record.key]">
                <td class="itemImageCart">
                    <a href="[param1].html[record.link]"><img src="[record.img]" style="[record.img_style]" alt=""></a>
                </td>                                         
                <td class="itemInfoGoodsCart">
                    <a class="linkname" href="[param1].html[record.link]">[record.name]</a>
                    <noempty:record.paramsname>
                        <div class="cartitem_params">[record.paramsname]</div>
                    </noempty>
                    <div class="cartitem_article">
                        [lang052] <span>[record.article]</span>
                    </div>
                    <div class="cartitem_presence">
                        [lang053] <span>[record.presence_count]</span>
                    </div>
                    <div class="cartitem_price">
                        <span class="itemPriceTitle">[lang024]</span>
                        <noempty:record.discount>
                            <span class="itemOldPrice">[record.oldprice]</span>
                        </noempty>
                        <span class="itemNewPrice">[record.newprice]</span>
                    </div>
                </td>  
                <td class="itemCountCart">
                    <div class="cartitem_count">
                        <a href="#" class="buttonSend decCountItem" data-action="decrement" style="display:none;">-</a>
                        <input class="cartitem_inputcn" type="text" name="countitem[[record.key]]" value="[record.count]" size="3" data-step="[record.step]">
                        <a href="#" class="buttonSend incCountItem" data-action="increment" style="display:none;">+</a>
                        <span class="measure">[record.measure]</span>
                    </div>
                </td>
                <td class="itemSumCart">
                    <span class="summBlock">[record.newsum]</span>
                </td>
                <td class="itemDeleteCart">
                    <a href="[thispage.link]delcartname/[record.key]/" class="
                     btnDeleteItem" title="[lang025]">&nbsp;</a>
                </td>
            </tr>
        </repeat:objects>
        
        <tr id="trTotalOrder">
            <td colspan="3">
                <serv><noscript></serv>
                    <div id="noScriptBlockButton">
                        <input type="submit" class="buttonSend" id="btnClearCart" name="cart_clear" value="[lang026]">
                        <input type="submit" class="buttonSend" id="btnReloadCart" name="cart_reload" value="[lang027]">
                    </div>
                <serv></noscript></serv>
            </td>
            <td colspan="2" id="tdTotalGoods">
                <div id="discountGoods">
                    [lang028] <span class="cartPriceSum">{$total_sum_discount}</span>
                </div>
                <div id="summGoods">
                    [lang029] <span class="cartPriceSum">{$total_sum_goods}</span>
                </div>
                <div id="weightGoods">
                    [lang079] <span class="cartPriceSum">{$total_weight_goods}</span>
                </div>
                <div id="volumeGoods">
                    [lang080] <span class="cartPriceSum">{$total_volume_goods}</span>
                </div>
            </td>
        </tr>         
    </tbody> 
</table>    
<if:[param26]=='Y'>            
    [subpage name=related]
</if>
