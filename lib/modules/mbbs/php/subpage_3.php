<?php

$bbs = new seTable('bbs');
$bbs->select('text,short,img');
$bbs->find($id);
$fulltext = nl2br($bbs->text); 
  
$titlepage = $bbs->short;
$imgfull = '';


if ($bbs->img != '') {
    $imgfull = '/images/bbs/' . $bbs->img;  
}

?>
