<?php
//BeginLib
//EndLib
function module_onlinelastnews($razdel, $section = null)
{
   getRequestList($__request, 'page,sub');
   getRequestList($__request, 'razdel', 1);
   $_page = $__request['page'];
   $_razdel = $__request['razdel'];
   $_sub = $__request['sub'];
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "3";
if (empty($section->params[1]->value)) $section->params[1]->value = "news";
if (empty($section->params[2]->value)) $section->params[2]->value = "подробнее";
if (empty($section->params[3]->value)) $section->params[3]->value = "150";
if (empty($section->params[4]->value)) $section->params[4]->value = "1";
if (empty($section->params[5]->value)) $section->params[5]->value = "70";
if (empty($section->params[6]->value)) $section->params[6]->value = "news";
if (empty($section->params[7]->value)) $section->params[7]->value = "Все новости";
global $newslink,$col1,$col2,$col3,$col4,$titlepage,$language,$sitearray;
require_once ("lib/lib_onlinenews.php");
se_db_connect();
if (!empty($sitearray['language'])) $lang = $sitearray['language']; else $lang="rus";
$lang=str_replace('/','',$language);
$newskod=$section->params[6]->value;
$nn       = 0;
$limit    = "LIMIT {$section->params[0]->value}";
//BeginSubPages
if (($razdel != $__request['razdel']) || empty($__request['sub'])){
//BeginRazdel
    $thisdate=time()+43200;
    $rnews = se_db_query("SELECT SQL_CACHE `news`.`id`,`news`.`text`,`news`.img,
    `news`.title,`news`.`date` FROM `news` INNER JOIN `news_category`
    ON (`news`.id_category = `news_category`.id) WHERE
    (`news`.lang ='$lang') AND (`news_category`.kod = '$newskod') AND (`news`.pub_date<='$thisdate') ORDER BY date DESC $limit");
    while ($news = mysql_fetch_array($rnews)){
        $id = se_db_output($news['id']);
        $notetext=str_replace("\n","",strip_tags(replase_teg_edittext($news['text'])));
        $data[$nn][0] = date("d.m.Y",htmlspecialchars($news['date'],ENT_QUOTES));
        $data[$nn][1] = se_db_output($news['title']);
        if (!empty($news['img'])){
          $wdth=$section->params[5]->value;
          $source="/images/$lng/newsimg/".str_replace(".","_prev.",$news['img']);
          list($width, $height) = GetImageSize(getcwd().$source);
          if ($wdth<$width) {
            $height=round($height * $wdth/$width,0);
            $width=$wdth;
          }
         $data[$nn][4] = "<a class=newslinks href='/".$section->params[1]->value."?razdel=".$section->params[4]->value."&sub=1&object=$id'>
         <IMG border=0 src=\"$source\" alt=\"".se_db_output($news['title'])."\" class=objectImage width=\"$width\" height=\"$height\"></a>";
        } else $data[$nn][4] = "";
        $endchars='..';
        $data[$nn][3] = '';
        if (strlen($notetext)>$section->params[3]->value) {
            $data[$nn][2]=substr($notetext,0,$section->params[3]->value);
            $data[$nn][3] = "<a class=newslinks href='/".$section->params[1]->value."?razdel=".$section->params[4]->value."&sub=1&object=$id'>{$section->params[2]->value}</a>";
            if (preg_match('/^(.+|\n)\W/i', $data[$nn][2], $matches)) $data[$nn][2]=$matches[1].$endchars;
            else $data[$nn][2].=$endchars;
        } else  $data[$nn][2]= strip_tags(replase_teg_edittext($news['text']));
        // ссылка на субстраницу, если есть подробный текст для новости
        $nn++;
    }
if (!empty($data)) se_show_fields($razdel,$data);
//EndRazdel
}
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<DIV class=content id=cont_lnws[part.style]>
<noempty:part.title><H3 class=contentTitle[part.style_title]>[part.title]</H3></noempty>
<noempty:part.image><IMG border=0 class=contentImage[contentstyle_img] src=\"[part.image]\" alt=\"[part.image_alt]\"></noempty>
<noempty:part.text><DIV class=contentText[part.style_text]>[part.text]</DIV></noempty>
".se_record_list($section, "
<DIV class=object>
<H4 class=objectTitle><FONT size=-1 class=dataType_date>[@col0]</FONT>&nbsp;<SPAN class=textTitle>[@col1]</SPAN></H4>
<DIV class=objectNote>[@col4][@col2] [@col3]</DIV>
</DIV>
")."
<a id=\"link_news\" href=\"/{$section->params[1]->value}\">{$section->params[7]->value}</a>
</DIV>
<!-- =============== END CONTENT ============= -->";
$__module_content['show'] = "
";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};