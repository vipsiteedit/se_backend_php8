<?php

//  доступ к странице создания/редактирования поста
    if($user_group == 0){
        header ("Location: ".$redirect);
        exit();
    }
    $_SESSION['editor_images_access'] = 1;
        
//  доступ на редактирование поста 
    $id = getRequest('id', 1);
    if($id){
        $sql = "(SELECT GROUP_CONCAT(CAST(`id_category` AS CHAR)) 
                        FROM blog_category_post 
                        WHERE id_post=bp.id) AS categors";
        $posts = new seTable('blog_posts', 'bp');
        $posts->select("*, {$sql}");
        $posts->where("`id`='?'", $id); 
        $post = $posts->fetchOne();     
        unset($posts);
            if ($bloggerAccess[2] == 3) {
                $post['access'] = 1;
            } else if (($bloggerAccess[2] == 2) && ($post['id_user'] != 0)) {
                $post['access'] = 1;
            } else if (($bloggerAccess[2] == 1) && ($post['id_user'] == $user_id)) {
                $post['access'] = 1;
            } else {
                header("Location: ".$redirect);
                exit;
            }
    }
//  проверка можно ли создавать пост (ограничение по времени)
    if (!$id && ($user_group < 2) && !$flagmoders) {        //  если создаем новый пост и пришел обычный пол-ль и пол-ль не модератор
        $timer = new seTable('blog_posts', 'bp');
        $timer->select('MAX(bp.created_at) AS last, NOW() AS now');
        $timer->innerjoin('person p', 'bp.id_user=p.id');
        $timer->where("bp.`id_user`=?", $user_id);
        $time_datas = $timer->fetchOne();
        unset($timer);
        $noPost = noPosting($section->parametrs->param10, $time_datas['last']);
        if ($noPost[0] == false) {
            header ("Location: ".$redirect);
            exit();
        }
    }
    
//  сохранить пост
    $errortext = '';               
    if(isRequest('GoTonewblog')){
        $title = trim(getRequest('title', 5));
        $selidcat = getRequest('selidcat', 5);
        $date = getRequest('dat', 3);
        $ehour = trim(getRequest('event_hour', 1));
        $eminute = trim(getRequest('event_minute', 1));
        $anons = trim(getRequest('anons', 5));
        $full = '';
        $pictures = getRequest('uploadFiles', 5);
        $pictures_alt = getRequest('uploadFilesAlt', 5);
        $price = trim(getRequest('price', 3));
        $hit = trim(getRequest('hit', 3));
        $country = trim(getRequest('country', 3));
        $keywords = trim(getRequest('keywords', 5));
        $description = trim(getRequest('description', 5));
        $tegi = trim(getRequest('tegi', 5));
        if($section->parametrs->param22=='Y') {
            $model = trim(getRequest('model', 3));
            $vidergka = trim(getRequest('vidergka', 3));
            $diafragma = trim(getRequest('diafragma', 3));
            $iso = trim(getRequest('iso', 3));
            $obectiv = trim(getRequest('obectiv', 3));
            $vspishka = trim(getRequest('vspishka', 3));
            $foto_param = array(
                        'model'     => $model,
                        'vidergka'  => $vidergka,
                        'diafragma' => $diafragma,
                        'iso'       => $iso,
                        'obectiv'   => $obectiv,
                        'vspishka'  => $vspishka
            );
        }
        $razcom = getRequest('razcom', 3);
        $show_anons = getRequest('show_anons', 3);
        $show_post = getRequest('show_post', 3);
   
        if(empty($title)){
            $errortext .= "\r\n"."Заголовок пустой";
        }
        if((count($selidcat) == 0) || ($selidcat[0] == '')){
            $errortext .= "\r\n"."Выберите категорию";
        }
        if((strtotime($date) < 1262293200) || (strtotime($date) > 2145902400)){
            $errortext .= "\r\n"."Выберите другую дату";        
        } else {
            $d = explode('-', date('Y-m-d', strtotime($date)));
        }
        if(($ehour < 0) || ($ehour > 24) || ($eminute < 0) || ($eminute > 59)){
            $errortext .= "\r\n"."Неверный формат времени";
        }
        if ($section->parametrs->param13 == 'Y') {
            $anonsCheck = strip_tags($anons);
        } else {
            $anons = strip_tags($anons);
            $anonsCheck = $anons;
        }
        if ((strlen($anonsCheck) < $min_len_text) && ($min_len_text > 0)) {
            $errortext .= "\r\n"."Описание слишком маленькое";
        }
        if ((strlen($anonsCheck) > $max_len_text) && ($max_len_text > 0)) {
            $errortext .= "\r\n"."Описание слишком большое";
        }
        if ((count($pictures) < $min_count_foto) && ($min_count_foto != 0)){
            $errortext .= "\r\n"."Слишком мало картинок";
        }
        if ((count($pictures) > $max_count_foto) && ($max_count_foto != 0)){
            $errortext .= "\r\n"."Слишком много картинок";
        }
        if($section->parametrs->param6 == 'tabs') {
            $tabName = $_POST['tabName'];
            $tabText = $_POST['tabText'];        
            $coun = count($tabName);
            $templateMass = array();
            foreach($tabName as $k=>$v){
                $full .= '<tab title="'.$tabName[$k].'">'.$tabText[$k].'</tab>';
            }  
        }
        $show_anons = ($show_anons == '') ? 'N' : $show_anons;
        /*  //  убрано так как пол-ль хочет, чтобы премодерация была для всех. Костыль стоит на следующей строке
        if ($section->parametrs->param20 == 'Y') {
            $show_post = (($bloggerAccess[0] == 'admin') || ($bloggerAccess[0] == 'moderator')) ? $show_post : 'N';
        }
        */
        $show_post = 'N';
        $hit = ($hit) ? '1' : '0';
        if ($errortext == '') {
            //  добавляем пост              
            $posts = new seTable('blog_posts');
            $posts->select('*');
            $addonT = '';                                                       //  избежать дублирование url поста
            $url_title = getTranslitName($title);
            $posts->where("`url` LIKE '?'", $url_title);
            $double_post = $posts->getList();
            $getCount = count($double_post);
            if ($getCount > 0) {                                                //  если мы нашли такой же url
                $postID = new seTable('blog_posts');
                $postID->select('MAX(id) AS id');
                $postID->fetchOne();
                $addonT = ($id) ? $id : intval($postID->id) + 1; 
                unset($postID);
            } 
            
            //  очищаем заголовок от муссора
            $emotion = new plugin_emotions();
            $emotionList = $emotion->getEmotions();
            $search = $replace = $match = array();
            foreach($emotionList as $line) {
                $search[] = $line['abbr'];
                $replace[] = chr(26).$line['abbr'].chr(27);
            }
            $title = str_replace($search, $replace, $title);
            preg_match_all('/([а-яА-Я\s\w])+|('.chr(26).'.*?'.chr(27).')+/iu', $title, $match);
            $title = '';
            foreach($match[0] as $line) {
                if((strpos($line, chr(26)) !== false) && (strpos($line, chr(27)) !== false))
                    $line = str_replace(array(chr(26), chr(27)), '', $line);
                $title .= $line;
            }             
            
            if($id) {
                $posts->find($id);
            } else {                                                            //  существует ли такой url в БД
                $posts->insert(); 
                $posts->id_user = $user_id;
            }
                $posts->lang = $lang;
                $posts->url = $addonT.$url_title; 
                $posts->title = addslashes(trim($title));
                $posts->listimages = json_encode($pictures);
                $posts->listimages_alt = json_encode($pictures_alt);
                $posts->short = addslashes($anons); 
                $posts->full = addslashes($full);
                $posts->foto_param = json_encode($foto_param);
                $posts->tags = addslashes($tegi);
                $posts->commenting = $razcom;
                $posts->show_anons = $show_anons;
                $posts->visible = $show_post;
                $posts->keywords = addslashes($keywords);
                $posts->description = addslashes($description);
                $posts->beginning = mktime($ehour, $eminute, 0, $d[1], $d[2], $d[0]);
                $posts->price = strip_tags($price);
                $posts->hit = strip_tags($hit);
                $posts->country = trim(strip_tags($country));
            $id_post = $posts->save();
            
            //  связавыем категории с постом
            $svaz = new seTable('blog_category_post');
            if ($id) {
                $svaz->where("`id_post` = '?'", $id);
                $svaz->deletelist();         
                $id_post = $id;                
            }
            foreach($selidcat as $item){
                $svaz->insert();
                    $svaz->id_category = $item;
                    $svaz->id_post = $id_post;
                $svaz->save();
            }
           
            $admin_mail = trim($section->parametrs->param21);
            if($admin_mail != '') {
                $mail_text = strval($section->language->lang080);
                $mail_text = str_replace("[%name%]", $title, $mail_text);
                $mail_text = str_replace("[%text%]", $anons, $mail_text);
                $mail_text = str_replace("[%site%]", "<a href='http://".$_SERVER["HTTP_HOST"]."/".$__data->getPageName()."/'>".$_SERVER["HTTP_HOST"]."</a>", $mail_text);
                if(count($pictures) > 0) {
                    foreach($pictures as $key=>$line) {
                        $mail_text .= "<br /><img src='http://".$_SERVER["HTTP_HOST"].$line."'>".$pictures_alt[$key];
                    }
                }
                $mail_text = str_replace('\r\n', "<br /><br />", $mail_text);
                $mail_title = strval($section->language->lang079)." '".$title."'";
                $from = "=?utf-8?b?" . base64_encode($title) . "?= <noreply@" . $_SERVER['HTTP_HOST'] . '>';
                $mailsend = new plugin_mail($mail_title, $admin_mail, $from, $mail_text, 'text/html');
                $mailsend->sendfile();
            }
            header("Location: ".$redirect);
            exit();
        }
        $errortext = nl2br(trim($errortext));
        
        $isFotoList = count($pictures);                                         //  ошибка при создании записи, выводим загруженные картинки
        $tempPict = array();
        if ($isFotoList > 0) {
            $tempArr = ($section->parametrs->param19 == 'J') ? array('.jpg', '.jpeg', '.JPG', '.JPEG') : array('.jpg','.gif','.png','.jpeg','.JPG', '.GIF', '.PNG', '.JPEG');
//            $tempArr = array('.jpg','.gif','.png','.jpeg','.JPG', '.GIF', '.PNG', '.JPEG');
            foreach($pictures as $k=>$line){
                $line = trim(strip_tags($line));
                $ext = substr($line,-4);
                $whois = (in_array($ext,$tempArr) && (strpos($line, 'http')===false)) ? 'foto' : 'video';
                $tempPict[] = array('num'=>$k, 'alt'=>$pictures_alt[$k], 'foto_video'=>$whois, 'text'=>$line);
            }
        }
        $__data->setList($section, 'fotos', $tempPict);
        unset($pictures,$pictures_alt);
        $nontabs = $full;
        if ($section->parametrs->param6 == 'tabs') {
            preg_match_all('/<tab[^>]+title="([^>\"]+)">(.*?)<\/tab>/uis', $full, $out, PREG_PATTERN_ORDER);
            $tabname = $tabtext = array();
            if(!empty($out[0])){
                foreach($out[1] as $k=>$v){
                    $add = '';
                    if($k == 0)
                        $add = ' class="active"';
                    $tabname[] = array('id'=>$k+2, 'title'=>$v, 'active'=>$add);
                }
                foreach($out[2] as $k=>$v){
                    $add = '';
                    if($k == 0)
                        $add = ' in active';
                    $tabtext[] = array('id'=>$k+2, 'text'=>$v, 'active'=>$add);
                }                
            } else {
                $tabname[] = array('id'=>2, 'title'=>'Новая вкладка', 'active'=>' class="active"'); 
                $tabtext[] = array('id'=>2, 'text'=>$nontabs, 'active'=>' in active'); 
            }
            $__data->setList($section, 'tabname', $tabname);
            $__data->setList($section, 'tabtext', $tabtext);        
        }
// удаление поста и его комментарии
    } else if (isRequest('delpost')) { 
        $svaz = new seTable('blog_category_post');
        $svaz->where("`id_post` = '?'", $id);
        $svaz->deletelist();
        $posts = new seTable('blog_posts');
        $posts->where("`id` = '?'", $id);
        $imgList = $posts->fetchOne();
        $posts->delete($id);
        $imgList = json_decode($imgList['listimages']);
        foreach($imgList as $line){
            @unlink(getcwd().$line);
        }
        header("Location: ".$redirect);
        exit();
//  редактирование поста
    } else if($id){
        $title = stripslashes(htmlspecialchars($post['title']));
        list($ehour, $eminute) = explode('.', date("H.i", $post['beginning']));
        $date = date('Y-m-d', $post['beginning']);
        $anons = stripslashes($post['short']); 
        if($section->parametrs->param13 == 'a') {
            $smile = new plugin_emotions();
            $anons = $smile->code2emotion($anons);
        }
        $nontabs = $full = stripslashes($post['full']);
        if ($section->parametrs->param6 == 'tabs') {
            preg_match_all('/<tab[^>]+title="([^>\"]+)">(.*?)<\/tab>/uis', $full, $out, PREG_PATTERN_ORDER);
            $tabname = $tabtext = array();
            if(!empty($out[0])){
                foreach($out[1] as $k=>$v){
                    $add = '';
                    if($k == 0)
                        $add = ' class="active"';
                    $tabname[] = array('id'=>$k+2, 'title'=>$v, 'active'=>$add);
                }
                foreach($out[2] as $k=>$v){
                    $add = '';
                    if($k == 0)
                        $add = ' in active';
                if($section->parametrs->param13 == 'a') {
                    $v = $smile->code2emotion($v);
                }
                    $tabtext[] = array('id'=>$k+2, 'text'=>$v, 'active'=>$add);
                }                
            } else {
                $tabname[] = array('id'=>2, 'title'=>'Новая вкладка', 'active'=>' class="active"'); 
                if($section->parametrs->param13 == 'a') {
                    $nontabs = $smile->code2emotion($nontabs);
                }
                $tabtext[] = array('id'=>2, 'text'=>$nontabs, 'active'=>' in active'); 
            }
            $__data->setList($section, 'tabname', $tabname);
            $__data->setList($section, 'tabtext', $tabtext);
        
        } else if($section->parametrs->param6 == 'text') {
                if($section->parametrs->param13 == 'a') {
                    $smile = new plugin_emotions();
                    $full = $smile->code2emotion($full);
                }        
        }
        
        //  выводим картинки
        $post['listimages'] = json_decode($post['listimages']);
        $post['listimages_alt'] = json_decode($post['listimages_alt']);
        $isFotoList = count($post['listimages']);  
        $tempPict = array();
        if ($isFotoList > 0) {
            $tempArr = ($section->parametrs->param19 == 'J') ? array('.jpg', '.jpeg', '.JPG', '.JPEG') : array('.jpg','.gif','.png','.jpeg','.JPG', '.GIF', '.PNG', '.JPEG');
            foreach($post['listimages'] as $k=>$line){
                $line = trim(strip_tags($line));
                $ext = substr($line,-4);
                $whois = (in_array($ext,$tempArr) && (strpos($line, 'http')===false)) ? 'foto' : 'video';
                $tempPict[] = array('num'=>$k, 'alt'=>$post['listimages_alt'][$k], 'foto_video'=>$whois, 'text'=>$line);
            }
        }
        $__data->setList($section, 'fotos', $tempPict);
        
        $keywords = stripslashes(htmlspecialchars($post['keywords']));
        $description = stripslashes(htmlspecialchars($post['description']));
        $tegi = stripslashes(htmlspecialchars($post['tags']));
        $foto_param = json_decode($post['foto_param'], 1);
        if(!empty($foto_param)) {
            $model = $foto_param['model'];
            $vidergka = $foto_param['vidergka'];
            $diafragma = $foto_param['diafragma'];
            $iso = $foto_param['iso'];
            $obectiv = $foto_param['obectiv'];
            $vspishka = $foto_param['vspishka'];            
        }
        $razcom = $post['commenting'];
        $show_anons = $post['show_anons'];
        $show_post = $post['visible'];
        $price = $post['price']; 
        $hit = ($post['hit']) ? 'checked' : '';
        $country = $post['country'];
//  отобразить форму
    } else {
        $title = $anons = $full = $keywords = $description = $tegi = $razcom = $show_post = $pname = $country = '';
        if($section->parametrs->param22=='Y')
            $model = $vidergka = $diafragma = $iso = $obectiv = $vspishka = ''; 
        list($ehour, $eminute) = explode('.', date("H.i"));
        $date = date('Y-m-d');
        $isFotoList = 0;
        $changeVisiblePost = 'N';
        /*  //  никогда не отображать строку. Кость строкой выше. Так захотел пол-ль
        if ($section->parametrs->param20 == 'Y') {
            $changeVisiblePost = (($bloggerAccess[0] == 'admin') || ($bloggerAccess[0] == 'moderator')) ? 'Y' : 'N';
        } else {
            $changeVisiblePost = 'Y';
        }
        */
    }

// категории
    $categories = new seTable('blogcategories');
    $categories->select('id, lang, upid, name');
    $categories->where("`lang` = '?'", $lang);   
    $categories->orderby('upid');
    $categories->addorderby('name');
    $categorieslist = $categories->getList();   

/*
    if(!empty($categorieslist) && ($section->parametrs->param12 == 'list')){
        $tmp_arr = $volume = array();
        foreach($categorieslist as $k=>$v){
            if($id){
                $categ = explode(",", $post['categors']);
                $categorieslist[$k]['checked'] = '';
                if(in_array($v['id'], $categ))
                    $categorieslist[$k]['checked'] = 'checked';    
            }
            $tmp_arr[$v['id']] = $k;
            if(empty($v['upid'])){          
                $categorieslist[$k]['level'] = 0;     
            } else {
                $cat_id = $tmp_arr[$v['upid']];
                $categorieslist[$k]['level'] = $categorieslist[$cat_id]['level']+1;
            }
            $volume[$k]  = $v['name'];       //  строка для сортировки по ключу 'name'
        }
        unset($tmp_arr, $cat_id); 
        array_multisort($volume, SORT_STRING, $categorieslist);
    }
    
    if(!empty($categorieslist) && ($section->parametrs->param12 == 'popup')){
        if($id){
            $categ = explode(",", $post['categors']);
            foreach($categorieslist as $k=>$v) {
                $categorieslist[$k]['checked'] = (in_array($v['id'], $categ)) ? 'selected' : ''; 
            }
        }
    }
*/
    $tmp = $tmp_arr = array();
 foreach($categorieslist as $v){
        $upid = ($v['upid']=='') ? 'null' : $v['upid'];
        $v['checked'] = $v['selected'] = '';
        if($id){
            $categ = explode(",", $post['categors']);
            if(in_array($v['id'], $categ)) {
                $v['checked'] = 'checked';
                $v['selected'] = 'selected';
            }    
        }
        if(empty($v['upid'])){
            $v['level'] = 0;
            $tmp_arr[$v['id']] = $v['level']+1;     
        } else if(isset($tmp_arr[$v['upid']])){
            $tmp_arr[$v['id']] = $tmp_arr[$v['upid']] + 1;
            $cat_id = $tmp_arr[$v['upid']];
            $v['level'] = $cat_id;
        } 
        $tmp[$upid][] = $v;
 }
    unset($tmp_arr);
 $categorieslist = array();
    foreach($tmp['null'] as $line){
        $categorieslist[] = $line;
        recurse($tmp, $categorieslist, $line['id']);
    }
 function recurse(&$tmp, &$categorieslist, $id){
        if(count($tmp[$id])>0){
            foreach($tmp[$id] as $line){
                $categorieslist[] = $line;
                recurse($tmp,$categorieslist,$line['id'], $level);
            }
        }
    }    
    $__data->setList($section, 'categories', $categorieslist);

//  нужно для загрузки файлов
    $timestamp = time();
    $token =  md5('unique_salt' . $timestamp);
    $refer = (($user_group == 3) && ($user_id == 0)) ? -1 : $user_id;

//  alert при премодерации 
    $showAlert = (($section->parametrs->param20 == 'Y') && !$id) ? 'Y' : 'N';

//  смайлики
    if($section->parametrs->param13=='N') {
        $smile = new seTable("emotions");
        $smile->select("id, img, abbr");
        $smileList = $smile->getList();
    } else {
        $smileList = array();
    }
    
    $__data->setList($section, 'smiles', $smileList);

?>