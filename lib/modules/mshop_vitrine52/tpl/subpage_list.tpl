<?php foreach($section->objects as $record): ?>
    <div class="productItem row">
        <div class="blockName col-lg-5 col-md-5 col-sm-5 col-xs-6">
            <?php if(strval($section->parametrs->param275)=='Y'): ?>
                <a class="goodsname" title="<?php echo $record->img_alt ?>" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $record->name ?></a>
            <?php else: ?>
                <span class="goodsname"><?php echo $record->name ?></span>
            <?php endif; ?>
        </div>
        <div class="blockArticle col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <?php if(!empty($record->article)): ?>
                #<span class="articleValue"><?php echo $record->article ?></span>
            <?php endif; ?>
        </div>
        <div class="blockPresence col-lg-1 col-md-1 col-sm-1 hidden-xs">
            <span class="presenceValue"><?php echo $record->count ?></span>
        </div>
        <div class="blockPrice col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <?php if(strval($section->parametrs->param113)=='Y' && $record->oldprice!=''): ?>
                <span class="oldPrice hidden-xs"><?php echo $record->oldprice ?></span>
            <?php endif; ?>
            <span class="newPrice"><?php echo $record->newprice ?></span>
        </div>
        <div class="blockAddCart col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <form class="form_addCart" method="post" action="">    
                <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                <?php if($record->maxcount==0 && strval($section->parametrs->param336)=='Y'): ?>
                    <button type="button" class="buttonSend btnPreorder btn btn-default">Предзаказ</button>
                <?php endif; ?>
                <input type="hidden" class="cartscount" name="addcartcount" data-step="<?php echo $record->step ?>" value="<?php echo $record->step ?>" size="3">
                <button class="buttonSend addcart btn btn-default btn-sm<?php if(!empty($record->incart)): ?> inCartActive<?php endif; ?>" title="<?php echo $section->language->lang022 ?>" <?php if(empty($record->maxcount)): ?><?php if(strval($section->parametrs->param233)=='Y'): ?>disabled<?php else: ?>style="display:none;"<?php endif; ?><?php endif; ?>><i class="glyphicon glyphicon-shopping-cart"></i> <?php echo $section->language->lang033 ?></button>
                <a class="details btn btn-link btn-sm" href="<?php echo $record->linkshow ?><?php echo $section->parametrs->param330 ?>"><?php echo $section->language->lang032 ?></a>
            </form>
        </div>
    </div>

<?php endforeach; ?>
