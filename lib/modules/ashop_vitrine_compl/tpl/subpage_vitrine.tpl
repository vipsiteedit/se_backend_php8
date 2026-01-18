<?php foreach($section->objects as $record): ?>
    <div class="productItem product-id-<?php echo $record->id ?>">
        <div class="product-item-block">
        <div class="blockImage<?php if(strval($section->parametrs->param341)=='Y'): ?> blockImageBox<?php endif; ?>"> 
            <?php if(strval($section->parametrs->param327)=='Y'): ?>
                <button class="quickView" title="<?php echo $section->language->lang094 ?>" data-id="<?php echo $record->id ?>"><i class="fa fa-search" aria-hidden="true"></i></button>
            <?php endif; ?>
            <a href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>" title="<?php echo $record->img_alt ?>">
                <img class="objectImage img-responsive center-block" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->img_alt ?>">
            </a>
            <div class="blockFlag">
            <?php if($record->flag_new=='Y'): ?>
                <span class="flag_new" title="<?php echo $section->language->lang050 ?>"><i class="fa fa-sun-o" aria-hidden="true"><div class="flag-new-circle"></div><span class="text-flag-new"><?php echo $section->language->lang050 ?></span></i></span>
            <?php endif; ?>
            <?php if($record->flag_hit=='Y'): ?>
                <span class="flag_hit" title="<?php echo $section->language->lang049 ?>"><i class="fa fa-sun-o" aria-hidden="true"><i class="fa fa-thumbs-up" aria-hidden="true"></i></i></span>
            <?php endif; ?>
            </div>
            <?php if($record->percent!=0): ?>
                <div class="flag_discount" title="<?php echo $record->percent ?>%"><?php echo $record->percent ?>%</div>
            <?php endif; ?>
        </div>
        <div class="total-object-box">
        <?php if(strval($section->parametrs->param266)=='Y'): ?>
            <div class="objectRating">
                <span class="ratingOff" title="<?php echo $section->language->lang058 ?> <?php echo $record->rating ?> <?php echo $section->language->lang059 ?> 5">
                    <span class="ratingOn" style="width:<?php echo $record->rating_percent ?>%;"></span>
                </span>
                <span class="ratingValue"><?php echo $record->rating ?></span>
                <span class="marks">(<a class="marksLabel" href="<?php echo $record->linkshow ?>#reviews"><?php echo $section->language->lang056 ?></a> <span class="marksValue"><?php echo $record->marks ?></span>)</span>
            </div>
        <?php endif; ?>
        <div class="object-brand-article toggle-object">
        <?php if(strval($section->parametrs->param84)=='Y'): ?>
            <?php if(!empty($record->brand)): ?>
                <div class="objectBrand">
                    <!--label class="brandLabel"><?php echo $section->language->lang024 ?></label-->
                    <span class="brandValue"><?php echo $record->brand ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param83)=='Y'): ?>
            <?php if(!empty($record->article)): ?>
                <div class="objectArticle">
                    <!--label class="articleLabel"><?php echo $section->language->lang005 ?></label-->
                    <span class="articleValue"><?php echo $record->article ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        </div>
        <div class="objectTitle">
            <?php if(strval($section->parametrs->param275)=='Y'): ?>
                <a class="textTitle" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $record->name ?></a>
            <?php else: ?>
                <span class="textTitle"><?php echo $record->name ?></span>
            <?php endif; ?>
        </div>
        <?php if(strval($section->parametrs->param114)=='Y' ): ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="priceBox">
            <form class="form_addCart" method="post" action="">
                <div class="price-button-box">
                <div class="priceStyle<?php if(empty($record->realprice)): ?> nullPrice<?php endif; ?>">
                <?php if(strval($section->parametrs->param226)=='Y'): ?>
                    <!--div class="priceLabel"><?php echo $section->language->lang008 ?></div-->
                <?php endif; ?>
                <?php if(strval($section->parametrs->param113)=='Y' && $record->oldprice!=''): ?>
                    <div class="oldPrice"><?php echo $record->oldprice ?></div>
                <?php endif; ?>
                <div class="newPrice"><?php echo $record->newprice ?></div>
                </div>
                <div class="buttonBox">
                    <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                    <?php if($record->maxcount==0 && strval($section->parametrs->param336)=='Y'): ?>
                        <button type="button" class="buttonSend btnPreorder btn btn-default"><i class="fa fa-briefcase sign-preorder" aria-hidden="true"></i><span class="text-preorder"><?php echo $section->language->lang107 ?></span></button><!-- Lang -->
                    <?php endif; ?>
                    <button class="buttonSend addcart btn btn-default<?php if(!empty($record->incart)): ?> inCartActive<?php endif; ?>" title="<?php echo $section->language->lang022 ?>" <?php if(empty($record->maxcount)): ?><?php if(strval($section->parametrs->param233)=='Y'): ?>disabled<?php else: ?>style="display:none;"<?php endif; ?><?php endif; ?>><i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                    <?php if(strval($section->parametrs->param339)=='Y'): ?><a class="details btn btn-link" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $section->language->lang032 ?></a><?php endif; ?>
                </div>
                </div>
                <?php if(strval($section->parametrs->param332)=='Y'): ?>
                    <div class="addCount toggle-object">
                         <div class="count-title"><?php echo $section->language->lang108 ?></div><!-- Lang -->
                         <div class="count-inp-box">
                        <input class="cartscount" type="number" min="<?php echo $record->step ?>" name="addcartcount" step="<?php echo $record->step ?>" value="<?php echo $record->step ?>" size="4">
                        <span class="measure" <?php if(!empty($record->measure)): ?>style="padding-left:9px;"<?php endif; ?>><?php echo $record->measure ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>
        <?php echo $record->modifications ?>
        </div>
        <div class="bottom-box toggle-object">
             <?php if(strval($section->parametrs->param269)=='Y'): ?>
             <div class="blockCompare">
                <label title="<?php echo $section->language->lang095 ?>">
                    <input class="compare" type="checkbox" data-id="<?php echo $record->id ?>"<?php if(!empty($record->compare)): ?> checked<?php endif; ?>>
                    <span class="compareLabel"><span class="compare-stext"<?php if($record->compare!=0): ?> style="display:none;"<?php endif; ?>><?php echo $section->language->lang098 ?></span></span>
                </label>
                <a class="lnkInCompare" href="<?php echo seMultiDir()."/".$section->parametrs->param331."/" ?>" title="<?php echo $section->language->lang096 ?>"<?php if(empty($record->compare)): ?> style="display:none;"<?php endif; ?>><?php echo $section->language->lang097 ?></a>
                <a class="del-compare" href="<?php echo seMultiDir()."/".$section->parametrs->param331."/" ?>?clear_compare" title="<?php echo $section->language->lang112 ?>"<?php if(empty($record->compare)): ?> style="display:none;"<?php endif; ?>><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            </div>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param111)=='Y'): ?>
            <div class="objectPresence">
                <?php if($record->count != 0): ?><label class="presenceLabel"><?php echo $section->language->lang009 ?></label><?php endif; ?>
                <span class="presenceValue"><?php echo $record->count ?></span>
            </div>
            <?php endif; ?>
        </div>
        </div>
    </div>

<?php endforeach; ?>
