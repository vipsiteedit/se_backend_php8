<?php

//  запрос в БД
    $posts = new seTable('blog_posts', 'bp');
    $posts->select('bp.id, bp.id_user, bp.title, bp.url, bp.picture, bp.listimages, bp.short, bp.visible, bp.price, bp.hit, bp.created_at as last');
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
    $posts->groupby('bp.id');                                                   // echo $posts->getsql();
    $navigator = $posts->pageNavigator($section->parametrs->param4);
    $postlist = $posts->getlist();
    unset($post); 
    
//  обрабатываем запрос
    $noPost = array('numDate'=>0, 'date'=>'');
    if(!empty($postlist)){
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
            //  заголовок  и картинка
            $post['title'] = stripslashes(htmlspecialchars($post['title']));
            $post['title'] = $smile->code2emotion($post['title']);
            //  картинка из списка картинок, если поле пустое, то брать картинку из picture
            if ($post['listimages'] != '')  {
                $post['listimages'] = json_decode($post['listimages'],1);
                if (!empty($post['listimages'])) {
                    $tempArr = array('.jpg','.gif','.png','.jpeg','.JPG', '.GIF', '.PNG', '.JPEG');
                    foreach($post['listimages'] as $line) {
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
                $post['img'] = (!empty($post['listimages'])) ? se_getDImage($post['listimages'], intval($section->parametrs->param7)) : '';
            }
            if (($post['picture'] != '') && ($post['img'] == '')){
                $src = '/images/blog/'.$post['picture'];
                $post['img'] = (file_exists(getcwd().$src)) ? se_getDImage($src, intval($section->parametrs->param7)) : '/'.$__MDL_URL.'/blogfotoprimer.jpg';
            } 
            if ($post['img'] == '') {
                $post['img'] = '/'.$__MDL_URL.'/blogfotoprimer.jpg';
            }
            //  краткий текст
            $post['short'] = stripslashes(htmlspecialchars_decode($post['short']));
            $post['short'] = $smile->code2emotion($post['short']);
            if($count_char > 0)
                $post['short'] = substr($post['short'], 0, $count_char);
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
    } else if ($noPost['date'] != '') {
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