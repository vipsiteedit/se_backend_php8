<?php

    <div class="loaderAjax" title=$section->language->lang019 style="display:none;">&nbsp;</div>
    <div id="headCart">
        <a id="linkGoCart" href=$section->parametrs->param4.html">$section->language->lang022</a>
        <a class="butShowHide" href="javascript:void(0);" title=$section->language->lang018>&nbsp;</a>
    </div>  
    <div id="bodyCart">
        <div class="shortInfoCart" style="display:none;">
            <div class="issetGoods" {$isset_goods}> 
                <div id="blockCount">
                    <span id="titleAllGoods">$section->language->lang026</span>
                    <span id="countGoods">{$count_goods}</span>
                </div>
                <div id="blockSumm">
                    <span id="titleSummGoods">$section->language->lang025</span>
                    <span id="summGoods">{$order_summ}</span>
                </div>
            </div>
            <div class="noGoods" {$no_goods}><span>$section->language->lang027</span></div>
        </div>
        <div class="extendInfoCart" style="height:100%;">
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
                    <a class="linkDelGood" href="javascript:void(0);" title=$section->language->lang020>&nbsp;</a>
                </div>
            </repeat:objects>            
            <div class="noGoods" {$no_goods}><span>$section->language->lang007</span></div>
            <div class="orderSummAll">
                <div id="blockDiscount">
                    <span id="titleDelivery">$section->language->lang024</span>
                    <span id="summDiscount">{$discount_summ}</span>
                </div>
                <div id="blockAmount">
                    <span id="titleOrder">$section->language->lang023</span>
                    <span id="summOrder">{$order_summ}</span>
                </div>
            </div>   
        </div>
    </div>
    <div id="footCart">
        <a href=$section->parametrs->param4.html" class="orderLink" title=$section->language->lang017>$section->language->lang028</a>
        <a href=$section->parametrs->param4.htmlcart_clear/" class="clearCartLink" title=$section->language->lang030 style="display:none;">$section->language->lang029</a>
    </div>

?>