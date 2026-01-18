<?php

if (!$uid) {
    header("Location: ".seMultiDir()."/$_page/");
    exit();
}
$alllist = 0;  
if (!isRequest('id')) {  //Показываем всех
    $alllist = 1;
    $tbl = new seTable("forum_users","fu");
    $tbl->where("enabled='Y'");
    $tbl->orderby("nick");
    $ru = $tbl->getList();
    $regusers = count($ru);
    unset($tbl);
  //Страницы
    if (isRequest('part')) {
        $ext_part = getRequest('part',1);//htmlspecialchars($_GET['part'], ENT_QUOTES);
    } else {
        $ext_part = 1;
    }

    $n = ceil($regusers/50);
    if ($ext_part > $n) {
        $ext_part = $n;
    }
    if ($ext_part < 1) {
        $ext_part = 1;
    }
    $ipages = 0;
    if (50 < $regusers) {
        $ipages = 1;
        for($i=1; $i<=$n; $i++) {
            $__data->setItemList($section, 'ipages', 
                array(
                    'ipage'   => $i,
                    'status' => (($i==$ext_part)?1:0)
                )
            );
        }
    }
    $start_page = ($ext_part-1)*50;
    for ($i = $start_page; ($i < $start_page + 50) && ($i<count($ru)); $i++) {
        $user = $ru[$i];
        $__data->setItemList($section, 'users',
            array(
                'id' => $user['id'],
                'nick' => htmlspecialchars($user['nick']),
                'date' => date("d", $user['registered'])." ".$month_R[date("m", $user['registered'])].date(" Y года, H:i", $user['registered'])
            )
        ); 
    }
} else {  //Показываем одного
    header("Location: ".seMultiDir()."/$_page/$razdel/sub17/?id=".getRequest('id', 1));
    exit();
}
// 07

?>