<div class="partSpecial<?php echo $section->id ?> object b_special_simple-object blockAllItem swiper-slide <?php echo $prod->empty_class ?>">
        <div class="b_special_simple-labels">
        <?php if($prod->flag_hit=='Y'): ?>
            <span class="b_special_simple-label_hit" title="<?php echo $section->language->lang009 ?>"><?php echo $section->language->lang009 ?></span>
        <?php endif; ?>
        <?php if($prod->flag_new=='Y'): ?>
            <span class="b_special_simple-label_new" title="<?php echo $section->language->lang010 ?>"><?php echo $section->language->lang010 ?></span>
        <?php endif; ?>
        <?php if(!empty($prod->percent)): ?>
            <span class="b_special_simple-label_discount" title="<?php echo $prod->percent ?>%"><?php echo $prod->percent ?>%</span>
        <?php endif; ?>
        <?php if($prod->unsold=='Y'): ?>
            <span class="b_special_simple-label_price" title="<?php echo $section->language->lang011 ?>"><?php echo $section->language->lang011 ?></span>
        <?php endif; ?>
        </div>
        <?php if(strval($section->parametrs->param23)=='Y'): ?>
            <div class="blockGroup">
                <a href="<?php echo $prod->view_group ?>"><?php echo $prod->group_name ?></a>
            </div>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param22)=='Y'): ?>
            <div class="blockImage b_special_simple-object_image_area">
                <a href="<?php echo $prod->view_goods ?>" class="b_special_simple-object_image_link">
                    <img class="b_special_simple-object_image" title="<?php echo $prod->altname ?>" alt="<?php echo $prod->altname ?>" src="<?php echo $prod->image_prev ?>" >
                    <input type="hidden" name="addcart" value="<?php echo $prod->id ?>">
                </a>
            </div>
        <?php endif; ?>
        <div class="b_special_simple-object_content blockGoodsInfo">
        <?php if(strval($section->parametrs->param24)=='Y'): ?>
            <div class="blockTitle b_special_simple-object_title">
                <a class="b_special_simple-object_title_text" href="<?php echo $prod->view_goods ?>" title="<?php echo $prod->altname ?>"><?php echo $prod->name ?></a>
                <input type="hidden" name="addcart" value="<?php echo $prod->id ?>">
            </div>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param21)=='Y'): ?>
            <div class="blockRating" title="<?php echo $section->language->lang005 ?> <?php echo $prod->rating ?> <?php echo $section->language->lang006 ?> 5">
                <span class="ratioOff">
                    <span class="ratioOn" style="width:<?php echo $prod->ratio ?>%;"></span>
                </span>
            </div>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param25)=='Y'): ?>
            <div class="blockArticle">
                <label class="artName"><?php echo $section->language->lang001 ?></label>
                <span class="artValue"><?php echo $prod->article ?></span>
            </div>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param27)=='Y'): ?>
            <?php if(!empty($prod->note)): ?>
            <div class="blockNote b_special_simple-object_text">
                <span class="noteText"><?php echo $prod->note ?></span>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(strval($section->parametrs->param49)!='N'): ?>
            <?php if(!empty($prod->param_block)): ?>
                <?php echo $prod->param_block ?>
            <?php endif; ?>
        <?php endif; ?>
        <div class="b_special_simple-object_price_block">
        <?php if(strval($section->parametrs->param26)=='Y'): ?>
            <div class="blockPrice b_special_simple-object_price">
                <?php if(!empty($prod->old_price)): ?>
                    <span class="oldPrice b_special_simple-object_old_price"><?php echo $prod->old_price ?></span>
                <?php endif; ?>
                <span class="newPrice b_special_simple-object_newprice"><?php echo $prod->new_price ?></span>
            </div>
        <?php endif; ?>
        <div class="blockButton b_special_simple-object_button_area">
            <form method="post" class="form_addCart b_special_simple-object_form_add_to_cart">
                  <input type="hidden" name="addcart" value="<?php echo $prod->id ?>">
                  <button title="<?php echo $prod->btn_title ?>" class="buttonSend addcart b_special_simple-object_button_add_to_cart" <?php echo $prod->disabled ?>><?php echo $section->language->lang003 ?></button>
            </form>
        </div>
        </div>
        </div>
</div>
