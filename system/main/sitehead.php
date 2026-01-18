<?php

$se = seData::getInstance();
$skin = '/' . $se->getSkinService();

$titlepage = strip_tags(str_replace('&#124;', '|', (empty($se->page->titlepage)) ? $se->page->title : $se->page->titlepage));
$keywords = strip_tags(str_replace('&#124;', '|', (empty($se->page->keywords)) ? $se->prj->gkeywords : $se->page->keywords));
$description = strip_tags(html_entity_decode(str_replace('&#124;', '|', (empty($se->page->description)) ? $se->prj->gdescription : $se->page->description), ENT_COMPAT, 'UTF-8'));
if (SE_DB_ENABLE && file_exists(SE_LIBS . 'plugins/plugin_geo/plugin_geovalues.class.php')) {
    $gval = plugin_geovalues::getInstance();
    $titlepage = $gval->parseValues($titlepage);
    $keywords = $gval->parseValues($keywords);
    $description = $gval->parseValues($description);
}


if (empty($se->prj->vars->documenttype) || 1 == $se->prj->vars->documenttype || $se->prj->documenttype == 1) {
    echo '<!DOCTYPE html>' . "\n";
} else {
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">' . "\n";
}
echo '<html lang="ru" id="' . $se->getPageName() . '"><head><title>' . str_replace('"', '&quot;', strip_tags($titlepage)) . '</title>' . "\n";

//echo '<base href="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">' . "\n";
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . "\n";
if ($se->prj->adaptive == 1) {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
}

echo '<meta name="keywords" content="' . str_replace('"', '&quot;', strip_tags($keywords)) . '"> 
<meta name="description" content="' . str_replace('"', '&quot;', $description) . '">';

if (class_exists('plugin_router'))
    echo plugin_router::getInstance()->showCanonical();

foreach ($se->modulesCss as $css) {
//    echo '<link href="' .$css.'" id="defaultCSS" rel="stylesheet">' . "\n";
}
if ($se->headercss) {
    echo join("\n", $se->headercss) . "\n";
}

if (file_exists(getcwd() . $skin . '/' . $se->page->css . '.css') && filesize(getcwd() . $skin . '/' . $se->page->css . '.css') > 0) {
    echo '<link href="' . $skin . '/' . $se->page->css . '.css" id="defaultCSS" rel="stylesheet">' . "\n";
}

if (file_exists(getcwd() . $skin . '/skin_' . $se->getPageName() . '.css') && filesize(getcwd() . $skin . '/skin_' . $se->getPageName() . '.css') > 0) {
    echo '<link href="' . $skin . '/skin_' . $se->getPageName() . '.css" id="pageCSS" rel="stylesheet">' . "\n";
}

/*
if (file_exists(getcwd() . '/system/main/semenu.js')) {
    $se->footer[] = '<script src="/system/main/semenu.js"></script>';
}
*/

if (strval($se->page->style) != "")
    echo '<style>' . $se->page->style . '</style>';
if (strval($se->page->head) != "")
    echo replace_link(str_replace('&#10;', "\n", $se->page->head)) . "\n";

if (!empty($se->prj->vars->globaljavascripthead)) {
    echo replace_link(str_replace('&#10;', "\n", $se->prj->vars->globaljavascripthead));
    echo "\n";
}

if (!empty($se->page->vars->localjavascripthead)) {
    echo replace_link(str_replace('&#10;', "\n", $se->page->vars->localjavascripthead));
    echo "\n";
}


if (!empty($se->header)) {
    echo replace_link(str_replace('&#10;', "\n", join("\n", $se->header)));
    echo "\n";
}
if ($se->footerhtml) {
    $se->footer = array_merge(array($se->footerhtml), $se->footer);
}
echo '</head>' . "\n";
