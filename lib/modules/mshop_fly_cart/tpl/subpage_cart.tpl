    <div class="loaderAjax" title="<?php echo $section->language->lang019 ?>" style="display:none;">&nbsp;</div>
    <div id="headCart">
        <a id="linkGoCart" href="<?php echo seMultiDir()."/".$section->parametrs->param4."/" ?>"><?php echo $section->language->lang022 ?></a>
        <a class="butShowHide" href="javascript:void(0);" title="<?php echo $section->language->lang018 ?>">&nbsp;</a>
    </div>  
    <div id="bodyCart">
        <div class="shortInfoCart" style="display:none;">
            <div class="issetGoods" <?php echo $isset_goods ?>> 
                <div id="blockCount">
                    <span id="titleAllGoods"><?php echo $section->language->lang026 ?></span>
                    <span id="countGoods"><?php echo $count_goods ?></span>
                </div>
                <div id="blockSumm">
                    <span id="titleSummGoods"><?php echo $section->language->lang025 ?></span>
                    <span id="summGoods"><?php echo $order_summ ?></span>
                </div>
            </div>
            <div class="noGoods" <?php echo $no_goods ?>><span><?php echo $section->language->lang027 ?></span></div>
        </div>   
        <div class="extendInfoCart" style="height:100%;">
            <div class="buttonDiv" style="display:none;">
                <button class="butCh countDec">-</button>
                <input id="inpCh" type="text" value="">
                <button class="butCh countInc">+</button>
            </div>
            <?php foreach($section->objects as $record): ?>
                <div class="goodInfo" data-id="<?php echo $record->key ?>">
                    <a href="javascript:void(0)" class="goodCount"><?php echo $record->count ?></a>
                    <span class="measure"><?php echo $record->measure ?></span>
                    <a class="linkShowGood" href="<?php echo $record->link ?>" title="<?php echo $record->name ?>"><?php echo $record->name ?></a>
                    <span class="goodPrice"><?php echo $record->newsum ?></span>
                    <a class="linkDelGood" href="javascript:void(0);" title="<?php echo $section->language->lang020 ?>">&nbsp;</a>
                </div>
            
<?php endforeach; ?>            
            <div class="noGoods" <?php echo $no_goods ?>><span><?php echo $section->language->lang007 ?></span></div>
            <div class="orderSummAll">
                <div id="blockDiscount">
                    <span id="titleDelivery"><?php echo $section->language->lang024 ?></span>
                    <span id="summDiscount"><?php echo $discount_summ ?></span>
                </div>
                <div id="blockAmount">
                    <span id="titleOrder"><?php echo $section->language->lang023 ?></span>
                    <span id="summOrder"><?php echo $order_summ ?></span>
                </div>
            </div>   
        </div>
    </div>
    <div id="footCart">
        <a href="<?php echo seMultiDir()."/".$section->parametrs->param4."/" ?>" class="orderLink" title="<?php echo $section->language->lang017 ?>"><?php echo $section->language->lang028 ?></a>
        <a href="<?php echo seMultiDir()."/".$section->parametrs->param4."/" ?>cart_clear/" class="clearCartLink" title="<?php echo $section->language->lang030 ?>" style="display:none;"><?php echo $section->language->lang029 ?></a>
    </div>
