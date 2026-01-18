<?php

if ($uid) {
    $error_exists = 0;
    if (isRequest ('upload') || isRequest ('doGo')) {
        $nick = getRequest('nick',3);
        $realname = getRequest('realname',3);
        $location = getRequest('location',3);
        $jobtitle = getRequest('jobtitle',3);
        $interests = getRequest('interests',3);
        $email = getRequest('email',3);
        $icq = getRequest('icq',3);
        $url = getRequest('url',3);
        $origin = getRequest('origin',3);
        $_SESSION ['userDT'] = array (
                                    $nick, $realname, $location, $jobtitle, 
                                    $interests, $email, $icq, $url, $origin  
                                );
    } else if (!empty ($_SESSION ['userDT'])) {
        list ($nick, $realname, $location, $jobtitle, 
                $interests, $email, $icq, $url, $origin) = $_SESSION ['userDT'];
    }
    if (isRequest('upload')) {
        
        $ext_sid = getRequest('sid',3);
        $tbl = new seTable("forum_session","fs");
        $tbl->where("sid = '?'",$ext_sid);
        $s = $tbl->fetchOne();
        unset($tbl);

        if ($s['id_users'] == $uid) {
            $userfile = $_FILES['userfile']['tmp_name']; 
            $userfile_size = $_FILES['userfile']['size'];
            $origfilename = mb_strtolower ($_FILES['userfile']['name'], 'UTF-8');
            if (!empty($userfile)) {
                list($width, $height, $mimetype) = GetImageSize($userfile);
                if (!(preg_match ("/^[^\t]+\.(gif|jpg|jpeg|png)$/u", $origfilename) && ($mimetype==1 || $mimetype==2 || $mimetype==3))) {
                    $error = "{$section->language->lang110} GIF/JPG/JPEG/PNG!";
                    $error_exists = 1;
                } else {
//                    $extimg = utf8_substr($origfilename, 3);
                    $fnam = utf8_substr($origfilename,0, utf8_strlen($origfilename) - 4); 
                    $ext = utf8_substr($origfilename, -3);
                    $previmg = @$fnam.'_prev.'.@$ext;                   
                    if (($width <= 100) && ($height <= 100)) {
                        move_uploaded_file ($userfile, getcwd().'/modules/forum/images/'.$previmg);
                    } else {
                        if ($width > $height) {
                            $wdth = 100;
                            $hght = $height * 100 / $width;
                        } else {
                            $hght = 100;
                            $wdth = $width * 100 / $height;
                        }
                        switch ($ext) {
                        case "gif": 
                            $img = imagecreatefromgif($userfile);
                            break;
                        case "png": 
                            $img = imagecreatefrompng($userfile);
                            break;
                        default:    
                            $img = imagecreatefromjpeg($userfile);    
                            break;
                        }
                        @$d_im = imagecreatetruecolor($wdth, $hght);
                        @imagecopyresampled($d_im,$img,0,0,0,0,$wdth,$hght,$width,$height);
                        switch ($ext){
                        case "gif": 
                            @imagegif($d_im, getcwd().'/modules/forum/images/'.$previmg);
                            break;
                        case "png": 
                            @imagepng($d_im, getcwd().'/modules/forum/images/'.$previmg);
                            break;
                        default:    
                            @imagejpeg($d_im, getcwd().'/modules/forum/images/'.$previmg, 75);
                            break;
                        }
                        @imagedestroy($d_im);
                        @imagedestroy($img);
                    }
                    $filename = $uid . '_' . time () . '.' . @$ext;
                    $uploadfile = getcwd () . "/modules/forum/images/" . $filename;
                    if (se_rename(getcwd () . "/modules/forum/images/" . $previmg, 
                                    $uploadfile)) {
                        $tbl = new seTable("forum_users","fu");
                        $tbl->find($uid);
                        if (file_exists (getcwd () . "/modules/forum/images/" . $tbl->img)) {
                            @unlink (getcwd () . "/modules/forum/images/" . $tbl->img);
                        }
                        $tbl->img = $filename;
                        $tbl->save();
                        unset($tbl);
                    }
                    Header("Location: ".seMultiDir()."/$_page/$razdel/sub5/?".time());
                    exit();    
                }
            }
        }
    }
    if (isRequest('doGo')) {
        $nick = AddSlashes($nick);
        $realname = AddSlashes($realname);
        $location = AddSlashes($location);
        $jobtitle = AddSlashes($jobtitle);
        $interests = AddSlashes($interests);
        $email = AddSlashes($email);
        $icq = AddSlashes($icq);
        $url = AddSlashes($url);
        $origin = AddSlashes($origin);
        $tbl = new seTable("forum_users","fu");
        $tbl->where("nick = '?'",$nick);
        $tbl->andWhere("id <> $uid");
        $ru = count($tbl->getList());
        unset($tbl);
        if ($ru) {
            $error = "{$section->language->lang111} {$nick} {$section->language->lang112}<br>{$section->language->lang113}";
            $error_exists = 1;
        } else {
            $tbl = new seTable("forum_users","fu");
            $tbl->find($uid);
            $tbl->nick = $nick;
            $tbl->realname = $realname;
            $tbl->location = $location;
            $tbl->jobtitle = $jobtitle;
            $tbl->interests = $interests;
            $tbl->email = $email;
            $tbl->icq = $icq;
            $tbl->url = $url;
            $tbl->origin = $origin;
            $tbl->save();
            unset($tbl);
        } 
    }     
    if ($error_exists == 0) {
        $tbl = new seTable("forum_users","fu");
        $tbl->select("fu.id, fu.id_status, fu.nick, fu.realname, fu.location,
                        fu.jobtitle, fu.interests,fu.email, fu.icq, fu.url, fu.img,
                        fu.enabled, fu.registered, fu.last, fu.origin,
                        fs.id as sid, fs.name as status,
                        (SELECT count(fm.id) FROM forum_msg fm WHERE 
                            (fm.id_users=fu.id) AND ((fm.to_whom='0') OR (fm.to_whom IS NULL))) AS msgcount,
                        (SELECT count(fm.id) FROM forum_msg fm WHERE
                            (fm.to_whom=fu.id)) AS pmsgcount,
                        (SELECT count(fm.id) FROM forum_msg fm WHERE 
                            (fm.id_users=fu.id) AND ((fm.to_whom!='0') AND NOT (fm.to_whom IS NULL))) AS ppmsgcount
                        ");
        $tbl->leftjoin("forum_status fs","fu.id_status = fs.id");
        $tbl->where("fu.id = '?'",$uid);
        $user = $tbl->fetchOne();
        unset($tbl);
//        $nick = htmlspecialchars($user['nick']);
        if (empty ($_SESSION ['userDT'])) {
            $realname = htmlspecialchars($user['realname']);
            $location = htmlspecialchars($user['location']);
            $jobtitle = htmlspecialchars($user['jobtitle']);
            $interests = htmlspecialchars($user['interests']);
            $status = htmlspecialchars($user['status']);
            $email = htmlspecialchars($user['email']);
            $icq = htmlspecialchars($user['icq']);
            $url = htmlspecialchars($user['url']);
        }
        $msg_count = intval ($user ['msgcount']);
        $msg_personalcount = intval ($user ['pmsgcount']);
        $msg_putpcount = intval ($user ['ppmsgcount']);
        $registered = date("d", $user['registered'])." ".$month_R[date("m", $user['registered'])].date(" Y {$section->language->lang114} H:i", $user['registered']);
        $last = date("d", $user['last'])." ".$month_R[date("m", $user['last'])].date(" Y {$section->language->lang114} H:i", $user['last']);
        $img = '-'; 
        $img_exists = 0;       
        if ($user['img'] != "") {
            $img_exists = 1;
            $sz = GetImageSize("modules/forum/images/".$user['img']);
            $imgsz0 = $sz[0];
            $imgsz1 = $sz[1];
            $img = $user['img'];//."?".time();
        }
        $origin = stripslashes(htmlspecialchars($user['origin']));
    }
}
if (!empty ($_SESSION ['userDT'])) {
    unset ($_SESSION ['userDT']);
}
// 05

?>