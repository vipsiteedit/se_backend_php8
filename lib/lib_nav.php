<?php

function sePaginatorBootstrap($navigation = array())
{
    $paginator = '<nav><ul class="seNavigator pagination pagination-sm">';

    foreach ($navigation as $key => $nav) {
        $class = '';

        if (!empty($nav['active'])) {
            $class = ' active';
            $nav['val'] = '<span class="pagenactive">' . $nav['val'] . '</span>';
        } elseif (empty($nav['url'])) {
            $class = ' disabled';
            $nav['val'] = '<span>' . $nav['val'] . '</span>';
        } else {
            $nav['val'] = '<a href="' . $nav['url'] . '">' . $nav['val'] . '</a>';
        }

        $paginator .= '<li class="pagen' . $class . '">' . $nav['val'] . '</li>';
    }

    $paginator .= '</ul></nav>';

    return $paginator;
}

function sePaginatorTable($navigation = array())
{
    $paginator = '<table border="0" class="seNavigator" cellspacing="0" cellpadding="0"><tr>';

    foreach ($navigation as $key => $nav) {
        if (!empty($nav['active'])) {
            $nav['val'] = '<span class="pagenactive">' . $nav['val'] . '</span>';
        } elseif (empty($nav['url'])) {
            $nav['val'] = '<span>' . $nav['val'] . '</span>';
        } else {
            $nav['val'] = '<a href="' . $nav['url'] . '">' . $nav['val'] . '</a>';
        }

        $paginator .= '<td width="20px" align="center" class="pagen">' . $nav['val'] . '</td>';
    }

    $paginator .= '</tr></table>';

    return $paginator;
}

function se_buildParam($params = array())
{
    return empty($params) ? '' : '?' . http_build_query($params);
}

function parse_query($str)
{
    $pairs = explode('&', $str);

    foreach ($pairs as $pair) {
        list($name, $value) = explode('=', $pair, 2);
        global $$name;
        $$name = $value;
    }
}

function se_Navigator($count, $limit = 30, $sheet = 1, $section_id = false, $sel = '')
{
    if (!$limit) return;

    $cnpage = ceil($count / $limit);
    if (!$section_id) {
        $get_query = '';
    } else {
        $get_query = '/' . $section_id;
    }
    if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
        list($listurl, $query_str) = explode('?', $_SERVER['REQUEST_URI']);
    } else
        $listurl = $_SERVER['REQUEST_URI'];

    //$query_str = (!empty($query_str)) ? '' . htmlentities($query_str) : '';

    //$listurl = from_Url($listurl);

    $startpage = 'home';

    //if (class_exists('seData'))
    //    $startpage = seData::getInstance()->startpage;

    //if (empty($listurl['page']))
    //    $listurl['page'] = $startpage;

    //$urlpath = '';
    parse_str($query_str, $output);
    foreach ($output as $name => $value) {
        if (in_array($name, array('', 'db', 'sheet', 'razdel'))) {
            unset($output[$name]);
        }
    }
    $query_str = http_build_query($output);

    if (substr($listurl, -1, 1) == '/') $listurl = substr($listurl, 0, -1);
    $urlpath = $listurl;

    /*
    $arr_params = explode('&', $params);

    $params = array();

    foreach ($arr_params as $val) {
        list($k, $v) = explode('=', $val);
        if ($k == 'sheet')
            continue;
        $params[$k] = $v;
    }
	*/

    parse_str($query_str, $params);

    unset($params['sheet']);

    if (isRequest('sheet') && $sheet == 1) {
        seData::getInstance()->go301($urlpath . $get_query . URL_END . se_buildParam($params));
    }

    $nav = array();

    if ($cnpage > 1) {
        if ($sheet == 1) {
            $nav['prev'] = array('val' => '&laquo;');
        } else {
            if ($sheet != 2)
                $params['sheet'] = $sheet - 1;
            $nav['prev'] = array('val' => '&laquo;', 'url' => $urlpath . $get_query . URL_END . se_buildParam($params));
        }

        if (class_exists('plugin_router')) {
            $pr = plugin_router::getInstance();
            if ($sheet + 1 <= $cnpage)
                $pr->setNextPage($sheet + 1);
            if ($sheet - 1 > 0)
                $pr->setPrevPage($sheet - 1);
        }

        $cnpw = 9;
        $in = 1;
        $ik = $cnpage;
        if ($cnpage > $cnpw) {
            $in = $sheet - floor($cnpw / 2);
            $ik = $sheet + floor($cnpw / 2);
            if ($in <= 1) {
                $in = 1;
                $ik = $sheet + ($cnpw - $sheet);
            }

            if ($ik > $cnpage) {
                $in = $sheet - (($cnpw - 1) - ($cnpage - $sheet));
                $ik = $cnpage;
            }
            if ($in > 1) {
                $in = $in + 2;
                $urlpathitem = $urlpath;
                if ($urlpathitem == '/' . $startpage) $urlpathitem = '';
                unset($params['sheet']);
                $nav[] = array('val' => '1', 'url' => $urlpathitem . URL_END . se_buildParam($params));
                $nav[] = array('val' => '...');
            }
            if ($ik < $cnpage) {
                $params['sheet'] = $cnpage;
                $ik = $ik - 2;
                $r_nav[] = array('val' => '...');
                $r_nav[] = array('val' => $cnpage, 'url' => $urlpath . $get_query . URL_END . se_buildParam($params));
            }
        }

        for ($i = $in; $i <= $ik; $i++) {
            unset($params['sheet']);
            if ($i == $sheet)
                $nav[] = array('val' => $i, 'active' => true);
            else {
                if ($i != 1) {
                    $params['sheet'] = $i;
                    $nav[] = array('val' => $i, 'url' => $urlpath . $get_query . URL_END . se_buildParam($params));
                } else {
                    $urlpathitem = $urlpath;
                    if ($urlpathitem == '/home') $urlpathitem = '';
                    $nav[] = array('val' => $i, 'url' => $urlpathitem . URL_END . se_buildParam($params));
                }
            }
        }

        if (!empty($r_nav))
            $nav = array_merge($nav, $r_nav);

        if ($sheet == $cnpage) {
            $nav['next'] = array('val' => '&raquo;');
        } else {
            $params['sheet'] = $sheet + 1;
            $nav['next'] = array('val' => '&raquo;', 'url' => $urlpath . $get_query . URL_END . se_buildParam($params));
        }
    }

    if (empty($nav)) {
        return;
    }

    if (intval(seData::getInstance()->prj->adaptive)) {
        return sePaginatorBootstrap($nav);
    } else {
        return sePaginatorTable($nav);
    }
}
