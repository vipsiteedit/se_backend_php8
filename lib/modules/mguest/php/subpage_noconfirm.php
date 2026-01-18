<?php

if (seUserGroup() < 3) {
    header("Location: ".$thispagelink);
    exit;
}    
        
$file = file($filename);
foreach($file as $i=>$item) {
   $it = unserialize($item);
   if ($it['active'] == 'N') {
        unset($file[$i]);
   }
}
file_put_contents($filename, $file);
header("Location: ".$thispagelink);

?>
