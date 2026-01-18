<div class="partSpecial<?php echo $section->id ?> object blockAllItem <?php if(empty($record->count)): ?> emptyGoods<?php endif; ?>"> 
    <div class="blockGoodsInfo"">                    
        <?php if($record->flag_hit=='Y'): ?>
            <span class="flag_hit" title="<?php echo $section->language->lang009 ?>"><?php echo $section->language->lang009 ?></span>
        <?php endif; ?>
        <?php if($record->flag_new=='Y'): ?>
            <span class="flag_new" title="<?php echo $section->language->lang010 ?>"><?php echo $section->language->lang010 ?></span>
        <?php endif; ?>
        <?php if(!empty($record->percent)): ?>
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
        <?php if(strval($section->parametrs->param23)=='Y'): ?>
            <div class="blockGroup">
                <a href="<?php echo $record->view_group ?>"><?php echo $record->group_name ?></a>
            </div>
        <?php endif; ?>  
        <?php if(strval($section->parametrs->param22)=='Y'): ?>
            <div class="blockImage">
                <a href="<?php echo $record->view_goods ?>">
                    <img class="img-responsive center-block" title="<?php echo $record->name ?>" alt="<?php echo $record->name ?>" src="<?php echo $record->image_prev ?>" style="<?php echo $img_style ?>">
                    <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                </a>
            </div>
        <?php endif; ?>             
        <?php if(strval($section->parametrs->param24)=='Y'): ?>
            <div class="blockTitle">
                <a href="<?php echo $record->view_goods ?>" title="<?php echo $record->name ?>"><?php echo $record->name ?></a>
                <input type="hidden" name="addcart" value="<?php echo $record->id ?>"> 
            </div>
        <?php endif; ?> 
        <?php if(strval($section->parametrs->param21)=='Y'): ?>
            <div class="blockRating" title="<?php echo $section->language->lang005 ?> <?php echo $record->rating ?> <?php echo $section->language->lang006 ?> 5">
                <span class="ratioOff">
                    <span class="ratioOn" style="width:<?php echo $record->ratio ?>%;"></span> 
                </span>
            </div>
        <?php endif; ?> 
        <?php if(strval($section->parametrs->param25)=='Y'): ?>
            <div class="blockArticle">
                <label class="artName"><?php echo $section->language->lang001 ?></label> 
                <span class="artValue"><?php echo $record->article ?></span> 
            </div>
        <?php endif; ?>           
        <?php if(strval($section->parametrs->param27)=='Y'): ?>
            <?php if(!empty($record->note)): ?>
            <div class="blockNote">
                <span class="noteText"><?php echo $record->note ?></span>
            </div>
            <?php endif; ?>
        <?php endif; ?> 
        <?php if(strval($section->parametrs->param49)!='N'): ?>
            <?php if(!empty($record->param_block)): ?>
                <?php echo $record->param_block ?>
            <?php endif; ?>    
        <?php endif; ?>
        <?php if(strval($section->parametrs->param26)=='Y'): ?>
            <div class="blockPrice">
                <label class="titlePrice"><?php echo $section->language->lang002 ?></label>
                <?php if(!empty($record->old_price)): ?>
                    <span class="oldPrice"><?php echo $record->old_price ?></span> 
                <?php endif; ?>
                <span class="newPrice"><?php echo $record->new_price ?></span> 
            </div>
        <?php endif; ?> 
    </div>   
    <div class="blockButton">
        <form style="margin:0;" method="post" class="form_addCart">
            <input type="hidden" name="addcart" value="<?php echo $record->id ?>"> 
            <button title="<?php echo $record->btn_title ?>" class="buttonSend addcart btn btn-default" <?php echo $record->disabled ?>><i class="glyphicon glyphicon-shopping-cart"></i> <?php echo $section->language->lang003 ?></button>
        </form> 
        <span title=""></span>
        <a href="<?php echo $record->view_goods ?>" class="goShowGoods"><?php echo $section->language->lang004 ?></a>
    </div>     
</div>
