<?php

$allpages = intval($curunit / $strlimit) + intval(($curunit % $strlimit) > 0);
if (($allpages > 1) && !$showpages) {
    $showpages = 1;
    $len = 2;
    if ($curpg - $len < 1) {
        $start = 1;
        $stop = 2 * $len + 1;
    } else {
        $start = $curpg - $len;
        $stop = $curpg + $len;
    }
    $startpg = $stoppg = $startshow = $stopshow = 0;
    if ($start > 1) {
        $startshow = 1;
        if ($start > 3) {
            $startpg = 1; //intval(($start - 1) / 2); 
        }
    }
    if ($stop <= $allpages) {
        if ($stop == $allpages - 1) {
            $stopshow = $stop;
        } else {
            $stopshow = 1;
        }
        if ($stop < $allpages - 1) {
            $stoppg = 1;//$stop + intval(($allpages - $stop) / 2);
        } 
    }
    for ($pg = $start; ($pg < $stop) && ($pg <= $allpages); ++$pg) {
        add_simplexml_from_array(&$section, 'mypages', array('pg' => $pg, 'cur' => intval($pg == $curpg)));    
    }
}

?>