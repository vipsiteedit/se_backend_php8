    <div class="loaderAjax" title="<?php echo $section->parametrs->param14 ?>" style="display:none;">&nbsp;</div>
    <div id="headCart">
        <a id="linkGoCart" href="<?php echo $link_cart ?>"><?php echo $section->parametrs->param12 ?></a>
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
        
        <div class="extendInfoCart">
            <div id="defHidden" class="goodInfo defHidden" style="display:none;">
                <input class="hiddenIdGood" type="hidden" size="1" value="0">
                <span class="goodCount">-</span>
                <a class="linkShowGood" href="<?php echo $link_shop ?>" title="-">-</a>
                <span class="goodPrice">-</span>
                <a class="linkDelGood" href="javascript:void(0);" title="<?php echo $section->parametrs->param11 ?>">&nbsp;</a>
            </div>
            <?php foreach($section->objects as $record): ?>
                <div id="g_id<?php echo $record->id ?>" class="goodInfo">
                    <input class="hiddenIdGood" type="hidden" size="1" value="<?php echo $record->id ?>">
                    <span class="goodCount"><?php echo $record->count ?></span>
                    <a class="linkShowGood" href="<?php echo $link_shop ?><?php echo $record->code ?>" title="<?php echo $record->name ?>"><?php echo $record->name ?></a>
                    <span class="goodPrice"><?php echo $record->price ?></span>
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
        <a href="<?php echo $link_cart ?>" class="orderLink" title="<?php echo $section->parametrs->param18 ?>"><?php echo $section->parametrs->param13 ?></a>
    </div>
