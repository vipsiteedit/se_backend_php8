<repeat:menulist name=menu>
<if:[menu.def]==1>
  <if:[menu.show_type]=='item'>
      <if:[menu.isparent]==1>
          <div class="submenu submenu[menu.level] submenu_mu[menu.parent] sub-sub" style="display:block;">
      <else>
          <div class="submenu submenu[menu.level] submenu_mu[menu.parent] sub-sub" style="display:none;">
      </if>
  <else>
     <repeat:menuend[menu.itemid]>
       </div></div>
    </repeat:menuend[menu.itemid]>
     </div>
  </if>
</if>
  <div data-id="[menu.id]" data-code="[menu.code]" data-mycount="[menu.mycount]" 
            data-level="[menu.level]" class="menuUnit menuUnit[menu.level] menuUnit[menu.id]" >
  <if:[param10]=='Y'>
      <if:[menu.choose]==1>
         <a href="[param1].html<se>?</se>cat/[menu.code]/" class="menu menu[menu.level'] menuActive">
      <else>
         <a href="[param1].html<se>?</se>cat/[menu.code]/" class="menu menu[menu.level]">
      </if>
   <else>
      <if:[menu.choose]==1>
         <a href="[thispage.link]<se>?</se>cat/[menu.code]/" class="menu menu[menu.level] menuActive">
      <else>
         <!--a href="[param1].html<se>?</se>cat/[menu.code]/" class="menu menu[menu.level]"-->
         <a href="[thispage.link]<se>?</se>cat/[menu.code]/" class="menu menu[menu.level]">
      </if>
   </if>
   <if:[menu.showimg]==1>
      <img src="{$path_imggroup}[menu.image]">
   </if>
   <if:[menu.txtsh]==1>
       <span class="span">[menu.name]
       <if:([param2]=='Y'&&(([param13]!='Y'&&[param10]=='Y')||[param4]==0))>
           <span>([menu.count])</span>
       </if>
       </span>
   </if>
   </a>
</repeat:menulist>
<repeat:menuend>
  </div></div>
</repeat:menuend>
</div>
