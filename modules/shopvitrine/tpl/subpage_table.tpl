<?php foreach($section->objects as $record): ?>
    <div class="productItem col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="blockImage col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <?php if(strval($section->parametrs->param327)=='Y'): ?>
                <button class="quickView" title="<?php echo $section->language->lang094 ?>" data-id="<?php echo $record->id ?>"><?php echo $section->language->lang094 ?></button>
            <?php endif; ?>
            <a href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>" title="<?php echo $record->img_alt ?>">
                <img class="objectImage img-responsive" src="<?php echo $record->image_prev ?>" style="<?php echo $img_style ?>" alt="<?php echo $record->img_alt ?>">
            </a>
            <?php if($record->flag_hit=='Y'): ?>
                <span class="flag_hit" title="<?php echo $section->language->lang049 ?>"><?php echo $section->language->lang049 ?></span>
            <?php endif; ?>
            <?php if($record->flag_new=='Y'): ?>
                <span class="flag_new" title="<?php echo $section->language->lang050 ?>"><?php echo $section->language->lang050 ?></span>
            <?php endif; ?>
            <?php if($record->percent!=0): ?>
                <span class="flag_discount" title="<?php echo $record->percent ?>%"><?php echo $record->percent ?>%</span>
            <?php endif; ?>
            <?php if(!empty($record->labels)): ?>
                <?php $__list = 'labels'.$record->id; foreach($section->$__list as $label): ?>
                    <span class="flag-label label-<?php echo $label->code ?>" title="<?php echo $label->name ?>">
                        <?php if(!empty($label->image)): ?><img class="flag-label-image" src="<?php echo $label->image ?>" alt="<?php echo $label->name ?>""><?php endif; ?>
                        <span class="flag-label-text"><?php echo $label->name ?></span>
                    </span>
                
<?php endforeach; ?>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param269)=='Y'): ?>
              <div class="blockCompare">
                <label title="<?php echo $section->language->lang095 ?>">
                    <input class="compare" type="checkbox" data-id="<?php echo $record->id ?>"<?php if(!empty($record->compare)): ?> checked<?php endif; ?>>
                    <span class="compareLabel"><?php echo $section->language->lang098 ?></span>
                </label>
                <a class="lnkInCompare" href="<?php echo $__data->getPagePattern($section->parametrs->param331) ?>" title="<?php echo $section->language->lang096 ?>"<?php if(empty($record->compare)): ?> style="display:none;"<?php endif; ?>><?php echo $section->language->lang097 ?></a>
              </div>
            <?php endif; ?>
        </div>
        <div class="blockInfo col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <?php if(strval($section->parametrs->param275)=='Y'): ?>
               <h4 class="hidden-xs"><a class="textTitle" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $record->name ?></a></h4>
               <a class="textTitle hidden-lg hidden-md hidden-sm" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $record->name ?></a>
            <?php else: ?>
                <span class="textTitle"><?php echo $record->name ?></span>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param74)=='Y' ): ?>
                <?php if(!empty($record->note)): ?>
                    <div class="objectNote"><?php echo $record->note ?></div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param57)=='Y'): ?>
              <?php if(!empty($record->article)): ?>
                <div class="objectArticle">
                    <label class="articleLabel"><?php echo $section->language->lang005 ?></label>
                    <span class="articleValue"><?php echo $record->article ?></span>
                </div>
              <?php endif; ?>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param65)=='Y'): ?>
            <?php if(!empty($record->brand)): ?>
                <div class="objectBrand">
                    <label class="brandLabel"><?php echo $section->language->lang024 ?></label>
                    <span class="brandValue"><?php echo $record->brand ?></span>
                </div>
            <?php endif; ?>
            <?php if(strval($section->parametrs->param266)=='Y'): ?>
              <div class="objectRating">
                <label class="ratingLabel"><?php echo $section->language->lang057 ?></label>
                <span class="ratingOff" title="<?php echo $section->language->lang058 ?> <?php echo $record->rating ?> <?php echo $section->language->lang059 ?> 5">
                    <span class="ratingOn" style="width:<?php echo $record->rating_percent ?>%;"></span>
                </span>
                <span class="ratingValue"><?php echo $record->rating ?></span>
                <span class="marks">(<a class="marksLabel" href="<?php echo $record->linkshow ?>#reviews"><?php echo $section->language->lang056 ?></a> <span class="marksValue"><?php echo $record->marks ?></span>)</span>
              </div>
            <?php endif; ?>
          <?php endif; ?>
          <?php echo $record->modifications ?>
        </div>
        
        <div class="se_cart col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="priceBox">
                <div class="priceStyle<?php if(empty($record->realprice)): ?> nullPrice<?php endif; ?>">
                    <?php if(strval($section->parametrs->param226)=='Y'): ?>
                        <label class="priceLabel"><?php echo $section->language->lang008 ?></label>
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param113)=='Y' && $record->oldprice!=''): ?>
                        <span class="oldPrice"><?php echo $record->oldprice ?></span>
                    <?php endif; ?>
                    <span class="newPrice"><?php echo $record->newprice ?></span>
                </div>
            </div>
            <?php if(strval($section->parametrs->param55)=='Y'): ?>
                <div class="objectPresence">
                    <label class="presenceLabel"><?php echo $section->language->lang009 ?></label>
                    <span class="presenceValue"><?php echo $record->count ?></span>
                </div>
            <?php endif; ?>
        
            <form class="form_addCart" method="post" action="">
                <?php if(strval($section->parametrs->param205)=='Y'): ?>
                    <div class="addCount">
                        <input class="cartscount" type="number" min="<?php echo $record->step ?>" name="addcartcount" step="<?php echo $record->step ?>" value="<?php echo $record->step ?>" size="4">
                        <span class="measure"><?php echo $record->measure ?></span>
                    </div>
                <?php endif; ?>
                <div class="buttonBox">
                    <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                    <?php if($record->maxcount==0 && strval($section->parametrs->param336)=='Y'): ?>
                        <button type="button" class="buttonSend btnPreorder btn btn-default">Предзаказ</button>
                    <?php endif; ?>
                    <button class="buttonSend addcart btn btn-default<?php if(!empty($record->incart)): ?> inCartActive<?php endif; ?>" title="<?php echo $section->language->lang022 ?>" <?php if(empty($record->maxcount)): ?><?php if(strval($section->parametrs->param233)=='Y'): ?>disabled<?php else: ?>style="display:none;"<?php endif; ?><?php endif; ?>><i class="glyphicon glyphicon-shopping-cart"></i> <?php echo $section->language->lang033 ?></button>
                    <a class="details btn btn-link" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $section->language->lang032 ?></a>
                </div>
            </form>
         </div>
    </div>
<?php endforeach; ?>
