<footer:js>
[js:jquery/jquery.min.js]
<serv>
[include_js({moduleurl:'[module_url]'})]
</serv>
</footer:js>
<section id="section-head-menu" [contedit] data-semenu="[param16]">
<div class="<if:[param17]=='n'>container<else>container-fluid</if>">
<nav class="topmenucollapse hidden-lg hidden-md button_menu clearfix" data-toggle="collapse" data-target="#bs-menu-collapse">
    <span class="title-button-menu">[lang001]</span>
    <div class="nav-button">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </div>
</nav>
<div id="bs-menu-collapse" class="row collapse">
    <nav class="headerCatalog">
       <createmenu:item-[param16],[param18],[param19]>
       <ul class="groupList">
       <repeat:menuitems name=item>
           <li class="headerCatalogItem horiz <if:[item.name]=='[param1]'>catalog</if>" data-id="[item.name]">
               <a href="[item.url]" class="headerCatalogSubItem <if:[thispage.name]==[item.name]>headerCatalogSubItem__active </if>headerCatalogSubSection headerCatalogSubNormal">
               <span class="text <if:([param7]=='1' || [item.image]=='')>noimg</if>">
                  <if:([param7]=='2' || [param7]=='3')>
                      <noempty:item.image>
                         <span class="item-img"><img class="catalogPromoIcon" src="[item.image]" alt=""></span>
                      </noempty> 
                  </if>
                  <span class='item-title'>[item.title]</span> 
                  <if:(([item.name]==[param1]) || ([item.items]))>
                    <div class="arrow-down" style=""></div>
                  </if>
               </span>
               <span class="headerCatalogNib"></span>
               <if:(([item.name]==[param1]) || ([item.items]))>
               </a>
               <div class="col-xs-12 headerCatalogSub" style="display:none">
                    <div class="catWindow">
                         <if:[item.name]=='[param1]'>
                         <se>
                             [subpage name=cat]
                         </se>
                         <else>
                             [subpage name=menu]
                         </if>
                    </div>
               </div>
               <else>
               </a>
               </if>
           </li>
       </repeat:menuitems>
       </ul>
       <se><emptymenu><div class="emptymenyblock"><span>Добавьте пункты меню</span> <i class="sesys-icon menu" data-semenu="[param16]"></i></div></emptymenu></se>
       </createmenu>
    </nav>
    </div>
</div>
</section>
