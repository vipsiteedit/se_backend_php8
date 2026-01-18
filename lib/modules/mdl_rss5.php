<?php
//BeginLib
if (!function_exists('download')){
function download($url, $filename){
 // $serverRSS=$url;
  preg_match('/\/\/(.+?)\//i',$url,$serverRSS);
  $serverRSS = $serverRSS[1];
  $fileRSS = explode('://',$url);
  $fileRSS = $fileRSS[1];
  //$fileRSS=explode('://',$rsspath);
  //$fileRSS=$fileRSS[1];
  $ittext="";
  @$fp = fsockopen($serverRSS, 80,$errno, $errstr, 10);
  if ($fp) {
   $pathalies = explode("/",$fileRSS);
   $fileRSS = substr($fileRSS,strlen($pathalies[0]));
   $pathfiles = $pathalies[0];
   fputs ($fp, "GET ".$fileRSS." HTTP/1.0\r\nHost: ".$pathfiles."\r\n\r\n");
   while ($data = fgets($fp,256)) $ittext.=$data;
   if (!preg_match("/encoding=[\'\"](utf-8)[\'\"]/m",$ittext)) {
     preg_match("/encoding=[\'\"](.+?)[\'\"]/m",$ittext,$encode);
     $encode=$encode[1];
     if (!empty($encode)) {
       $ittext = preg_replace("/encoding=[\'\"](.+?)[\'\"]/m","encoding=\"utf-8\"",$ittext);
       $ittext = iconv($encode,'utf-8',$ittext);
     } 
   }
   $itt =explode("<?xml",$ittext);
   $ittext="<?xml".@$itt[1];
     fclose($fp);
  if (!empty($ittext)) {
    $frss=se_fopen($filename,"wb");
    fputs($frss,$ittext);
    fclose($frss);
  }
    // $file = se_file_get_contents($ittext);
    // if ($file) file_put_contents($filename,$file);
  }
 }
}
//EndLib
function module_rss5($razdel, $section = null)
{
   $__module_subpage = array();
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "5";
if (empty($section->params[1]->value)) $section->params[1]->value = "http://edgestile.ru/www/rss/news.xml";
if (empty($section->params[2]->value)) $section->params[2]->value = "Далее";
if (empty($section->params[3]->value)) $section->params[3]->value = "15";
if (empty($section->params[4]->value)) $section->params[4]->value = "windows-1251";
if (empty($section->params[5]->value)) $section->params[5]->value = "0";
global $RSS, $encode;
//* PHP 5
//* simplexml_load_file
//* allow_url_fopen
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
 //BeginLib
//EndLib
$rss_url = $section->params[1]->value;
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
$codepage = $section->params[4]->value;
$size = $section->params[0]->value;
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
     $lentext=intval($section->params[5]->value);
     if (($lentext > 0) && (strlen($stext) > $lentext)) {
            $stext = strip_tags($stext);
            $stext = substr($stext, 0, $lentext);
            if (preg_match('/^(.+|\n)\W/i', $stext, $matches)) $stext = $matches[1];
     }
     $RSS .= '<div class="object"><h3 class="objectTitle"><span class="objectTitleTxt">'.$title.'</span></h3>';
     if (!empty($pubdate))
       $RSS .= '<font size=-1 class="dataType_date">'.$pubdate.'</font>';
     if (!empty($image))
     {
       $RSS .= '<img border="0" class="objectImage" src="'.$image.'" alt="' . $title . '">';
     }
     $RSS .= '<div class="objectNote">' . str_replace('<img ','<img style="width:80px;" ', $stext);
     if (!empty($link))
        $RSS .= ' <a class="objectNext" href="'.$link.'">'.$section->params[2]->value.'</a>';
     $RSS .= '</div></div>';
     $i++;
      if ($i > $size)
          {break;}
   }
 $time = intval($section->params[3]->value);
 $time = 60*$time;
 if (file_exists($filename)) {
   if (time()>(se_filemtime($filename)+$time)){
    download($rss_url,$filename);
    }
 }
//EndRazdel
}
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"rss\" [part.style]>
<noempty:part.title><h3 class=\"contentTitle\"[part.style_title]><span class=\"contentTitleTxt\">[part.title]</span> </h3> </noempty>
<noempty:part.image><img border=\"0\" class=\"contentImage\"[part.style_image] src=\"[part.image]\" alt=\"[part.image_alt]\"></noempty>
<noempty:part.text>[part.text]</noempty>
$RSS
</div> 
<!-- =============== END CONTENT ============= -->";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};