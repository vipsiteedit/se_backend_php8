<?php

 //BeginLib
//EndLib
$xml_atom = simplexml_load_file($section->parametrs->param2);
if(isset($xml_atom->channel->item)){
    $rss_url = $section->parametrs->param2;
    $rssdir = getcwd()."/rss";
    if (!is_dir("$rssdir"))  {
     mkdir ($rssdir);}
    $rssdir .= "/in";
    if (!is_dir("$rssdir")) {
     mkdir ($rssdir);}
    $filename = $rssdir.'/';
    $filename.=md5($rss_url).'.rss';

    if (!file_exists($filename))  {
    download($rss_url,$filename);
    }
    $xml = simplexml_load_file($filename);
}elseif(isset($xml_atom->entry)){
    $xml = simplexml_load_file($section->parametrs->param2);
}


$codepage = $section->parametrs->param6;
$size = $section->parametrs->param3;
$i = 0;
$lentext = intval($section->parametrs->param7);
$RSS = array();

// RSS **********************
foreach($xml->channel->item as $items) {
    $RSS['title'] = $items->title;
    $RSS['note'] = strip_tags(str_replace(array('<![CDATA[', ']]>'), '', $items->description));
    $RSS['link'] = $items->link;
    $RSS['image'] = $items->image;
    $RSS['image_alt'] = htmlspecialchars($items->title);
    $RSS['pubdate'] = date("d.m.Y г.  H:i", strtotime($items->pubDate));
    // $stext = iconv("UTF-8",$codepage,$decription);
    if (($lentext >= 0) && (utf8_strlen($RSS['note']) > $lentext)) {
        $RSS['note'] = utf8_substr($RSS['note'], 0, $lentext) . (($lentext) ? '...' : ''); //se_LimitString($RSS['note'], $lentext);
    }
    $i++;
    if ($i > $size) {
        break;
    }
    $__data->setItemList($section, 'objects', $RSS);
}      

// ATOM ********************* 
foreach($xml->entry as $items) {
     
     $RSS['title'] = $items->title;
     if(isset($items->content)){
        $RSS['note'] = $items->content;
     }elseif(isset($items->summary)){
        $RSS['note'] = $items->summary;
     }
    $RSS['link'] = $items->link['href'];
    $RSS['image'] = $items->image;
    $RSS['image_alt'] = htmlspecialchars($items->title);
    $RSS['pubdate'] = date("d.m.Y г.  H:i", strtotime($items->pubDate));
    // $stext = iconv("UTF-8",$codepage,$decription);
    if (($lentext >= 0) && (utf8_strlen($RSS['note']) > $lentext)) {
        $RSS['note'] = utf8_substr($RSS['note'], 0, $lentext) . (($lentext) ? '...' : ''); //se_LimitString($RSS['note'], $lentext);
    }
    $i++;
    if ($i > $size) {
        break;
    }
    $__data->setItemList($section, 'objects', $RSS);
} 
$time = intval($section->parametrs->param5);
$time = 60*$time;

 if (file_exists($filename)) {
   if (time()>(filemtime($filename)+$time)){
    download($rss_url,$filename);
    }
 }

?>
