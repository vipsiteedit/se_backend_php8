<header:css>
[include_css]
</header:css>
<section id="section-head-menu"  data-semenu="<?php echo $section->parametrs->param16 ?>">
<div class="<?php if(strval($section->parametrs->param17)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>">
<nav class="topmenucollapse hidden-lg hidden-md button_menu" data-toggle="collapse" data-target="#bs-menu-collapse">
    <span class="title-button-menu"><?php echo $section->language->lang001 ?></span>
    <div class="nav-button">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </div>
</nav>
<div id="bs-menu-collapse" class="row collapse">
    <nav class="headerCatalog">
       <? if(function_exists('getItemMenu')){ list($menuitems) = getItemMenu($section->parametrs->param16); $__data->setList($section,'menuitems', $menuitems);} ?>
       <ul class="groupList">
       <?php foreach($section->menuitems as $item): ?>
           <li class="headerCatalogItem horiz <?php if($item->name==strval($section->parametrs->param1)): ?>catalog<?php endif; ?>" data-id="<?php echo $item->name ?>">
               <a href="<?php echo $item->url ?>" class="headerCatalogSubItem <?php if($_page==$item->name): ?>headerCatalogSubItem__active <?php endif; ?>headerCatalogSubSection headerCatalogSubNormal">
               <span class="text">
                  <?php if(!empty($item->image)): ?>
                     <span class="item-img"><img class="catalogPromoIcon" src="<?php echo $item->image ?>" alt=""></span>
                  <?php endif; ?> 
                  <span class='item-title'><?php echo $item->title ?></span>
               </span>
               <span class="headerCatalogNib"></span>
               <?php if(($item->name==strval($section->parametrs->param1)) || ($item->items)): ?>
               <div class="arrow-down" style=""></div></a>
               <div class="col-xs-12 headerCatalogSub" style="display:none">
                    <div class="catWindow">
                        <?php if(file_exists($__MDL_ROOT."/php/subpage_menu.php")) include $__MDL_ROOT."/php/subpage_menu.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_menu.tpl")) include $__data->include_tpl($section, "subpage_menu"); ?>
                    </div>
               </div>
               <?php else: ?>
               </a>
               <?php endif; ?>
           </li>
       
<?php endforeach; ?>
       </ul>
       
       
    </nav>
    </div>
</div>
</section>
