<header:css>
  [include_css]
</header:css>
<nav role="navigation" class="navbar navbar-default menuhor" data-semenu="[param16]" [contedit]>
  <div class="navbar-header">
    <button type="button" data-target="#navbarCollapseMenuhor" data-toggle="collapse" class="navbar-toggle">
      <span class="sr-only">[part.title]</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a href="#" class="navbar-brand" data-seedit="sitetitle">[%sitetitle%]</a>
  </div>
  <div id="navbarCollapseMenuhor" class="collapse navbar-collapse">
    <createmenu:item-[param16]>
    <ul class="nav navbar-nav">
    <repeat:menuitems name=item>
      <if:([item.items])>
      <li class="dropdown">
        <a data-toggle="dropdown" class="dropdown-toggle" 
            href="[item.url]">[item.title] <b class="caret"></b></a>
        <ul role="menu" class="dropdown-menu">
        <repeat:[item.items] name=sitem>
            <if:([sitem.items])>
            <li class="dropdown-submenu">
                <a data-toggle="dropdown" class="dropdown-toggle" 
                    href="[sitem.url]">[sitem.title]</a>
                <ul role="menu" class="dropdown-menu">
                <repeat:[sitem.items] name=ssitem>
                    <li><a href="[ssitem.url]">[ssitem.title]</a></li>
                </repeat:[sitem.items]>
                </ul>
            </li>
            <else>
                <li><a href="[sitem.url]">[sitem.title]</a></li>
            </if>
        </repeat:[item.items]>
        </ul>
      </li>
      <else>
      <li><a href="[item.url]">[item.title]</a></li>
      </if>
    </repeat:menuitems>
    </ul>
    
    <if:[param18]=="Y">
      <form role="search" action="[param19]" class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" placeholder="Найти" class="form-control" name="query">
          <input type="hidden" name="GoTo_search" value="">
        </div>
      </form>
    </if>
    
    <ul class="nav navbar-nav navbar-right">
      <li><a href="#" class="se-login-modal">[lang002]</a></li>
    </ul>
    <se>
    <emptymenu><div class="emptymenyblock"><span>[lang003]</span> 
        <i class="sesys-icon menu" data-semenu="[param16]"></i></div>
    </emptymenu>
    </se>
    </createmenu>
  </div>
</nav>
