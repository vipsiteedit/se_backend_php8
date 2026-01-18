<div class="partSpecial<?php echo $section->id ?> object blockAllItem <?php echo $record->empty_class ?>"> 
    <div class="blockGoodsInfo">               
        <?php if($section->parametrs->param21=='Y'): ?>
            <div class="blockRating" title="<?php echo $section->parametrs->param28 ?> <?php echo $record->rating ?><?php echo $section->parametrs->param47 ?>5">
                <div class="ratioOff" style="">
                    <div class="ratioOn" style="width:<?php echo $record->ratio ?>%;">&nbsp;</div> 
                </div>
            </div>
        <?php endif; ?>  
        
        <?php if($section->parametrs->param23=='Y'): ?>
            <div class="blockGroup">
                <a href="<?php echo $record->view_group ?>"><?php echo $record->group_name ?></a>
            </div>
        <?php endif; ?>
        
        <?php if($section->parametrs->param22=='Y'): ?>
            <div class="blockImage" style="">
                <a href="<?php echo $record->view_goods ?>">
                    <img border="0" style="" title="<?php echo $record->name ?>" alt="<?php echo $record->name ?>" src="<?php echo $record->image_prev ?>">
                    <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                </a>
            </div>
        <?php endif; ?> 
                    
        <?php if($section->parametrs->param25=='Y'): ?>
            <div class="blockArticle">
                <span class="artName"><?php echo $section->parametrs->param7 ?></span> 
                <span class="artValue"><?php echo $record->article ?></span> 
            </div>
        <?php endif; ?> 
        
        <?php if($section->parametrs->param24=='Y'): ?>
            <div class="blockTitle">
                <a href="<?php echo $record->view_goods ?>" title="<?php echo $record->name ?>"><?php echo $record->name ?></a>
                <input type="hidden" name="addcart" value="<?php echo $record->id ?>"> 
            </div>
        <?php endif; ?> 
                    
        <?php if($section->parametrs->param27=='Y'): ?>
            <div class="blockNote">
                <span class="noteText"><?php echo $record->note ?></span>
            </div>
        <?php endif; ?> 
        <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
        <?php if($section->parametrs->param26=='Y'): ?>
            <div class="blockPrice">
                <span class="titlePrice"><?php echo $section->parametrs->param17 ?></span>
                <span class="oldPrice"><?php echo $record->old_price ?></span> 
                <span class="newPrice"><?php echo $record->new_price ?></span> 
            </div>
        <?php endif; ?> 
    </div> 
    
    <div class="blockButton">
        <form style="margin:0;" method="post" class="form_addCart">
            <input type="hidden" size="3" name="addcart" value="<?php echo $record->id ?>"> 
            <input title="<?php echo $record->btn_title ?>" class="buttonSend butAddCart" name="subcart" type="submit" value="<?php echo $section->parametrs->param19 ?>" <?php echo $record->disabled ?>> 
        </form> 
        <a href="<?php echo $record->view_goods ?>" class="buttonSend goShowGoods"><?php echo $section->parametrs->param1 ?></a>
    </div>     
</div>
