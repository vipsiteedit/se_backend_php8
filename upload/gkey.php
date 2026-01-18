<?php
chdir("../");
$path=getcwd()."/system/.key";

if (file_exists($path)) echo join("",file($path)) ;
