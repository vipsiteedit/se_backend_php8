    <div class="loaderAjax" title="[lang019]" style="display:none;">&nbsp;</div>
    <div id="headCart">
        <a id="linkGoCart" href="[param4].html">[lang022]</a>
        <a class="butShowHide" href="javascript:void(0);" title="[lang018]">&nbsp;</a>
    </div>  
    <div id="bodyCart">
        <div class="shortInfoCart" <if:[param19]=='ext'>style="display:none;"</if>>
            <div class="issetGoods" {$isset_goods}> 
                <div id="blockCount">
                    <span id="titleAllGoods">[lang026]</span>
                    <span id="countGoods">{$count_goods}</span>
                </div>
                <div id="blockSumm">
                    <span id="titleSummGoods">[lang025]</span>
                    <span id="summGoods">{$order_summ}</span>
                </div>
            </div>
            <div class="noGoods" {$no_goods}><span>[lang027]</span></div>
        </div>
        <div class="extendInfoCart" style="height:100%;<if:[param19]=='short'>display:none;</if>">
            <div class="buttonDiv" style="display:none;">
                <button class="butCh countDec">-</button>
                <input id="inpCh" type="text" value="">
                <button class="butCh countInc">+</button>
            </div>
            <repeat:objects>
                <div class="goodInfo" data-id="[record.key]">
                    <a href="javascript:void(0)" class="goodCount">[record.count]</a>
                    <span class="measure">[record.measure]</span>
                    <a class="linkShowGood" href="[record.link]" title="[record.name]">[record.name]</a>
                    <span class="goodPrice">[record.newsum]</span>
                    <a class="linkDelGood" href="javascript:void(0);" title="[lang020]">&nbsp;</a>
                </div>
            </repeat:objects>            
            <div class="noGoods" {$no_goods}><span>[lang007]</span></div>
            <div class="orderSummAll">
                <div id="blockDiscount">
                    <span id="titleDelivery">[lang024]</span>
                    <span id="summDiscount">{$discount_summ}</span>
                </div>
                <div id="blockAmount">
                    <span id="titleOrder">[lang023]</span>
                    <span id="summOrder">{$order_summ}</span>
                </div>
            </div>   
        </div>
    </div>
    <div id="footCart">
        <a href="[param4].html" class="orderLink" title="[lang017]">[lang028]</a>
        <a href="[param4].htmlcart_clear/" class="clearCartLink" title="[lang030]" style="display:none;">[lang029]</a>
    </div>
