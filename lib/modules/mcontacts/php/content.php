<?php

include_once getcwd().'/lib/idna_convert.class.php';

$idna = new idna_convert();
$host = $idna->decode($_SERVER['HTTP_HOST']);
?>