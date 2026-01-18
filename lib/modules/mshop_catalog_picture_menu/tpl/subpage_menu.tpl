<?php foreach($section->menulist as $menu): ?>
<?php if($menu->def==1): ?>
  <?php if($menu->show_type=='item'): ?>
      <?php if($menu->isparent==1): ?>
          <div class="submenu submenu<?php echo $menu->level ?> submenu_mu<?php echo $menu->parent ?> sub-sub" style="display:block;">
      <?php else: ?>
          <div class="submenu submenu<?php echo $menu->level ?> submenu_mu<?php echo $menu->parent ?> sub-sub" style="display:none;">
      <?php endif; ?>
  <?php else: ?>
     <?php $__list = 'menuend'.$menu->itemid; foreach($section->$__list as $record): ?>
       </div></div>
    
<?php endforeach; ?>
     </div>
  <?php endif; ?>
<?php endif; ?>
  <div data-id="<?php echo $menu->id ?>" data-code="<?php echo $menu->code ?>" data-mycount="<?php echo $menu->mycount ?>" 
            data-level="<?php echo $menu->level ?>" class="menuUnit menuUnit<?php echo $menu->level ?> menuUnit<?php echo $menu->id ?>" >
  <?php if(strval($section->parametrs->param10)=='Y'): ?>
      <?php if($menu->choose==1): ?>
         <a href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?>cat/<?php echo $menu->code ?>/" class="menu menu[menu.level'] menuActive">
      <?php else: ?>
         <a href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?>cat/<?php echo $menu->code ?>/" class="menu menu<?php echo $menu->level ?>">
      <?php endif; ?>
   <?php else: ?>
      <?php if($menu->choose==1): ?>
         <a href="<?php echo $__data->getLinkPageName() ?>cat/<?php echo $menu->code ?>/" class="menu menu<?php echo $menu->level ?> menuActive">
      <?php else: ?>
         <!--a href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?>cat/<?php echo $menu->code ?>/" class="menu menu<?php echo $menu->level ?>"-->
         <a href="<?php echo $__data->getLinkPageName() ?>cat/<?php echo $menu->code ?>/" class="menu menu<?php echo $menu->level ?>">
      <?php endif; ?>
   <?php endif; ?>
   <?php if($menu->showimg==1): ?>
      <img src="<?php echo $path_imggroup ?><?php echo $menu->image ?>">
   <?php endif; ?>
   <?php if($menu->txtsh==1): ?>
       <span class="span"><?php echo $menu->name ?>
       <?php if(strval($section->parametrs->param2)=='Y'&&((strval($section->parametrs->param13)!='Y'&&strval($section->parametrs->param10)=='Y')||strval($section->parametrs->param4)==0)): ?>
           <span>(<?php echo $menu->count ?>)</span>
       <?php endif; ?>
       </span>
   <?php endif; ?>
   </a>
<?php endforeach; ?>
<?php foreach($section->menuend as $record): ?>
  </div></div>

<?php endforeach; ?>
</div>
