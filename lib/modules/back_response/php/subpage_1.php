<?php

    if (seUserGroup() < 3) return;
    $pages = (int)$section->parametrs->param17;
    $id_subsc = getRequest('id', 3);
    $id_subsc = urldecode($id_subsc);
    $tab = new seTable('back_response');
    $tab->select('l_name, f_name, s_name, email, phone');
    $tab->where("`id_subscribe`='?'",$id_subsc);
    $MANYPAGE = $tab->pageNavigator($pages);
    $tablist = $tab->getList(); 
    $__data->setList($section, 'scribers', $tablist);
    
    $pass = new seTable('back_response');
    $pass->select('DISTINCT id_subscribe');
    $passlist = $pass->getList();
    $__data->setList($section, 'subscribes', $passlist);
   

?>