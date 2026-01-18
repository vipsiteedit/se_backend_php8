<?php

 //BeginLib
//EndLib
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

$codepage = $section->parametrs->param6;
$size = $section->parametrs->param3;
$i = 1;
$RSS ='';
  
foreach($xml->channel->item as $items) {
     
     $title = $items->title;
     $decription = $items->description;
     $link = $items->link;
     $image = $items->image;
     $pubdate = $items->pubDate;

    // $stext = iconv("UTF-8",$codepage,$decription);
     $stext = str_replace(array('<![CDATA[', ']]>'), '', $decription);
     $lentext=intval($section->parametrs->param7);
     if (($lentext > 0) && (utf8_strlen($stext) > $lentext)) {
            $stext = strip_tags($stext);
            $stext = se_LimitString($stext, $lentext);
           // if (preg_match('/^(.+|\n)\W/i', $stext, $matches)) $stext = $matches[1];
     }

     
     $RSS .= '<div class="object"><h3 class="objectTitle"><span class="objectTitleTxt">'.$title.'</span></h3>';

     if (!empty($pubdate))
       $RSS .= '<span class="dataType_date">'.$pubdate.'</span>';
     if (!empty($image))
     {
       $RSS .= '<img border="0" class="objectImage" src="'.$image.'" alt="' . $title . '">';
     }
     $RSS .= '<div class="objectNote">' . str_replace('<img ','<img style="width:80px;" ', $stext);
     $RSS .= '</div>';
     if (!empty($link))
        $RSS .= ' <a class="objectNext" href="'.$link.'">'.$section->parametrs->param4.'</a>';
     $RSS .= '</div>';
     $i++;
      if ($i > $size)
          {break;}
   }

 $time = intval($section->parametrs->param5);
 $time = 60*$time;

 if (file_exists($filename)) {
   if (time()>(se_filemtime($filename)+$time)){
    download($rss_url,$filename);
    }
 }
?>