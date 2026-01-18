    <div class="loaderAjax" title="<?php echo $section->parametrs->param14 ?>" style="display:none;">&nbsp;</div>
    <div id="headCart">
        <a id="linkGoCart" href="<?php echo seMultiDir()."/".$section->parametrs->param4."/" ?>"><?php echo $section->parametrs->param12 ?></a>
        <a class="butShowHide" href="javascript:void(0);" title="<?php echo $section->parametrs->param15 ?>">&nbsp;</a>
    </div>  
    <div id="bodyCart">
        <div class="shortInfoCart" style="display:none;">
            <div class="issetGoods" <?php echo $isset_goods ?>> 
                <div id="blockCount">
                    <span id="titleAllGoods"><?php echo $section->parametrs->param7 ?></span>
                    <span id="countGoods"><?php echo $count_goods ?></span>
                </div>
                <div id="blockSumm">
                    <span id="titleSummGoods"><?php echo $section->parametrs->param8 ?></span>
                    <span id="summGoods"><?php echo $order_summ ?></span>
                </div>
            </div>
            <div class="noGoods" <?php echo $no_goods ?>><span><?php echo $section->parametrs->param6 ?></span></div>
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
                    <a class="linkShowGood" href="<?php echo $record->link ?>" title="<?php echo $record->name ?>"><?php echo $record->name ?></a>
                    <span class="goodPrice"><?php echo $record->newsum ?></span>
                    <a class="linkDelGood" href="javascript:void(0);" title="<?php echo $section->parametrs->param11 ?>">&nbsp;</a>
                </div>
            
<?php endforeach; ?>            
            <div class="noGoods" <?php echo $no_goods ?>><span><?php echo $section->parametrs->param5 ?></span></div>
            <div class="orderSummAll">
                <div id="blockDiscount">
                    <span id="titleDelivery"><?php echo $section->parametrs->param9 ?></span>
                    <span id="summDiscount"><?php echo $discount_summ ?></span>
                </div>
                <div id="blockAmount">
                    <span id="titleOrder"><?php echo $section->parametrs->param10 ?></span>
                    <span id="summOrder"><?php echo $order_summ ?></span>
                </div>
            </div>   
        </div>
    </div>
    <div id="footCart">
        <a href="<?php echo seMultiDir()."/".$section->parametrs->param4."/" ?>" class="orderLink" title="<?php echo $section->parametrs->param18 ?>">Оформить</a>
        <a href="javascript:void(0);" class="clearCartLink" title="Очистить корзину" style="display:none;">Очистить</a>
    </div>
