<?php if($allcount!=0): ?>
    
 <div class="sticky_compare-control_block">
    <div class="sticky_compare-control_block__button_show">Фильтр</div>
    <div class="sticky_compare-control_block__area">  
        <?php if(!empty($categories_compare)): ?>
  <form method="get" action="" onchange="submit();" class="sticky_compare-control_item__category">
            <label class="sticky_compare-control_item__category_label"><?php echo $section->language->lang017 ?></label>
   <select name="category" class="sticky_compare-control_item__category_select">
    <?php foreach($section->categories as $cat): ?><option value="<?php echo $cat->id ?>" <?php if(!empty($cat->selected)): ?>selected<?php endif; ?>><?php echo $cat->name ?> (<?php echo $cat->count ?>)</option>
<?php endforeach; ?>
   </select>
  </form>
        <?php endif; ?>
   <label class="sticky_compare-control_item__difference">
    <input type="checkbox" class="sticky_compare-control_item__difference__checkbox">
    <span class="sticky_compare-control_item__difference__text"><?php echo $section->language->lang021 ?></span>
   </label>
  <div class="sticky_compare-control_item__remove_all">
   <a class="sticky_compare-control_item__remove_all__btn_remove_all" href="?clear_compare"><?php echo $section->language->lang022 ?></a>
  </div>
  </div>
 </div>
    
    
    <div class="sticky_compare-compare_items_block" >
            <div class="sticky_compare-compare_items__params__fixed_titles">
            
                <?php foreach($section->compare_test as $cm): ?>
     <div class="sticky_compare-group_param" data-id="<?php echo $cm->id ?>" data-diff="<?php echo $cm->diff ?>">
                        <?php if($cm->group!=""): ?>
          <?php if(!empty($cm->name)): ?>
                            <div class="sticky_compare-group_param__title">
            <div class="sticky_compare-group_param__title_text"><?php echo $cm->name ?></div>
          </div>
                            <?php endif; ?>
                        <?php else: ?> 
       <div class="sticky_compare-item_param">
        <div class="sticky_compare-item_param__title">
         <div class="sticky_compare-item_param__title_text"><?php echo $cm->name ?></div>
        </div>
       </div>
                        <?php endif; ?>
     </div>
    
<?php endforeach; ?>
            </div>
            
            <div class="sticky_compare-compare_items_area__wrapper">
            <div class="sticky_compare-compare_items_area">
            <div class="sticky_compare-compare_items__header__wrapper"> 
   <div class="sticky_compare-compare_items__header"> 
    <?php foreach($section->product as $pr): ?>
     <div class="sticky_compare-item_header" data-item-id="<?php echo $pr->id ?>">
      <div class="sticky_compare-item_description">
       <div class="sticky_compare-item_image_block">
        <img class="sticky_compare-item_image" src="<?php echo $pr->image ?>" alt="">
       </div>
       <div class="sticky_compare-item_title">
        <a class="sticky_compare-item_title_text" href="<?php echo $pr->link ?>" title="<?php echo $pr->name ?>"><?php echo $pr->name ?></a> 
       </div>
      </div>
      <form class="sticky_compare-item_control form_addCart" method="post" action="">
                            <input type="hidden" name="addcart" value="<?php echo $pr->id ?>">
       <button class="sticky_compare-item__button_buy buttonSend" title="<?php echo $section->language->lang025 ?>"><?php echo $section->language->lang026 ?></button>
       <a class="sticky_compare-item__button_remove" title="<?php echo $section->language->lang023 ?>" data-remove-id="<?php echo $pr->id ?>" href="?del_compare=<?php echo $pr->id ?>"><?php echo $section->language->lang024 ?></a>
      </form>
     </div>
    
<?php endforeach; ?>
   </div>
            </div>
   <div class="sticky_compare-compare_items__params">
                
    <?php foreach($section->compare_test as $cm): ?>
     <div class="sticky_compare-group_param" data-id="<?php echo $cm->id ?>" data-diff="<?php echo $cm->diff ?>">
                        <?php if($cm->group!=""): ?>
          <?php if(!empty($cm->name)): ?>
                            <div class="sticky_compare-group_param__title __fixed_element">
            <div class="sticky_compare-group_param__title_text"><?php echo $cm->name ?></div>
          </div>
                            <?php endif; ?>
                        <?php else: ?> 
       <div class="sticky_compare-item_param">
        <div class="sticky_compare-item_param__title __fixed_element">
         <div class="sticky_compare-item_param__title_text"><?php echo $cm->name ?></div>
        </div>
        <div class="sticky_compare-item_param__value_list">
         <?php $__list = 'features'.$cm->id; foreach($section->$__list as $ft): ?>
         <div class="sticky_compare-item_param__value" data-item-id="<?php echo $ft->id ?>">
          <div class="sticky_compare-item_param__value_text"><?php if($ft->val!=""): ?><?php echo $ft->val ?><?php else: ?><?php echo $section->language->lang039 ?><?php endif; ?></div>
         </div>
         
<?php endforeach; ?>
        </div>
       </div>
                        <?php endif; ?>
     </div>
    <?php endforeach; ?>
   </div>
            
                </div>
            </div>
 </div>
    
<?php else: ?>
    <div class="sticky_compare-empty_list">
  <div class="sticky_compare-empty_list__title"><?php echo $section->language->lang018 ?></div>
  <a class="sticky_compare-empty_list__goto_catalog" href="<?php echo seMultiDir()."/".$section->parametrs->param3."/" ?>"><?php echo $section->language->lang019 ?></a>
 </div>
    
<?php endif; ?>
