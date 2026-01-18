<?php

$comms = new seTable('shop_comm');
$comms->where('id_price=?',  $viewgoods);
$comms->orderby('id', 1);
$commlist = $comms->getList();
$flstyle = false;
foreach ($commlist as $comm) {
    $flstyle = !$flstyle;
    $comm['style'] = ($flstyle) ? 'tableRowOdd' : 'tableRowEven';
    $comm['date'] = date('m.d.Y', strtotime($comm['date']));
    list($comments, $response) = explode('<%comment%>', $comm['commentary']);
    if (empty($response))
        $response = $comm['response'];
    unset($comm['commentary']);
    
    $comm['comment'] = str_replace("\r\n", '<br>', $comments);
    if (!empty($response)) {
        $comm['adminnote'] = str_replace("\r\n", '<br>', $response);
    }
    $__data->setItemList($section, 'comments', $comm);
}

?>