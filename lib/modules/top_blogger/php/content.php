<?php

//  запрос в бд 
    $select = "(SELECT COUNT(id) FROM blog_posts bp WHERE id_user=bus.id_blogger) as countpost,
                (SELECT COUNT(id) FROM blog_user_subscribe bus1 WHERE bus1.id_blogger=bus.id_blogger) AS countsibscribe";
    $blogger = new seTable('blog_user_subscribe', 'bus');
    $blogger->select("p.id as uid, p.last_name, p.first_name, p.avatar, {$select}");
    $blogger->innerjoin('person p', 'bus.id_blogger=p.id');
    $blogger->groupby('bus.id_blogger');
    $blogger->orderby('`countsibscribe` DESC, `countpost` DESC, `bus`.`id_blogger`');                             
    $bloggerlist = $blogger->getList(0,strval($section->parametrs->param1));

    foreach($bloggerlist as $k=>$item){
        if(!$item['avatar'])    
            $bloggerlist[$k]['avatar'] = '/'.$__MDL_URL.'/no_foto.png';
        else
            $bloggerlist[$k]['avatar'] = '/public/avatars/'.$item['avatar'];
        $bloggerlist[$k]['avatar'] = se_getDImage($bloggerlist[$k]['avatar'], (int)$section->parametrs->param4);
        $bloggerlist[$k]['fio'] = $item['first_name'].' '.$item['last_name'];
    }
    $__data->setList($section, 'bloggers', $bloggerlist);
    
?>