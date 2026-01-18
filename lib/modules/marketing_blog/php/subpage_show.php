<?php

//  создаем запрос в БД
    $select = "bp.id, bp.id_user, bp.title, bp.short, bp.full, bp.foto_param, bp.views, bp.listimages, bp.listimages_alt, bp.rating_correction, bp.tags, bp.show_anons, bp.commenting, bp.description, bp.keywords, bp.beginning, bp.id_user, 
        GROUP_CONCAT(CONCAT_WS('|', bc.name, bc.url)) as categors, (
        SELECT COUNT(id) 
        FROM blog_comments 
        WHERE id_post = bp.id) AS comment, (
        SELECT SUM(rating)
        FROM blog_post_rating 
        WHERE id_post = bp.id) AS rating, (
        SELECT COUNT(id)
        FROM blog_post_rating 
        WHERE (id_post = bp.id) AND (favorite = 1)) AS favorite,
        IF(bp.id_user=0,0,
            (SELECT CONCAT_WS('|',`first_name`,IF(`last_name`='','-',IFNULL(`last_name`,'-')),IF(`avatar`='','-',IFNULL(`avatar`,'-')))
            FROM person
            WHERE id = bp.id_user) 
        ) AS fio";
    $posts = new seTable('blog_posts', 'bp');
    $posts->select("{$select}"); 
    $posts->innerjoin('blog_category_post bcp', 'bp.id=bcp.id_post');
    $posts->innerjoin('blogcategories bc', 'bcp.id_category=bc.id');
    $posts->where("`bp`.`lang`='?'", $lang);
    $posts->andwhere("`bp`.`url` like '?'", $postname); 
    //$posts->andwhere("`bp`.`visible` like 'Y'");    
    //echo $posts->getsql();          
    $posts->fetchOne();

//  отображать посты, которые на модерации
    $visible = 0;
    if ($bloggerAccess[2] == 3) {
        $visible = 1;
    } else if (($bloggerAccess[2] == 2) && ($posts->id_user != 0)) {
        $visible = 1;
    } else if (($bloggerAccess[2] == 1) && ($posts->id_user == $user_id)) {
        $visible = 1;
    }

//  если такого поста нет
    if((!$posts->id) || (($visible == 0) && ($posts->visible == 'N'))) {
        header("Location: ".$redirect);
        exit();
    }
                        
//  распарсим запрос
    if($posts->fio == '0'){                 
        $fio = 'Администратор';
        $avatar = '/'.$__MDL_URL.'/no_foto.png';
    } else {
        $ifa = explode("|", $posts->fio);                                       //  имя|фамилия|аватарка    
        $fio = $ifa[0];              
        if($ifa[1] != '-')
            $fio .= ' '.$ifa[1]; 
        $avatar = $ifa[2];
        if($ifa[2] == '-')
            $avatar = '/'.$__MDL_URL.'/no_foto.png';
        else 
            $avatar = '/public/avatars/'.$ifa[2];
    }
    $id = $posts->id;
    $d = blogPreloadDate($posts->beginning);
    $date = $d['day'].".".$d['month'].".".$d['year'];
    $metaDate = date('Y-m-d', $posts->beginning);
    $smile = new plugin_emotions();
    $title = stripslashes(htmlspecialchars($posts->title));
    $title = $smile->code2emotion($title); 
    if($section->parametrs->param6 == 'text'){
        $full = '';
        if(($posts->show_anons == 'Y') || (($posts->show_anons == 'N') && ($posts->full == '')))        //  совместимость со старой версией
            $full .= stripslashes(htmlspecialchars_decode($posts->short));
        $full .= stripslashes(htmlspecialchars_decode($posts->full));
        $full = str_replace('"', "'", $full);
        $full = preg_replace("/(<img[\W\w].*?src=(\"[^\"]*\"|'[^']*'|[^\r\s\t\n>]*)[^>]*>)(?!<\/a>)/iu", "<a class='fullgallery' href=$2 rel='group'>$1</a>", $full);
        $full = $smile->code2emotion($full);                 
    } else if ($section->parametrs->param6 == 'tabs'){
        $short = '';
        if($posts->show_anons == 'Y') {
            $short .= stripslashes(htmlspecialchars_decode($posts->short));
            $short = str_replace('"', "'", $short);
            $short = preg_replace("/(<img[\W\w].*?src=(\"[^\"]*\"|'[^']*'|[^\r\s\t\n>]*)[^>]*>)(?!<\/a>)/iu", "<a class='fullgallery' href=$2 rel='group'>$1</a>", $short);
            $short = $smile->code2emotion($short);
        }         
        $full = stripslashes(htmlspecialchars_decode($posts->full));
        $full = str_replace('"', "'", $full);
        $nontabs = $full = preg_replace("/(<img[\W\w].*?src=(\"[^\"]*\"|'[^']*'|[^\r\s\t\n>]*)[^>]*>)(?!<\/a>)/iu", "<a class='fullgallery' href=$2 rel='group'>$1</a>", $full);         
        $full = $smile->code2emotion($full);
        preg_match_all('/<tab[^>]+title="([^>\"]+)">(.*?)<\/tab>/uis', $full, $out, PREG_PATTERN_ORDER);     
        $tabname = $tabtext = array();
        if(!empty($out[0])){
            foreach($out[1] as $k=>$v){
                $add = '';
                if($k == 0)
                    $add = ' class="active"';
                $tabname[] = array('id'=>$k, 'title'=>$v, 'active'=>$add);
        
            }
            foreach($out[2] as $k=>$v){
                $add = '';
                if($k == 0)
                    $add = ' in active';
                $tabtext[] = array('id'=>$k, 'text'=>$v, 'active'=>$add);
        
            }
        } else {
            $tabname[] = array('id'=>0, 'title'=>'Новая вкладка', 'active'=>' class="active"'); 
            $tabtext[] = array('id'=>0, 'text'=>$nontabs, 'active'=>' in active'); 
        }
        $__data->setList($section, 'tabname', $tabname);
        $__data->setList($section, 'tabtext', $tabtext);
    }
    //  выводим картинки
    $listimages = json_decode($posts->listimages,1);
    $listimages_alt = json_decode($posts->listimages_alt,1);
    $isFotoList = count($listimages);  
    $tempPict = $metaPict = array();
    if ($isFotoList > 0) {
        $tempArr = array('.jpg','.gif','.png','.jpeg','.JPG', '.GIF', '.PNG', '.JPEG');
        foreach($listimages as $k=>$line){
            $line = trim(strip_tags($line));
            $ext = substr($line,-4);
            $whois = (in_array($ext,$tempArr) && (strpos($line, 'http')===false)) ? 'foto' : 'video';
            if ($whois == 'video') {
                $line = preg_replace("/.*?(youtube\.com|youtu.be)\/?(watch\?v=|v\/)?(.*)/ui","$3",$line);
                if ($line == '') 
                    continue;
            } else {
                $metaPict[] = array('url'=>$line);
            }
            $tempPict[] = array('alt'=>$listimages_alt[$k], 'foto_video'=>$whois, 'url'=>$line);
        }
    }
    $__data->setList($section, 'fotos', $tempPict);
    
    if($posts->tags != ''){                            //  теги
        $tgs = array();                                                             
        $isTag = 1;
        foreach(explode(",", $posts->tags) as $tag){
            $tgs[] = trim($tag);
            $__data->setItemList($section, 'tagis', array('tags' => stripslashes(htmlspecialchars(trim($tag))), 'tagurl' => urlencode(trim($tag))));
        }
    } else {
        $isTag = '';    
    }
    foreach(explode(',', $posts->categors) as $cat){                            //  категории
        $arr = array(); 
        list($arr['edincat'], $arr['edurl']) = explode('|', $cat);
        $arr['edincat'] = trim($arr['edincat']);
        $arr['edurl'] = trim($arr['edurl']);
        $__data->setItemList($section, 'cats', $arr);
    }
    $rating = ($posts->rating) ? (int)$posts->rating : 0;
    $rating += $posts->rating_correction;
    $favorite = $posts->favorite;
    $id_subc_user = $posts->id_user;
    $views = $posts->views;
    $comment = $posts->comment;
    $commenting = $posts->commenting;
    $__data->page->titlepage = $title;
    if($posts->description) {
        $__data->page->description = htmlspecialchars($posts->description);   
    } else {                        
        $__data->page->description = (strlen($full) > 240) ? substr($full,0,239) : $full;
    }
    $__data->page->keywords = htmlspecialchars($posts->keywords);
                               
//  параметры фото
    $isParam = false;
    $foto_param = json_decode($posts->foto_param, 1);         
    if(!empty($foto_param)) {
        $isParam = true;
        $model = $foto_param['model'];
        $vidergka = $foto_param['vidergka'];
        $diafragma = $foto_param['diafragma'];
        $iso = $foto_param['iso'];
        $obectiv = $foto_param['obectiv'];
        $vspishka = $foto_param['vspishka'];            
    }


//  пункт смотрите также
    if($isTag != ''){
        $tgs = "'".implode("','", $tgs)."'";    
        $other = new seTable('blog_posts', 'bp');
        $other->select("title, url"); 
        $other->where("`bp`.`lang`='?'", $lang);
        $other->andwhere("`bp`.`tags` IN({$tgs}) AND `bp`.`url` NOT LIKE '{$postname}'");
        $other_post = $other->getList();                                        
        $other_count = count($other_post);
        if($other_count > 0){
            foreach($other_post as $item){
                $__data->setItemList($section, 'o_posts', array('name' => stripslashes(htmlspecialchars(trim($item['title']))), 'url' => urlencode(trim($item['url']))));
            }        
        } 
    }
    
//  оставить комментарий
    $err = '';
    if(isRequest('putComment')){
        $idlink = getRequest('idlink', 1);
        $name = getRequest('guest_name', 3);
        $message = getRequest('message', 5);
        $isCapcha = true;
        if ($section->parametrs->param9=='Y') {
                require_once getcwd()."/lib/card.php";
                if (!checkcard($_POST['pin'])) {
                    $isCapcha = false;
                }
        }
        if($message && (getRequest('strCheck',5) == '') && ($isCapcha == true)){
            $msgs = new seTable('blog_comments');
            $msgs->insert();
                if($idlink != 0)
                    $msgs->upid = $idlink; 
                $msgs->lang = $lang;
                $msgs->id_post = $id;
                $msgs->message = $message;
                $msgs->id_user = (trim($name)) ? 1000000 : $user_id;  
                if (trim($name))
                    $msgs->user_name = $name;
            $msgs->save();
        } else {
            $err = 'Забыли оставить комментарий';
            $err .= ($isCapcha==false) ? ' или неверно введена капча' : '';
        }        
        unset($msgs);
    }
    $idlink = 0;

//  обработка коментариев 
    if($commenting == 'Y'){
        $delComment = '';
        if(($flagmoders == 1) || ($posts->id_user == $user_id) || ($user_group == 3) || ($user_group == 2)){
            $delComment = 'Y';        
        }
        $post_comments = new seTable('blog_comments','bc');
        if(($delComment == 'Y') && isRequest('delcomments')){
            $del_id = getRequest('delcomments', 1);
            $post_comments->delete($del_id);            
        }
        $post_comments->select("bc.id, bc.upid, bc.id_post, bc.message, bc.id_user, bc.user_name, bc.rating_correction, bc.created_at as date, 
                                (SELECT CONCAT_WS(' ', p.`first_name`, p.`last_name`) FROM person p WHERE p.id=bc.id_user) as fio, 
                                (SELECT avatar FROM person p WHERE p.id=bc.id_user) as avatar, 
                                (SELECT SUM(rating) FROM blog_comment_rating WHERE id_comment=bc.id) as com_rating");
        //$post_comments->innerjoin('person p', 'p.id=bc.id_user');
        $post_comments->where("`id_post` = '?'", $id);
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
                $commentlist[$k]['message'] = $smile->code2emotion($commentlist[$k]['message']);
                $newList[$v['upid']][] = $commentlist[$k];
            }
            $commentlist = array(); 
            tree_print($newList, $commentlist);
            $__data->setList($section, 'comments', $commentlist);
        }
    }

//  кол-во просмотров
    $posts->update('views', $posts->views+1);
    $posts->where("`id`=?", $id);
    $posts->save();
    
//  meta data
    $__data->page->html = ' prefix="og: http://ogp.me/ns#"';
    $__data->page->head = '<meta property="og:title" content="'.$title.'" /><meta property="og:type" content="blog" /><meta property="og:url" content="'.$_SERVER[REQUEST_SCHEME].'://'.$_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI].'" /><meta property="og:image" content="'.$_SERVER[REQUEST_SCHEME].'://'.$_SERVER[SERVER_NAME].$metaPict[0]['url'].'" /><meta property="og:description" content="'.$__data->page->description.'" />';

?>