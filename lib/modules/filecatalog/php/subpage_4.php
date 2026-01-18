<?php

if (isRequest('insimg')) {   
    $num = getRequest('kol');
    for ($i = 0; $i < $num; $i++) {  
        $select .= "
        <div class=\"obj userimg\">
             <label class=\"titleimg\" for=\"userfile\">Добавить скриншот ".($i+1)."</label> 
             <div class=\"field\"><input id=\"userfile\" type=\"file\" name=\"userfile[]\"></div> 
         </div> ";
    } 
    echo $select;
    exit();
}
if (isRequest('showlink')) {      // пример работающей ссылки  http://testeng.e-stile.ru/filecatalog/1/sub4/?showlink&idlink=10
    $idlink = getRequest('idlink'); // получаем номер обьявления
    $filecatalog = new seTable('filecatalog');
    $filecatalog->find($idlink);
    $nameurl = $filecatalog->nameurl;
    $url = $filecatalog->url;
// Повышаем статистику файла
    if ($_SESSION['ST'][$idlink] != 1) {
        $_SESSION['ST'][$idlink] = 1;
        $filecatalog->statistics = $filecatalog->statistics + 1;
        $filecatalog->save();
    }
// обробатываем ссылки
    $nameurl = explode(chr(8), $nameurl);
    $url = explode(chr(8), $url);
    $links = "";
    for ($j = 0; $j <= count($url); $j++) {
        if (empty($nameurl[$j])) { 
            $links .= '<div class="links"><a href="http://' . $url[$j] . '" target=_blank>' . $url[$j] . '</a></div><br>'; 
        } else {
            $links .= '<div class="links"><a href="http://' . $url[$j] . '" target=_blank>' . $nameurl[$j] . '</a></div><br>';
        }
   
    }
// конец обработки ссылок
    echo $links;
    exit();
}

?>