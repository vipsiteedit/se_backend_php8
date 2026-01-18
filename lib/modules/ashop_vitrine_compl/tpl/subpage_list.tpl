<?php foreach($section->objects as $record): ?>
    <div class="productItem">
         <div class="blockImage<?php if(strval($section->parametrs->param341)=='Y'): ?> blockImageBox<?php endif; ?>">
            <?php if(strval($section->parametrs->param327)=='Y'): ?>
                <!--button class="quickView" title="<?php echo $section->language->lang094 ?>" data-id="<?php echo $record->id ?>"><i class="fa fa-search" aria-hidden="true"></i></button-->
            <?php endif; ?>
            <a href="#" title="<?php echo $record->img_alt ?>" class="quickView" data-id="<?php echo $record->id ?>">
                <img class="objectImage img-responsive" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->img_alt ?>">
                <?php if($record->percent!=0): ?>
                        <span class="flag_discount" title="<?php echo $record->percent ?>%"><?php echo $record->percent ?>%</span>
                <?php endif; ?>
            </a>
        </div>
        <div class="price-info-box">
        <div class="blockInfo">
        <div class="objectTitle">
             <?php if($record->flag_new=='Y'): ?>
                        <span class="flag_new" title="<?php echo $section->language->lang050 ?>"><i class="fa fa-certificate" aria-hidden="true"><span class="text-flag-new"><?php echo $section->language->lang050 ?></span></i></span>
                <?php endif; ?>
             <?php if($record->flag_hit=='Y'): ?>
                        <span class="flag_hit" title="<?php echo $section->language->lang049 ?>"><i class="fa fa-sun-o" aria-hidden="true"><i class="fa fa-thumbs-up" aria-hidden="true"></i></i></span>
                <?php endif; ?>
            <?php if(strval($section->parametrs->param275)=='Y'): ?>
                <a class="textTitle" title="<?php echo $record->img_alt ?>" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $record->name ?></a>
            <?php else: ?>
                <div class="good-list-title"><span class="goodsname"><?php echo $record->name ?></span></div>
            <?php endif; ?>
        </div>
        <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div>
        <?php endif; ?>
        <!--div class="blockArticle">
            <?php if(!empty($record->article)): ?>
                #<span class="articleValue"><?php echo $record->article ?></span>
            <?php endif; ?>
        </div-->
        <!--div class="blockPresence">
            <span class="presenceValue"><?php echo $record->count ?></span>
        </div-->
        </div>
        <div class="priceBox">
            <form class="form_addCart" method="post" action="">
                  <div class="price-addcart-box">
                <div class="priceStyle">
                       <?php if(strval($section->parametrs->param113)=='Y' && $record->oldprice!=''): ?>
                                <span class="oldPrice hidden-xs"><?php echo $record->oldprice ?></span>
                        <?php endif; ?>
                        <span class="newPrice"><?php echo $record->newprice ?></span>
                </div>
                <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                <?php if($record->maxcount==0 && strval($section->parametrs->param336)=='Y'): ?>
                    <button type="button" class="buttonSend btnPreorder btn btn-default"><i class="fa fa-briefcase sign-preorder" aria-hidden="true"></i><span class="text-preorder"><?php echo $section->language->lang107 ?></span></button>
                <?php endif; ?>
                <input type="hidden" class="cartscount" name="addcartcount" data-step="<?php echo $record->step ?>" value="<?php echo $record->step ?>" size="3">
                <button class="buttonSend addcart btn btn-default btn-sm<?php if(!empty($record->incart)): ?> inCartActive<?php endif; ?>" title="<?php echo $section->language->lang022 ?>" <?php if(empty($record->maxcount)): ?><?php if(strval($section->parametrs->param233)=='Y'): ?>disabled<?php else: ?>style="display:none;"<?php endif; ?><?php endif; ?>><i class="fa fa-shopping-cart" aria-hidden="true"></i><label class="label-addcart"><?php echo $section->language->lang033 ?></label></button>
                <!--a class="details btn btn-link btn-sm" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $section->language->lang032 ?></a-->
                </div>
            </form>
        </div>
        </div>
    </div>

<?php endforeach; ?>
