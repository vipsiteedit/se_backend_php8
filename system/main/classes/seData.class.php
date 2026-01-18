<?php

/***********************************************************
 ** seData - core siteedit
 ** Author - Edgestile LTD 2016
 ***********************************************************/
require_once(dirname(__FILE__) . '/seMenuExecute.class.php');
require_once(dirname(__FILE__) . '/seModule39.class.php');
require_once dirname(__FILE__) . "/seImages.class.php";
require_once SE_CORE . 'sitepagemenu.php';
require_once SE_CORE . 'sitemainmenu.php';

if (!defined('SE_END')) define('SE_END', '/');


class seData
{
    private static $instance = null;
    private $dir;
    private $services = null;
    private $path = array();
    public $interface_lang = 'ru';
    private $ajaxId = false;
    private $ajaxType = '';
    private $urllist = array();

    public $language = 'rus';
    public $page = null;
    public $pages = null;
    public $prj = null;
    public $adminlogin = '';
    public $header = array();
    public $headercss = array();
    public $footercss = array();
    public $footer = array();
    public $footerhtml = '';
    public $adminpassw = '';
    public $skin;
    public $img;
    public $files;
    public $versionproduct;
    public $gkeywords;
    public $gdescription;
    public $sections = null;
    public $pagename;
    public $pagemenu;
    public $mainmenu;
    public $mainmeny_type = 0;
    public $req;
    public $error;
    public $modulesCss = array();
    public $editor = null;
    public $lastmodif = 0;
    private $linkredirect = false;
    public $startpage = '';
    private $contarr = array();
    public $URLS = array();
    public $breadcrumbs = array();


    private function __construct($namepage = '', $dir = '')
    {
        registerName('_openstat');
        registerName('merchant');
        registerName('invnum');
        $this->pagename = is_null($namepage) ? '' : (string)$namepage;
        if ($this->pagename == 'index') $this->pagename = '';

        // Загрузка языка проектаpagename
        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . 'cache/project_lang.dat')) {
            $this->language = join('', file(SE_SAFE . 'projects/' . SE_DIR . 'cache/project_lang.dat'));
        }

        $this->openUrlList();
        // Инициализация массива запросов
        $this->req = new stdClass;
        $this->page = new stdClass;
        $this->prj = new stdClass;

        $this->req->sub = $this->req->razdel = $this->req->object = false;
        if (str_replace('/', '', $_SERVER['REQUEST_URI']) == 'index') {
            $this->go301(seMultiDir() . '/');
        }

        if (isRequest('lang-site')) {
            list($url) = explode('?', $_SERVER['REQUEST_URI']);
            $this->go301(seMultiDir() . $url);
        }
        list($urlline) = explode('?', $_SERVER['REQUEST_URI']);
        if (strpos($urlline, '.') && !strpos($urlline, '.php')) {
            $this->go404();
        }
        $urlline_parts = explode('#', $urlline, 2);
        $urlline = $urlline_parts[0];
        $this->req->param = explode('/', $urlline);
        if (!empty($this->req->param[1]) && $this->req->param[1] == str_replace('/', '', seMultiDir())) {
            array_splice($this->req->param, 1, 1);
        }

        $redirectpage = $this->redirect($this->pagename); // Редиректы
        if ($redirectpage) {
            $this->pagename = $redirectpage;
        }

        if (strpos($this->pagename, '_') !== false) {
            list($this->pagename) = explode('_', $this->pagename);
        }

        if (strpos($this->pagename, 'show') === 0) {
            $virtualpage = $this->getVirtualPage($this->pagename);
            if ($virtualpage != '') {
                $this->pagename = $virtualpage;
            } else $this->pagename = substr($this->pagename, 4);
        }

        $this->redirectPage();

        // Инициализация редактора
        if (strpos($this->pagename, 'show') === 0) {
            if (!empty($_SESSION['SE'][$this->pagename]))
                $this->pagename = $_SESSION['SE'][$this->pagename];
            else
                if (!empty($_SESSION['se']['page']))
                $this->pagename = $_SESSION['se']['page'];
            else
                $this->pagename = substr($this->pagename, 4);
        }

        if (isset($_GET['interfacelang'])) {
            $this->interface_lang = substr($_GET['interfacelang'], 0, 2);
            $fp = fopen(SE_SAFE . 'projects/' . SE_DIR . 'cache/interface_lang.dat', "w+");
            fwrite($fp, $this->interface_lang);
            fclose($fp);
        } elseif (file_exists(SE_SAFE . 'projects/' . SE_DIR . 'cache/interface_lang.dat')) {
            $this->interface_lang = join('', file(SE_SAFE . 'projects/' . SE_DIR . 'cache/interface_lang.dat'));
        }

        $this->error = false;
        if (!empty($dir)) $dir .= '/';
        $this->dir = $dir;
        // Инициализируем проект
        $this->initprj();
        if (isRequest('login-AJAX')) {
            $lang = $this->prj->language;
            include SE_CORE . 'loginform.php';
            exit;
        }

        $this->onAjax();

        // Загрузим файл проекта
        $this->initmenu();
        // Инициализация списка страниц
        $this->initpages();
        $this->initpage();
        $this->rootUrl();
        $this->req->page = $this->pagename;
        list($_SESSION['SE_BACK_URL']) = explode('?', $_SERVER['REQUEST_URI']);

        $startpagetitle = '';
        foreach ($this->pages as $item) {
            if ($item['name'] == $this->startpage) {
                $startpagetitle = $item->title;
                break;
            }
        }
        $this->breadcrumbs[] = array('lnk' => seMultiDir() . '/', 'name' => $startpagetitle);
        if ($this->pagename !== $this->startpage) {
            $active = ($_SERVER['REQUEST_URI'] == seMultiDir() . '/' . $this->pagename);
            $this->breadcrumbs[] = array('lnk' => seMultiDir() . '/' . $this->pagename, 'name' => $this->page->title, 'active' => $active);
        }
    }


    /**
     * @param $sectionId
     * @return string
     * @Comment: Обработка ссылок на страницe
     **/
    public function execute()
    {
        global $SE_REQUEST_NAME;
        if (empty($this->page->title_tag)) $this->page->title_tag = 'h1';
        $this->sections = array();
        if (!empty($this->prj->sections) && is_iterable($this->prj->sections)) {
            foreach ($this->prj->sections as $value) {
                $id_content = strval($value['name']);
                if ($this->ajaxId && $this->ajaxId != $id_content) continue;
                //if ($this->ajaxType && $this->ajaxType != strval($value->type)) continue;
                if (empty($value->title_tag)) $value->title_tag = 'h3';
                $this->sections[$id_content] = $value;
            }
        }

        if (!empty($this->page->sections) && is_iterable($this->page->sections)) {
            foreach ($this->page->sections as $value) {
                $id_content = strval($value['name']);
                if ($this->ajaxId && $this->ajaxId != $id_content) continue;
                //if ($this->ajaxType && $this->ajaxType != strval($value->type)) continue;
                if (empty($value->title_tag)) $value->title_tag = 'h3';
                $this->sections[strval($id_content)] = $value;
            }
        }

        $modulesArr = array();
        if (!empty($this->sections)) {
            foreach ($this->sections as $id_content => $section) {
                $id_content = strval($section->id);
                $cont = ($id_content > 100000) ? 100 + floor(($id_content - 100000) / 1000) : floor($id_content / 1000);
                //if (!empty($this->contarr) && !in_array($cont, $this->contarr)) continue;
                if ($this->req->object && $this->req->razdel == $id_content) {
                    $obj = $this->getObject($section, $this->req->object);
                    $this->page->titlepage = (!empty($obj->meta_title)) ? strip_tags($obj->meta_title) : strip_tags($obj->title);
                    $this->page->keywords = (!empty($obj->meta_keywords)) ? strip_tags($obj->meta_keywords) : strip_tags($obj->title);
                    $this->page->description = (!empty($obj->meta_descr)) ? strip_tags($obj->meta_descr) : strip_tags($obj->note);
                }

                $is_add_url = false;
                $first = 0;
                $row = 1;
                if (!empty($section->objects) && is_iterable($section->objects)) {
                    foreach ($section->objects as $object) {
                        if ($first == 0) {
                            $first = intval($object->id);
                        }
                        if ($section->showrecord != 'off') {
                            $object->link_detail = $this->objectLink($section->id, $object);
                        }

                        $object->first = $first;
                        $object->row = $row;
                        $object->num = $row - 1;
                        if (!empty($object->image) && empty($object->image_prev)) {
                            if (strpos($object->image, '://') === false) {
                                $prev = explode('.', $object->image);
                                $object->image_prev = $prev[0] . '_prev.' . $prev[1];
                            } else {
                                $object->image_prev = $object->image;
                            }
                        }
                        if (!strval($object->image_alt)) {
                            $object->image_alt = htmlspecialchars($object->title);
                        }
                        if (empty($object->title_tag)) $object->title_tag = 'h4';
                        $row++;
                    }
                }
                if (strval($section->showrecord) == 'off' && strval($section->id) == strval($this->req->razdel) && intval($this->req->object)) {
                    $this->go404();
                }
                if (!empty($section->translates) && is_iterable($section->translates)) {
                    foreach ($section->translates as $language) {
                        foreach ($language as $name => $value)
                            $section->language->$name = $value;
                    }
                }

                list($nametype) = explode('.', $section->type);
                $id_content = strval($section->id);
                if (!function_exists('start_' . $nametype)) {
                    $root = getcwd() . $this->getFolderModule($nametype);
                    if (file_exists($root . '/mdl_' . $nametype . '.php')) require_once($root . '/mdl_' . $nametype . '.php');
                    if ($this->req->sub && $section->id == $this->req->razdel && !file_exists($root . '/' . $nametype . '/php/subpage_' . $this->req->sub . '.php')) {
                        $this->go404();
                    }
                }


                $nametype = $section->type;
                $id_content = strval($section->id);
                $modulepath = '';
                if (!function_exists('start_' . $nametype)) {
                    $modulepath = $this->getFolderModule($nametype);
                    $root = getcwd() . $modulepath . '/mdl_' . $nametype . '.php';
                    if (file_exists($root)) require_once($root);
                }
                $isShow = false;

                if (function_exists('module_' . $nametype) && $this->getStatusService($nametype)) {
                    $fl_find_source = false;
                    if (!empty($section->sources)) {
                        $fl_find_source = true;
                        $pagename = ($section->id > 100000) ? '_' : $this->pagename;
                        $path_cache = SE_SAFE . "projects/" . SE_DIR . 'cache/' . $pagename . '/';

                        $filename_page = SE_SAFE . "projects/" . SE_DIR;
                        $filename_page .= ($pagename == '_') ? 'project.xml' : 'pages/' . $this->pagename . '.xml';
                        if (!is_dir($path_cache)) {
                            @mkdir($path_cache, 0777, true);
                        }
                        foreach ($section->sources[0] as $name => $value) {
                            if ($value == '') continue;
                            if (strpos($name, 'sub') === 0) $name = substr($name, 3);
                            if (file_exists(getcwd() . $modulepath . '/' . $nametype . '/tpl/' . 'subpage_' . $name . '.tpl')) {
                                $name = 'subpage_' . $name;
                            }
                            $name_cache = $path_cache . $nametype . '_' . $name . '_' . $section->id . '.tpl';
                            if (!file_exists($name_cache) || filemtime($name_cache) < filemtime($filename_page) || filemtime($name_cache) < filemtime(__FILE__)) {
                                $fp = fopen($name_cache, "w+");
                                if ($fp) {
                                    fwrite($fp, $this->parseModule($value, $section));
                                    fclose($fp);
                                }
                            }
                        }
                    }


                    if (!in_array($nametype, $modulesArr)) {
                        $modulesArr[] = $nametype;
                        if ($link = $this->getLinkStyle($section, $modulepath . '/' . $nametype)) {
                            if (!in_array($link, $this->headercss)) {
                                $this->headercss[] = $link;
                            }
                        }
                    }
                    $arr = array();

                    $this->parseParams($section);

                    $arr = call_user_func_array('module_' . $nametype, array($id_content, $section));
                    /*if (!SE_ALL_SERVICES && !$this->getStatusService($nametype, false)) {
                        $arr['content']['form'] = '<div style="color: #FF0000;">&nbsp;'.$this->editor->getTextLanguage('close_service').'</div>' . $arr['content']['form'];
                    }*/
                    $arr['content']['form'] = $this->setEditorLinks($section, $arr['content']['form']);

                    $section->body = replace_link($this->getHeader($arr['content']['form'], $section));
                    if (!empty($arr['content']['object'])) {
                        $section->formobject = replace_link($this->getHeader($arr['content']['object'], $section));
                    }
                    if (!empty($arr['content']['show'])) {
                        $section->formshow = replace_link($this->getHeader($arr['content']['show'], $section));
                        $isShow = true;
                    }
                    if (!empty($arr['content']['arhiv'])) {
                        $section->formarhiv = replace_link($this->getHeader($arr['content']['arhiv'], $section));
                    }
                    if (!empty($arr['subpage']))
                        foreach ($arr['subpage'] as $subname => $value) {
                            $section->subpage->$subname->form = $this->getHeader($value['form'], $section);
                            $section->subpage->$subname->group = $value['group'];
                        }
                }
            }
        }
        if ($this->ajaxId || $this->ajaxType) exit;

        $this->checkUrls($isShow);
        $footer = array();
        foreach ($this->footer as $key => $line) {
            $line = trim($line);
            if (in_array($line, $this->header, true)) {
                unset($this->footer[$key]);
                //$footer[] = $line;
            }
        }
    }

    public function checkUrls($isShow)
    {
        global $SE_REQUEST_NAME;
        $urllist = from_Url();
        if (empty($urllist) || !is_iterable($urllist)) {
            return;
        }
        $uri = $_SERVER['REQUEST_URI'];
        foreach ($urllist as $uname => $arr) {
            $find = false;
            @list($uname) = explode('[', urldecode($uname));
            if (empty($uname)) continue;
            if (is_numeric($uname) && empty($_GET[$uname]) && strpos($uri, '?') !== false) {
                list($url,) = explode('?', $uri);
                // echo $url[0];
                $this->go301($url);
            }
            foreach ($SE_REQUEST_NAME as $qname => $name) {
                if (strval($uname) == strval($qname) || !isset($arr) || isset($_GET[$uname]) || $isShow || $qname == $uri || isset($urllist[$uname])) {
                    $find = true;
                    break;
                }
            }
            if (!$find) {
                $this->go404();
            }
        }
        if (isset($_GET['page'])) {
            $this->go404();
        }
    }


    private function objectLinkName($sect_id, $object)
    {
        $urlname = (!empty($object->url)) ? $object->url : se_translite_url($object->title);
        $urlname = (is_numeric($urlname)) ? 'r' . $urlname : $urlname;
        if (empty($urlname)) {
            $urlname = $sect_id . '-' . $object->id;
        }
        return $urlname;
    }

    private function objectLink($sect_id, $object)
    {
        if ($sect_id < 100000)
            $pagelink = $this->pagename;
        else $pagelink = 'index';
        return seMultiDir() . '/' . $pagelink . '/' . $this->objectLinkName($sect_id, $object) . SE_END;
    }


    private function getOldUrl($nameurl)
    {
        if (preg_match("/\b([^_]+)?\_([^_]+)\_([^_]+)/", $nameurl, $m) || (getRequest('razdel', 0) && getRequest('object', 1))) {
            if (getRequest('object', 1)) {
                $this->req->razdel = getRequest('razdel', 0);
                $this->req->object = getRequest('object', 1);
            } else {
                $this->req->razdel = $m[2];
                if (strpos($m[3], 'sub') !== 0) {
                    $this->req->object = $m[3];
                    if ($this->req->object == 0) $this->go404();
                } else $this->req->sub = substr($m[3], 3);
            }
            $this->req->page = $this->pagename;
            return true;
        }
    }

    // Получить адрес страницы по имени
    public function getPagePattern($name)
    {
        $name = strval($name);
        if (!empty($this->URLS[$name]) && is_iterable($this->URLS[$name])) {
            foreach ($this->URLS[$name] as $urls) {
                if ($urls['action'] == 'page') {
                    return $urls['pattern'];
                }
            }
        }
    }

    // Парсер ссылок
    private function getFromUrl($nameurl)
    {
        $namepage = '';
        $param1 = $this->req->param[1] ?? '';
        if (!empty($param1) && $this->getOldUrl($param1)) {
            if ($this->req->razdel > 100000) {
                $sections = $this->prj->sections;
            } else {
                $sections = $this->page->sections;
            }
            $fs = $fo = false;
            if (!empty($sections) && is_iterable($sections)) {
                foreach ($sections as $section) {
                    if (strval($section->id) == $this->req->razdel) {
                        $fo = false;
                        $fs = true;
                        if (!empty($section->objects) && is_iterable($section->objects)) {
                            foreach ($section->objects as $object) {
                                if (strval($object->id) == $this->req->object) {
                                    $fo = true;
                                    $this->go301($this->objectLink($section->id, $object));
                                }
                            }
                        }
                        break;
                    }
                }
            }
            if ((!$fs && $this->req->razdel) || (!$fo && $this->req->object)) {
                $this->go404();
            }
        }
        $param2 = $this->req->param[2] ?? '';
        if (!empty($param2)) {
            if ($nameurl == $param1) {
                $sections = $this->page->sections;
            } else {
                $sections = $this->prj->sections;
            }
            if ($param1 == 'index') $param1 = '';
            $url = $param2;
            if (!empty($sections) && is_iterable($sections)) {
                foreach ($sections as $section) {
                    if (!empty($section->objects) && is_iterable($section->objects)) {
                        foreach ($section->objects as $object) {
                            if ($this->objectLinkName($section->id, $object) == $url) {
                                $this->req->razdel = $section->id;
                                $this->req->object = $object->id;
                                $nameurl = $param1;
                                break 2;
                            }
                        }
                    }
                }
            }
        }
        if ($newname = $this->getOldUrl($nameurl)) {
            return $newname;
        }
    }

    private function rootUrl()
    {
        if ($url = $this->getFromUrl($this->pagename)) {
            $this->pagename = $url;
        } else {
            $this->req->sub = (!getRequest('sub')) ? $this->req->sub : getRequest('sub');
            $this->req->razdel = (!getRequest('razdel', 0)) ? $this->req->razdel : getRequest('razdel', 0);
            $this->req->object = (!getRequest('object', 1)) ? $this->req->object : getRequest('object', 1);


            if ($this->req->object || $this->req->sub) {
                if (empty($_POST)) {
                    $razdel = strval($this->req->razdel);
                    list($num_sect,) = explode('.', $razdel);
                    if ($num_sect < 100000)
                        $pagelink = $this->pagename . '_';
                    else $pagelink = '_';
                    $addlink = '';
                    $offs = 0;
                    foreach ($this->req->param as $prm) {
                        if ($offs > 3 && $prm) {
                            if (strpos($prm, '?') === false) {
                                $addlink .= $prm . '/';
                            } else {
                                $addlink .= $prm;
                            }
                        }
                        $offs++;
                    }
                    $req_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
                    $req = isset($req_parts[1]) ? '?' . $req_parts[1] : '';
                    if (!$this->req->sub) {
                        //  $link = seMultiDir() . '/' . $pagelink.$this->req->razdel.'_sub'.$this->req->sub . '/' . $addlink . $req;
                        //else
                        //$link = seMultiDir() . '/' . $pagelink.$this->req->razdel . '_' . $this->req->object . '/' . $addlink . $req;
                        //$this->go301($link);
                    }
                }
            }
        }
        if (strpos($this->pagename, 'show') === 0) {
            if (!empty($_SESSION['SE'][$this->pagename]))
                $this->pagename = $_SESSION['SE'][$this->pagename];
            else
                if (!empty($_SESSION['se']['page']))
                $this->pagename = $_SESSION['se']['page'];
            else
                $this->pagename = substr($this->pagename, 4);
        }
    }

    public function getBreadCrumbs()
    {
        ob_start();
        include dirname(__FILE__) . '/tpl/breadcrumbs.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }


    private function redirectPage()
    {
        if (
            empty($_POST) && $this->pagename && ($_SERVER['REQUEST_URI'] == seMultiDir() . '/' . $this->pagename
                || $_SERVER['REQUEST_URI'] == seMultiDir() . '/' . $this->pagename . '.html')
        ) {
            $this->go301(seMultiDir() . '/' . $this->pagename . '/');
        }
        if (!preg_match("/[\?\&\=\.]/", $_SERVER['REQUEST_URI']) && substr($_SERVER['REQUEST_URI'], -1, 1) != '/') {
            $this->go301($_SERVER['REQUEST_URI'] . '/');
        }

        if (strpos($_SERVER['REQUEST_URI'], seMultiDir() . '/' . $this->pagename . '/') === 0) {
            $alias = explode('?', substr($_SERVER['REQUEST_URI'], strlen(seMultiDir() . '/' . $this->pagename . '/')), 2);
        }
    }


    // Получение ID раздела
    private function getAjaxId()
    {
        $reqajax = array_keys($_REQUEST);
        if (!empty($reqajax))
            foreach ($reqajax as $res) {
                if (strpos($res, 'ajax') !== false) {
                    if (preg_match("/ajax([\d\.]+)/", $res, $reqajax)) {
                        return strval($reqajax[1]);
                    }
                }
            }
        return false;
    }


    // Получение ID раздела
    private function getAjaxType()
    {
        $reqajax = array_keys($_REQUEST);
        if (!empty($reqajax))
            foreach ($reqajax as $res) {
                if (strpos($res, 'ajax') !== false) {
                    if (preg_match("/ajax_([\w]+)/", $res, $reqajax)) {
                        return strval($reqajax[1]);
                    }
                }
            }
        return false;
    }

    // Обработка Ajax запросов
    private function onAjax()
    {
        // Проверка на Ajax запросы
        $this->ajaxId = $this->getAjaxId();
        if ($this->ajaxId) {
            list($part_id) = explode('.', $this->ajaxId);
            if ($part_id < 100000) {
                $this->initpage();
            }
        } else {
            $this->ajaxType = $this->getAjaxType();
            if ($this->ajaxType) {
                $this->initpage();
            }
        }
    }

    /* Получить имя рабочей папки */
    private function getWorkFolder($namefile)
    {
        return ($this->editorAccess() && file_exists(SE_SAFE . 'projects/' . SE_DIR . 'edit/' . $namefile)
            && filemtime(SE_SAFE . 'projects/' . SE_DIR . 'edit/' . $namefile) > filemtime(SE_SAFE . 'projects/' . SE_DIR . $namefile)) ? 'edit/' : '';
    }

    // Загрузка файла проекта
    private function initprj()
    {
        $folder = $this->getWorkFolder('project.xml');
        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . $folder . 'project.xml')) {
            $this->prj = simplexml_load_file(SE_SAFE . 'projects/' . SE_DIR . $folder . 'project.xml');
            $this->startpage = (!empty($this->prj->vars->startpage)) ? strval($this->prj->vars->startpage) : 'home';
            if (SE_DB_ENABLE && file_exists(SE_LIBS . 'plugins/plugin_geo/plugin_geovalues.class.php')) {
                $gval = plugin_geovalues::getInstance();
                if (method_exists($gval, 'getAltPageName') && $gval->getAltPageName('home')) {
                    $this->startpage = $gval->getAltPageName('home');
                }
                if (empty($this->pagename)) $this->pagename = $this->startpage;
                if (method_exists($gval, 'getAltDesign') && $gval->getAltDesign($this->pagename)) {
                    $this->skin = $gval->getAltDesign($this->pagename);
                }
                if (method_exists($gval, 'getAltPageName') && $gval->getAltPageName($this->pagename)) {
                    $this->pagename = $gval->getAltPageName($this->pagename);
                }
            }
            define('SE_STARTPAGE', $this->startpage);
            if (strval($this->prj->vars->language) == '') {
                $this->prj->vars->language = 'rus';
            }
            if (strval($this->prj->sitedomain) && $this->prj->siteredirect == '1') {
                //$urlsite = strtolower(str_replace(array('http://', 'https://'), '', $this->prj->sitedomain));
                if (strpos($this->prj->sitedomain, '://') === false) $this->prj->sitedomain = _HTTP_ . $this->prj->sitedomain;
                if (_HTTP_ . $_SERVER['HTTP_HOST'] != $this->prj->sitedomain) {
                    $this->go301($this->prj->sitedomain . $_SERVER['REQUEST_URI']);
                }
            }
            if (strval($this->language) != strval($this->prj->vars->language)) {
                $this->language = strval($this->prj->vars->language);
                $fp = fopen(SE_SAFE . 'projects/' . SE_DIR . 'cache/project_lang.dat', "w+");
                fwrite($fp, $this->language);
                fclose($fp);
            }
            if (empty($this->pagename)) {
                $this->pagename = 'home';
                if (!empty($this->startpage)) {
                    $this->pagename = $this->startpage;
                    $folder = $this->getWorkFolder('pages/' . $this->pagename . '.xml');
                    if (!file_exists(SE_SAFE . 'projects/' . SE_DIR . $folder . 'pages/' . $this->pagename . '.xml')) {
                        $this->pagename = 'home';
                    }
                }
            }
            if (!$this->startpage || !file_exists(SE_SAFE . 'projects/' . SE_DIR . $folder . 'pages/' . $this->startpage . '.xml')) {
                $this->startpage = 'home';
            }
            $uri = $_SERVER['REQUEST_URI'];
            if (
                str_replace('/', '', $uri) == $this->startpage
                || ($uri == '/' . SE_DIR . $this->startpage . URL_END && seMultiDir() == '')
            ) {
                $this->go301(seMultiDir() . '/');
            }
            if (SE_DIR != '' && substr($uri, 1, strlen(SE_DIR)) == SE_DIR && seMultiDir() == '') {
                $uri = substr($uri, strlen(SE_DIR), strlen($uri));
                $this->go301($uri);
            }
        }
        $this->prj->wmgoogle = trim($this->prj->wmgoogle);
        $this->prj->wmyandex = trim($this->prj->wmyandex);
        if (!empty($this->prj->wmgoogle) && !file_exists(SE_ROOT . SE_DIR . $this->prj->wmgoogle) && strpos($this->prj->wmgoogle, '.html') !== false) {
            $fp = fopen(SE_ROOT . SE_DIR . $this->prj->wmgoogle, "w+");
            fwrite($fp, 'google-site-verification: ' . strval($this->prj->wmgoogle));
            fclose($fp);
        }
        if (!empty($this->prj->wmyandex)) {
            $this->headercss[] = '<meta name="yandex-verification" content="' . $this->prj->wmyandex . '" />';
        }

        if (!empty($this->prj->bootstraptools) || !empty($this->prj->adaptive)) {
            $this->footer[] = "<script src=\"/lib/js/jquery/jquery.min.js\"></script>";
        }
        if ((!empty($this->prj->adaptive) && !isset($this->prj->bootstraptools)) || $this->prj->bootstraptools == 1) {
            $this->headercss[] = '<link href="/lib/js/bootstrap/css/bootstrap.min.css" id="pageCSS" rel="stylesheet">';
            $this->footer[] = "<script src=\"/lib/js/bootstrap/bootstrap.min.js\"></script>";
            $this->footer[] = "<script src=\"/lib/js/bootstrap/bootstrap.init.js\"></script>";
        }

        $_SESSION['editor_page'] = strval($this->pagename);
        define('DEFAULT_LANG', strval($this->language));
        $this->path[0] = array('name' => $this->startpage, 'title' => '');
    }


    private function initpage()
    {
        $pname = $this->pagename;
        $folder = $this->getWorkFolder('pages/' . $pname . '.xml');
        if (!file_exists(SE_SAFE . 'projects/' . SE_DIR . $folder . 'pages/' . $pname . '.xml')) {
            $this->go404();
        } else {
            $this->lastmodif = filemtime(SE_SAFE . 'projects/' . SE_DIR . $folder . 'pages/' . $pname . '.xml');
        }
        if ($this->pagename == '404') {
            header('HTTP/1.0 404 File not found');
        }
        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . $folder . 'pages/' . $pname . '.xml')) {
            $this->page = simplexml_load_file(SE_SAFE . 'projects/' . SE_DIR . $folder . 'pages/' . $pname . '.xml');
            $_SESSION['se']['page'] = strval($pname);
        } else {
            $this->page = new SimpleXMLElement('<page></page>');
        }
        if ($this->skin) {
            $this->page->css = $this->skin;
        } else if (empty($this->page->css)) {
            $this->page->css = 'default';
        }

        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . 'cache/map_' . $this->page->css . '.json')) {
            $this->contarr = json_decode(file_get_contents(SE_SAFE . 'projects/' . SE_DIR . 'cache/map_' . $this->page->css . '.json'), true);
        }
    }

    private function initpages()
    {
        $folder = $this->getWorkFolder('pages.xml');
        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . $folder . 'pages.xml')) {
            $this->pages = simplexml_load_file(SE_SAFE . 'projects/' . SE_DIR . $folder . 'pages.xml');
        } else {
            $this->pages = new stdClass;
        }
        if (!$this->editorAccess()) {
            if (is_object($this->pages) && method_exists($this->pages, 'xpath')) {
                $result = $this->pages->xpath('page[@name="' . $this->pagename . '"]');
                if (empty($result)) {
                    if (file_exists(SE_SAFE . 'projects/' . SE_DIR . 'pages/' . $this->pagename . '.xml')) {
                        unlink(SE_SAFE . 'projects/' . SE_DIR . 'pages/' . $this->pagename . '.xml');
                    }
                    //echo $url = $this->getAltUrlList($this->pagename);

                    if ($url = $this->findUrlList($this->pagename)) {
                        $this->go301($url);
                    }
                    //}

                    //elseif ($url = $this->getAltUrlList($nameurl)) {
                    //echo $url;
                    //exit;
                    //$this->go301(seMultiDir().'/'.$url.'/');
                    //}
                    //$this->go404();
                }
            }
        }
    }

    // Загрузка файлов меню
    private function initmenu()
    {
        $folder = $this->getWorkFolder('pagemenu.xml');
        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . $folder . 'pagemenu.xml')) {
            $this->pagemenu = simplexml_load_file(SE_SAFE . 'projects/' . SE_DIR . $folder . 'pagemenu.xml');
        } else {
            $this->pagemenu = new stdClass;
        }

        $folder = $this->getWorkFolder('mainmenu.xml');
        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . $folder . 'mainmenu.xml')) {
            $this->mainmenu = simplexml_load_file(SE_SAFE . 'projects/' . SE_DIR . $folder . 'mainmenu.xml');
        } else {
            $this->mainmenu = new stdClass;
        }
    }

    public function go301($url)
    {
        if (empty($_SESSION['EDITOR_ADMIN'])) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $url);
            exit;
        }
    }

    public function go302($url)
    {
        header("HTTP/1.1 302 Moved Permanently");
        header("Location: " . $url);
        exit;
    }

    public function getHTTP($url)
    {
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($c);
        curl_close($c);
        return $content;
    }


    public function go404()
    {
        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . 'pages/404.xml')) {
            header('HTTP/1.0 404 File not found');
            print $this->getHTTP(_HOST_ . seMultiDir() . '/404/');
        } elseif (file_exists(SE_SAFE . 'projects/' . SE_DIR . 'pages/page404.xml')) {
            header('HTTP/1.0 404 File not found');
            print $this->getHTTP(_HOST_ . seMultiDir() . '/page404/');
        } else {
            header('HTTP/1.0 404 File not found');
        }
        exit;
    }

    public function include_tpl($section, $name)
    {
        $pagename = ($section->id > 100000) ? '_' : $this->pagename;
        $path_cache = SE_SAFE . "projects/" . SE_DIR . 'cache/' . $pagename . '/';
        $filename = $section->type . '_' . $name . '_' . $section->id . '.tpl';
        $objname = str_replace('subpage_', '', $name);
        $subname = str_replace('subpage_', 'sub', $name);
        $is_source = (!empty($section->sources->$subname) || !empty($section->sources->$objname));
        if (file_exists($path_cache . $filename) && $is_source) {
            return $path_cache . $filename;
        } else {
            if (file_exists($path_cache . $filename)) unlink($path_cache . $filename);
            $MDL_ROOT = getcwd() . $this->getFolderModule($section->type) . '/' . strval($section->type);
            return $MDL_ROOT . '/tpl/' . $name . '.tpl';
        }
    }

    public function link_tpl($section, $name)
    {
        $pagename = ($section->id > 100000) ? '_' : $this->pagename;
        $path_cache = "projects/" . SE_DIR . 'cache/' . $pagename . '/';
        $filename = $section->type . '_' . $name . '_' . $section->id . '.tpl';
        $objname = str_replace('subpage_', '', $name);
        $subname = str_replace('subpage_', 'sub', $name);
        $is_source = (!empty($section->sources->$subname) || !empty($section->sources->$objname));
        if (file_exists(SE_SAFE . $path_cache . $filename) && $is_source) {
            return '/' . $path_cache . $filename;
        } else {
            if (file_exists(SE_SAFE . $path_cache . $filename)) unlink(SE_SAFE . $path_cache . $filename);
            $MDL_ROOT = $this->getFolderModule($section->type) . '/' . strval($section->type);
            return $MDL_ROOT . '/tpl/' . $name . '.tpl';
        }
    }

    private function getLinkStyle($section, $modulepath)
    {
        $link = '';
        list($nametype) = explode('.', strval($section->type));
        $cssfolder = $this->getSkinService() . '/' . $nametype;
        if (!file_exists($cssfolder . '/style.css')) {
            $cssfolder = $modulepath;
        } else {
            $cssfolder = '/' . $cssfolder;
        }
        if (file_exists(getcwd() . $cssfolder . '/style.css') && intval($section->oncss)) {
            $link = '<link href="' . $cssfolder . '/style.css" rel="stylesheet">';
        }
        return $link;
    }

    private function parseParams($section)
    {
        if (empty($section->parametrs)) {
            return;
        }
        foreach ($section->parametrs as $param) {
            foreach ($param as $name => $value) {
                while (preg_match("/\[%([\w\d\-]+)%\]/u", $value, $m) != false) {
                    if (empty($m[1])) {
                        break;
                    }
                    $__result = $this->prj->vars->{$m[1]};
                    $value = str_replace($m[0], $__result, $value);
                }
                $section->parametrs->$name = $value;
            }
        }
    }

    private function getSectionNumber($sectionId)
    {
        $section_parts = explode('.', strval($sectionId), 2);
        $sectionId = $section_parts[0];
        return intval($sectionId);
    }

    public function getArhivUrl($sectionId)
    {
        $pagelink =  ($this->getSectionNumber($sectionId) < 100000) ? strval($this->pagename) : 'index';
        if (!empty($this->URLS[$pagelink]) && is_iterable($this->URLS[$pagelink])) {
            foreach ($this->URLS[$pagelink] as $url) {
                if ($url['action'] == 'arhiv' && $url['id'] == $sectionId) {
                    return $url['pattern'];
                }
            }
        }
    }

    public function getStatusService($servicename, $fl = true)
    {
        if ($fl && (SE_ALL_SERVICES || $this->editorAccess())) return true;
        if ($this->services != null) {
            if (!empty($this->services->module))
                foreach ($this->services->module as $serv) {
                    if (strval($serv['name']) == strval($servicename) && $serv[0] == 1) {
                        return true;
                    }
                }

            // Если модуль пользователя
            if (!empty($this->services->packet) && preg_match("/\bmain_/", $servicename) && $this->services->packet == 'usermodule') {
                return true;
            }
            return false;
        } else return true;
    }

    public function getSkinService()
    {
        return SE_WWWDATA . SE_DIR . 'skin';
    }

    public function getThisService($serv)
    {
        if ($this->services != null) {
            if (!empty($this->services->$serv))
                return $this->services->$serv;
        }
        return false;
    }


    private function redirect($namepage)
    {
        if (file_exists(SE_SAFE . 'projects/urlredirect.dat')) {
            $redirect = file(SE_SAFE . 'projects/urlredirect.dat');
            @list($oldurl,) = explode('?', $_SERVER['REQUEST_URI']);
            $url_in = autoencode($oldurl);
            $host = $_SERVER['HTTP_HOST'];
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            
            foreach ($redirect as $ur) {
                $ur = explode("\t", trim($ur));
                if (count($ur) !== 2) continue; // Пропускаем некорректные строки
                
                $pattern = trim($ur[0]);
                $target = trim($ur[1]);
                
                // Удаляем протокол и хост из паттерна для сравнения, если они есть
                $pattern_clean = preg_replace('#^https?://[^/]+#', '', $pattern);
                if ($pattern_clean === '') $pattern_clean = '/';
                
                // Если в паттерне есть *, убираем последний слэш для сравнения
                $has_wildcard = strpos($pattern_clean, '*') !== false;
                if ($has_wildcard) {
                    $url_in_compare = rtrim($url_in, '/');
                    $pattern_clean = rtrim($pattern_clean, '/');
                } else {
                    $url_in_compare = $url_in;
                }

                $pattern_regex = str_replace('*', '(.*)', str_replace('\*', '*', preg_quote($pattern_clean, '/')));

                // Проверяем совпадение URL с паттерном
                if (preg_match("/^$pattern_regex$/", $url_in_compare, $matches)) {
                    // Формируем целевой URL

                    $redirect_url = $target;
                    if (isset($matches[1])) {
                        $redirect_url = str_replace('*', $matches[1], $target);
                    }
                    
                    // Обрабатываем относительные и абсолютные URL
                    if (strpos($redirect_url, '://') === false) {
                        // Для относительных путей добавляем протокол и хост
                        $redirect_url = rtrim($protocol . $host, '/') . '/' . ltrim($redirect_url, '/');
                    }
                    //$this->log($redirect_url);
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: $redirect_url");
                    exit;
                }
            }
        }
        
        if (!empty($namepage) && file_exists($namepage) && !is_dir($namepage)) {
            echo join('', file($namepage));
            exit;
        }
    }
/*
    private function redirect($namepage)
    {
        if (file_exists(SE_SAFE . 'projects/urlredirect.dat')) {
            $redirect = file(SE_SAFE . 'projects/urlredirect.dat');
            @list($oldurl,) = explode('?', $_SERVER['REQUEST_URI']);
            foreach ($redirect as $ur) {
                $ur = explode("\t", $ur);
                $url_in = $_SERVER['HTTP_HOST'] . autoencode($oldurl);
                $url_find = str_replace('http://', '', str_replace('$1', '', trim($ur[1])));
                if (strpos($url_in, $url_find) !== false && strpos($url_in, $url_find) == 0) {
                    continue;
                }
                @list($url_protocol, $url_start) = explode('://', autoencode($ur[0]));
                if (!$url_protocol) {
                    $url_start = autoencode($ur[0]);
                }
                if (
                    $ur[0] != '' && (autoencode($oldurl) == autoencode($ur[0])
                        || ($_SERVER['HTTP_HOST'] . autoencode($oldurl) == $url_start)
                        || (autoencode(urldecode($_SERVER['REQUEST_URI'])) == $url_start))
                ) {
                    if (autoencode($oldurl) == '/' && strpos($ur[1], '://') === false) {
                        return trim(str_replace('/', '', $ur[1]));
                    } else {
                        header("HTTP/1.1 301 Moved Permanently");
                        header("Location: " . str_replace('$1', $oldurl, $ur[1]));
                        exit;
                    }
                } elseif ($ur[0] != '' && $_SERVER['HTTP_HOST'] == autoencode(str_replace(array('http://', 'https://'), '', $ur[0])) && strpos($ur[1], '://') !== false) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: " . str_replace('$1', $oldurl, $ur[1]));
                    exit;
                }
            }
        }
        if (file_exists($namepage) && !is_dir($namepage)) {
            echo join('', file($namepage));
            exit;
        }
    }
*/
    private function openUrlList()
    {
        $fileurl = SE_SAFE . 'projects/' . SE_DIR;
        if (file_exists($fileurl . '/roots.url')) {
            $this->urllist = json_decode(join('', file($fileurl . '/roots.url')), true);
        }
    }


    private function findUrlList($urlname, $fullpath = '')
    {
        if (!empty($this->urllist[$urlname])) {
            return $this->urllist[$urlname];
        }
    }


    public function getVirtualPage($type)
    {
        if (file_exists(SE_SAFE . 'projects/' . SE_DIR . 'types/' . $type)) {
            $ftype = file(SE_SAFE . 'projects/' . SE_DIR . 'types/' . $type);
            foreach ($ftype as $item) {
                return $item;
            }
        }
    }

    public function setVirtualPage($namepage, $type = 'text', $rewrite = false)
    {
        if ($namepage) {
            if (!file_exists(SE_SAFE . 'projects/' . SE_DIR . 'types/')) {
                mkdir(SE_SAFE . 'projects/' . SE_DIR . 'types/');
            }
            if (
                !file_exists(SE_SAFE . 'projects/' . SE_DIR . 'types/' . $type)
                || filemtime(SE_SAFE . 'projects/' . SE_DIR . 'types/' . $type) < filemtime(SE_SAFE . 'projects/' . SE_DIR . 'project.xml')
                || $rewrite || $this->getVirtualPage($type) != $namepage
            ) {
                $fp = fopen(SE_SAFE . 'projects/' . SE_DIR . 'types/' . $type, 'w+');
                fwrite($fp, $namepage);
                fclose($fp);
            }
        }
    }


    public function showSection($section, $add = true)
    {
        $nametype = $section->type;
        $id_content = strval($section->id);
        $root = getcwd() . $this->getFolderModule($nametype) . '/mdl_' . $nametype . '.php';
        if (file_exists($root)) require_once($root);
        if (function_exists('module_' . $nametype)) {
            if (!empty($section->objects) && is_iterable($section->objects)) {
                foreach ($section->objects as $object) {
                    if (empty($object->title_tag)) $object->title_tag = 'h4';
                    $link = seMultiDir() . '/' . htmlspecialchars($this->pagename) . '/' . $id_content . '/' . $object->id . '/';
                    $object->link_detail = $link;
                    if (!empty($object->image)) {
                        $prev = explode('.', $object->image);
                        $object->image_prev = $prev[0] . '_prev.' . $prev[1];
                    }
                }
            }
            $arr = call_user_func_array('module_' . $nametype, array($id_content, $section));
            if ($section->type == '') $section->type = 'mtext';
            return $this->addClassSection($section, $this->getHeader(replace_values($arr['content']['form']), $section), $add);
        }
    }

    private function getSrcNameScript($tag)
    {
        $jsname = '';
        if (preg_match("/src=\"(.+?)\"/", $tag, $jsname)) {
            $jsname = basename($jsname[1]);
        }
        return $jsname;
    }

    private function getUrlNameLink($tag)
    {
        $jsname = '';
        if (preg_match("/href=\"(.+?)\"/", $tag, $jsname)) {
            $jsname = basename($jsname[1]);
        }
        return $jsname;
    }


    private function parseHeader($header, $in)
    {
        $in = preg_replace("/\[js:([\w\d\.\/\-]+)\]/", "<script src=\"/lib/js/$1\"></script>", $in);
        $in = trim(preg_replace("/\[lnk:([\w\d\.\/\-]+)\]/", "<link rel=\"stylesheet\" href=\"/lib/js/$1\">", $in));
        preg_match_all("/<style.+?<\/style>/usim", $in, $arrheaderstyle);
        preg_match_all("/<script.+?<\/script>/usim", $in, $arrheaderjs);
        preg_match_all("/<link.+?>/usim", $in, $arrheaderlink);
        foreach ($arrheaderjs[0] as $link) {
            $link = trim($link);
            if ($link && !in_array($link, $header, true)) {
                $header[] = $link;
            }
        }

        foreach ($arrheaderstyle[0] as $link) {
            $link = trim($link);
            if ($link && !in_array($link, $header, true)) {
                $header[] = $link;
            }
        }


        foreach ($arrheaderlink[0] as $link) {
            $link = trim($link);
            if ($link && !in_array($link, $header, true)) {
                $header[] = $link;
            }
        }
        return $header;
    }


    public function getHeader($text, $section)
    {
        $modulefolder = '';
        if (!empty($section->type)) {
            $modulefolder = $this->getFolderModule(strval($section->type)) . '/' . strval($section->type);
        }
        while ($modulefolder && preg_match("/\[include_js(\(.*?\))?\]/isum", $text, $m)) {
            $jsfile = $modulefolder . '/' . $section->type . '.js';
            if (file_exists(getcwd() . $jsfile)) {
                $s1 = "\r\n<script src=\"{$jsfile}\"></script>";
                $s1 .= "\r\n<script> {$section->type}_execute(";
                if (!empty($m[1])) $s1 .= utf8_substr($m[1], 1, -1);
                $s1 .= ');</script>';
            } else {
                $s1 = "\r\n<script src=\"{$modulefolder}/engine.js\"></script>";
            }
            $text = str_replace($m[0], $s1, $text);
        }

        while ($modulefolder && preg_match("/\[include_css\]/imu", $text, $m)) {
            $section->oncss = 1;
            $link = $this->getLinkStyle($section, $modulefolder);
            $text = str_replace($m[0], $link, $text);
        }

        while ($modulefolder && preg_match("/\[module_js:([^\]]*)\]/imu", $text, $m)) {
            $s1 = "\r\n<script src=\"{$modulefolder}/{$m[1]}\"></script>";
            $text = str_replace($m[0], $s1, $text);
        }

        while (preg_match("/<header:js>(.+?)<\/header:js>/usim", $text, $m)) {
            if (!empty($modulefolder)) {
                $m[1] = str_replace(array("[this_url_modul]", "[module_url]"), $modulefolder . '/', $m[1]);
            }
            $this->header = $this->parseHeader($this->header, $m[1]);
            $text = str_replace($m[0], '', $text);
        }
        // header css
        while (preg_match("/<header:css>(.+?)<\/header:css>/usim", $text, $m)) {
            if (!empty($modulefolder)) {
                $m[1] = str_replace(array("[this_url_modul]", "[module_url]"), $modulefolder . '/', $m[1]);
            }
            $this->headercss = $this->parseHeader($this->headercss, $m[1]);
            $text = str_replace($m[0], '', $text);
        }

        // footer css
        while (preg_match("/<footer:css>(.+?)<\/footer:css>/usim", $text, $m)) {
            if (!empty($modulefolder)) {
                $m[1] = str_replace(array("[this_url_modul]", "[module_url]"), $modulefolder . '/', $m[1]);
            }
            $this->footercss = $this->parseHeader($this->footercss, $m[1]);
            $text = str_replace($m[0], '', $text);
        }

        while (preg_match("/<footer:html>(.+?)<\/footer:html>/usim", $text, $m)) {
            if (!empty($modulefolder)) {
                $m[1] = str_replace(array("[this_url_modul]", "[module_url]"), $modulefolder . '/', $m[1]);
            }
            $this->footerhtml .= $m[1];
            $text = str_replace($m[0], '', $text);
        }

        // footer js
        while (preg_match("/<footer:js>(.+?)<\/footer:js>/usim", $text, $m)) {
            if (!empty($modulefolder)) {
                $m[1] = str_replace(array("[this_url_modul]", "[module_url]"), $modulefolder . '/', $m[1]);
            }
            $this->footer = $this->parseHeader($this->footer, $m[1]);
            $text = str_replace($m[0], '', $text);
        }
        return $text;
    }


    public function getFolderModule($type)
    {
        $pathalt = '/lib';
        $path = '/modules';

        if (
            file_exists(getcwd() . $pathalt . $path . '/module_' . $type . '.class.php')
            || file_exists(getcwd() . $pathalt . $path . '/mdl_' . $type . '.php')
        ) {
            return $pathalt . $path;
        } else
            if (
            file_exists(getcwd() . $path . '/module_' . $type . '.class.php')
            || file_exists(getcwd() . $path . '/mdl_' . $type . '.php')
        ) {
            return $path;
        }
        return;
    }

    public function getPathArray()
    {
        $level_arr = array();
        $endlevel = 0;
        if ($this->startpage != $this->pagename) {
            foreach ($this->pages as $page) {
                $level = $page->level;
                if ($level < 1) $level = 1;
                $name = strval($page['name']);
                if (!empty($name)) {
                    $level_arr[$level - 1]['name'] = $name;
                    $level_arr[$level - 1]['title'] = strval($page->title);
                    if ($name == $this->pagename) {
                        $endlevel = $level - 1;
                        break;
                    }
                }
            }
        }
        $tmparr = array();

        foreach ($level_arr as $level => $data) {
            if ($level <= $endlevel)
                $tmparr[$level] = $data;
        }
        return $tmparr;
    }


    // Хлебные крошки
    public function getPathLinks($space = '/', $endtitle = 'Home')
    {
        // Главная страница
        $startpage = $this->startpage;
        if (empty($startpage)) $startpage = 'home';
        $link = '';
        $level_arr = $this->getPathArray();
        $level_num = 1;
        foreach ($this->pages as $page) {
            if (strval($page['name']) == $startpage) {
                $link = ' <span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="' . seMultiDir() . '/">
                <span itemprop="name">' . $page->title . '</span>
                </a>
                <meta itemprop="position" content="' . $level_num . '" />
                </span> ';
                $level_num++;
                break;
            }
        }

        $linkTemplate = '';     //  если НЕ пустой, то значит мы находимся на субстранице
        if ($this->req->razdel && $this->req->object) {

            $razdel_key = strval($this->req->razdel);
            if (!empty($this->sections[$razdel_key])
                && !empty($this->sections[$razdel_key]->objects)
                && is_iterable($this->sections[$razdel_key]->objects)) {
                $objects = $this->sections[$razdel_key]->objects;
                foreach ($objects as $object) {
                    if ($object->id == intval($this->req->object)) {
                        $linkTemplate = '<span class="space">' . $space . '</span> ' . '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <span class="endtitle" itemprop="name">' . $object->title . '</span>
                    <meta itemprop="position" content="' . $level_num . '" />
                    </span> ';
                        $level_num++;
                        break;
                    }
                }
            }
        }

        $getLastElement = end($level_arr);  //  вытаскиваем последний элемент для того, чтобы он далее не стал ссылкой
        $getLastTitle = (is_array($getLastElement) && isset($getLastElement['title'])) ? $getLastElement['title'] : '';
        foreach ($level_arr as $line) {
            if ($line['name'] == $startpage) continue;
            if (empty($line['name'])) break;
            if (($linkTemplate == '') && ($getLastTitle == $line['title'])) {
                $link .= '<span class="space">' . $space . '</span> <span itemprop="itemListElement" itemscope  itemtype="http://schema.org/ListItem">
                <span itemprop="name">' . $line['title'] . '</span>
                <meta itemprop="position" content="' . $level_num . '" />
                </span> ';
            } else {
                $link .= '<span class="space">' . $space . '</span> <span itemprop="itemListElement" itemscope  itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="' . seMultiDir() . '/' . $line['name'] . SE_END . '">
                <span itemprop="name">' . $line['title'] . '</span>
                </a>
                <meta itemprop="position" content="' . $level_num . '" />
                </span> ';
            }
            $level_num++;
        }
        $link .= $linkTemplate;
        if ($endtitle != '') {
            $link .= '<span class="space">' . $space . '</span> <span itemprop="itemListElement" itemscope  itemtype="http://schema.org/ListItem">
            <span class="endtitle" itemprop="name">' . $endtitle . '</span>
            <meta itemprop="position" content="' . $level_num . '" />
            </span>';
        }
        $link = '<span itemscope itemtype="http://schema.org/BreadcrumbList">' . $link . '</span>';

        return $link;
    }

    public static function getInstance($namepage = '', $dir = '')
    {
        if (self::$instance === null) {
            self::$instance = new self($namepage, $dir);
        }
        return self::$instance;
    }

    public function getPageName()
    {
        return strval($this->pagename);
    }

    public function getLinkPageName()
    {
        return ($this->getPageName() == strval($this->startpage)) ? seMultiDir() . URL_END : seMultiDir() . '/' . $this->getPageName() . URL_END;
    }

    public function setHead($head)
    {
        self::$instance->page->head = $head;
    }

    public function getPages()
    {
        return self::$instance->pages;
    }

    public function setPageTitle($titlepage)
    {
        self::$instance->page->titlepage = $titlepage;
    }

    public function getObject($section, $id_object)
    {
        if ($id_object) {
            if (!empty($section->objects) && is_iterable($section->objects)) {
                foreach ($section->objects as $object) {
                    if (intval($object->id) == $id_object) {
                        return $object;
                    }
                }
            }
            if (strval($section->id) == strval($this->req->razdel)) {
                $this->go404(); // 301(seMultiDir().'/'.$this->getPageName().'/');
            }
        }
    }

    public function getSectionContent($id_content, $scope = null)
    {
        if (is_null($scope)) {
            $scope = $this->sections;
        }
        $section_array = array();
        if (!empty($scope) && is_iterable($scope)) {
            foreach ($scope as $section) {
                $id_section = strval($section->id);
                list($id_section,) = explode('.', $id_section);
                if (floor($id_section / 1000) == $id_content) {
                    $section_array[] = $section;
                }
            }
        }
        return $section_array;
    }

    public function getSection($id_section)
    {
        if ($id_section) {
            if (!empty($this->sections) && is_iterable($this->sections)) {
                foreach ($this->sections as $section) {
                    if (strval($section['name']) == strval($id_section)) {
                        return $section;
                    }
                }
            }
            if (!$this->editorAccess())
                $this->go301(seMultiDir() . '/' . $this->getPageName() . '/');
        }
    }

    public function deleteSection($id_section)
    {
        $i = 0;
        if (!$id_section) return;
        if ($id_section < 10000) {
            if (!empty($this->page->sections) && is_iterable($this->page->sections)) {
                foreach ($this->page->sections as $section) {
                    if (strval($section['name']) === $id_section) {
                        unset($this->page->sections[$i]);
                        break;
                    }
                    $i++;
                }
            }
        } else {
            if (!empty($this->prj->sections) && is_iterable($this->prj->sections)) {
                foreach ($this->prj->sections as $section) {
                    if (strval($section['name']) === $id_section) {
                        unset($this->prj->sections[$i]);
                        break;
                    }
                    $i++;
                }
            }
        }
    }


    public function getMaxSection($id_content, $sections = null)
    {
        if ($sections == null) $sections = $this->sections;
        $max = $id_content * 1000;
        if (!empty($sections) && is_iterable($sections)) {
            foreach ($sections as $section) {
                if (intval($section->id) < $id_content * 1000 || intval($section->id) > $id_content * 1000 + 1000) {
                    continue;
                }
                if (intval($section->id) > $max) {
                    $max = intval($section->id);
                }
            }
        }
        return $max;
    }

    public function setList($section, $nameobject, $array)
    {
        unset($section->$nameobject);
        foreach ($array as $line)
            add_simplexml_from_array($section, $nameobject, $line);
    }

    public function setItemList($section, $nameobject, $itemarray)
    {
        add_simplexml_from_array($section, $nameobject, $itemarray);
    }

    public function goSubName($section, $subname)
    {
        $this->req->razdel = $section->id;
        $this->req->sub = $subname;
    }

    public function limitObjects($section, $limit = -1, $sort = 0)
    {
        if ($limit < 0) $limit = isset($section->objectcount) ? intval($section->objectcount) : 0;
        if ($limit < 1) $limit = 30;
        $_item = getRequest('item', 1);
        $page = $this->getPageName();
        if (!empty($_SESSION['SE'][$page . '_' . $section . '_item'])) {
            $_item = $_SESSION['SE'][$page . '_' . $section . '_item'];
        } else {
            $_item = getRequest('item', 1);
        }
        if ($_item < 1) {
            $_item = 1;
        }
        $objects_count = (!empty($section->objects) && is_iterable($section->objects)) ? count($section->objects) : 0;
        if ($limit) {
            if ($_item * intval($limit) >= $objects_count)
                $_item = ($objects_count > 0) ? ceil($objects_count / $limit) : 1;

            if ($_item < 1) {
                $_item = 1;
            }

            $startitem = ($_item - 1) * $limit;
            $enditem = ($_item * $limit);
        } else {
            $startitem = 0;
            $enditem = $objects_count;
        }
        if (!$sort) {
            $objects = (!empty($section->objects) && is_iterable($section->objects)) ? $section->objects : array();
        } else {
            $objects = array();
            if (!empty($section->objects) && is_iterable($section->objects)) {
                $k = count($section->objects);
                foreach ($section->objects as $it) {
                    $objects[] = $section->objects[$k - 1];
                    $k--;
                }
            }
        }
        $i = 0;
        unset($section->records);
        foreach ($objects as $record) {
            if (empty($record) || $record->visible == 'off') continue;
            $i++;
            if ($i <= $startitem) continue;
            if ($i > $enditem) break;
            if ($record->text1 != '') list($record->text1) = explode('|', $record->text1);
            $record->row = $i;
            $this->setItemList($section, 'records', $record);
        }
        return $section->records;
    }

    public function getVars($type, $name)
    {
        $result = '';
        if ($name == 'enteringtext' || $name == 'closingtext' || $name == 'title') {
            $result = $this->page->$name;
        } else {
            if ($name == 'reklamform' && $this->getThisService('reclam') && file_exists($this->getSkinService() . '/reclam.dat')) {
                $result = join('', file($this->getSkinService() . '/reclam.dat'));
            } else
                $result = $this->$type->vars->$name;

            if ($name == 'newsform') {
                $result = skin_news($result);
            }
        }

        if (utf8_strpos($result, '[') !== false) {
            $result = replace_values($result);
        } else {
            $result = replace_link($result);
        }

        if ($this->editorAccess() && !$_SESSION['siteediteditor']) {
            $result = "<span data-editvar=\"{$type}_{$name}\">" . $result . "</span>";
        }
        return $result;
    }

    public function getModuleOption($type_name, $param = 'interface')
    {
        //$type_name = $section->type;
        $root = getcwd() . $this->getFolderModule($type_name) . '/' . strval($type_name) . '/property/option.xml';
        if (file_exists($root)) {
            $res = simplexml_load_file($root);
            //echo '!'.$res->$param.'!';
            return intval($res->$param);
        }
    }

    public function setEditorLinks($section, $text)
    {
        $text = $this->addClassSection($section, $text);
        return $text;
    }

    public function addClassSection($section, $text)
    {
        // Определяем, можно ли добавлять записи
        if ($this->editorAccess()) {
            if (trim($section->type) == '') $section->type = 'mtext';
            $records = ($this->getModuleOption(trim($section->type))) ? ' data-records="true"' : '';
            if (preg_match("/[\s]class=[\"]content([^>]+)/", $text, $m)) {
                $nm = $m[0];
                if (strpos($m[1], 'data-id') === false) {
                    $nm .= ' data-id="' . $section->id . '"';
                }
                $text = str_replace($m[0], str_replace('"content', '"content se-section-block', $nm) . ' data-event-dbl="frame_edit" data-subject="section" data-target="frame"' . $records, $text);
            }
        }
        return $text;
    }


    // Методы для работы с редактором
    public function editor()
    {
    }

    public function editorHeader()
    {
        if ($this->editorAccess()) {
            //include SE_CORE .'editor/header_editor.tpl';
            echo '<!-- EDITORMODE:' . $_SESSION['siteediteditor'] . ' -->';
        } else {
            echo '<!-- EDITORMODE:disabled -->';
        }
    }

    public function editorAccess()
    {
        $_SESSION['editor_images_access'] = seUserGroup();
        return (seUserGroup() == 3 && $_SESSION['EDITOR_ADMIN']);
    }

    public function editItemRecord($section_id, $record_id)
    {
        //return $this->editor->editItemRecord($section_id, $record_id);
    }

    public function editorAddPhotos($section)
    {
        //return $this->editor->editorAddPhotos($section);
    }

    public function linkEditRecord($section_id, $record_id, $type)
    {
        //return $this->editor->linkEditRecord($section_id, $record_id, $type);
    }

    public function recordsWrapperStart($id)
    {
        //$this->editor->recordsWrapperStart($id);
    }

    public function recordsWrapperEnd()
    {
        // $this->editor->recordsWrapperEnd();
    }

    public function linkAddRecord($section_id)
    {
    }

    public function groupWrapper($content_id, $text)
    {
        return $text;
    }

    private function getParseMenu($text, $section)
    {
        while (preg_match("/<createmenu:item\-([^>]+)>(.+?)<\/createmenu>/umis", $text, $m)) {
            if (!preg_match("/[\'\"]?\[param([^\]]+)\][\'\"]?/im", $m[1], $m1)) {
                $m[1] = '"' . $m[1] . '"';
            }
            while (preg_match("/[\'\"]?\[param([^\]]+)\][\'\"]?/im", $m[1], $m1)) {
                $m[1] = str_replace($m1[0], '$section->parametrs->param' . $m1[1], $m[1]);
            }
            $text = str_replace($m[0], '<? if(function_exists(\'getItemMenu\')){ list($menuitems) = getItemMenu(' . $m[1] . '); $__data->setList($section,\'menuitems\', $menuitems);} ?>' . $m[2], $text);
        }
        return $text;
    }

    private function conditions($text)
    {
        while (
            preg_match("/\<if:\(\s?(.+?)\s?\)\>/im", $text, $m)
            || preg_match("/\<if:\s?([^\>]+)\s?\>/im", $text, $m)
        ) {
            $m[1] = str_replace('[thispage.link]', '$__data->getLinkPageName()', $m[1]);
            $m[1] = str_replace('[arhiv.link]', '<?php echo seMultiDir()."/".$__data->getPageName()."/".$section->id."/arhiv/" ?>', $m[1]);
            $m[1] = str_replace('[thispage.name]', '$__data->getPageName()', $m[1]);

            while (preg_match("/\[sys\.isrequest.([^\]]+)\]/im", $m[1], $m1)) {
                $m[1] = str_replace($m1[0], "isRequest('" . $m1[1] . "')", $m[1]);
            }

            while (preg_match("/\[sys\.request\.([^\]]+)\]/im", $m[1], $m1)) {
                $m[1] = str_replace($m1[0], "getRequest('" . $m1[1] . "', 3)", $m[1]);
            }
            while (preg_match("/[\'\"]?\[params\.param([^\]]+)\][\'\"]?/im", $m[1], $m1)) {
                $m[1] = str_replace($m1[0], 'trim($section->parametrs->param' . $m1[1] . ')', $m[1]);
            }
            while (preg_match("/[\'\"]?\[param([^\]]+)\][\'\"]?/im", $m[1], $m1)) {
                $m[1] = str_replace($m1[0], 'trim($section->parametrs->param' . $m1[1] . ')', $m[1]);
            }
            while (preg_match("/[\'\"]?\[%(site[\d\w]+)%\][\'\"]?/im", $m[1], $m1)) {
                $m[1] = str_replace($m1[0], 'strval($__data->prj->vars->' . $m1[1] . ')', $m[1]);
            }
            while (preg_match("/\[([^\.]+)\.([^\]]+)\](\.html)/im", $m[1], $mm)) {
                $m[1] = str_replace($mm[0], '$' . $mm[1] . '->' . $mm[2] . '.\'' . $mm[3] . '\'', $m[1]);
            }
            while (preg_match("/\b\[([^\.]+)\.([^\]]+)\]\$/im", $m[1], $mm)) {
                $m[1] = str_replace($mm[0], '!empty($' . $mm[1] . '->' . $mm[2] . ')', $m[1]);
            }
            while (preg_match("/\[([^\.]+)\.([^\]]+)\]/im", $m[1], $mm)) {
                $m[1] = str_replace($mm[0], '$' . $mm[1] . '->' . $mm[2], $m[1]);
            }

            $arr = array('{', '}');
            $m[1] = str_replace($arr, '', $m[1]);
            $text = str_replace($m[0], "<?php if({$m[1]}): ?>", $text);
        }
        return $text;
    }

    public function parseModule($tpl, $section)
    {
        $tpl = str_replace(array('<serv>', '</serv>', '<SERV>', '</SERV>'), '', $tpl);

        $tpl = preg_replace("/<se>(.+?)<\/se>/imus", "", $tpl);
        $tpl = preg_replace("/\[#\"(.+?)\"\]/imus", "$1", $tpl);
        $tpl = preg_replace("/\[se\.\"(.+?)\"\]/imus", "", $tpl);
        $tpl = str_replace(array('[contedit]'), '', $tpl);
        $tpl = str_replace('[menu.mainmenu]', '<?php echo fmainmenu(0) ?>', $tpl);
        $tpl = str_replace('[menu.mainhoriz]', '<?php echo fmainmenu(1) ?>', $tpl);
        $tpl = str_replace('[menu.mainvert]', '<?php echo fmainmenu(2) ?>', $tpl);
        $tpl = str_replace('[menu.pagemenu]', '<?php echo pageMenu() ?>', $tpl);
        while (preg_match("/\[menu.item-(\d{1,})\]/i", $tpl, $mm)) {
            $tpl = str_replace("[menu.item-" . $mm[1] . "]", '<?php echo ItemsMenu(\'' . $mm[1] . '\') ?>', $tpl);
        }

        $tpl = preg_replace(
            "/<wrapper>(.+?)<\/wrapper>/imus",
            "<?php \$__data->recordsWrapperStart(\$section->id) ?>$1<?php \$__data->recordsWrapperEnd() ?>",
            $tpl
        );
        $tpl = preg_replace(
            "/<arhiv:item>(.+?)<\/arhiv:item>/imus",
            "<?php foreach(\$__data->limitObjects(\$section, \$section->objectcount) as \$record): ?>$1<?php endforeach; ?>",
            $tpl
        );


        $tpl = str_replace('[site.authorizeform]', '<?php echo replace_link(seAuthorize($__data->prj->vars->authorizeform)) ?>', $tpl);

        $tpl = str_replace(array('<SE>', '</SE>'), array('<se>', '</se>'), $tpl);
        $tpl = str_replace(array('</noempty>', '</empty>'), '<?php endif; ?>', $tpl);
        $tpl = str_replace('</if>', '<?php endif; ?>', $tpl);
        $tpl = str_replace(array('</else>', '<else>'), '<?php else: ?>', $tpl);


        while (preg_match("/\[\@subpage_?([\d\w]+)\]/", $tpl, $m) || preg_match("/\[link\.subpage=([\d\w]+)\]/", $tpl, $m)) {
            $tpl = str_replace($m[0], '<?php echo seMultiDir().\'/\' . $__data->getPageName() . \'/\' . $section->id . \'/sub' . $m[1] . '/\' ?>', $tpl);
        }

        $tpl = $this->getParseMenu($tpl, $section);
        $tpl = $this->conditions($tpl);

        $tpl = preg_replace("/(=[\"\'])?([\w\d\-_\[\]\.]+)\.html/u", "$1" . seMultiDir() . "/$2/", $tpl);

        $tpl = preg_replace("/<noempty:\[sys\.request\.([\w\d_]+)\]>/i", "<?php if(getRequest('$1', 3)): ?>", $tpl);
        $tpl = preg_replace("/<noempty:part\.([\w\d_]+)>/i", "<?php if(!empty(\$section->$1)): ?>", $tpl);
        $tpl = preg_replace("/<noempty:\[?site\.([\w\d_]+)\]?>/i", "<?php if(!empty(\$__data->prj->vars->$1)): ?>", $tpl);
        $tpl = preg_replace("/<noempty:\[([\w\d_]+)\.([\w\d_]+)\]>/i", "<?php if(!empty(\$$1->$2)): ?>", $tpl);
        $tpl = preg_replace("/<noempty:\(\[([\w\d_]+)\.([\w\d_]+)\]\)>/i", "<?php if(!empty(\$$1->$2)): ?>", $tpl);
        $tpl = preg_replace("/<noempty:([\w\d_]+)\.([\w\d_]+)>/i", "<?php if(!empty(\$$1->$2)): ?>", $tpl);
        $tpl = preg_replace("/<noempty:[\(]?\{(.+?)\}[\)]?>/i", "<?php if(!empty($1)): ?>", $tpl);
        $tpl = preg_replace("/<noempty:\[lang([\d]+)\]>/i", "<?php if(!empty(\$section->language->lang$1)): ?>", $tpl);
        $tpl = preg_replace("/<noempty:\[param([\d]+)\]>/i", "<?php if(!empty(\$section->parametrs->param$1)): ?>", $tpl);
        $tpl = preg_replace("/<noempty:\[%site([\w\d]+)%\]>/i", "<?php if(!empty(\$__data->prj->vars->$1)): ?>", $tpl);

        $tpl = preg_replace("/<empty:\[sys\.request\.([\w\d_]+)\]>/i", "<?php if(!getRequest('$1', 3)): ?>", $tpl);
        $tpl = preg_replace("/<empty:part\.([\w\d_]+)>/i", "<?php if(empty(\$section->$1)): ?>", $tpl);
        $tpl = preg_replace("/<empty:\[?site\.([\w\d_]+)\]?>/i", "<?php if(empty(\$__data->prj->vars->$1)): ?>", $tpl);
        $tpl = preg_replace("/<empty:\[([\w\d_]+)\.([\w\d_]+)\]>/i", "<?php if(empty(\$$1->$2)): ?>", $tpl);
        $tpl = preg_replace("/<empty:\(\[([\w\d_]+)\.([\w\d_]+)\]\)>/i", "<?php if(empty(\$$1->$2)): ?>", $tpl);
        $tpl = preg_replace("/<empty:([\w\d_]+)\.([\w\d_]+)>/i", "<?php if(empty(\$$1->$2)): ?>", $tpl);
        $tpl = preg_replace("/<empty:\[%site([\w\d]+)%\]>/i", "<?php if(empty(\$__data->prj->vars->$1)): ?>", $tpl);
        $tpl = preg_replace("/<empty:[\(]?\{(.+?)\}[\)]?>/i", "<?php if(empty($1)): ?>", $tpl);
        $tpl = preg_replace("/<empty:\[lang([\d]+)\]>/i", "<?php if(empty(\$section->language->lang$1)): ?>", $tpl);
        $tpl = preg_replace("/<empty:\[param([\d]+)\]>/i", "<?php if(empty(\$section->parametrs->param$1)): ?>", $tpl);


        while (preg_match("/<repeat:pages>(.+?)<\/repeat:pages>/imus", $tpl, $m)) {

            $s1 = '<?php foreach($__data->getPages() as $map): ?><?php if(intval($map->indexes)): ?>';
            $m[1] = str_replace('[map.name]', '<?php echo $map["name"] ?>', $m[1]);
            $m[1] = str_replace('[map.title]', '<?php echo $map->title ?>', $m[1]);
            $m[1] = str_replace('[map.link]', '<?php echo seMultiDir()."/".$map["name"]."/" ?>', $m[1]);
            $m[1] = str_replace('[map.mapid]', '<?php echo "maplinks" . $map->level ?>', $m[1]);
            $s1 = $s1 . $m[1] . "\r\n" . '<?php endif; ?><?php endforeach; ?>';
            $tpl = str_replace($m[0], $s1, $tpl);
        }

        //$tpl = str_replace('[include_css]', '<link href="[module_url]css/style.css" rel="stylesheet">', $tpl);
        //$tpl = str_replace('[include_js]', '<script src="[module_url]engine.js"></script>', $tpl);
        $tpl = preg_replace(
            "/<repeat:records>(.+?)<\/repeat:records>/imus",
            "<?php foreach(\$__data->limitObjects(\$section, \$section->objectcount) as \$record): ?>\n$1\n<?php endforeach; ?>",
            $tpl
        );
        $tpl = preg_replace(
            "/<repeat:records\|desc>(.+?)<\/repeat:records>/imus",
            "<?php foreach(\$__data->limitObjects(\$section, \$section->objectcount, 1) as \$record): ?>\n$1\n<?php endforeach; ?>",
            $tpl
        );

        while (preg_match("/<repeat:\[([\w\d]+)\.([\w\d]+)\]([^\>]+)?>/imus", $tpl, $m)) {
            $s1 = 'record';
            if (count($m) == 4 && trim($m[3])) {
                $s1_parts = explode('=', $m[3], 2);
                if (isset($s1_parts[1])) {
                    $s1 = $s1_parts[1];
                }
            }
            $tpl = str_replace($m[0], '<?php foreach($' . $m[1] . '->' . $m[2] . ' as $' . $s1 . '): ?>', $tpl);
        }


        while (preg_match("/<repeat:([\d\w]+)\[([\w\d]+)\.([\w\d]+)\]([^\>]+)?>/imus", $tpl, $m)) {
            $s1 = 'record';
            if (count($m) == 5 && trim($m[4])) {
                $s1_parts = explode('=', $m[4], 2);
                if (isset($s1_parts[1])) {
                    $s1 = $s1_parts[1];
                }
            }
            $tpl = str_replace($m[0], '<?php $__list = \'' . $m[1] . '\'.$' . $m[2] . '->' . $m[3] . ';
          foreach($section->$__list as $' . $s1 . '): ?>', $tpl);
        }

        while (preg_match("/\<repeat:([^\>]+)\>(.+?)\<\/repeat:([^\>]+)\>/umis", $tpl, $m)) {
            list($s1,) = explode(' ', $m[1]);
            if (strpos($m[1], ' name=') !== false) {
                list(, $s2) = explode(' name=', $m[1]);
                $s2 = trim($s2);
            } else {
                $s2 = 'record';
            }
            if (empty($s2)) $s2 = 'record';
            if ($s1 == 'records') $s1 = 'objects';

            if (strpos($m[2], '<if:record.text1') !== false) {
                $s3 = '<?php list($record->text1)=explode("|",$record->text1) ?>' . $m[2] . "\n<?php endforeach; ?>";
            } else $s3 = $m[2] . "\n<?php endforeach; ?>";
            $tpl = str_replace($m[0], '<?php foreach($section->' . $s1 . ' as $' . $s2 . '): ?>' . $s3, $tpl);
        }
        $tpl = preg_replace("/\<\/repeat:(.+?)\>/m", '<?php endforeach; ?>', $tpl);


        $tpl = preg_replace(
            "/\[subpage\sname=([\w\d]+)\]/m",
            "<?php if(file_exists(\$__MDL_ROOT.\"/php/subpage_$1.php\")) 
            include \$__MDL_ROOT.\"/php/subpage_$1.php\"; 
            if(file_exists(\$__MDL_ROOT.\"/tpl/subpage_$1.tpl\")) include \$__data->include_tpl(\$section, \"subpage_$1\"); ?>",
            $tpl
        );

        while (preg_match("/\[textline\.(.+?)\/textline\]/usim", $tpl, $m)) {
            $s1 = '<?php $noteitem = explode("\n", str_replace("\n\n","\n", 
            trim(str_replace(array("<br>","<br />","<p>","</p>"),array("\n","\n","","\n"),str_replace("\r", "", $record->note))))); ?>' . "\r\n";
            $s1 .= '<?php foreach($noteitem as $num=>$noteline): ?>' . "\r\n";
            $m[1] = str_replace('%SELECTED%', '<?php if(strpos($noteline, "*")!==false) echo "selected"; ?>', $m[1]);
            $m[1] = str_replace('%CHECKED%', '<?php if(strpos($noteline, "*")!==false) echo "checked"; ?>', $m[1]);
            $m[1] = str_replace('@textlineval', '<?php list(,$noteline_) = explode("%%", trim($noteline)); 
           if (empty($noteline_)) $noteline_ =  (strip_tags($noteline)); echo str_replace("*", "", htmlspecialchars($noteline_)) ?>', $m[1]);
            $m[1] = str_replace('@textline_num', '<?php echo str_replace("*", "", $num+1) ?>', $m[1]);
            $m[1] = str_replace('@textline', '<?php list($noteline_) = explode("%%", $noteline); echo str_replace("*", "", $noteline_) ?>', $m[1]);
            $s1 .= $m[1] . "\r\n<?php endforeach; ?>";
            $tpl = str_replace($m[0], $s1, $tpl);
        }

        while (preg_match("/\[\%([\d\w]+)\%\]/", $tpl, $m)) {
            $tpl = str_replace($m[0], '<?php echo $__data->prj->vars->' . $m[1] . ' ?>', $tpl);
        }

        $tpl = str_replace('[thispage.link]', '<?php echo $__data->getLinkPageName() ?>', $tpl);
        $tpl = str_replace('[thispage.name]', '<?php echo $__data->getPageName() ?>', $tpl);
        $tpl = str_replace('[arhiv.link]', '<?php echo seMultiDir()."/".$__data->getPageName()."/".$section->id."/arhiv/" ?>', $tpl);
        $tpl = preg_replace("/\[part\.([^\]]*)\]/m", "<?php echo \$section->$1 ?>", $tpl);
        $tpl = preg_replace("/\[sys\.request\.([\w\d_]+)\]/i", "<?php echo getRequest('$1', 3) ?>", $tpl);
        $tpl = preg_replace("/\[params\.param([\d]+)\]/i", "<?php echo \$section->parametrs->param$1 ?>", $tpl);
        $tpl = preg_replace("/\[param([\d]+)\]/i", "<?php echo \$section->parametrs->param$1 ?>", $tpl);
        $tpl = preg_replace("/\[site\.(copyright|sitetitle|sitesubtitle|sitephone|siteemail|siteaddr|sitepostcode|siteregion|sitelocality|statistic)\]/im", "<?php echo \$__data->prj->vars->$1 ?>", $tpl);

        $tpl = preg_replace("/\[([\w\d_]+)\.([\w\d_]+)\]/im", "<?php echo \$$1->$2 ?>", $tpl);
        $tpl = str_replace(array('<serv>', '</serv>', '[*addobj]', '[*edobj]'), '', $tpl);
        $tpl = str_replace(
            '[SE_PARTSELECTOR]',
            '<?php echo SE_PARTSELECTOR($section->id,count($section->objects),
               $section->objectcount, getRequest("item",1), getRequest("sel",1)) ?>',
            $tpl
        );


        $tpl = str_replace('[objedit]', '<?php echo $__data->editItemRecord($section->id, $record->id) ?>', $tpl);
        $tpl = str_replace('[editrecord]', '<?php echo $__data->linkEditRecord($section->id, $record->id, "") ?>', $tpl);
        $tpl = str_replace('[addrecord]', '<?php echo $__data->linkAddRecord($section->id) ?>', $tpl);
        $tpl = str_replace('[addphotos]', '<?php if(method_exists($__data, "editorAddPhotos")) echo $__data->editorAddPhotos($section); ?>', $tpl);
        $tpl = str_replace('[editrecord_title]', '<?php echo $__data->linkEditRecord($section->id, $record->id, "Title") ?>', $tpl);
        $tpl = str_replace('[editrecord_image_prev]', '<?php echo $__data->linkEditRecord($section->id, $record->id, "PImage") ?>', $tpl);
        $tpl = str_replace('[editrecord_image]', '<?php echo $__data->linkEditRecord($section->id, $record->id, "Image") ?>', $tpl);
        $tpl = str_replace('[editrecord_note]', '<?php echo $__data->linkEditRecord($section->id, $record->id, "Note") ?>', $tpl);
        $tpl = str_replace('[editrecord_text]', '<?php echo $__data->linkEditRecord($section->id, $record->id, "Text") ?>', $tpl);

        $tpl = preg_replace("/\[lang([\d]+)\]/m", "<?php echo \$section->language->lang$1 ?>", $tpl);
        $tpl = preg_replace("/\[([\$][^\]]+)\]/m", "<?php echo $1 ?>", $tpl);
        $tpl = preg_replace("/\{([\$][^\}]+)\}/m", "<?php echo $1 ?>", $tpl);
        $tpl = preg_replace("/\[\$([^\}]+)\]/m", "<?php echo $1 ?>", $tpl);

        return $tpl;
    }
}
