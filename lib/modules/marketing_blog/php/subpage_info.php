<?php

    $id_user = $isUser ='';

//  создаем запрос на выбор мнформации о пользователе
    if(isRequest('user')){
        $id_user = getRequest('user', 1);
        $info = new seTable('person', 'p');
        $info->select("p.id, p.avatar, post_index, p.social_url, CONCAT_WS(' ',`first_name`,`last_name`) AS fio, 
            (SELECT COUNT(id) 
            FROM blog_comments 
            WHERE id_post IN (SELECT id FROM blog_posts bp WHERE p.id = bp.id_user)) AS comment, 
            (SELECT SUM(views) 
            FROM blog_posts 
            WHERE id_user = p.id) AS view, 
            (SELECT COUNT(id)
            FROM blog_user_subscribe  `buf` 
            WHERE buf.id_blogger = p.id) AS favors,
            (SELECT name
            FROM country `cou` 
            WHERE cou.id = p.country_id) AS country,
            (SELECT name
            FROM region `reg` 
            WHERE reg.id = p.state_id) AS region,
            (SELECT name
            FROM town `tow` 
            WHERE tow.id = p.town_id) AS town
        
        "); 
        $info->where("`p`.`id` = ?", $id_user);       
//echo $info->getsql(); 
        $user = $info->fetchOne();          
        unset($info);
        $isUser = 1;
        $user['point'] = $user['post_index'];
        $user['point'] .= ($user['country']) ? ' '.$user['country'] : '';
        $user['point'] .= ($user['region']) ? ', '.$user['region'] : '';
        $user['point'] .= ($user['town']) ? ', '.$user['town'] : '';
        $user['view'] = ($user['view']) ? $user['view'] : 0;
        $tosubsc = (($user['id'] == seUserId()) || (seUserId() == 0)) ? 0 : 1;
//print_r($user);        
    }

//  создаем запрос в БД на выбор постов
    $postactive = $likeactive = $comactive = '';                                //  определить активну вкладку
    $select = "bp.id, 
                bp.id_user, 
                bp.picture,
                bp.listimages,
                bp.listimages_alt, 
                bp.title, 
                bp.short, 
                bp.views, 
                IFNULL(bp.rating_correction+(SELECT SUM(rating) FROM blog_post_rating WHERE (id_post = bp.id)),bp.rating_correction) AS favorite, 
                bp.url,
                bp.visible, 
                bp.commenting, 
                bp.beginning, 
                bp.created_at as last,
                (SELECT COUNT(id) 
                    FROM blog_comments 
                    WHERE id_post = bp.id) AS comment";

    $posts = new seTable('blog_posts', 'bp');
    $posts->select("{$select}"); 
    $posts->innerjoin('blog_category_post bcp', 'bp.id=bcp.id_post');
    $posts->innerjoin('blogcategories bc', 'bcp.id_category=bc.id');
    $posts->where("`bp`.`lang`='?'", $lang);
//    $posts->andwhere("`bp`.`visible`='Y'");
    if(isRequest('search_phase')){
        $req_phase = trim($_POST['search_phase']);  
        if ($req_phase != '') {
            $posts->andwhere("(bp.`title` LIKE '%?%' OR bp.`short` LIKE '%?%' OR bp.`full` LIKE '%?%')", $req_phase);
        }
    }
    if(isRequest('search_topic')){
        $req_topic = "'".implode("','", $_POST['search_topic'])."'";  
        if($req_topic != "''")
            $posts->andwhere("bp.`url` IN ($req_topic)");
    }
    if(isRequest('search_tag')){
        foreach($_POST['search_tag'] as $line){
            if($line != '')
                $posts->andwhere("bp.`tags` like '%{$line}%'");        
        }
    }
    if(isRequest('search_country')){
        foreach($_POST['search_country'] as $line){
            if($line != '')
                $posts->andwhere("bp.`country` like '%{$line}%'");        
        }
    }
    if(isRequest('topic')){
        $req_topic = trim(getRequest('topic', 3));
        if($req_topic != '')
            $posts->andwhere("bp.`url` like '?'", $req_topic);
    }
    if(isRequest('blog')){
        $req_url = trim(getRequest('blog', 3));
        if($req_url != '')
            $posts->andwhere("bc.`url` like '?'", $req_url);
    }
    if(isRequest('tag')){
        $req_tags = trim(getRequest('tag', 3));
        if($req_tags != ''){
            $posts->andwhere("bp.`tags` like '%?%'", urldecode($req_tags));
        }
    }
    if($id_user){
        $posts->andwhere("`id_user` = ?", $id_user);
    } 
    $posts->groupby('bp.id');
    $posts->orderby('beginning', 1); 
    
    $likeactive = $comactive = $postactive = '';
    if(isRequest('tab') && (getRequest('tab', 3) != 'bypost')){
        switch(getRequest('tab', 3)){
            case 'bylike': 
                $posts->orderby('favorite', 1);
                $likeactive = 1;
                break;
            case 'bycomment': 
                $posts->orderby('comment', 1);
                $comactive = 1;
                break;
            default: 
                $posts->orderby('beginning', 1);
                $postactive = 1;
                break;
        }        
    } else {
        $posts->orderby('beginning', 1);
        $postactive = 1;
        //$_SESSION['mblog_posts_new']['tabs'] = 'bypost';
    }    
    
    $SE_NAVIGATOR = $posts->pageNavigator($section->parametrs->param4); 
    $postlist = $posts->getList();
    unset($posts);
    
//  обрабатываем запрос
    $noPost = array('numDate'=>0, 'date'=>'');
    if(!empty($postlist)){
        $common_views = $common_comments = $common_favorite = 0;
        $arraylist = $volume = $sort = array();
        //  подрубаем смайлики
        $smile = new plugin_emotions();
        foreach($postlist as $k=>$post){
            //  отображать ли пост
            $post['modarationLabel'] = 0;
            $post['modarationButton'] = 0;
            $post['switch'] = 'off';
            if ($post['visible'] == 'N') {                                      //  статус поста невидимый
                if (!(($bloggerAccess[0] == 'admin') || ($bloggerAccess[0] == 'moderator'))) {  //  если не админ, не модератор     
                    if (($post['id_user'] != $user_id) || ($post['id_user'] == 0)) {    //  не свой пост
                        continue;
                    } else {
                        $post['modarationLabel'] = ($section->parametrs->param20 == 'Y') ? 1 : 0;
                    }
                } else {
                    $post['modarationLabel'] = 1;
                    $post['modarationButton'] = 1;
                }
                $post['switch'] = 'on';
            }
            //  формирование даты
            $post += blogPreloadDate($post['beginning'], 'full');
            //  заголовок
            $post['title'] = stripslashes(htmlspecialchars($post['title']));
            $post['title'] = $smile->code2emotion($post['title']);
            //  картинка из списка картинок, если поле пустое, то брать картинку из picture
            if ($post['listimages'] != '')  {
                $post['listimages'] = json_decode($post['listimages'],1);
                if (!empty($post['listimages'])) {
                    $tempArr = array('.jpg','.gif','.png','.jpeg','.JPG', '.GIF', '.PNG', '.JPEG');
                    foreach($post['listimages'] as $key=>$line) {
                        $line = trim(strip_tags($line));
                        $ext = substr($line,-4);
                        if (in_array($ext,$tempArr)) {
                            $post['listimages'] = $line;
                            break;
                        } else {
                            continue;
                        }
                                    
                    }
                }

                $post['picture_src'] = (!empty($post['listimages'])) ? se_getDImage($post['listimages'], intval($section->parametrs->param7)) : '';
            }
            if (($post['picture'] != '') && ($post['picture_src'] == '')){
                $src = '/images/blog/'.$post['picture'];
                $post['picture_src'] = (file_exists(getcwd().$src)) ? se_getDImage($src, intval($section->parametrs->param7)) : '';
            }
            //  имя фамилия
            if($post['fio'] == 0){
                $post['fio'] = $section->language->lang059;
            }
            //  краткий текст
            $post['short'] = stripslashes(htmlspecialchars_decode($post['short']));
            $post['short'] = $smile->code2emotion($post['short']);
            if($count_char > 0)
                $post['short'] = substr($post['short'], 0, $count_char);
            //  теги
            foreach(explode(',', $post['tags']) as $tag){
                $__data->setItemList($section, 'tagis'.$post['id'], array('tags' => stripslashes(htmlspecialchars(trim($tag))), 'tagurl' => urlencode(trim($tag))));
            }
            //  категории
            foreach (explode(',', $post['categors']) as $cat) {
                $arr = array(); 
                list($arr['edincat'], $arr['edurl']) = explode('|', $cat);
                $arr['edincat'] = trim($arr['edincat']);
                $arr['edurl'] = trim($arr['edurl']);
                $__data->setItemList($section, 'cat'.$post['id'], $arr);
            }
            // определяем можно ли редактировать
            $post['access'] = 0;
            if ($bloggerAccess[2] == 3) {
                $post['access'] = 1;
            } else if (($bloggerAccess[2] == 2) && ($post['id_user'] != 0)) {
                $post['access'] = 1;
            } else if (($bloggerAccess[2] == 1) && ($post['id_user'] == $user_id)) {
                $post['access'] = 1;
            }
            $post['url'] = urlencode(trim($post['url']));
            
            $__data->setItemList($section, 'blogs', $post);
            
            //  нужен для поиска последнего созданного поста
            $dateToStr = strtotime($post['last']);
            if (($post['id_user'] == $user_id) && ($dateToStr > $noPost['numDate'])) {
                $noPost = array('numDate'=>$dateToStr, 'date'=>$post['last']);
            } 
        } 
    }

//  может ли создавать посты (ограничение по времени)
    if (($user_group > 1) || $flagmoders) {
        $noPost['flag'] = 0;
    } else if (($noPost['date'] != '') && ($user_group < 2) && !$flagmoders) {
        $getResult = noPosting($section->parametrs->param10, $noPost['date']);
        if ($getResult[0] == false){ 
            $noPost['flag'] = 1; 
            $endTime = $getResult[1]/60/60;
            $noPost['endTime'] = ceil($endTime);
        } else {
            $noPost['flag'] = 0; 
        }
    } else {
        $noPost['flag'] = 0;
    }    

?>