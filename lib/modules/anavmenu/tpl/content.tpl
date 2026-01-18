<nav class="navmenu navmenu-default part<?php echo $section->id ?>" <?php echo $section->style ?>>
<? if(function_exists('getItemMenu')){ list($menuitems) = getItemMenu($section->parametrs->param1); $__data->setList($section,'menuitems', $menuitems);} ?>
<div class="block-categories row hidden-xs hidden-sm">
     <ul>
     <?php foreach($section->menuitems as $item): ?>
         <li class="head-categories col-lg-3">
            <?php if(!empty($item->image)): ?><img src="<?php echo $item->image ?>"><?php endif; ?>
            <?php if($item->url=='#'): ?>
            <span class="linkobject-img"><?php echo $item->title ?></span>
            <?php else: ?>
            <a class="linkobject-img" href="<?php echo $item->url ?>"><?php echo $item->title ?></a>
            <?php endif; ?>
            <?php if($item->items): ?>
            <ul>
            <?php foreach($item->items as $sitem): ?>
                <li>
                <?php if(!empty($sitem->image)): ?><img src="<?php echo $sitem->image ?>"><?php endif; ?>
                <a class="linkobjectImg" href="<?php echo $sitem->url ?>"><?php echo $sitem->title ?></a>
                <?php if($sitem->items): ?>
                <ul>
                <?php foreach($sitem->items as $ssitem): ?>
                   <li>
                   <a class="linkobject-img" href="<?php echo $ssitem->url ?>"><?php echo $ssitem->title ?></a>
                   </li>
                
<?php endforeach; ?>
                </ul><?php endif; ?>
                </li>
            
<?php endforeach; ?>
            </ul><?php endif; ?>
         </li>
     
<?php endforeach; ?>
     </ul>
</div>

</nav>
