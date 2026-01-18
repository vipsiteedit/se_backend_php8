<?php

//  общая инфа
    $posts = new seTable('blog_posts', 'bp');
    $posts->select("bp.views, IFNULL(bp.rating_correction+(SELECT SUM(rating) FROM blog_post_rating WHERE (id_post = bp.id)),bp.rating_correction) AS rating"); 
    $posts->where("`bp`.`lang`='?'", $lang);
    $posts->andwhere("`bp`.`id`='?'",$postId);
    $posts->fetchOne();
    
//  инфа по комментам
        $post_comments = new seTable('blog_comments','bc');
        $post_comments->select("bc.id, bc.upid, bc.id_post, bc.message, bc.id_user, bc.user_name, bc.rating_correction, bc.created_at as date, 
                                (SELECT CONCAT_WS(' ', p.`first_name`, p.`last_name`) FROM person p WHERE p.id=bc.id_user) as fio, 
                                (SELECT avatar FROM person p WHERE p.id=bc.id_user) as avatar, 
                                (SELECT SUM(rating) FROM blog_comment_rating WHERE id_comment=bc.id) as com_rating");
        $post_comments->where("`id_post` = '?'", $postId);
        $post_comments->orderby('id', 0);             
        $commentlist = $post_comments->getList();   
        $commentlist_count = count($commentlist);

        if(!empty($commentlist)){
            $newList = array();
            foreach($commentlist as $k=>$v){
                if(!$v['avatar'])                                                   //  аватарка
                    $commentlist[$k]['avatar'] = '/'.$__MDL_URL.'/no_foto.png';
                else 
                     $commentlist[$k]['avatar'] = '/public/avatars/'.$v['avatar'];
                $com_date = blogPreloadDate(strtotime($v['date']),'comment');       //  дата
                if($com_date['today']!='')
                    $commentlist[$k]['date'] = $com_date['today'].' '.$com_date['hour'].':'.$com_date['min'];
                else
                    $commentlist[$k]['date'] = $com_date['day'].'.'.$com_date['month'].'.'.$com_date['year'].' '.$com_date['hour'].':'.$com_date['min'];
                if(!$v['upid'])
                    $v['upid'] = 0;
                if(empty($newList[$v['upid']])){          
                    $newList[$v['upid']] = array();
                }
                $commentlist[$k]['com_rating'] = ($v['com_rating']) ? (int)$v['com_rating'] : 0; 
                $commentlist[$k]['com_rating'] += $v['rating_correction'];
                $commentlist[$k]['fio'] = ($v['id_user'] == 1000000) ? $v['user_name'] : $v['fio'];
                $commentlist[$k]['message'] = nl2br($v['message']);
                $newList[$v['upid']][] = $commentlist[$k];
            }
            $commentlist = array(); 
            tree_print($newList, $commentlist);
            $__data->setList($section, 'comments', $commentlist);
        }

?>