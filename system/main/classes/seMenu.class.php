<?php

/**
 * Класс для работы с навигацией
 *
 **/
class seMenu
{

    private $pathmenu;
    private $fl_openstree;
    private $flaguniversal;
    private $menu;
    private $typemenu;
    private $flDinamic;
    private $pagename;
    private $flagPath;
    private $startpage;
    private $typedoc = 0;
    private $multi = false;
    private $inPageTree = false;

    public function __construct($pagename = 'home', $menulist = array(), $flUniversal = false, $mtype = 0, $multilang = true)
    {
        $this->pathmenu = array();
        $this->fl_openstree = false;
        $this->flaguniversal = $flUniversal;
        $this->menu = $menulist;
        $this->typemenu = $mtype;
        $this->pagename = strval($pagename);
        $se = seData::getInstance();
        $this->typedoc = $se->prj->documenttype;
        $this->startpage = $se->startpage;
        $this->multi = $multilang;

        if ($this->typemenu > 2) {
            $this->flDinamic = true;
            $this->typemenu = $this->typemenu - 2;
        } else {
            $this->flDinamic = false;
        }
        if ($this->typemenu < 0) {
            $this->typemenu = 0;
            $this->fl_openstree = true;
        }

        $this->flagPath = false;
        $this->setPath($this->menu, 0);
    }


    private function setPath($menulist, $level = 0)
    {
        if ($this->flagPath) {
            return false;
        }

        $result = false;
        if (!empty($menulist))
            foreach ($menulist as $line) {
                $this->pathmenu[$level] = $line->name;
                if ($line->name == $this->pagename) {
                    $this->flagPath = true;
                    for ($j = $level + 1; $j < 10; $j++) {
                        $this->pathmenu[$j] = '';
                    }
                    $result = true;
                    break;
                }

                if (!empty($line->item)) {
                    if ($this->setPath($line->item, $level + 1)) {
                        $result = true;
                        break;
                    }
                }
            }
        if (!$result) $this->pathmenu[$level] = '';
        return $result;
    }


    private function checkActiveMenu($item, $level)
    {
        return (!empty($item->item) && ($item->name == $this->pathmenu[$level] || !$this->fl_openstree));
    }


    private function SubMenuTable($MList, $thisname, $level)
    {
        if ($this->typemenu == 2 || $level > 1) {
            $style = 'style="text-align:left; float:left;"';
        } elseif ($this->typemenu == 1) {
            $style = 'style="text-align:left; clear:both;"';
        } else {
            $style = '';
        }

        $subname = 'submenu_' . $thisname;
        $subnum = 'submenu';

        if ($level > 1 && $level <= 3) {
            $subnum = 'submenu' . ($level - 1);
        }

        if ($level > 3) {
            $subnum = 'submenun';
        }

        if ($this->flDinamic) {
            $submenustyle = ' style="visibility:hidden; position:absolute;  z-index:1000;"';
        } else {
            $submenustyle = ' style="visibility:show; position:relative;  z-index:1000;"';
        }

        $result = "\t\t" . '<div class="mreldiv" ' . $style . ">\r\n";
        $result .= "\t\t" . '<table ' . $submenustyle . $this->getLinkJava($thisname) . ' id="' . $subname . '" border=0 cellpadding="0" cellspacing="0" vAlign=top class="' . $subnum . '" >' . "\r\n";
        $result .= "\t\t" . '<tr><td class="lefttop"></td><td  class="midtop"><td class="righttop"></td></tr>' . "\r\n";

        foreach ($MList as $line) {
            if (!$this->isAccess($line) || (isset($line->visible) && $line->visible == 0)) continue;
            $name = $line->name;
            if (!empty($line->item)) {
                $java = $this->getLinkJava($name);
            } else {
                $java = '';
            }
            if ($this->typemenu > 0) {
                $result .= "\t\t" . '<tr><td class="leftline"></td><td ' . $java . ' class="midline">' . "\r\n";
            }

            $result .= "\t\t\t" . $this->getItem($line, $level) . "\r\n";
            $nlevel = ($this->inPageTree) ? $level + 1 : $level;
            if ($this->checkActiveMenu($line, $nlevel)) {
                $result .= $this->SubMenuTable($line->item, $name, $level + 1);
            }
            if ($this->typemenu > 0) {
            }
            $result .= "\t\t" . '</td><td class="rightline"></td></tr>' . "\r\n";
        }

        $result .= "\t\t" . '<tr><td class="leftbot"></td><td  class="midbot"><td class="rightbot"></td></tr>' . "\r\n";
        $result .= "\t\t" . '</table></div>' . "\r\n";
        return $result;
    }


    private function SubMenuDiv($menulist, $thisname, $level)
    {
        $subname = 'submenu_' . $thisname;
        $subnum = 'submenu';
        if ($level > 1 && $level <= 3) {
            $subnum = 'submenu' . ($level - 1);
        }
        if ($level > 3) {
            $subnum = 'submenun';
        }

        if ($this->flDinamic) {
            $submenustyle = ' style="visibility:hidden; position:absolute;  z-index:1000;"';
        } else {
            $submenustyle = ' style="visibility:show; position:relative;  z-index:1000;"';
        }

        $result = "\t\t" . '<div class="' . $subnum . '" >' . "\r\n";

        foreach ($menulist as $line) {
            if (!$this->isAccess($line) || (isset($line->visible) && $line->visible == 0)) continue;
            $name = $line->name;
            if (!empty($line->item)) {
                $java = $this->getLinkJava($name);
            } else {
                $java = '';
            }

            $result .= "\t\t\t" . $this->getItem($line, $level) . "\r\n";
            $nlevel = ($this->inPageTree) ? $level + 1 : $level;
            if ($this->checkActiveMenu($line, $nlevel)) {
                $result .= $this->SubMenuDiv($line->item, $name, $level + 1);
            }
        }
        return $result . "\t\t" . '</div>' . "\r\n";
    }


    private function getLinkJava($name)
    {
        if ($this->flDinamic) {
            return ' onMouseOver="show_menu(\'' . $name . '\')" onMouseOut="hide_menu(\'' . $name . '\')" ';
        }
    }


    private function buildMenuTable($menulist, $level = 0, $subLevel = 0)
    {
        $result = '<table border="0" cellpadding="0" cellspacing="0" class="tableMenu">' . "\r\n";
        $style = '';// style="float:left;"';

        if ($this->typemenu == 1) {
            $result .= '<tr>' . "\r\n";
        }

        $i = -1;
        foreach ($menulist as $line) {
            $i++;
            if (!$this->isAccess($line) || (isset($line->visible) && $line->visible == 0)) continue;
            $flActive = ($line->name == $this->pagename);
            $active_middl = $active_left = $active_right = '';
            if ($flActive || (!empty($this->pathmenu[$level]) && $this->pathmenu[$level] == $line->name)) {
                $active_middl = ' midActive';
                $active_left = ' leftActive';
                $active_right = ' rightActive';
            }


            if ($this->typemenu > 1)
                $result .= '<tr>' . "\r\n";
            $result .= "\t" . '<td width="2" class="mbordl' . $active_left . '">&nbsp;</td>' . "\r\n";
            if (!empty($line->item))
                $java = $this->getLinkJava($line->name);
            else
                $java = '';

            $result .= "\t" . '<td ' . $style . $java . ' class="mtditem' . $active_middl . '">' . "\r\n";

            $result .= "\t\t" . $this->getItem($line, $level) . "\r\n";

            if ($this->checkActiveMenu($line, $level)) {
                $result .= $this->subMenuTable($line->item, $line->name, $subLevel + 1);
            }

            $result .= "\t" . '</td>' . "\r\n";
            $result .= "\t" . '<td width="2" class="mbordr' . $active_right . '">&nbsp;</td>' . "\r\n";
            if ($this->typemenu == 1) {
                if ($i < (count($menulist) - 1))
                    $result .= "\t" . '<td class="mids">|</td>' . "\r\n";
            }
            if ($this->typemenu > 1)
                $result .= '</tr>' . "\r\n";
        }
        if ($this->typemenu == 1)
            $result .= '</tr>' . "\r\n";
        $result .= '</table>' . "\r\n";
        return $result;
    }


    private function buildMenuDiv($menulist, $level, $subLevel = 0)
    {
        $result = '';
        $style = ' style="float:none;"';
        if (!empty($menulist))
            foreach ($menulist as $line) {
                $submenu = '';
                if (!$this->isAccess($line) || (isset($line->visible) && $line->visible == 0)) continue;
                $result .= "\t\t";
                if ($this->checkActiveMenu($line, $level)) {
                    $submenu = $this->subMenuDiv($line->item, $line->name, $subLevel + 1);
                    if ($this->typedoc == 1 && !empty($submenu)) {
                        $result .= "<div class=\"mtditem\">";
                    }
                }
                $result .= $this->getItem($line, $level) . "\r\n" . $submenu;
                if ($this->typedoc == 1 && !empty($submenu)) $result .= '</div>';
            }
        return $result;
    }


    private function getItem($itemMenu, $level)
    {
        if (!empty($itemMenu->target)) {
            $target = ' target="' . $itemMenu->target . '"';
        } else $target = '';

        $flActive = ($itemMenu->name == $this->pagename);
        $itemclass = ' mitem_' . se_translite_url($itemMenu->title);
        //$itemclass = ' mitem_' . $itemMenu->name;

        if ($flActive || (!empty($this->pathmenu[$level]) && $this->pathmenu[$level] == $itemMenu->name)) {
            $textclass = 'TextActiveMenu';
            $iclass = 'menu menuActive' . $itemclass;
            if (!empty($itemMenu->imageactive)) {
                $image = trim($itemMenu->imageactive);
            }
        } else {
            $textclass = 'TextItemMenu';
            $iclass = 'menu' . $itemclass;
            if (!empty($itemMenu->image)) {
                $image = trim($itemMenu->image);
            }
        }
        $title = '';
        if (empty($image)) {
            @$image = trim($itemMenu->image);
        }

        if (!empty($image)) {
            $SE_DIR = SE_DIR;
            $image = preg_replace("/\b(skin|images|files)\//u", "/{$SE_DIR}$1/", $image);
            $title = '<img class="ImgMenu" alt="' . htmlspecialchars($itemMenu->title) . '" src="' . $image . '" border="0">';
        }
        $title .= '<span class="' . $textclass . '">' . $itemMenu->title . '</span>';
        $style = '';
        if ($itemMenu->url == '/#')
            return '<span' . $style . ' class="' . $iclass . '">' . $title . '</span>';
        if ($this->multi && strpos($itemMenu->url, '://') === false) {
            if ($itemMenu->url == '/' . $this->startpage) $itemMenu->url = '/';
            $url = tsl($itemMenu->url);
        } else {
            $url = $itemMenu->url;
        }
        return '<a' . $style . ' class="' . $iclass . '" href="' . $url . '"' . $target . '>' . $title . '</a>';
    }


    public function execute($inPageTree = false)
    {
        $level = 0;
        $this->inPageTree = $inPageTree;
        if ($inPageTree && $this->flaguniversal) {
            $fl_find = false;
            foreach ($this->menu as $item) {
                if ($this->pathmenu[0] == $item->name && !empty($item->item)) {
                    $this->menu = $item->item;
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
        $item->access = intval($item->access);
        if (!$item->access) {
            return seUserAccess($item->name);
        } else {
            if ($item->access > 3) {
                if ($item->access == 4)
                    return seUserGroup() && $item->access != 1;
                else return !seUserGroup();
            } else {
                return $item->access <= seUserGroup();
            }
        }
    }
}

?>
