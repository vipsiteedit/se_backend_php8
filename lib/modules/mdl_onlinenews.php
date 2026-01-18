<?php
//BeginLib
// �����������������
// se_DivPages(�����_�������, �������_��_��������)
if (!function_exists('se_DivPages')) {
function se_DivPages($cnrowfull, $cnrowpage, $helptitle="������� ����� �������� � ������� enter ��� ��������") {
    $r = "";
    $cnpage = ceil($cnrowfull/$cnrowpage);
    if ($cnpage > 1) {
        //$squery = $_SERVER['QUERY_STRING'];
        if (empty($L_VARS['get'])) {
            // ������ ��� ����������, ���������� � $GET ��� $remove
            $link = array();
            $remove = array('page', 'razdel', 'sub', 'sheet');
            foreach($_GET as $k => $v) if (!in_array($k, $remove)) $link[$k] = $k.'='.$v;
            $link['sheet'] = 'sheet=';
            $L_VARS['get'] = join('&', $link);
        }
        // -------------------------------
        if (!empty($_GET['sheet']))
            $sheet = htmlspecialchars($_GET['sheet'], ENT_QUOTES);
        else $sheet = 1;
        $r .= '<center><table border="0">';
        $r .= '<form style="margin:0px"
        onSubmit="if ((this.elements[0].value)>'.$cnpage.' || this.elements[0].value < 1) {
                       alert(\'�������� � ����� ������� �� ����������\'); return false; }
                   location.href=\'?'.$L_VARS['get'].'\'+(this.elements[0].value);
                   return false;" method="get" enctype="multipart/form-data">';
        //$r .= '<tr><td colspan="9" align="center">�������: <b>'.$cnrowfull.'</b>; �������: <b>'.$cnpage.'</b></td></tr>';
        $r .= "<tr>";
        $r_left = "";
        $r_right = "";
        $cnpw = 11;
        $in = 1; $ik = $cnpage;
        if ($cnpage > $cnpw) {
            $in = $sheet-floor($cnpw/2); $ik = $sheet+floor($cnpw/2);
            if ($in <= 1) { $in = 1; $ik = $sheet+($cnpw-$sheet); }
            if ($ik > $cnpage) { $in = $sheet-(($cnpw-1)-($cnpage-$sheet)); $ik = $cnpage; }
            if ($in > 1) {
                $in = $in + 3;
                $r_left .= '<td width="20px" align="center" class="pagen"><a href="?'.$L_VARS['get'].'1">1</a></td>
                            <td width="20px" align="center" class="pagen"><a href="?'.$L_VARS['get'].'2">2</a></td>';
               $r_left .= '<td width="20px" align="center" class="pagen">...</td>';
            }
            if ($ik < $cnpage) {
                $ik = $ik - 3;
                $r_right = '<td width="20px" align="center" class="pagen">...</td>';
                $r_right .= '<td width="20px" align="center" class="pagen"><a href="?'.$L_VARS['get'].''.($cnpage - 1).'">'.($cnpage - 1).'</a></td>';
                $r_right .= '<td width="20px" align="center" class="pagen"><a href="?'.$L_VARS['get'].''.$cnpage.'">'.$cnpage.'</a></td>';
            }
        }
        $r .= $r_left;
        for ($i = $in; $i <= $ik; $i++) {
            if ($i == $sheet)
                $r .= '<td width="20px" align="center">
                       <input class="pagenactive" title="'.$helptitle.'"
                        name="sheet" type="text" size="2" maxlength="'.strlen($cnpage).'" value="'.$i.'" OnKeyPress="EnsureNumeric()"></td>';
            else
                $r .= '<td width="20px" align="center" class="pagen"><a href=\'?'.$L_VARS['get'].''.$i.'\'>'.$i.'</a></td>';
        }
        $r .= $r_right;
        $r .= "</tr>";
        $r .= "</form></table></center>";
    }
    return $r;
}}
//EndLib
function module_onlinenews($razdel, $section = null)
{
   getRequestList($__request, 'page,sub');
   getRequestList($__request, 'razdel', 1);
   $_page = $__request['page'];
   $_razdel = $__request['razdel'];
   $_sub = $__request['sub'];
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "";
if (empty($section->params[1]->value)) $section->params[1]->value = "подробнее...";
if (empty($section->params[2]->value)) $section->params[2]->value = "15";
if (empty($section->params[3]->value)) $section->params[3]->value = "200";
if (empty($section->params[4]->value)) $section->params[4]->value = "100";
if (empty($section->params[5]->value)) $section->params[5]->value = "[+] Добавить новость";
if (empty($section->params[6]->value)) $section->params[6]->value = "Введите краткий текст новости";
if (empty($section->params[7]->value)) $section->params[7]->value = "Введите заголовок";
if (empty($section->params[8]->value)) $section->params[8]->value = "Введите месяц";
if (empty($section->params[9]->value)) $section->params[9]->value = "Введите день";
if (empty($section->params[10]->value)) $section->params[10]->value = "Введите год";
if (empty($section->params[11]->value)) $section->params[11]->value = "Неправильно введена дата";
if (empty($section->params[12]->value)) $section->params[12]->value = "Выбранный файл не является картинкой  в формате GIF/JPG/PNG!";
if (empty($section->params[13]->value)) $section->params[13]->value = "Выбранный файл превышает размер 1 Мб!";
if (empty($section->params[14]->value)) $section->params[14]->value = "&nbsp;»";
if (empty($section->params[15]->value)) $section->params[15]->value = "Редактировать";
if (empty($section->params[16]->value)) $section->params[16]->value = "250";
if (empty($section->params[17]->value)) $section->params[17]->value = "Все новости";
if (empty($section->params[18]->value)) $section->params[18]->value = "Вернуться назад";
if (empty($section->params[19]->value)) $section->params[19]->value = "news";
if (empty($section->params[20]->value)) $section->params[20]->value = "Введите номер страницы для перехода ";
if (empty($section->params[21]->value)) $section->params[21]->value = "";
if (empty($section->params[22]->value)) $section->params[22]->value = "Дата";
if (empty($section->params[23]->value)) $section->params[23]->value = "Заголовок";
if (empty($section->params[24]->value)) $section->params[24]->value = "Загрузить рисунок";
if (empty($section->params[25]->value)) $section->params[25]->value = "Текст новости";
if (empty($section->params[26]->value)) $section->params[26]->value = "Сохранить";
if (empty($section->params[27]->value)) $section->params[27]->value = "Удалить";
if (empty($section->params[28]->value)) $section->params[28]->value = "Назад";
global $add_news,$errortext,$col3,$archiv,$col1,$col2,$col4,$_text,$_short_text,$MANYPAGE, $sitearray;
global $_title,$_day,$_month,$_year,$_page,$_sub,$news,$SESSION_VARS,$titlepage,$language,$skin;
//первичная переменная
    $text=array();
    $text[1]  = $section->params[5]->value;
    $text[2]  = $section->params[6]->value;
    $text[3]  = $section->params[7]->value;
    $text[4]  = $section->params[8]->value;
    $text[5]  = $section->params[9]->value;
    $text[6]  = $section->params[10]->value;
    $text[7]  = $section->params[11]->value;
    $text[8]  = htmlspecialchars($section->params[1]->value, ENT_QUOTES);
    $text[9]  = $section->params[12]->value;
    $text[10] = $section->params[13]->value;
    $text[11] = $section->params[14]->value;
    $text[12] = $section->params[15]->value;
    $modername=$section->params[0]->value;
    $pagen=$section->params[2]->value;
    $width=$section->params[3]->value;
    $thumbwdth=$section->params[4]->value;
    $nn        = 0;
    $moder     = 0;
// Подгружаем библиотеку
require_once("lib/lib_images.php");
require_once ("lib/lib_onlinenews.php");
se_db_connect();
// Создаем таблицу если таковой нет
if (@se_db_num_rows(se_db_query("SHOW COLUMNS FROM `news`"))==0){
   se_db_query("CREATE TABLE `news` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(16) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `short_txt` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `img` varchar(14) NOT NULL default '',
  PRIMARY KEY  (`id`)
  ) TYPE=MyISAM COMMENT='Таблица онлайн новостей' AUTO_INCREMENT=1;");
};
// массив используемых строк
    if (!empty($language)) {
        $lang = $language;
        $lang=str_replace('/','',$language);}
        else {$lang="rus";}
    $IMAGE_DIR = "/images/".$lang."/newsimg/";
    if (!is_dir(getcwd()."/images"))
          mkdir(getcwd()."/images");
    if (!is_dir(getcwd()."/images/".$lang))
          mkdir(getcwd()."/images/".$lang);
    if (!is_dir(getcwd().$IMAGE_DIR))
          mkdir(getcwd().$IMAGE_DIR);
//BeginSubPages
if (($razdel != $__request['razdel']) || empty($__request['sub'])){
//BeginRazdel
//определяем модера
   if ($SESSION_VARS['GROUPUSER']>1 || isModer($modername)) $moder=1;
    else $moder=0;
    if ($pagen == 0) $limit = " ";
    if (!empty($_GET['sheet'])) $sheet = htmlspecialchars($_GET['sheet'], ENT_QUOTES); else $sheet = "1";
// если модератор показать ссылку добавления новостей
    if ($moder == 1)
    $add_news = "<a id=\"addlink\" href=\"?razdel=$razdel&amp;sub=2\">$text[1]</a>";
// заполнить массив раздела объектами (новостями)
   $newskod=$section->params[19]->value;
   if (str_replace(" ","",$newskod)!="")
     $newskod="AND (`news_category`.kod = '$newskod')";
   else $newskod="";
    if (intval($pagen)==0) {
        $limitpage = "";
    }else {
        if ((!empty($sheet))&&($sheet > 1)) $limitpage = "LIMIT ".($pagen*$sheet-$pagen).",".$pagen;
        else $limitpage = "LIMIT ".$pagen;
    }
    $thisdate=time()+86400;
    $sql="SELECT SQL_CALC_FOUND_ROWS `news`.`id`,LEFT(`news`.`text`,600) as `text`,`news`.img,
    `news`.title,`news`.`date` FROM `news` INNER JOIN `news_category` ON (`news`.id_category = `news_category`.id)
     WHERE
    (`news`.lang ='$lang') $newskod AND (`news`.pub_date<='$thisdate') ORDER BY date DESC $limitpage";
    $rnews = se_db_query($sql); //
    $cnr = mysql_fetch_row(se_db_query("SELECT FOUND_ROWS()")); $cnrow = $cnr[0];
    if (intval($pagen)>0)
      $MANYPAGE = str_replace("%alt%",$section->params[20]->value,se_divpages($cnrow, $pagen));
    while ($news = mysql_fetch_array($rnews)){
        $notetext=str_replace("\n","",strip_tags(replase_teg_edittext($news['text'])));
        $id = $news['id'];
        if ($moder == 1) $data[$nn][0] = "<a id=\"editlink\" href=\"?razdel=$razdel&amp;sub=3&amp;object=$id\">$text[11]</a>";
        else $data[$nn][0] = " ";
        $data[$nn][1] = date("d.m.Y",htmlspecialchars($news['date'],ENT_QUOTES));
        $data[$nn][2] = htmlspecialchars($news['title'],ENT_QUOTES);
        if (empty($news['img'])) $data[$nn][3] = " ";
        else
        {
            $_imnames = explode(".",$news['img']);
            $_image = $_imnames[0]."_prev.".$_imnames[1];
            $data[$nn][3] = "<IMG border=0 class=objectImage src=\"".$IMAGE_DIR.$_image."\" alt=\"\">";
        }
        // ссылка на субстраницу, если есть подробный текст для новости
        $endchars='..';
        if (strlen($notetext)>$section->params[16]->value) {
            $data[$nn][4]=substr($notetext,0,$section->params[16]->value);
            if (preg_match('/^(.+|\n)\W/i', $data[$nn][4], $matches)) $data[$nn][4]=$matches[1].$endchars;
            else $data[$nn][4].=$endchars;
            $data[$nn][5] = "<br><a id=newslink href=\"?razdel=$razdel&amp;sub=1&amp;object=$id\">$text[8]</a>";
        } else {
           $data[$nn][4]= strip_tags(replase_teg_edittext($news['text']));
           $data[$nn][5] = "<br><a id=newslink href=\"?razdel=$razdel&amp;sub=1&amp;object=$id\">$text[8]</a>";
        };
        $nn++;
    }
// показать новости
    if (isset($data))
    se_show_fields($razdel,$data);
//EndRazdel
}
else{
if(($razdel == $__request['razdel']) && !empty($__request['sub']) && ($__request['sub']==1)){
//BeginSubPage1
// показать субстраницу для текущей новости
        if (isset($_GET['object'])){
            $Obj = htmlspecialchars($_GET['object'],ENT_QUOTES);
            $rnews = se_db_query("SELECT SQL_CACHE id, title, short_txt, text, img FROM news WHERE id='$Obj'");
            $news = mysql_fetch_array($rnews);
            $col1 = se_db_output($news['title']);
            $titlepage=$col1;
            if (!((strpos($news['text'],'<')!==false) && (strpos($news['text'],'>')!==false)))
            $col2 = str_replace("\n","<br>",replase_teg_edittext($news['text']));
            else $col2 = replase_teg_edittext($news['text']);
            if (empty($news['img'])) $col4 = " ";
            else $col4 = "<IMG class=viewImage alt=\"$col1\" src=\"".$IMAGE_DIR.htmlspecialchars($news['img'],ENT_QUOTES)."\" border=\"0\">";
        }
//EndSubPage1
} else
if(($razdel == $__request['razdel']) && !empty($__request['sub']) && ($__request['sub']==2)){
//BeginSubPage2
    // добавление новости
        // сформировать дату
        $_time = explode(".",date("d.m.Y",time()));
        $_day  = $_time[0];
        $_month  = $_time[1];
        $_year  = $_time[2];
        if (isset($_POST['Save'])){
            $flag     = true;
            $file     = false;
            $filename = "";
            if (empty($_POST['day']) && $flag){
                 $flag = false;
                 $errortext = $text[5];
            }
            if (empty($_POST['month']) && $flag){
                 $flag = false;
                 $errortext = $text[4];
            }
            if (empty($_POST['year']) && $flag){
                 $flag = false;
                 $errortext = $text[6];
            }
            if ($flag && !checkdate(intval($_POST['month']), intval($_POST['day']), intval($_POST['year']))){
                 $flag = false;
                 $errortext = $text[7];
            }
            if (empty($_POST['title']) && $flag){
                 $flag = false;
                 $errortext = $text[3];
            }
            // если загружается картинка
            if (is_uploaded_file($_FILES['userfile']['tmp_name'])){
                   $userfile=$_FILES['userfile']['tmp_name'];
                   $userfile_size=$_FILES['userfile']['size'];
                   $user=strtolower(htmlspecialchars($_FILES['userfile']['name'], ENT_QUOTES));
                   //Проверяем, что загруженный файл - картинка
                   $sz=GetImageSize($userfile);
                   if (!(ereg("^.+\.(gif|jpg|png)$", $user) && ($sz[2]==1 || $sz[2]==2 || $sz[2]==3))) {
                        $errortext = $text[9];
                        $flag = false;
                        return;
                   }
                   //Если размер файла больше заданного
                   if ($userfile_size > 1024000){
                       $errortext = $text[10];
                       $flag = false;
                       return;
                   }
                //   $sz[0]; //Ширина
                //   $sz[1]; //Высота
                   $file = true;
            }
            // если нет какого либо обязательного параметра
            if (!$flag){
                $_day        = htmlspecialchars($_POST['day']);
                $_month      = htmlspecialchars($_POST['month']);
                $_year       = htmlspecialchars($_POST['year']);
                $_title      = htmlspecialchars($_POST['title']);
                $_text       = htmlspecialchars($_POST['text']);
            }
            else
            {
              $time = mktime(date("G"),date("i"),date("s"), $_POST['month'],$_POST['day'],$_POST['year']);
              $title  = $_POST['title'];
              $text   = $_POST['text'];
              $resmax = se_db_query("SELECT max(id) AS obid FROM news");
              $rmax   = mysql_fetch_array($resmax);
              $maxid  = $rmax['obid']+1;
              // если загружаем файл
              if ($file){
                $uploadfile     = getcwd().$IMAGE_DIR.$maxid.".".substr($user, -3);
                $uploadfileprev = getcwd().$IMAGE_DIR.$maxid."_prev.".substr($user, -3);
                $filename       = "$maxid.".substr($user, -3);
                $fileextens     = substr($user, -3);
                if ($sz[0]>$width) {
                    $uploadfiletmp  = getcwd().$IMAGE_DIR.$maxid.".temp";
                    move_uploaded_file($userfile, $uploadfiletmp);
                    ImgCreate($uploadfileprev,$uploadfile,$uploadfiletmp,$fileextens, $width, $thumbwdth);
                    @unlink($uploadfiletmp);
                }
                else {
                    move_uploaded_file($userfile, $uploadfile);
                    ThumbCreate($uploadfileprev,$uploadfile,$fileextens,$thumbwdth);
                }
              }
              $cat_name=$section->params[19]->value;
              if (!se_db_is_item("news_category","kod='$cat_name'")) {
               se_db_perform("news_category", array('kod'=>$cat_name, 'title'=>$cat_name),'insert');
               $id_cat=se_db_insert_id("news_category");
              } else  
               $id_cat=se_db_fields_item("news_category","kod='$cat_name'",'id');
              se_db_query("INSERT INTO news
                           (lang, id_category,id, date, pub_date, title, text, img)
                           VALUES ('$lng','$id_cat','$maxid', '$time', '$time', '$title','$text', '$filename')");
             // RSS_NEWS($razdel);
              Header("Location: http://".$_SERVER['HTTP_HOST']."/".$_page);
            }
        }
//EndSubPage2
} else
if(($razdel == $__request['razdel']) && !empty($__request['sub']) && ($__request['sub']==3)){
//BeginSubPage3
    // редактирование новости
        if (isset($_GET['object'])){
            $nid = $_GET['object'];
            $redit = se_db_query("SELECT date, title, text, img FROM news WHERE id = '$nid'");
            $edit = mysql_fetch_array($redit);
            // сформировать дату
            $_time = explode(".",date("d.m.Y", $edit['date']));
            $_day       = htmlspecialchars(stripslashes($_time[0]));
            $_month     = htmlspecialchars(stripslashes($_time[1]));
            $_year      = htmlspecialchars(stripslashes($_time[2]));
            $_title      = htmlspecialchars(stripslashes($edit['title']));
            $_text       = htmlspecialchars(stripslashes($edit['text']));
            $filename    = $edit['img'];
            if (isset($_POST['Save'])){
                $flag     = true;
                $file     = false;
                if (empty($_POST['day']) && $flag){
                     $flag = false;
                     $errortext = $text[5];
                }
                if (empty($_POST['month']) && $flag){
                     $flag = false;
                     $errortext = $text[4];
                }
                if (empty($_POST['year']) && $flag){
                     $flag = false;
                     $errortext = $text[6];
                }
                if (!checkdate(intval($_POST['month']), intval($_POST['day']), intval($_POST['year'])) && flag){
                     $flag = false;
                     $errortext = $text[7];
                }
                if (empty($_POST['title']) && $flag){
                     $flag = false;
                     $errortext = $text[3];
                }
                // если загружается картинка
                if (@is_uploaded_file($_FILES['userfile']['tmp_name'])){
                      $userfile=$_FILES['userfile']['tmp_name'];
                       $userfile_size=$_FILES['userfile']['size'];
                       $user=strtolower(htmlspecialchars($_FILES['userfile']['name'], ENT_QUOTES));
                       //Проверяем, что загруженный файл - картинка
                       $sz=@GetImageSize($userfile);
                       if (!(ereg("^.+\.(gif|jpg|png)$", $user) /*&& ($sz[2]==1 || $sz[2]==2 || $sz[2]==3)*/)) {
                            $errortext = $text[9];
                            $flag = false;
                            return;
                       }
                       //Если размер файла больше заданного
                       if ($userfile_size > 1024000) {
                           $errortext = $text[10];
                           $flag = false;
                           return;
                       }
                       $sz[0]; //Ширина
                       $sz[1]; //Высота
                       $file = true; // файл загружен
                }
                // если нет какого либо обязательного параметра
                if (!$flag){
                    $_day        = htmlspecialchars($_POST['day']);
                    $_month      = htmlspecialchars($_POST['month']);
                    $_year       = htmlspecialchars($_POST['year']);
                    $_title      = htmlspecialchars($_POST['title']);
                    $_text       = htmlspecialchars($_POST['text']);
                }
                else
                {
                  $time = mktime(date("G"),date("i"),date("s"), $_POST['month'],$_POST['day'],$_POST['year']);
                  $title  = $_POST['title'];
                  $text   = $_POST['text'];
                  // если загружаем файл
                  if ($file)
                  {
                    $uploadfile     = getcwd().$IMAGE_DIR.$nid.".".substr($user, -3);
                    $uploadfileprev = getcwd().$IMAGE_DIR.$nid."_prev.".substr($user, -3);
                    $filename       = "$nid.".substr($user, -3);
                    $fileextens     = substr($user, -3);
                    if ($sz[0]>$width) {
                        $uploadfiletmp  = getcwd().$IMAGE_DIR.$nid.".temp";
                        move_uploaded_file($userfile, $uploadfiletmp);
                        ImgCreate($uploadfileprev,$uploadfile,$uploadfiletmp,$fileextens, $width, $thumbwdth);
                        @unlink($uploadfiletmp);
                    }
                    else {
                        move_uploaded_file($userfile, $uploadfile);
                        ThumbCreate($uploadfileprev,$uploadfile,$fileextens,$thumbwdth);
                    }
                  }
                  se_db_query("UPDATE news
                               SET   `date`      = '$time',
                                     `title`     = '$title',
                                     `text`      = '$text',
                                     `img`       = '$filename'
                               WHERE id = '$nid'");
                  //RSS_NEWS($razdel);
                  Header("Location: http://".$_SERVER['HTTP_HOST']."/".$_page);
                }
            } //if (isset($_POST['Save']))
            if (isset($_POST['Delete'])){
                if (!empty($filename)){
                    $temp = explode(".",$filename);
                    $delprevimg = $temp[0]."_prev.".$temp[1];
                    $delprevimg = getcwd().$IMAGE_DIR.$delprevimg;
                    $filename   = getcwd().$IMAGE_DIR.$filename;
                    if (file_exists($delprevimg)) @unlink($delprevimg);
                    if (file_exists($filename)) @unlink($filename);
                }
                se_db_query("DELETE FROM news WHERE id = '$nid'");
                //RSS_NEWS($razdel);
                Header("Location: http://".$_SERVER['HTTP_HOST']."/".$_page);
            }
        }
//EndSubPage3
}
}
//EndSubPages
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"cont_olnews\" [part.style]>
<noempty:part.title><h3 class=\"contentTitle[part.style_title]\">[part.title]</h3> </noempty>
<noempty:part.image><img border=\"0\" class=\"contentImage[contentstyle_img]\" src=\"[part.image]\" alt=\"[part.image_alt]\"></noempty>
<noempty:part.text><div class=\"contentText\" [part.style_text]>[part.text]</div> </noempty>
<br clear=\"all\">
$add_news
$MANYPAGE
".se_record_list($section, "
<div class=\"object\">
<h4 class=\"objectTitle\">[@col0]<FONT size=-1 class=dataType_date>[@col1]</font> &nbsp;
<span class=\"textTitle\">[@col2]</span> </h4> 
 [@col3]
<div class=\"objectNote\">[@col4][@col5]</div> 
</div> 
")."
<br clear=\"all\">
$MANYPAGE
</div> 
<!-- =============== END CONTENT ============= -->";
$__module_content['show'] = "
";
$__module_subpage[1]['form'] = "<DIV class=\"content\" id=\"olnews_show\">
<a id=\"backlink\" href=\"/".$_page."\">{$section->params[17]->value}</a>
<h4 class=\"objectTitle\" id=\"viewTitle\">
$col1</h4>
$col4<DIV class=\"viewText\">
$col2</DIV><br clear=\"all\">
<INPUT class=\"buttonSend\" id=\"ViewBack\" onclick='javascript:history.back()' type=\"button\" value=\"{$section->params[18]->value}\">
</DIV>
";
$__module_subpage[2]['group'] = "2";
$__module_subpage[2]['form'] = "<script type=\"text/javascript\">
var flag=true;
function button(tag,texts)
{
eval('document.form.'+texts+'.focus()');
var str = document.selection;
var range = str.createRange();
range.colapse;
range.text= \"[\"+tag+\"]\" + range.text + \"[/\"+tag+\"]\";
}
function www(typik,texts)
{
eval('document.form.'+texts+'.focus()');
var str = document.selection;
var range = str.createRange();
range.colapse;
if (typik==\"mail\")
{ 
 var mail = prompt(\"Введите e-mail адрес\",\"\");
  if (str.type == \"Text\")
  range.text= \"[mailto=\"+mail+\"]\"+range.text+\"[/mailto]\";
  else range.text = \"[mailto=\"+mail+\"]\"+mail+\"[/mailto]\";
}
else if (typik==\"img\")
  { 
   var url = prompt(\"Введиte URL рисунка\",\"http://\");
   if (str.type == \"Text\")
   range.text= \"[img src=\"+url+\"]\";
   else range.text = \"[img src=\"+url+\"]\";
  }
 else 
  { 
   var url = prompt(\"Введитe URL\",\"http://\");
   if (str.type == \"Text\")
   range.text= \"[a href=\"+url+\"]\"+range.text+\"[/a]\";
   else range.text = \"[a href=\"+url+\"]\"+url+\"[/a]\";
  }
}
</script>
<!-- Добавить новость -->
<DIV class=\"content\" id=\"cont_olnews\">
<b id=\"errortext\">$errortext</b>
<FORM method=\"post\" action=\"\" enctype=\"multipart/form-data\">
<TABLE class=\"tableTable\" id=\"edNews\">
<TBODY>
<TR><TD class=\"title\">Дата*</td><TD class=\"field\">
&nbsp;<input id=\"date\" type=\"text\" name=\"day\" maxlength=\"2\" size=\"2\" value=\"$_day\">
<input id=\"date\" type=\"text\" name=\"month\" maxlength=\"2\" size=\"2\" value=\"$_month\">
<input id=\"date\" type=\"text\" name=\"year\" maxlength=\"4\" size=\"4\" value=\"$_year\">
</td></TR>
<TR><TD class=\"title\">{$section->params[23]->value}*</td><TD class=\"field\">&nbsp;<input id=\"add_title\" type=\"text\" name=\"title\" value=\"$_title\"></td></TR>
<TR><TD class=\"title\">{$section->params[24]->value}</TD><TD>&nbsp;<input id=\"add_img\" type=\"file\" name=\"userfile\"></td></TR>
<TR><TD class=\"title\" valign=\"top\">{$section->params[25]->value}</td><TD class=\"field\">
  <DIV class=\"erm_allButtons\">
  <button id=\"erm_ButtonsBlock\" onclick=\"button('b','text');return false;\"><img src='$language/skin/icons/b.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('em','text');return false;\"><img src='$language/skin/icons/i.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('u','text');return false;\"><img src='$language/skin/icons/u.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('ul','text');return false;\"><img src='$language/skin/icons/ul.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('ol','text');return false;\"><img src='$language/skin/icons/ol.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('center','text');return false;\"><img src='$language/skin/icons/center.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('sup','text');return false;\"><img src='$language/skin/icons/sup.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('sub','text');return false;\"><img src='$language/skin/icons/sub.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('code','text');return false;\"><img src='$language/skin/icons/code.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"www('url','text');return false;\"><img src='$language/skin/icons/url.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"www('mail','text');return false;\"><img src='$language/skin/icons/mail.gif' alt=\"button\"></button>
  </div></TD></TR>
<TR><TD colspan=\"2\">
<textarea style=\"width:100%;\" rows=\"10\" cols=\"40\" id=\"add_txt\" name=\"text\">$_text</textarea></td></TR>
<TR><TD colspan=\"2\">&nbsp;</td></TR>
<TR><TD colspan=\"2\">
<input class=\"buttonSend\" id=\"ed_Save\"  type=\"submit\" name=\"Save\" value=\"{$section->params[26]->value}\" >
<input class=\"buttonSend\" id=\"ed_Back\" type=\"button\" value=\"{$section->params[28]->value}\" onclick='javascript:history.back()'>
</td></TR>
</TBODY>
</TABLE>
</FORM>
</div>";
$__module_subpage[3]['group'] = "2";
$__module_subpage[3]['form'] = "<script type=\"text/javascript\">
var flag=true;
function button(tag,texts)
{
eval('document.form.'+texts+'.focus()');
var str = document.selection;
var range = str.createRange();
range.colapse;
range.text= \"[\"+tag+\"]\" + range.text + \"[/\"+tag+\"]\";
}
function www(typik,texts)
{
eval('document.form.'+texts+'.focus()');
var str = document.selection;
var range = str.createRange();
range.colapse;
if (typik==\"mail\")
{ 
 var mail = prompt(\"Введите e-mail адрес\",\"\");
  if (str.type == \"Text\")
  range.text= \"[mailto=\"+mail+\"]\"+range.text+\"[/mailto]\";
  else range.text = \"[mailto=\"+mail+\"]\"+mail+\"[/mailto]\";
}
else if (typik==\"img\")
  { 
   var url = prompt(\"Введиte URL рисунка\",\"http://\");
   if (str.type == \"Text\")
   range.text= \"[img src=\"+url+\"]\";
   else range.text = \"[img src=\"+url+\"]\";
  }
 else 
  { 
   var url = prompt(\"Введитe URL\",\"http://\");
   if (str.type == \"Text\")
   range.text= \"[a href=\"+url+\"]\"+range.text+\"[/a]\";
   else range.text = \"[a href=\"+url+\"]\"+url+\"[/a]\";
  }
}
</script>
<!-- Добавить новость -->
<DIV class=\"content\" id=\"cont_olnews\">
<b id=\"errortext\">$errortext</b>
<FORM method=\"post\" action=\"\" enctype=\"multipart/form-data\">
<TABLE class=\"tableTable\" id=\"edNews\">
<TBODY>
<TR><TD class=\"title\">Дата*</td><TD class=\"field\">
&nbsp;<input id=\"date\" type=\"text\" name=\"day\" maxlength=\"2\" size=\"2\" value=\"$_day\">
<input id=\"date\" type=\"text\" name=\"month\" maxlength=\"2\" size=\"2\" value=\"$_month\">
<input id=\"date\" type=\"text\" name=\"year\" maxlength=\"4\" size=\"4\" value=\"$_year\">
</td></TR>
<TR><TD class=\"title\">{$section->params[23]->value}*</td><TD class=\"field\">&nbsp;<input id=\"add_title\" type=\"text\" name=\"title\" value=\"$_title\"></td></TR>
<TR><TD class=\"title\">{$section->params[24]->value}</TD><TD>&nbsp;<input id=\"add_img\" type=\"file\" name=\"userfile\"></td></TR>
<TR><TD class=\"title\" valign=\"top\">{$section->params[25]->value}</td><TD class=\"field\">
  <DIV class=erm_allButtons>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('b','text');return false;\"><img src='$language/skin/icons/b.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('em','text');return false;\"><img src='$language/skin/icons/i.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('u','text');return false;\"><img src='$language/skin/icons/u.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('ul','text');return false;\"><img src='$language/skin/icons/ul.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('ol','text');return false;\"><img src='$language/skin/icons/ol.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('center','text');return false;\"><img src='$language/skin/icons/center.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('sup','text');return false;\"><img src='$language/skin/icons/sup.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('sub','text');return false;\"><img src='$language/skin/icons/sub.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"button('code','text');return false;\"><img src='$language/skin/icons/code.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"www('url','text');return false;\"><img src='$language/skin/icons/url.gif' alt=\"button\"></button>
  <button id=\"erm_ButtonsBlock\" onclick=\"www('mail','text');return false;\"><img src='$language/skin/icons/mail.gif' alt=\"button\"></button>
  </div></TD></TR>
<TR><TD colspan=\"2\">
<textarea style=\"width:100%;\" rows=\"10\" cols=\"40\" id=\"add_txt\" name=\"text\">$_text</textarea></td></TR>
<TR><TD colspan=\"2\">&nbsp;</td></TR>
<TR><TD colspan=\"2\">
<input class=\"buttonSend\" id=\"ed_Save\"  type=\"submit\" name=\"Save\" value=\"{$section->params[26]->value}\" >
<input class=\"buttonSend\" id=\"ed_Back\" type=\"button\" value=\"{$section->params[28]->value}\" onclick='javascript:history.back()'>
</td></TR>
</TBODY>
</TABLE>
</FORM>
</div>";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};