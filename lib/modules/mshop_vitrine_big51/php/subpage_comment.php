<?php

$comm = $psg->getGoodsComment($viewgoods);
$count_comment = count($comm);
if(empty($comm)){
    $comm = '0'; 
} else {
    $__data->setList($section, 'comments', $comm);
}
if (!isset($error_comm_message))
    $error_comm_message = '';    

?>