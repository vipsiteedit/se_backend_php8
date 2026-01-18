<?php

$usergroup = seUserGroup();
$comm = $psg->getGoodsComment($viewgoods);
if(empty($comm)){
    $comm = '0'; 
} else {
    $__data->setList($section, 'comments', $comm);
}

?>