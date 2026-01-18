<?php

if (!$ACCESS_ON) {
    return;
}   
$townhtml = ' ';
$kolvo = '1';
if (seUserGroup() > 0) {
    $name = seUserName(); 
}
if (isRequest('GoToFilecatalog')) {
//require_once("lib/lib_images.php"); //Присоединяем графическую библиотеку
    $filecatalog = new seTable('filecatalog');
    $filecatalog->select('MAX(id) AS obid');
    $filecatalog->fetchone();
    $resmax = $filecatalog->obid; 
    $maxid  = $resmax + 1;
    $width_prew = $section->parametrs->param4; 
    if ($width_prew == 0) {
        $width_prew = 100;
    }
    $width = $section->parametrs->param5; 
    if ($width == 0) {
        $width=350;
    }
    $img = se_set_image_prev($width_prew, $width, "filecatalog", ($maxid . 'vrem' . time()));
    //считование 
    $name = se_db_output($req['name']);
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
    $flag = true;
// из-за некотрых изменений отлавливаем то что заполнено в полях
//обрабатываем name
    if (empty($name)) {
        $flag = false;
        $errortext = $section->parametrs->param29;
    //    echo "имя";
    }
//обрабатываем краткий текст
    if ($flag && empty($short)) {
        $flag = false;
        $errortext = $section->parametrs->param32;
    }
//обрабатываем текст
    if ($flag && empty($text)) {
        $flag = false;
        $errortext = $section->parametrs->param33;
    }
//обрабатываем скриншоты
    $kolvoimg = $section->parametrs->param43;
    $width_prew = $section->parametrs->param40; 
    if ($width_prew == 0){
        $width_prew = 50;
    }
    $width = $section->parametrs->param41; 
    if ($width == 0) {
        $width=150;
    }
    for ($i = 0; $i < $kolvoimg; $i++) {
        $imgnames = $maxid . 'num' . $i . 'vrem' . time(); 
        $skrin[$i] = se_set_image_prev($width_prew, $width, "filecatalog", $imgnames, ($i + 1));
    } 
    $skrin = Clear_array_empty($skrin);
//обрабатываем ссылки
    $kolvo = $section->parametrs->param42;  
    for ($i = 0; $i < $kolvo; $i++) {
        if (isRequest('adrlink' . $i)) {
            if ((isRequest('namelink' . $i)) && (isRequest('adrlink' . $i))) {
                $namelinks[$i] = (getRequest(('namelink' . $i), 3));
                $adrlinkspr = (getRequest(('adrlink' . $i),3)) ;
    // проверяем как была добвлена ссылка с hhtp  в начале или без
                if ((substr($adrlinkspr, 0, 7)) == "http://") {
                    $adrlinks[$i] = substr($adrlinkspr, 7, (strlen($adrlinkspr)));
                } else {
                    $adrlinks[$i] = $adrlinkspr;
                }
            }
        } 
    }
//обрабатываем url
// if (substr($url, 0, 7)=="http://" && !empty($url)) $url=substr($url, 7);
    if (!$flag) {  //если есть ошибки, то отправляем в космос
        $_razdel = $razdel;
        $_sub = 1;
    } else {
    //добавляем запись
        $date = date("Y-m-d");
//$filecatalog = new seTable('filecatalog');
        $filecatalog->insert();
        $filecatalog->user_id = seUserId();
        $filecatalog->page = $_page;
        $filecatalog->date = date("Y-m-d");
        $filecatalog->name = utf8_substr($req['name'], 0, 50);
        $filecatalog->skrin = implode(chr(8), $skrin);        
        $filecatalog->nameurl = implode(chr(8), $namelinks);
        $filecatalog->url = implode(chr(8), $adrlinks);
        $filecatalog->short = $req['short'];
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
        $filecatalog->rating = ("3" . chr(8) . "0");
        $filecatalog->text = utf8_substr($req['text'], 0, intval($section->parametrs->param38));
        $filecatalog->img = $img;
        $filecatalog->statistics = 0;
        $filecatalog->viewing = 0;
        if ($filecatalog->save()) {     
            header("Location: /" . $_page . "/?" . time());
            exit();
        }
    }
}
 //  print_r ($_FILES);

?>