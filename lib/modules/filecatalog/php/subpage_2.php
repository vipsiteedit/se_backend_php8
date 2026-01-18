<?php

if (seUserGroup() < $group_num) {
    return;
} 
if (!isRequest('id')) {
    return; //это проверочка отсылает в небеса тех кто прописывает ссылку на вторую субстраницу ручками
}

$filecatalog = new seTable('filecatalog');
$id = getRequest('id', 1);
$filecatalog->find($id);
//считываем данные 
$name = $filecatalog->name;
$url = $filecatalog->url;
$nameurl = $filecatalog->nameurl;
$skrin = $filecatalog->skrin;
$short = $filecatalog->short;
$text = $filecatalog->text;
$_img = $filecatalog->img;
$pole1txt = $filecatalog->pole1;
$pole2txt = $filecatalog->pole2;
$pole3txt = $filecatalog->pole3;
$pole4txt = $filecatalog->pole4;
$pole5txt = $filecatalog->pole5;
$pole6txt = $filecatalog->pole6;
$pole7txt = $filecatalog->pole7;
$pole8txt = $filecatalog->pole8;
$pole9txt = $filecatalog->pole9;
$pole10txt = $filecatalog->pole10;
// обработка имеющийся информации для более легкого редактированиия
//d вывод картинки
if ($_img != "") {
    $imgfull = '/images/filecatalog/' . ($filecatalog->img);
    $picture='<div id="objimage">                 
    <img class="objectImage" alt="' . $titlepage . '" src="' . $imgfull . '" border="0"></div>';
} 
// обработка скриншотов
$skrin = se_db_output($filecatalog->skrin);
$skrins = explode(chr(8), $skrin);
$skrins = Clear_array_empty($skrins);
$skrinvivod = "";
for ($j = 0; $j <= (count($skrins) - 1); $j++) {
    if ($skrins[$j] != "") { 
        $imgfull='/images/filecatalog/'.$skrins[$j];  
        $skrinvivod .= '<DIV id="objimage">
                <IMG class="objectImage skrin" alt="' . $titlepage . '" src="' . $imgfull . '" border="0"></div>';
        $skrinvivod .= '    <div class="skrindel">
        <label class="title" for="del"> ' . $section->parametrs->param48 . ' ' . ($j + 1) . '</label>
        <div class="field"><input class="inputs" type="checkbox" name="delskrin' . $j . '" value="true" id="delskrin' . $j . '"></div>
        </div> ';
    } 
}
$kolskrins = (count($skrins) - 1);
// конец обработки сриншотов 
// вывод набора ссылок 
$nameurl = explode(chr(8), $nameurl);
$url = explode(chr(8), $url);
$kollinks = (count($url) - 1);
$links = "";
for ($j = 0; $j <= count($url) - 1; $j++) {
    $links .= '        <div>
                <div class="namelinktitle">'. $section->parametrs->param49.' '.($j+1).'</div>
             <div class="namelinktitleedit">'. $section->parametrs->param46 .'</div>
                <input class="namelinkedit inputs" name="namelink'.$j.'" value="'.$nameurl[$j].'">
         </div>';   
    $links .= '
            <div>
            <div class="adrlinktitle">'. $section->parametrs->param45  .' </div>
           <input   class="adrlink inputs"  name="adrlink'.$j.'" value="'.$url[$j].'">
           </div>    ';
}
//=============================================================================================  
if (isRequest('GoToFilecatalog')) { //изменяем запись
   //require_once("lib/lib_images.php"); //Присоединяем графическую библиотеку
    //print_r($_FILES['userfile']);
    $width_prew = intval($section->parametrs->param4); 
    if ($width_prew == 0) {
        $width_prew = 100;
    }
    $width = intval($section->parametrs->param5); 
    if ($width == 0) {
        $width = 350;
    }
    if (empty($req['img']) || is_uploaded_file($_FILES['userfile']['tmp_name'][0])) { 
        $img = se_set_image_prev($width_prew, $width, "filecatalog", $id, 0);
    } else {
        $img = $req['img'];
    }
    if (isRequest('delimg') && getRequest('delimg')) { //удаляем рисунок
        $img="";
    }
    if (isRequest('del') && getRequest('del')) { //удаляем запись
         //se_db_query("DELETE QUICK FROM filecatalog WHERE id='$id';");
        $filecatalog->delete($id);
        Header("Location: /" . $_page . "/?" . time());
        exit();
    }
//считование 
    $name = se_db_output($req['name']);
//$url = se_db_output($req['url']);
    $short = se_db_output($req['short']);
    $text = se_db_output($req['text']);
    $pole1txt = se_db_output($req['pole1']); 
    $pole2txt = se_db_output($req['pole2']);
    $pole3txt = se_db_output($req['pole3']);
    $pole4txt = se_db_output($req['pole4']);
    $pole5txt = se_db_output($req['pole5']);
    $pole6txt = se_db_output($req['pole6']);
    $pole7txt = se_db_output($req['pole7']);
    $pole8txt = se_db_output($req['pole8']);
    $pole9txt = se_db_output($req['pole9']);
    $pole10txt = se_db_output($req['pole10']);
//Предполагаем, что все введеные данные корректны
    $flag=true;
//обрабатываем name
    if (empty($name)) {
        $flag = false;
        $errortext = $section->parametrs->param29;
    }
    //обрабатываем скриншоты
    $kolvoimg = $section->parametrs->param43;
    $width_prew = $section->parametrs->param40; 
    if ($width_prew == 0) {
        $width_prew = 50;
    }
    $width = $section->parametrs->param41; 
    if ($width == 0) {
        $width = 150;
    }
    for ($i = 1; $i < $kolvoimg; $i++) {
        if (isRequest('delskrin' . ($i - 1)) && getRequest('delskrin' . ($i - 1))) { //удаляем скриншот
            unset($skrins[($i - 1)]);
        } 
        $imgnames = $id . 'num' . ($i + $kolskrins + 1); 
        $skrins[($i + $kolskrins)] =  se_set_image_prev($width_prew, $width, "filecatalog", $imgnames, ($i));
    } 
    $skrins = Clear_array_empty($skrins);
    $skrin =  
    // конец обработки скришнотов
    //обрабатываем ссылки
    $kolvo = $section->parametrs->param42;  
    for ($i = 0; $i < $kolvo; $i++) {
        if (isRequest('adrlink' . $i)) {
            if ((isRequest('namelink' . $i)) && (isRequest('adrlink' . $i))) {
                $namelinks[$i] = (getRequest(('namelink' . $i) , 3));
                $adrlinkspr = (getRequest(('adrlink' . $i), 3));
    // обробатываем как была добавлена ссылка
                if ((substr($adrlinkspr, 0, 7)) == "http://") {
                    $adrlinks[$i] = substr($adrlinkspr, 7, (strlen($adrlinkspr)));
                } else {
                    $adrlinks[$i] = $adrlinkspr ;
                }
            }
        } 
    }
    //обрабатываем краткий текст
    if ($flag && empty($short)) {
        $flag=false;
        $errortext=$section->parametrs->param32;
    }
//обрабатываем текст
    if ($flag && empty($text)) {
        $flag = false;
        $errortext = $section->parametrs->param33;
    }
    if (!$flag) {  //если есть ошибки, то отправляем в космос
        $_razdel = $razdel;
        $_sub = 1;
    } else {
//добавляем запись
        $date=date("Y-m-d");
        $filecatalog->find($id);
        $filecatalog->user_id = seUserId();
        $filecatalog->page = $_page;
        $filecatalog->name = utf8_substr($req['name'], 0, 50);
        $filecatalog->skrin = implode(chr(8), $skrins);        
        $filecatalog->nameurl = implode(chr(8), $namelinks);
        $filecatalog->url = implode(chr(8), $adrlinks);
        $filecatalog->pole1 = utf8_substr($req['pole1'], 0, intval($section->parametrs->param92));
        $filecatalog->pole2 = utf8_substr($req['pole2'], 0, intval($section->parametrs->param93));
        $filecatalog->pole3 = utf8_substr($req['pole3'], 0, intval($section->parametrs->param94));
        $filecatalog->pole4 = utf8_substr($req['pole4'], 0, intval($section->parametrs->param95));
        $filecatalog->pole5 = utf8_substr($req['pole5'], 0, intval($section->parametrs->param96));
        $filecatalog->pole6 = utf8_substr($req['pole6'], 0, intval($section->parametrs->param97));
        $filecatalog->pole7 = utf8_substr($req['pole7'], 0, intval($section->parametrs->param98));
        $filecatalog->pole8 = utf8_substr($req['pole8'], 0, intval($section->parametrs->param99));
        $filecatalog->pole9 = utf8_substr($req['pole9'], 0, intval($section->parametrs->param100));
        $filecatalog->pole10 = utf8_substr($req['pole10'], 0, intval($section->parametrs->param101));
        $filecatalog->short = $req['short'];
        $filecatalog->text = utf8_substr($req['text'], 0, intval($section->parametrs->param38));
        $filecatalog->img = $img;
        $filecatalog->save();
//        header("Location: /" . $_page . "/?" . time());
//        exit();
    }
    Header("Location: /" . $_page . "/?" . time());
    exit();
}
//=========================================================================================   
// Проверка не модер ли нас посетил который прописан в параметрах
$flag = 0; 
$moderi = $section->parametrs->param47;
$moders = explode(",", $moderi);
for ($j = 0; $j <= count($moders); $j++) {
    $userlogin = trim($moders[$j]); // очищаем от лишних пробелов массив модераторов
    if (($userlogin == seUserLogin()) && (seUserLogin() != "")) {
        $flag = 1;
    }
}
// конец проверки на модера    
$author = $filecatalog->user_id;
if ((!(($author != 0) && ($author == seUserId())  || (seUserGroup() == 3))) && ($flag == 0)) {
    return;    // это проверочка не дает левым пользователям менять чужие обьявления
}

?>