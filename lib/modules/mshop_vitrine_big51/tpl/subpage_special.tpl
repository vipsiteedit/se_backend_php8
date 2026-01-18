<div class="specialProducts">
<?php foreach($section->objects as $record): ?>
    <div class="specialItem" style="position:relative;">
        <?php if($record->flag_hit=='Y'): ?>
            <span class="flag_hit" title="<?php echo $section->language->lang049 ?>"><?php echo $section->language->lang049 ?></span>
        <?php endif; ?>
        <?php if($record->flag_new=='Y'): ?>
            <span class="flag_new" title="<?php echo $section->language->lang050 ?>"><?php echo $section->language->lang050 ?></span>
        <?php endif; ?>
        <?php if(!empty($record->percent)): ?>
            <span class="flag_discount" title="<?php echo $record->percent ?>%"><?php echo $record->percent ?>%</span>
        <?php endif; ?>
        <?php if($record->unsold=='Y'): ?>
            <span class="user_price" title="<?php echo $section->language->lang051 ?>"><?php echo $section->language->lang051 ?></span>
        <?php endif; ?>
        <div class="specialImage"> 
            <a href="<?php echo $record->linkshow ?>" title="<?php echo $record->name ?>">
                <img class="objectImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->img_alt ?>">
            </a>
        </div>
        <div class="specialTitle">
            <?php if(strval($section->parametrs->param275)=='Y'): ?>
                <a class="textTitle" href="<?php echo $record->linkshow ?>" title="<?php echo $record->name ?>"><?php echo $record->name ?></a>
            <?php else: ?>
                <span class="textTitle" title="<?php echo $record->name ?>"><?php echo $record->name ?></span>
            <?php endif; ?>
        </div>
        <div class="specialRating">
            <span class="ratingOff" title="<?php echo $section->language->lang058 ?> <?php echo $record->rating ?> <?php echo $section->language->lang059 ?> 5">
                <span class="ratingOn" style="width:<?php echo $record->rating_percent ?>%;"></span>
            </span>
            <span class="ratingValue"><?php echo $record->rating ?></span>
            <span class="marks">(<label class="marksLabel"><?php echo $section->language->lang056 ?></label> <span class="marksValue"><?php echo $record->marks ?></span>)</span>
        </div>
        <div class="specialPrice">
            <?php if(strval($section->parametrs->param113)=='Y' && $record->oldprice!=''): ?>
                <span class="oldPrice"><?php echo $record->oldprice ?></span>
            <?php endif; ?>
            <span class="newPrice"><?php echo $record->newprice ?></span>
        </div>
        <div class="specialButton">
            <form class="form_addCart" style="margin:0px;" method="post" action="">
                <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                <button class="buttonSend addcart" title="<?php echo $section->language->lang022 ?>" <?php if(empty($record->maxcount)): ?>disabled<?php endif; ?>><?php echo $section->language->lang033 ?></button>
                <a class="details" href="<?php echo $record->linkshow ?>"><?php echo $section->language->lang032 ?></a>
            </form>
        </div>                                
    </div>

<?php endforeach; ?>
</div>
