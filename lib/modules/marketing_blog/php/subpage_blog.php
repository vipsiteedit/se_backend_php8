<?php

//  создаем запрос в БД
    $select = "GROUP_CONCAT(CONCAT_WS('|', bc.name, bc.url)) as categors, bp.id, bp.id_user, bp.title, bp.listimages, bp.listimages_alt, bp.short, bp.views, bp.rating_correction, bp.url, bp.tags, bp.commenting, bp.beginning, bp.visible, bp.created_at as last,
        (SELECT COUNT(id) 
        FROM blog_comments 
        WHERE id_post = bp.id) AS comment, 
        (SELECT SUM(rating)
        FROM blog_post_rating 
        WHERE id_post = bp.id) AS rating,
        (SELECT COUNT(id)
        FROM blog_post_rating 
        WHERE (id_post = bp.id) AND (favorite = 1)) AS favorite,
        IF(bp.id_user=0,0,
            (SELECT CONCAT_WS(' ',`first_name`,`last_name`)
            FROM person
            WHERE id = bp.id_user) 
        ) AS fio";

    $posts = new seTable('blog_posts', 'bp');
    $posts->select("{$select}"); 
    $posts->innerjoin('blog_category_post bcp', 'bp.id=bcp.id_post');
    $posts->innerjoin('blogcategories bc', 'bcp.id_category=bc.id');
    $posts->where("`bp`.`lang`='?'", $lang);
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
    $posts->orderby('beginning', 1);
    $posts->groupby('bp.id');                                                   //  echo $posts->getsql();
    $SE_NAVIGATOR = $posts->pageNavigator($section->parametrs->param4); 
    $postlist = $posts->getList();
    unset($posts);


//  обрабатываем запрос
    $noPost = array('numDate'=>0, 'date'=>'');
    if (!empty($postlist)) {
        //  подрубаем смайлики
        $smile = new plugin_emotions();
        foreach($postlist as $post){
            //  отображать ли пост
            $post['modarationLabel'] = 0;
            $post['modarationButton'] = 0;
            $post['switch'] = 'off';
            if ($post['visible'] == 'N') {                                      //  статус поста невидимый
                if (!(($bloggerAccess[0] == 'admin') || ($bloggerAccess[0] == 'moderator'))) {  //  если не админ, не модератор     
                    if (($post['id_user'] != $user_id) || ($post['id_user'] == 0)) {                         //  не свой пост
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
            //  имя фамилия
            if($post['fio'] == '0'){
                $post['fio'] = $section->language->lang059;
            }
            //  краткий текст
            $post['short'] = stripslashes(htmlspecialchars_decode($post['short']));
            $post['short'] = $smile->code2emotion($post['short']);
            if(($count_char > 0) && (strlen($post['short']) > $count_char)) {
                $resImg = array();
                preg_match("/^(?>(?><[^>]*>\s*)*[^<]){0,".$count_char."}(?=\s)/umi", $post['short'], $res);
                if (preg_match("/(<img.*?>)/umi", $res[0]) == 0)
                    preg_match("/(<img.*?>)/umi", $post['short'], $resImg);
                $post['short'] = (count($resImg) > 0) ? $res[0].$resImg[0] : $res[0];
            }
            //  картинка
            $post['listimages'] = ($post['listimages']!='') ? json_decode($post['listimages'],1) : '';
            $tempKey = '';
            if (!empty($post['listimages'])) {
                $tempArr = array('.jpg','.gif','.png','.jpeg','.JPG', '.GIF', '.PNG', '.JPEG');
                foreach($post['listimages'] as $key=>$line) {
                    $line = trim(strip_tags($line));
                    $ext = substr($line,-4);
                    if (in_array($ext,$tempArr)) {
                        $post['listimages'] = se_getDImage($line,$section->parametrs->param7);
                        $post['listimages_type'] = 'foto';
                        $tempKey = $key;
                        break;
                    } else {
                        $line = preg_replace("/.*?(youtube\.com|youtu.be)\/?(watch\?v=|v\/)?(.*)/ui","$3",$line);
                        $post['listimages'] = $line;
                        $post['listimages_type'] = 'video';
                        break;
                    }                                
                }
            }    
            //  ALT к картинке
            $post['listimages_alt'] = ($post['listimages_alt']!='') ? json_decode($post['listimages_alt'],1) : '';
            $post['listimages_alt'] = (!empty($post['listimages_alt']) && ($tempKey != '')) ? $post['listimages_alt'][$tempKey] : '';
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
            if($post['rating'] == '')
                $post['rating'] = 0;   
            $post['rating'] += $post['rating_correction'];
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
    } else if ($noPost['date'] != '') {
        $getResult = noPosting($section->parametrs->param10, $noPost['date']);
        if ($getResult[0] == false) { 
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