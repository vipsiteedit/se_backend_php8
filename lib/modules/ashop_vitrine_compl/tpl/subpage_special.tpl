<div class="specialProducts">
<?php foreach($section->objects as $record): ?>
    <div class="specialItem" style="position:relative;">
        <div class="specialImage"> 
            <a href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>" title="<?php echo $record->name ?>">
                <img class="objectImage" src="<?php echo $record->image_prev ?>" style="<?php echo $img_style ?>" alt="<?php echo $record->img_alt ?>">
            </a>
            <div class="blockFlag">
                <?php if($record->flag_new=='Y'): ?>
                    <span class="flag_new" title="<?php echo $section->language->lang050 ?>"><i class="fa fa-certificate" aria-hidden="true"><span class="text-flag-new"><?php echo $section->language->lang050 ?></span></i></span>
                <?php endif; ?>
                <?php if($record->flag_hit=='Y'): ?>
                    <span class="flag_hit" title="<?php echo $section->language->lang049 ?>"><i class="fa fa-sun-o" aria-hidden="true"><i class="fa fa-thumbs-up" aria-hidden="true"></i></i></span>
                <?php endif; ?>
            </div>
            <?php if(!empty($record->percent)): ?>
            <span class="flag_discount" title="<?php echo $record->percent ?>%"><?php echo $record->percent ?>%</span>
            <?php endif; ?>
        </div>
        <div class="specialRating objectRating">
                <span class="ratingOff" title="<?php echo $section->language->lang058 ?> <?php echo $record->rating ?> <?php echo $section->language->lang059 ?> 5">
                    <span class="ratingOn" style="width:<?php echo $record->rating_percent ?>%;"></span>
                </span>
                <span class="ratingValue"><?php echo $record->rating ?></span>
                <span class="marks">(<a class="marksLabel" href="<?php echo $record->linkshow ?>#reviews"><?php echo $section->language->lang056 ?></a> <span class="marksValue"><?php echo $record->marks ?></span>)</span>
        </div>
        <div class="specialTitle">
            <?php if(strval($section->parametrs->param275)=='Y'): ?>
                <a class="textTitle" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>" title="<?php echo $record->name ?>"><?php echo $record->name ?></a>
            <?php else: ?>
                <span class="textTitle" title="<?php echo $record->name ?>"><?php echo $record->name ?></span>
            <?php endif; ?>
        </div>
        <div class="priceBox priceBoxSpecial">
            <form class="form_addCart" style="margin:0px;" method="post" action="">
            <div class="priceStyle specialTitle">
                <?php if(strval($section->parametrs->param113)=='Y' && $record->oldprice!=''): ?>
                    <span class="oldPrice"><?php echo $record->oldprice ?></span>
                <?php endif; ?>
                <span class="newPrice"><?php echo $record->newprice ?></span>
            </div>
            <div class="buttonBox specialButton">
                <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                <?php if($record->maxcount==0 && strval($section->parametrs->param336)=='Y'): ?>
                    <button type="button" class="buttonSend btnPreorder btn btn-default"><i class="fa fa-briefcase sign-preorder" aria-hidden="true"></i><span class="text-preorder"><?php echo $section->language->lang107 ?></span></button>
                <?php endif; ?>
                <button class="buttonSend addcart btn btn-default<?php if(!empty($record->incart)): ?> inCartActive<?php endif; ?>" title="<?php echo $section->language->lang022 ?>" <?php if(empty($record->maxcount)): ?><?php if(strval($section->parametrs->param233)=='Y'): ?>disabled<?php else: ?>style="display:none;"<?php endif; ?><?php endif; ?>><i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                <?php if(strval($section->parametrs->param339)=='Y'): ?><a class="details" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $section->language->lang032 ?></a><?php endif; ?>
            </div>
            </form>
        </div>                                
    </div>

<?php endforeach; ?>
</div>
