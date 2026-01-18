<?php

if ($pagesStep < count($printPages)) {
    $pgInfo = $printPages[$pagesStep++];
    $allpages = intval($pgInfo['COUNT'] / $pgInfo['PAGE']) + intval(($pgInfo['COUNT'] % $pgInfo['PAGE']) > 0);
    $showpages = 0;
    if ($allpages > 1) {
        $showpages = 1;
        $len = 4;
        if ($pgInfo['NUM'] - $len < 1) {
            $start = 1;
            $stop = 2 * $len + 1;
        } else {
            $start = $pgInfo['NUM'] - $len;
            $stop = $pgInfo['NUM'] + $len;
        }
        $startpg = $stoppg = $startshow = $stopshow = 0;
        if ($start > 1) {
            $startshow = 1;
            if ($start > 2) {
                $startpg = intval(($start - 1) / 2); 
            }
        }
        if ($stop <= $allpages) {
            $stopshow = 1;
            if ($stop < $allpages) {
                $stoppg = $stop + intval(($allpages - $stop) / 2);
            } 
        }
        unset($section->mypages);
        for (; ($start < $stop) && ($start <= $allpages); ++$start) {
            $__data->setItemList($section, "mypages", array('pg' => $start, 'cur' => intval($start == $pgInfo['NUM'])));    
        }
    }
}

?>