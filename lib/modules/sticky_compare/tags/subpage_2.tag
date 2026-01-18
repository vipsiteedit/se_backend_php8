<serv><if:{$allcount}!=0></serv>
    
 <div class="sticky_compare-control_block">
    <div class="sticky_compare-control_block__button_show">Фильтр</div>
    <div class="sticky_compare-control_block__area">  
        <noempty:{$categories_compare}>
  <form method="get" action="" onchange="submit();" class="sticky_compare-control_item__category">
            <label class="sticky_compare-control_item__category_label">[lang017]</label>
   <select name="category" class="sticky_compare-control_item__category_select">
    <repeat:categories name=cat><option value="[cat.id]" <noempty:[cat.selected]>selected</noempty>>[cat.name] ([cat.count])</option></repeat:categories>
   </select>
  </form>
        </noempty>
   <label class="sticky_compare-control_item__difference">
    <input type="checkbox" class="sticky_compare-control_item__difference__checkbox">
    <span class="sticky_compare-control_item__difference__text">[lang021]</span>
   </label>
  <div class="sticky_compare-control_item__remove_all">
   <a class="sticky_compare-control_item__remove_all__btn_remove_all" href="<serv>?clear_compare</serv><se>#</se>">[lang022]</a>
  </div>
  </div>
 </div>
    
    
    <div class="sticky_compare-compare_items_block" >
            <div class="sticky_compare-compare_items__params__fixed_titles">
            <se>{$separams_fixed}</se>
                <repeat:compare_test name=cm>
     <div class="sticky_compare-group_param" data-id="[cm.id]" data-diff="[cm.diff]">
                        <if:[cm.group]!="">
          <noempty:[cm.name]>
                            <div class="sticky_compare-group_param__title">
            <div class="sticky_compare-group_param__title_text">[cm.name]</div>
          </div>
                            </noempty>
                        <else> 
       <div class="sticky_compare-item_param">
        <div class="sticky_compare-item_param__title">
         <div class="sticky_compare-item_param__title_text">[cm.name]</div>
        </div>
       </div>
                        </if>
     </div>
    </repeat:compare_test>
            </div>
            
            <div class="sticky_compare-compare_items_area__wrapper">
            <div class="sticky_compare-compare_items_area">
            <div class="sticky_compare-compare_items__header__wrapper"> 
   <div class="sticky_compare-compare_items__header"> 
    <repeat:product name=pr>
     <div class="sticky_compare-item_header" data-item-id="[pr.id]">
      <div class="sticky_compare-item_description">
       <div class="sticky_compare-item_image_block">
        <img class="sticky_compare-item_image" src="[pr.image]" alt="">
       </div>
       <div class="sticky_compare-item_title">
        <a class="sticky_compare-item_title_text" href="[pr.link]" title="[pr.name]">[pr.name]</a> 
       </div>
      </div>
      <form class="sticky_compare-item_control form_addCart" method="post" action="<se>[thispage.link]</se>">
                            <input type="hidden" name="addcart" value="[pr.id]">
       <button class="sticky_compare-item__button_buy buttonSend" title="[lang025]">[lang026]</button>
       <a class="sticky_compare-item__button_remove" title="[lang023]" data-remove-id="[pr.id]" href="<serv>?del_compare=[pr.id]</serv><se>#</se>">[lang024]</a>
      </form>
     </div>
    </repeat:product>
   </div>
            </div>
   <div class="sticky_compare-compare_items__params">
                <se>{$separams}</se>
    <repeat:compare_test name=cm>
     <div class="sticky_compare-group_param" data-id="[cm.id]" data-diff="[cm.diff]">
                        <if:[cm.group]!="">
          <noempty:[cm.name]>
                            <div class="sticky_compare-group_param__title __fixed_element">
            <div class="sticky_compare-group_param__title_text">[cm.name]</div>
          </div>
                            </noempty>
                        <else> 
       <div class="sticky_compare-item_param">
        <div class="sticky_compare-item_param__title __fixed_element">
         <div class="sticky_compare-item_param__title_text">[cm.name]</div>
        </div>
        <div class="sticky_compare-item_param__value_list">
         <repeat:features[cm.id] name=ft>
         <div class="sticky_compare-item_param__value" data-item-id="[ft.id]">
          <div class="sticky_compare-item_param__value_text"><if:[ft.val]!="">[ft.val]<else>[lang039]</if></div>
         </div>
         </repeat:features[cm.id]>
        </div>
       </div>
                        </if>
     </div>
    </repeat:compare_test>
   </div>
            
                </div>
            </div>
 </div>
    
<serv><else></serv>
    <div class="sticky_compare-empty_list">
  <div class="sticky_compare-empty_list__title">[lang018]</div>
  <a class="sticky_compare-empty_list__goto_catalog" href="[param3].html">[lang019]</a>
 </div>
    
<serv></if></serv>
