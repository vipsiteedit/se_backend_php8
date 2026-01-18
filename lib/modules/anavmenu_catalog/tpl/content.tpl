<footer:js>
[js:jquery/jquery.min.js]
[include_js()]
[include_css]
</footer:js>
<nav class="topmenucollapse hidden-lg hidden-md button_menu" data-toggle="collapse" data-target="#bs-menu-collapse">
    <span class="title-button-menu"><?php echo $section->language->lang001 ?></span>
    <div class="nav-button">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </div>
</nav>
<div id="bs-menu-collapse" class="collapse" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>">
    <nav class="headerCatalog col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <? if(function_exists('getItemMenu')){ list($menuitems) = getItemMenu($section->parametrs->param16); $__data->setList($section,'menuitems', $menuitems);} ?>
       <ul class="groupList">
       <?php foreach($section->menuitems as $item): ?>
           <li class="headerCatalogItem horiz <?php if($item->name==strval($section->parametrs->param1)): ?>catalog<?php endif; ?>" data-id="<?php echo $item->name ?>">
               <a href="<?php echo $item->url ?>" class="headerCatalogSubItem headerCatalogSubSection headerCatalogSubNormal">
               <span class="text"><?php if(!empty($item->image)): ?><img class="catalogPromoIcon" src="<?php echo $item->image ?>" alt=""><?php endif; ?> <?php echo $item->title ?></span>
               <span class="headerCatalogNib"></span></a>
               <?php if(($item->name==strval($section->parametrs->param1)) || ($item->items)): ?>
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 headerCatalogSub" style="display:none">
                    <div class="catWindow">
                         <?php if($item->name==strval($section->parametrs->param1)): ?>
                         
                         <?php else: ?>
                             <?php if(file_exists($__MDL_ROOT."/php/subpage_menu.php")) include $__MDL_ROOT."/php/subpage_menu.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_menu.tpl")) include $__data->include_tpl($section, "subpage_menu"); ?>
                         <?php endif; ?>
                    </div>
               </div>
               <div class="arrow-down" style=""></div>
               <?php endif; ?>
               
           </li>
       
<?php endforeach; ?>
       </ul>
       
    </nav>
</div>
