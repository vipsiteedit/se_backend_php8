<?php foreach($section->objects as $record): ?>
    <div class="productItem">
        <h4 class="objectTitle">
            <?php if(strval($section->parametrs->param275)=='Y'): ?>
                <a class="textTitle" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $record->name ?></a>
            <?php else: ?>
                <span class="textTitle"><?php echo $record->name ?></span>
            <?php endif; ?>
        </h4>
        <div class="blockImage"> 
            <?php if(strval($section->parametrs->param327)=='Y'): ?>
                <button class="quickView" title="<?php echo $section->language->lang094 ?>" data-id="<?php echo $record->id ?>"><?php echo $section->language->lang094 ?></button>
            <?php endif; ?>
            <a href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>" title="<?php echo $record->img_alt ?>">
                <img class="objectImage" src="<?php echo $record->image_prev ?>" style="<?php echo $img_style ?>" alt="<?php echo $record->img_alt ?>">
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
            <?php if($record->unsold=='Y'): ?>
                <span class="user_price" title="<?php echo $section->language->lang051 ?>"><?php echo $section->language->lang051 ?></span>
            <?php endif; ?>
        </div>
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
        <?php if(strval($section->parametrs->param114)=='Y' ): ?>
            <?php if(!empty($record->note)): ?>
                <div class="objectNote"><?php echo $record->note ?></div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param83)=='Y'): ?>
            <?php if(!empty($record->article)): ?>
                <div class="objectArticle">
                    <label class="articleLabel"><?php echo $section->language->lang005 ?></label>
                    <span class="articleValue"><?php echo $record->article ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param84)=='Y'): ?>
            <?php if(!empty($record->brand)): ?>
                <div class="objectBrand">
                    <label class="brandLabel"><?php echo $section->language->lang024 ?></label>
                    <span class="brandValue"><?php echo $record->brand ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param111)=='Y'): ?>                       
            <div class="objectPresence">
                <label class="presenceLabel"><?php echo $section->language->lang009 ?></label>
                <span class="presenceValue"><?php echo $record->count ?></span>
            </div>
        <?php endif; ?>
        <?php echo $record->modifications ?>
        <?php if(strval($section->parametrs->param269)=='Y'): ?>
            <div class="blockCompare">
                <label title="<?php echo $section->language->lang095 ?>">
                    <input class="compare" type="checkbox" data-id="<?php echo $record->id ?>"<?php if(!empty($record->compare)): ?> checked<?php endif; ?>>
                    <span class="compareLabel"><?php echo $section->language->lang098 ?></span>
                </label>
            </div>
        <?php endif; ?>
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
            <form class="form_addCart" style="margin:0px;" method="post" action="">
                <div class="buttonBox">
                    <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                    <button class="buttonSend addcart" title="<?php echo $section->language->lang022 ?>" <?php if(empty($record->maxcount)): ?><?php if(strval($section->parametrs->param233)=='Y'): ?>disabled<?php else: ?>style="display:none;"<?php endif; ?><?php endif; ?>><?php echo $section->language->lang033 ?></button>
                    <a class="details" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $section->language->lang032 ?></a>
                </div>                            
            </form>
        </div>
    </div>

<?php endforeach; ?>
