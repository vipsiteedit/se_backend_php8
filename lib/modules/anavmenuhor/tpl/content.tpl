<header:css>
  [include_css]
</header:css>
<nav role="navigation" class="navbar navbar-default menuhor" data-semenu="<?php echo $section->parametrs->param16 ?>" >
  <div class="navbar-header">
    <button type="button" data-target="#navbarCollapseMenuhor" data-toggle="collapse" class="navbar-toggle">
      <span class="sr-only"><?php echo $section->title ?></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a href="#" class="navbar-brand" data-seedit="sitetitle">[%sitetitle%]</a>
  </div>
  <div id="navbarCollapseMenuhor" class="collapse navbar-collapse">
    <? if(function_exists('getItemMenu')){ list($menuitems) = getItemMenu($section->parametrs->param16); $__data->setList($section,'menuitems', $menuitems);} ?>
    <ul class="nav navbar-nav">
    <?php foreach($section->menuitems as $item): ?>
      <?php if($item->items): ?>
      <li class="dropdown">
        <a data-toggle="dropdown" class="dropdown-toggle" 
            href="<?php echo $item->url ?>"><?php echo $item->title ?> <b class="caret"></b></a>
        <ul role="menu" class="dropdown-menu">
        <?php foreach($item->items as $sitem): ?>
            <?php if($sitem->items): ?>
            <li class="dropdown-submenu">
                <a data-toggle="dropdown" class="dropdown-toggle" 
                    href="<?php echo $sitem->url ?>"><?php echo $sitem->title ?></a>
                <ul role="menu" class="dropdown-menu">
                <?php foreach($sitem->items as $ssitem): ?>
                    <li><a href="<?php echo $ssitem->url ?>"><?php echo $ssitem->title ?></a></li>
                
<?php endforeach; ?>
                </ul>
            </li>
            <?php else: ?>
                <li><a href="<?php echo $sitem->url ?>"><?php echo $sitem->title ?></a></li>
            <?php endif; ?>
        
<?php endforeach; ?>
        </ul>
      </li>
      <?php else: ?>
      <li><a href="<?php echo $item->url ?>"><?php echo $item->title ?></a></li>
      <?php endif; ?>
    
<?php endforeach; ?>
    </ul>
    
    <?php if(strval($section->parametrs->param18)=="Y"): ?>
      <form role="search" action="<?php echo $section->parametrs->param19 ?>" class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" placeholder="Найти" class="form-control" name="query">
          <input type="hidden" name="GoTo_search" value="">
        </div>
      </form>
    <?php endif; ?>
    
    <ul class="nav navbar-nav navbar-right">
      <li><a href="#" class="se-login-modal"><?php echo $section->language->lang002 ?></a></li>
    </ul>
    
    
  </div>
</nav>
