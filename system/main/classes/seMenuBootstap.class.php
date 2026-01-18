<?php

/**
 * Класс формирования всех видов меню 999
 * 
 **/
class seMenu {
	
  private $pathmenu;
  private $fl_openstree;
  private $flaguniversal;
  private $menu;
  private $typemenu;
  private $flDinamic;
  private $pagename;
  private $flagPath;
  private $typedoc = 0;

  public function __construct($pagename = 'home', $menulist = array(), $flUniversal = false, $mtype = 0)
  {
    $this->pathmenu = array();
    $this->fl_openstree = false;
    $this->flaguniversal = $flUniversal;
    $this->menu = $menulist;
    $this->typemenu = $mtype;
    $this->pagename = strval($pagename);
    $se = seData::getInstance();
    $this->typedoc = $se->prj->documenttype;

    if ($this->typemenu > 2)
    {
      $this->flDinamic = true;
      $this->typemenu = $this->typemenu - 2;
    }
    else
    {
      $this->flDinamic = false;
    }
    if ($this->typemenu < 0)
    {
      $this->typemenu = 0;
      $this->fl_openstree = true;
    }

    $this->flagPath = false;
    $this->setPath($this->menu, 0);
  }

  /**
   * Метод формирования пути активной ветки
   **/
  private function setPath($menulist, $level = 0)
  {
    if ($this->flagPath)
    {
      return false;
    }

	$result = false;
    if (!empty($menulist))
    foreach ($menulist as $line)
    {
      $this->pathmenu[$level] = $line->name;
      if ($line->name == $this->pagename)
      {
        $this->flagPath = true;
        for ($j = $level + 1; $j < 10; $j++)
        {
          $this->pathmenu[$j] = '';
        }
        $result = true;
        break;
      }

      if (!empty($line->item))
      {
        if ($this->setPath($line->item, $level + 1))
        {
        $result = true;
        break;
        }
      }
    }
    if (!$result) $this->pathmenu[$level] = '';
    return $result;
  }

  // Сервисное меню
  /**
   * Метод проверки активного пункта меню
   **/
  private function checkActiveMenu($item, $level)
  {
    return (!empty($item->item) && ($item->name == $this->pathmenu[$level] || !$this->fl_openstree));
  }

  /** 
   * Метод для формирования табличных субменю
   **/
  private function SubMenuTable($MList, $thisname, $level)
  {
    if ($this->typemenu == 2 || $level > 1)
    {
      $style = 'style="text-align:left; float:left;"';
    } elseif ($this->typemenu == 1)
    {
      $style = 'style="text-align:left; clear:both;"';
    }
    else
    {
      $style = '';
    }

    $subname = 'submenu_' . $thisname;
    $subnum = 'submenu';

    if ($level > 1 && $level <= 3)
    {
      $subnum = 'submenu' . ($level - 1);
    }

    if ($level > 3)
    {
      $subnum = 'submenun';
    }

    if ($this->flDinamic)
    {
      $submenustyle = ' style="visibility:hidden; position:absolute;  z-index:1000;"';
    }
    else
    {
      $submenustyle = ' style="visibility:show; position:relative;  z-index:1000;"';
    }

    $result = "\t\t" . '<ul class="dropdown-menu">' . "\r\n";

    foreach ($MList as $line)
    {
      if (!$this->isAccess($line) || (isset($line->visible) && $line->visible == 0)) continue;
      $name = $line->name;
  
       $dropflag = false; 
      $dropdownclass = '';
      $activeclass = '';
      
      if (!empty($line->item)) {  
        if ($line->name == $this->pagename) $activeclass = ' active';
        $dropdownclass = ' class="dropdown' . $activeclass . '"';
        $dropflag = true;
      }  else  {
        if ($line->name == $this->pagename) $dropdownclass = ' class="active"';}


      $result .= "\t\t\t" . '<li' . $dropdownclass . '>' . $this->getItem($line, $level, $dropflag) . "\r\n";
      if ($this->checkActiveMenu($line, $level))
      {
        $result .= $this->SubMenuTable($line->item, $name, $level + 1);
      }
      if ($this->typemenu > 0)
      {
      }
      $result .= '</li>' .  "\r\n";
    }


    $result .= "\t\t" . '</ul>' . "\r\n";
    return $result;
  }


  /** 
   * Метод для формирования DIV субменю
   **/
  private function SubMenuDiv($menulist, $thisname, $level)
  {
    $subname = 'submenu_' . $thisname;
    $subnum = 'submenu';
    if ($level > 1 && $level <= 3)
    {
      $subnum = 'submenu' . ($level - 1);
    }
    if ($level > 3)
    {
      $subnum = 'submenun';
    }

    if ($this->flDinamic)
    {
      $submenustyle = ' style="visibility:hidden; position:absolute;  z-index:1000;"';
    }
    else
    {
      $submenustyle = ' style="visibility:show; position:relative;  z-index:1000;"';
    }

    $result = "\t\t" . '<div class="' . $subnum . '" >' . "\r\n";

    foreach ($menulist as $line)
    {
      if (!$this->isAccess($line) || (isset($line->visible) && $line->visible == 0)) continue;
      $name = $line->name;
      if (!empty($line->item))
      {
        $java = $this->getLinkJava($name);
      }
      else
      {
        $java = '';
      }

      $result .= "\t\t\t" . $this->getItem($line, $level, false) . "\r\n";
      if ($this->checkActiveMenu($line, $level))
      {
        $result .= $this->SubMenuDiv($line->item, $name, $level + 1);
      }
    }
    return $result . "\t\t" . '</div>' . "\r\n";
  }


  private function getLinkJava($name)
  {
    if ($this->flDinamic)
    {
      return ' onMouseOver="show_menu(\'' . $name . '\')" onMouseOut="hide_menu(\'' . $name . '\')" ';
    }
  }

  /** 
   * Метод для формирования табличных меню
   **/
  private function buildMenuTable($menulist, $level = 0, $subLevel = 0)
  {
     $result = '<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">' . "\r\n";
  
  $result .= '<div class="navbar-header">' .  "\r\n";
  $result .= '    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">' .  "\r\n";
  $result .= '      <span class="sr-only">Toggle navigation</span>' .  "\r\n";
  $result .= '      <span class="icon-bar"></span>' .  "\r\n";
  $result .= '      <span class="icon-bar"></span>' .  "\r\n";
  $result .= '      <span class="icon-bar"></span>' .  "\r\n";
  $result .= '    </button>' .  "\r\n";
  $result .= '    <a  class="navbar-brand" href="/"><img id="siteLogotype" src="/rus/images/logo.jpg" border="0" ></a>' .  "\r\n";
  
  $result .= '  </div>' .  "\r\n";
    
   $result .= '<div class="navbar-collapse collapse navbar-right" id="bs-example-navbar-collapse-1">' . "\r\n";
   $result .=  '<ul class="nav navbar-nav">' . "\r\n";
  
 


    $style = '';// style="float:left;"';

    
    $i = -1;
    foreach ($menulist as $line)
    {
      $i ++;
      if (!$this->isAccess($line) || (isset($line->visible) && $line->visible == 0)) continue;

      $dropflag = false; 
      $dropdownclass = '';
      $activeclass = '';
      
      if (!empty($line->item)) {  
        if ($line->name == $this->pagename) $activeclass = ' active';
        $dropdownclass = ' class="dropdown' . $activeclass . '"';
        $dropflag = true;
      }  else  {
        if ($line->name == $this->pagename) $dropdownclass = ' class="active"';}

      $result .= "\t\t" . '<li' . $dropdownclass . '>' . $this->getItem($line, $level, $dropflag) .  "\r\n";

      if ($this->checkActiveMenu($line, $level))
      {
        $result .= $this->subMenuTable($line->item, $line->name, $subLevel + 1);
      }

      $result .= '</li>' .  "\r\n";

    }         
//    if ($this->typemenu == 1)
      $result .= '</ul>' . "\r\n";
      $result .= '</div>' . "\r\n";
      $result .= '</div>' . "\r\n";
    $result .= '</nav>' . "\r\n";
    return $result;
  }

  /** 
   * Метод для формирования DIV меню
   **/
  private function buildMenuDiv($menulist, $level, $subLevel = 0)
  {
    $result = '';
    $style = ' style="float:none;"';
    if (!empty($menulist))
    foreach ($menulist as $line)
    {
      $submenu = '';
      if (!$this->isAccess($line) || (isset($line->visible) && $line->visible == 0)) continue;
      $result .= "\t\t";
      if ($this->checkActiveMenu($line, $level))
      {
    	$submenu = $this->subMenuDiv($line->item, $line->name, $subLevel + 1);
    	if ($this->typedoc == 1 && !empty($submenu)){
    	    $result .= "<div class=\"mtditem\">";
    	}
      }
      $result .= $this->getItem($line, $level, false) . "\r\n" . $submenu;
      if ($this->typedoc == 1 && !empty($submenu)) $result .= '</div>';
    }
    return $result;
  }


  /** 
   * Метод для вывода пункта меню
   **/
  private function getItem($itemMenu, $level, $dropdown)
  {
    if (!empty($itemMenu->target))
    {
      $target = ' target="' . $itemMenu->target . '"';
    } else $target = '';
    
    $flActive = ($itemMenu->name == $this->pagename);
    $itemclass = ' mitem_' . $itemMenu->name;

    $addinfo = ''; 
    $carsymb = '';    
    
    if ($dropdown) {
      $addinfo = ' class="dropdown-toggle" data-toggle="dropdown" '; 
      $carsymb = ' <b class="caret"></b>';
    }

    if ($flActive || (!empty( $this->pathmenu[$level]) && $this->pathmenu[$level] == $itemMenu->name))
    {
 //     $textclass = 'TextActiveMenu';
      $iclass = 'menu menuActive' . $itemclass;
      if (!empty($itemMenu->imageactive))
      {
        $image = trim($itemMenu->imageactive);
      }
    }
    else
    {
 //     $textclass = 'TextItemMenu';
      $iclass = 'menu' . $itemclass;
      if (!empty($itemMenu->image))
      {
        $image = trim($itemMenu->image);
      }
    }
    $title = '';
    if (empty($image))
    {
	  	@$image = trim($itemMenu->image);
    }
    
    if (!empty($image))
    {
      $SE_DIR = SE_DIR;
      $image = preg_replace("/\b(skin|images|files)\//u", "/{$SE_DIR}$1/", $image);
      $title = '<img class="ImgMenu" alt="' . htmlspecialchars($itemMenu->title) . '" src="' . $image . '" border="0">';
    }
    $title .= '<span class="' . $textclass . '">' . $itemMenu->title . $carsymb . '</span>';
    $style = '';
    


    $folder = seMultiDir();
    
    if (strpos($itemMenu->url,'://')==false && substr($itemMenu->url, strlen($itemMenu->url)-1, 1) != '/' && strpos($itemMenu->url, '#')==false)
	$url = $folder. $itemMenu->url.'/';
    else 
	$url = $itemMenu->url;
    return '<a ' . $style . $addinfo . ' href="' . $url . '"' . $target . '>' . $title . '</a>';
  }

  /** 
   * Метод для вывода меню
   **/
  public function execute($inPageTree = false)
  {
  	 $level = 0;
  	if ($inPageTree && $this->flaguniversal)
  	{
  	$fl_find = false;
    	foreach($this->menu as $item)
    	{
           if ($this->pathmenu[0] == $item->name && !empty($item->item))
           {
        		$this->menu  = $item->item;
        		$fl_find = true;
        		$level = 1;
        		break;
      	   }
    	}
    	if (!$fl_find) unset($this->menu);
    	}
    if ($this->typemenu > 0)
      return $this->buildMenuTable($this->menu, $level);
    else
      return $this->buildMenuDiv($this->menu, $level);
  }
  
  private function isAccess($item)
  {
  	if (empty($item->access))
	{  
  		return seUserAccess($item->name);
	} else 
	{
		if ($item->access > 3)
		{
			if ($item->access == 4)
				return seUserGroup() && $item->access != 1;
			else return !seUserGroup();
		} else
		{
			return $item->access <= seUserGroup();
		}
	}
  }
}

?>