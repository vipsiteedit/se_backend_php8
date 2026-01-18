<?php

if (isRequest('region')){
    $regionid = getRequest('region',1);
    $region = new seTable('region');
    $region -> where('id_country=?',$regionid); 
    $regionlist = $region ->getList();
    $select = "";
    foreach($regionlist as $row) {
        $select .= '<option value="'.$row['id'].'">'. $row['name'] . '</option>';
    }
    echo '<select name="state" onChange="loadBox(\'nametown\',\'town\',this.value);">
    <option value="0">'.$section->language->lang028.'</option>'
    .$select.
    '</select>
    ';
    exit();
}  

if (isRequest('town')){
    $townid = getRequest('town',1);
    $town = new seTable('town');
    $town -> where('id_region=?',$townid); 
    $townlist = $town ->getList();
    $select = "";
    foreach($townlist as $row) {
        $select .= '<option value="'.$row['id'].'">'. $row['name'] . '</option>';
    }
    echo '<select name="city">
    <option value="0">'.$section->language->lang029.'</option>'
    .$select.
    '</select>
    ';
    exit();
}
exit();

?>