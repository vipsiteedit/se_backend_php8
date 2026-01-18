<?php

if (seUserGroup() != 3) {
    return;
}
if (isRequest('region')) {
    $regionid = getRequest('region', 1);
    $region = new seTable('region', 'r');
    $region->where('r.id_country = ?', $regionid); 
    $regionlist = $region->getList();
    foreach ($regionlist as $row) {
        $select .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
    echo '<option value="0">' . $section->language->lang070 . '</option>' . $select;
    exit();
}  
if (isRequest('town')) {
    $townid = getRequest('town', 1);
    $town = new seTable('town', 't');
    $town->where('t.id_region = ?',$townid); 
    $townlist = $town->getList();
    foreach ($townlist as $row) {
        $select .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
    echo '<option value="0">' . $section->language->lang071 . '</option>' . $select;
    exit();
} 

?>