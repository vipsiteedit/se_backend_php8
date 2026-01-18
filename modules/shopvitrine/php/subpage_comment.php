<?php

$comments = $psg->getGoodsComment($viewgoods);
$count_comment = count($comments);
if(!empty($comments)){
    $__data->setList($section, 'comments', $comments);
}
$sid = session_id(); 
$time = time();

?>