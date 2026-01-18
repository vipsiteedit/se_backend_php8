<?php

if (!$flag) return; 
$lng= se_getLang();
$shop_group->select('id,name,code_gr,picture,lang');
$shop_group->where("lang='?'",$lng);
$shop_group_list = $shop_group->getList();
foreach($shop_group_list as $spr){
    $id_gr = $spr['id'];
    $name_gr = $spr['name'];
    $code_gr = $spr['code_gr'];
    $picture = $spr['picture']; 
    
    $__data->setItemList($section,'shop_group',$spr);    
}     


if(isset($_POST['AddGroup'])){

    Header ("Location: ".seMultiDir().'/'.$_page.'/'.$section->id.'/'.sub14.'/');

}

if(isset($_POST['Back'])){

    Header("Location: ".seMultiDir().'/'.$_page.'/'); 

}

?>