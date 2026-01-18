<?php

//  отобразить пользователей по убыванию по регистрации
    $select = "(SELECT COUNT(id) FROM blog_posts bp WHERE id_user=su.id) as countpost,
                (SELECT COUNT(id) FROM blog_user_subscribe WHERE id_blogger=su.id) AS countsibscribe";
    $blogger = new seTable('se_user', 'su');
    $blogger->select("su.id, p.id as uid, p.avatar, p.last_name, p.first_name, {$select}");
    $blogger->innerjoin('person p', 'su.id=p.id');
    $blogger->where("`su`.`id`!=?", seUserId());
    $blogger->orderby('su.id',1);
    $nav = $blogger->pageNavigator($section->parametrs->param3);  
    $bloggerlist = $blogger->getList();

    foreach($bloggerlist as $k=>$item){
        if(!$item['avatar'])    
            $bloggerlist[$k]['avatar'] = '/'.$__MDL_URL.'/no_foto.png';
        else
            $bloggerlist[$k]['avatar'] = '/public/avatars/'.$item['avatar'];
            $bloggerlist[$k]['avatar'] = se_getDImage($bloggerlist[$k]['avatar'], intval($section->parametrs->param4));
        $bloggerlist[$k]['fio'] = $item['first_name'].' '.$item['last_name'];
    };

    $__data->setList($section, 'bloggers', $bloggerlist);

?>