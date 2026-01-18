<?php

// Проверка не модер ли нас посетил который прописан в параметрах
$flag = 0; 
$moderi = $section->parametrs->param47;
$moders = explode(",", $moderi);
for ($j = 0; $j < count($moders); $j++) {
    $userlogin = trim($moders[$j]); // очищаем от лишних пробелов массив модераторов
    if (($userlogin == seUserLogin())&& (seUserLogin() != "")) {
        $flag = 1;
    }
}
// конец проверки на модера 
$filecatalog = new seTable('filecatalog');
$filecatalog->select('text, short, img, viewing, id, statistics, skrin, rating, pole1, pole2, pole3, pole4, pole5, pole6, pole7, pole8, pole9, pole10');
$filecatalog->find($_object);
$fulltext = se_db_output($filecatalog->text); 
$ratingn = ($filecatalog->rating);
//echo $ratingn.'<br>';  
$titlepage = se_db_output($filecatalog->short);
$pole1txt = se_db_output($filecatalog->pole1); 
$pole2txt = se_db_output($filecatalog->pole2);
$pole3txt = se_db_output($filecatalog->pole3);
$pole4txt = se_db_output($filecatalog->pole4);
$pole5txt = se_db_output($filecatalog->pole5);
$pole6txt = se_db_output($filecatalog->pole6);
$pole7txt = se_db_output($filecatalog->pole7);
$pole8txt = se_db_output($filecatalog->pole8);
$pole9txt = se_db_output($filecatalog->pole9);
$pole10txt = se_db_output($filecatalog->pole10);
if (($filecatalog->img) != "") {
    $imgfull = '/images/filecatalog/' . ($filecatalog->img);
    $picture = '<div id="objimage">
    <img class="objectImage" alt="' . $titlepage . '" src="' . $imgfull . '" border="0"></div>';
}
$idlink = se_db_output($filecatalog->id);
$statistics = se_db_output($filecatalog->statistics);
// обработка количества просмотров
if ($_SESSION['VW'][$idlink] != 1) {
    $_SESSION['VW'][$idlink] = 1;
    $viewing = $filecatalog->viewing;
    $viewing = $viewing + 1;
    $filecatalog->viewing = $viewing;
    $filecatalog->save();
}
$viewing = se_db_output($filecatalog->viewing);
//Конец обработки просмотров
// обработка скриншотов
$skrin = se_db_output($filecatalog->skrin);
$skrins = explode(chr(8), $skrin);
$skrinvivod = "";
for ($j = 0; $j <= (count($skrins) - 1); $j++) {
    if ($skrins[$j] != "") { 
        $imgfull = '/images/filecatalog/' . $skrins[$j];  
        $skrinvivod .= '
            <li>
            <a href="' . $imgfull . '" title="' . $titlepage . '">
                <img class="skrin" src="' . $imgfull . '" width="' . $section->parametrs->param53 . '" height="' . $section->parametrs->param54 . '" alt="" />
            </a>
        </li>';
    } 
}
// конец обработки сриншотов 
// Обработка рейтинга
$ratingtext = "";
$rating = explode(chr(8), $ratingn);
if (isRequest('GoRating')) {  //обработка кнопки голосовать 
    $rating0 = floatval(str_replace(',', '.', $rating[0]));
    $rating1 = floatval(str_replace(',', '.', $rating[1]));
    $golos= (getRequest('adv2', 2));
    if ($_SESSION['RT'][$idlink] != 1) {
        $_SESSION['RT'][$idlink] = 1;
        if ($golos > 0) {
//   echo $golos.'-'.$rating0.'=';
            $golos = ($golos-$rating0);
// echo $golos.' Действие 1 <br>';
            $rating1 = ($rating1+1);
//  echo $rating1.' =Повысили голосующих<br>';
//  echo $golos.'/'.$rating1.'=';
            $golos = ($golos/$rating1);
//  echo $golos.' Действие 2 повыш коэф <br>';
// echo $golos.'+'.$rating0.'=';
            $rating0 = ((floatval ($rating0))+(floatval ($golos)));
// echo $rating0.' Действие 3 результат <br>';
            $rating[0] = $rating0;
            $rating[1] = $rating1;
            $filecatalog->rating = implode(chr(8), $rating);
            $filecatalog->save();
        }
    }
}
$celreiting = round($rating[0]);
$ratingtext .= '<input name="adv2" type="radio" class="wow" value="1" title="' . $section->parametrs->param63 . '" '; 
if ($celreiting == "1") {
    $ratingtext .= 'checked="checked"'; 
} 
$ratingtext .= '/>';
$ratingtext .= '<input name="adv2" type="radio" class="wow" value="2" title="' . $section->parametrs->param64 . '" '; 
if ($celreiting == "2") {
    $ratingtext .= 'checked="checked"'; 
} 
$ratingtext .='/>';
$ratingtext .= '<input name="adv2" type="radio" class="wow" value="3" title="' . $section->parametrs->param65 . '" '; 
if ($celreiting == "3") {
    $ratingtext .= 'checked="checked"';
} 
$ratingtext .= '/>';
$ratingtext .= '<input name="adv2" type="radio" class="wow" value="4" title="' . $section->parametrs->param66 . '" '; 
if ($celreiting == "4") {
    $ratingtext .=  'checked="checked"'; 
} 
$ratingtext .= '/>';
$ratingtext .= '<input name="adv2" type="radio" class="wow" value="5" title="' . $section->parametrs->param67 . '" '; 
if ($celreiting == "5") {
    $ratingtext .= 'checked="checked"'; 
} 
$ratingtext .= '/>';
$ratinggolos = '<div class="golosa">
 <DIV class="golosa_titl">'.$section->parametrs->param69.'</div>
 <DIV class="golosa_kol">'. $rating[1].'</div>
 <DIV class="golosa_izm">'.$section->parametrs->param70.'</div>
</div>';
// Конец обработки рейтинга   
if (isRequest('GoToFilecatalog')) {
//проверка антиспама
    if (isset($_POST['pin'])) {
        if (empty($_POST['pin'])) {
            $errstpin = "errorinp";
            $errorpin = $section->parametrs->param60;
            $err = true;
        } else {
            require_once getcwd()."/lib/card.php";
            if(!checkcard($_POST['pin'])) {
                $errstpin = "errorinp"; 
                $errorpin = $section->parametrs->param59;
                $err = true;
            }
        }
    }   
// конец проверки антиспасма
    if ($err) {
        $commentsinstext = (getRequest('commentsinstext',3));
    }
    if (!$err) {
        $filecatalogmsg = new seTable('filecatalogmsg');
        $filecatalogmsg->select('max(id) as obid');
        $filecatalogmsg->fetchone();
        $filecatalogmsg->insert();
        $filecatalogmsg->idfiles = $idlink;
        $filecatalogmsg->message = (getRequest('commentsinstext',3));
        if (seUserName() == "") {
            $nameauthor = $section->parametrs->param52;
        } else {
            $nameauthor = seUserName();
        }
        $filecatalogmsg->author = ($nameauthor);
        $filecatalogmsg->save();
    }
}
//обработка коментариев 
$filecatalogmsg = new seTable('filecatalogmsg');
$filecatalogmsg->where("`idfiles` = '$idlink'");
$pages = $section->parametrs->param108;
$navigator = $filecatalogmsg->pageNavigator($pages);
$comments = '';
foreach ($filecatalogmsg->getlist() as $msg) {
// вставь сюда ссылку для модерирования коментария пример обращения к странице http://testeng.e-stile.ru/filecatalog/1/sub5/?id=30k&idmsg=23
    $comments .= ' <div class="comment_un">';
    if ((seUserGroup() == 3) || ($flag == 1)) {
        $comments .= '<a class="comments_link" style="text-decoration:none;" href="[link.subpage=5]?id=' . $idlink . '&idmsg=' . ($msg['id']) . '/">' . $section->parametrs->param6 . '</a>';
    } 
    $comments .= '<div class="comments_athor">' . $msg['author'] . '</div>';
    $comments .= '<div class="comments_text">' . $msg['message'] . '</div></div>'; 
}
// конец обработки коментариев
// генерация антиспама 
$sid = session_id();
$anti_spam = '<div>
                    <div  class="tablrow">
                    <img id="pin_img" src="/lib/cardimage.php?session=' . $sid . '">
                    <div class="titlepin">' . $section->parametrs->param61 . '</div>

                        <input class="inp inppin ' . $errstpin . '"' . $glob_err_stryle . ' name="pin" maxlength="5" value="" autocomplete="off">
                        <div class="err">' . $errorpin . '</div>
                    </div> 
                  </div>';
//конец генерации антиспама
 

?>