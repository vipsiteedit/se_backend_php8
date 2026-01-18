<?php

    if(seUserGroup()==3)return;             
    $time = time();           
    $id = seUserId();                                                      
    $account = new seTable('se_loginza');                     
    $account->select('id, user_id, real_user_id, photo');
    $account->where('user_id=?', $id); 
    $per = $account->getList();                 
    if($per){                        
        foreach($per as $key=>$item){        
            $fio = new seUser();
            $fio->select('*')->find($item['real_user_id']);
            $pers = $fio->getPerson();
            $fio = $pers->first_name." ".$pers->last_name;
            $per["$key"]['fio']  = $fio;
        }   
        $__data->setList($section, 'dels', $per); 
    } else {
        $er = $section->parametrs->param31;
    } 

?>