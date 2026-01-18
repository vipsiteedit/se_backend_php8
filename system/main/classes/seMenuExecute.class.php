<?php

require_once(dirname(__FILE__) . '/seMenu.class.php');

class seMenuExecute
{
    static $instance;

    private function fpagemenu($typmenu = 0, $menulist = array(), $drivemenu = false)
    {
        $se = seData::getInstance();
        if (empty($menulist)) {
            $menulist = $se->pagemenu;
        }
        $menu = new seMenu($se->getPageName(), $menulist, true, $typmenu);
        $result = $menu->execute($drivemenu);
        return $result;
    }

    public function getPageMenu()
    {
        $se = seData::getInstance();
        $ncss = '/' . $se->getSkinService() . '/' . $se->page->css . ".stk";

        $flag_nodat = true;

        if (file_exists(getcwd() . $ncss)) {
            $pmenu = file(getcwd() . $ncss);
            foreach ($pmenu as $line) {
                @list($line, $drivemenu) = explode(':', $line);
                $drivemenu = (!empty($drivemenu));

                if (preg_match("/\bpagemenu-(.+?)\b/i", $line, $m)) {
                    if ($m[1] == 'full' || $m[1] == 'horiz' || $m[1] == 'vert' || $m[1] == 'hstat' || $m[1] == 'vstat')
                        $flpmen = true;

                    $flag_nodat = false;

                    if ($m[1] == 'ntree')
                        return $this->fpagemenu(-1, array(), $drivemenu);
                    elseif ($m[1] == 'full')
                        return $this->fpagemenu(0, array(), $drivemenu);
                    elseif ($m[1] == 'horiz')
                        return $this->fpagemenu(3, array(), $drivemenu); //2
                    elseif ($m[1] == 'vert')
                        return $this->fpagemenu(4, array(), $drivemenu); //1
                    elseif ($m[1] == 'hstat')
                        return $this->fpagemenu(1, array(), $drivemenu); //4
                    elseif ($m[1] == 'vstat')
                        return $this->fpagemenu(2, array(), $drivemenu); //3
                    else
                        return $this->fpagemenu(0, array(), $drivemenu);
                    break;
                }
            }
        }

        if ($flag_nodat) {
            return $this->fpagemenu(-1);
        }
    }

    public function getMainMenu($typmenu = -1)
    {
        $se = seData::getInstance();
        if (intval($typmenu) != -1) {
            $_SESSION['site_config']['mainmenytype'] = $typmenu;
        } else $typmenu = $_SESSION['site_config']['mainmenytype'];

        $menu = new seMenu($se->getPageName(), seData::getInstance()->mainmenu, false, $typmenu);
        $result = $menu->execute();
        return $result;
    }

    public static function getInstance($namepage = '', $dir = '')
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

} 