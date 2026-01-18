<?php

$_SESSION ['msgAddTime'] = time();
if (isRequest('delfile') && $uid) {
    $delfile = getRequest('delfile', 3);
    $_SESSION['forum_msgtext'] = preg_replace("/(\[(?:attimg|attfile))\s+(src=.+\])/i", "$1 $2", $_SESSION['forum_msgtext']);
    if (file_exists(getcwd() . "/modules/forum/upload/$delfile")) {
        unlink(getcwd() . "/modules/forum/upload/$delfile");
    }
    se_db_query("DELETE FROM forum_attached
                    WHERE
                        file = '$delfile'");       
    if (!empty($_SESSION['forum_attached'])) {
        foreach ($_SESSION['forum_attached'] as $k => $v) {
            if ($v['id'] == $delfile) {
                $filename = $_SESSION['forum_attached'][$k]['name'];
                if (preg_match("/\.(gif|jpg|png|jpeg)$/u", $filename)) {
                    $_SESSION['forum_msgtext'] = str_replace("[attimg src=$filename]", "", $_SESSION['forum_msgtext']);
                } else {
                    $_SESSION['forum_msgtext'] = str_replace("[attfile src=$filename]", "", $_SESSION['forum_msgtext']);
                }
                unset($_SESSION['forum_attached'][$k]);
                break;
            }
        }
    }
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit();
}
$personal = $mod = $mmid = $newt = $ext_topic = $quote_id = $forumId = 0;
if (isRequest('text')) {
    $_SESSION['forum_msgtext'] = getRequest('text', 3);
}
if (isRequest ('personal')) {
    $personal = getRequest('personal', 1);
}
$createt = 0;
if (isRequest('mod')) {
    $mod = getRequest('mod', 1);
    $createt = 1;
}
if (isRequest('quote')) {
    $quote_id = getRequest('quote', 1);
}
if (isRequest('newt')) {
    $newt = 1;
    $createt = 1;
} else {
    $ext_topic = $ext_id;
}
if (isRequest('mmid')) {
    $mmid = getRequest('mmid', 1);
} else if (isRequest('mid') || $mod) {
    if ($mod) {
        $mmid = $mod; 
    } else {
        $mmid = getRequest('mid', 1);
    }
    if (empty($_SESSION['imgadd'])) {
        if (empty($_SESSION['forum_msgtext'])) {
            $tbl = new seTable("forum_msg");
            $tbl->find($mmid);
            $_SESSION['forum_msgtext'] = stripslashes($tbl->text);//stripslashes($mess['text']);
            unset($tbl);
        }
        $forum_attached = array();
        $tbl = new seTable("forum_attached", "fa");
        $tbl->where("id_msg = '?'", $mmid);
        $files = $tbl->getList();
        unset($tbl);  
        foreach ($files as $v) {
            $forum_attached[$v['file']]['name'] = $v['realname'];
            $forum_attached[$v['file']]['size'] = $v['size'];
            $forum_attached[$v['file']]['id'] = $v['file'];
        }
        $_SESSION['forum_attached'] = $forum_attached;
    }  
}
if (!$mod && !$newt) {
    $tbl = new seTable("forum_topic", "ft");
//    $tbl->innerjoin("forum_msg fm", "fm.id_topic = ft.id"); 
//    $tbl->where("fm.id = '$mmid'");   
    $tbl->where("ft.id = '$ext_topic'");
    $tbl->andWhere("ft.id_users = '$uid'");
    $tbl->fetchOne();
    $createt = intval($tbl->id);
    unset($tbl);
}
$save_button = $mod + $mmid;
$_SESSION['imgadd'] = 0;
$error = 0;
$error_exists = 0;
// Загрузка файла
if (isRequest('upload')) {
    $userfile = $_FILES['userfile']['tmp_name'];
    $userfile_size = $_FILES['userfile']['size'];
    $userfname = $_FILES['userfile']['name'];
    $userfname = str_replace('[', '_', $userfname);
    $userfname = str_replace(']', '_', $userfname);
//    if (!(preg_math("/^[^\.]+\.(gif|jpg|jpeg|png|rar|zip|arj|gz)$/u", $userfname))) {
    if (!(preg_match("/\.(gif|jpg|jpeg|png|rar|zip|arj|gz)$/i", utf8_strtolower($userfname)))) {
        $error = "{$section->language->lang099} gif, jpg, jpeg, png, rar, zip, arj, gz.";
        $error_exists = 1;
    } else {
        @$forum_attached = $_SESSION['forum_attached'];
        if (count($forum_attached) >= $maxFilesAttached) {
            $error = $section->language->lang100;
            $error_exists = 1;
        } else {
            $fsize = 0;
            if (count($forum_attached)) {
                foreach ($forum_attached as $af) {
                    $fsize += $af['size'];
                }
            }
            if ($fsize + $userfile_size > $maxFilesAttachedSize) {
                $error = "{$section->language->lang101} (" . round((($fsize + $userfile_size) / 1024), 2) . " {$section->language->lang102}) {$section->language->lang103}";
                $error_exists = 1;
            } else {
            //----------------------------------------------------\/
                $tbl = new seTable("forum_attached", "fa");
                $tbl->select("SUM(size) AS size");
                $tbl->where("id_user = '?'", $uid);
                $ms = $tbl->fetchOne();
                unset($tbl);
                if (($ms['size'] + $userfile_size) > $maxFilesAttachedUser) {
                    $error = "{$section->language->lang104} (" . round((($ms['size'] + $userfile_size) / 1024), 2) . " {$section->language->lang102}) {$section->language->lang103}";
                    $error_exists = 1;
                } else {
            //----------------------------------------------------/\
                    $filename = $uid . "-" . time() . utf8_substr($userfname, -4);
                    $uploadfile = "modules/forum/upload/" . $filename;
                    if (move_uploaded_file($userfile, $uploadfile)) {
                        $tbl = new seTable("forum_attached", "fa");
                        $tbl->file = $filename;
                        $tbl->realname = $userfname;
                        $tbl->id_user = $uid;
                        $tbl->size = $userfile_size;
                        if ($mmid) {
                            $tbl->id_msg = $mmid;
                        }
                        $tbl->save();
                        unset($tbl);
                        $forum_attached[$filename]['id'] = $filename;
                        $forum_attached[$filename]['name'] = $userfname;
                        $forum_attached[$filename]['size'] = $userfile_size;
                        $_SESSION['forum_attached'] = $forum_attached;
                        if (preg_match("/^.+\.(gif|jpg|jpeg|png)$/u", utf8_strtolower($uploadfile))) {
                        
                            $sz = GetImageSize($uploadfile);
                            if ($sz[0]>400 || $sz[1]>300) {
                                $fnameNewImage = utf8_substr($uploadfile, 0, utf8_strlen($uploadfile)-4)."-1".utf8_substr($userfname, -4);
                                if (($sz[0]/$sz[1]) > (4/3)) {
                                    $wimr = 400;
                                    $himr = round($sz[1]*(400/$sz[0]));
                                } else {
                                    $himr = 300;
                                    $wimr = round($sz[0]*(300/$sz[1]));
                                }
                                $imr = imagecreatetruecolor($wimr, $himr);
                                if ($sz[2]==1) {
                                    $im = imagecreatefromgif($uploadfile);
                                    imagecopyresized ($imr, $im, 0, 0, 0, 0, $wimr, $himr, $sz[0], $sz[1]);
                                    imagegif($imr, $fnameNewImage);
                                } else if ($sz[2]==2) {
                                    $im = imagecreatefromjpeg($uploadfile);
                                    imagecopyresized($imr, $im, 0, 0, 0, 0, $wimr, $himr, $sz[0], $sz[1]);
                                    imagejpeg($imr, $fnameNewImage);
                                } else if ($sz[2]==3) {
                                    $im = imagecreatefrompng($uploadfile);
                                    imagecopyresized($imr, $im, 0, 0, 0, 0, $wimr, $himr, $sz[0], $sz[1]);
                                    imagepng($imr, $fnameNewImage);
                                }
                                imagedestroy ($im);
                                imagedestroy ($imr);
                            }
                            $_SESSION['forum_msgtext'] .= "\n[attimg src={$userfname}]";
                        } else {
                            $_SESSION['forum_msgtext'] .= "\n[attfile src={$userfname}]";
                        }
                    }
                    $_SESSION['imgadd'] = 1;
                    if (isRequest('topic_nm')) {
                        $_SESSION ['topic_nm'] = getRequest('topic_nm', 3);
                    }
                    Header("Location: ".$_SERVER['HTTP_REFERER']."#edit");
                    exit();
                }
            }
        }
    }
} else if (!isRequest('doGo') || !getRequest('doGo', 1)) {
//echo nl2br(print_r($_SESSION['forum_attached'], true));
    if (!$personal) {
        $tbl = new seTable("forum_forums", "ff");
        if (!$newt) {
            $tbl->select('ft.id');
            $tbl->innerjoin("forum_topic ft", "ft.id_forums = ff.id");
            $tbl->where("ft.id = '?'", $ext_id);
        } else {
            $tbl->where("ff.id = '?'", $ext_id);
        }
        if ($mod && !$smod) {
            $tbl->andWhere("ff.moderator != '$uid'");
        }
        $tbl->andWhere("ff.enable='N'");
        $qtd = count($tbl->getList());
        unset($tbl);
    } else {
        $qtd = !$uid;
    }
    if ($qtd) {
        $error = $section->language->lang169;
        $error_exists = 1;
    } else {
        if (isset($_SESSION['forum_msgtext'])) {
           $text = $_SESSION['forum_msgtext'];
        }
        if ($newt) {
            if (isset($_SESSION['forum_msgtopic'])) {
                $topic = $_SESSION['forum_msgtopic'];
            } else {
                $topic = "";
            }
            $tbl = new seTable("forum_forums","ff");
            $tbl->select("ff.id AS id, ff.name AS forum, fa.lang, ff.img AS img,
                            ff.moderator AS moduid, fa.name AS area, 
                            fu.nick AS moderator, fa.id AS idArea");
            $tbl->innerjoin("forum_area fa", "fa.id = ff.id_area");
            $tbl->leftjoin("forum_users fu", "fu.id = ff.moderator");
            $tbl->where("ff.id = '?'",$ext_id);
            $tbl->andwhere("fa.lang='?'" , $lang);   // выводим только нужны язык
            $tbl->andWhere("ff.visible='Y'");
            $forum = $tbl->fetchOne();
            unset($tbl);
            $moduid = $forum['moduid'];
            $moderator = $forum['moderator'];
            $areaId = $forum['idArea'];
            $fArea = $forum['area'];
            $forumName = $forum['forum'];
            $forumId = $forum['id'];
        } else if (!$personal) {
  //Проверяем, не закрыта ли тема.
            $tbl = new seTable("forum_topic", "ft");
            $tbl->select("ft.id AS tid, ft.name AS topic, 
                          fa.id AS aid, fa.name AS area, 
                          ff.id AS fid, ff.name AS forum");
            $tbl->innerjoin("forum_forums ff", "ff.id = ft.id_forums");
            $tbl->innerjoin("forum_area fa", "fa.id = ff.id_area");
            $tbl->where("ft.enable = 'Y'");
            $tbl->andWhere("ft.id = '?'", $ext_id);
            $qtd1 = $tbl->fetchOne();
            unset($tbl);
            if (empty($qtd1)) {
                $error = $section->language->lang170;
                $error_exists = 1;
            } else {
                $topicID = $qtd1['tid'];
                $topic = stripslashes(htmlspecialchars($qtd1['topic'], ENT_NOQUOTES));
                $areaId = $qtd1['aid'];
                $fArea = $qtd1['area'];
                $forumName = $qtd1['forum'];
                $forumId = $qtd1['fid'];
            } 
        }
        if (!$error_exists) {
            $ipart = 0;
            if ($quote_id) {
                $ipart = 1;    
                $id_user = getRequest('user', 1);
                $tbl = new seTable("forum_msg","fm");
                $tbl->select("id_topic, fm.id_users AS id_users, text, 
                                    fm.date_time AS date_time, date_time_edit, 
                                    moderator_edit, date_time_moderator_edit, 
                                    ft.name AS topic, nick, fs.name AS status, 
                                    origin, fu.img AS img, location");
                $tbl->innerjoin("forum_topic ft","ft.id = fm.id_topic");
                $tbl->innerjoin("forum_users fu","fu.id = fm.id_users");
                $tbl->leftjoin("forum_status fs","fs.id = fu.id_status");
                $tbl->where("fm.id = '?'",$quote_id);
                $quote = $tbl->fetchOne();
                unset($tbl);
                $qtext = htmlspecialchars(stripslashes($quote['text']), ENT_QUOTES);
                $qtopic = stripslashes($quote['topic']);
                $qdate = date("d", $quote['date_time'])." ".$month_R[date("m", $quote['date_time'])].date(" Y года, H:i", $quote['date_time']);
                $q_id_users = $quote['id_users'];
                $qnick = $quote['nick'];
                $qstatus = $quote['status'];
                $qlocation = $quote['location'];
                $img_exists = 0; 
                if (!empty($quote['img'])) {
                    $img_exists = 1;
                    $img = $quote['img'];
                }
                $qtext = textedit ($qtext, $q_id_users, $quote_id, 
                                $iconssmiles, $_page, $razdel); 
                $moderator_edit_exists = $date_time_edit_exists = 0;
                if (!empty($quote['date_time_edit'])) {
                    $date_time_edit_exists = 1;
                    $date_time_edit = date("d", $quote['date_time_edit'])." ".$month_R[date("m", $quote['date_time_edit'])].date(" Y года в H:i", $quote['date_time_edit']);
                }
                if ($quote['moderator_edit']=='Y') {
                    $moderator_edit_exists = 1;   
                    $moderator_edit = date("d", $quote['date_time_moderator_edit'])." ".$month_R[date("m", $quote['date_time_moderator_edit'])].date(" Y года в H:i", $quote['date_time_moderator_edit']);
                } 
            } 
            $i = 1;
            while (file_exists(getcwd().$iconssmiles."/smile".sprintf("%03d", $i).".gif")) {
                $__data->setItemList($section, 'smilelist', array('smile' => sprintf ("%03d", $i)));
                $i++;
            }
            $forum_attached_count = 0;
            if (isset($_SESSION['forum_attached'])) {
                $forum_attached = $_SESSION['forum_attached'];

                $allsize = 0;
                $i = 0;
                foreach($forum_attached as $af) {
                    $__data->setItemList($section, 'fatt', 
                        array(
                                'name' => $af['name'],
                                'name2' => addslashes ($af['name']),
                                'size' => round($af['size']/1024, 2),
                                'id' => $af['id'],
                                'img' => intval(preg_match("/^.+\.(gif|jpg|jpeg|png)$/u", $af['name']) != 0),
                                'next' => (($i<count($forum_attached)-1)?',':'') 
                            )
                    );                    
                    ++$i;
                    $allsize += $af['size'];
                }
                $forum_attached_count = count($forum_attached);
                $forum_attached_size = round($allsize/1024, 2);
                $forum_attached_max = round($maxFilesAttachedSize/1024, 2);
            }
            if ($personal) {
                $tbl = new seTable ("forum_users", "fu");
                $tbl->find ($personal);
                $to_whom = $tbl->nick;
                unset ($tbl);
            }
            if (!empty($_SESSION ['topic_nm'])) {
                $topic = $_SESSION ['topic_nm'];
                unset ($_SESSION ['topic_nm']);
            }
        }
    }
} else if (getRequest('doGo', 1)) {
    $error_exists = 0;
    if (!empty($_SESSION['USER_ADD_MSG'])) {
        $_SESSION['forum_attached'] = NULL;
        $_SESSION['forum_msgtext'] = NULL;
        if ($personal) {
            Header("Location: ".seMultiDir()."/$_page/$razdel/sub3/?puser=$uid&new&last&".time());
            exit();
        } else if ($ext_topic) {
            Header("Location: ".seMultiDir()."/$_page/$razdel/sub3/?id=$ext_topic&new&last&".time());
            exit();
        } else {
            $error = "{$section->language->lang105}<br>
                        {$section->language->lang106}";
            $error_exists = 1;
        }
    }
    if (!$uid) {
        $error = $section->language->lang109;
        $error_exists = 1;
    }  
    $_SESSION['USER_ADD_MSG'] = 1;
    if (!$error_exists) {
        if ($createt) {
            $topic = utf8_substr(getRequest('topic_nm', 4), 0, $msgMaxLengthTopic);
            $topic = AddSlashes($topic);
            if (empty($topic)) {
                $error = $section->language->lang108;
                $error_exists = 1;
                unset($_SESSION['USER_ADD_MSG']);
            } else if ($newt) {
                $email = AddSlashes(getRequest('email', 3));
                $date = time();
                $forum = getRequest('forum', 4);
 //Проверяем, не является ли пользователь гостем
            }
        }
//------------------------------------------------------------------------------------------
        $ext_text = getRequest('text', 3);
        if (empty($ext_text)) {
            $error = $section->language->lang107;
            $error_exists = 1;
            unset($_SESSION['USER_ADD_MSG']);
        }
//------------------------------------------------------------------------------------------
        if (isRequest('topic')) {
            $ext_topic = getRequest('topic', 1);
        }
 //Проверяем, не закрыт ли форум.
        if (!$personal) {
            $tbl = new seTable("forum_forums", "ff");
            if ($newt) {
                $tbl->where("ff.id = '?'", $ext_id);
            } else {
                $tbl->select("ft.id");
                $tbl->where("ft.id = '?'", $ext_topic);
                $tbl->innerjoin("forum_topic ft", "ff.id = ft.id_forums");
            }
            $tbl->andWhere("ff.enable = 'N'");
            $qtd = count($tbl->getList());
            unset($tbl);
        } else {
            $qtd = !$uid;
        }
        if ($qtd) {
            $error = $section->language->lang169;
        } else { 
            if ($newt && $forum) {
                $tbl = new seTable("forum_topic");
                $tbl->select("max(id) AS id");
                $tbl->fetchOne();                    
                $ext_topic = $tbl->id + 1;
                unset($tbl);
                $tbl = new seTable("forum_topic");
                $tbl->id = $ext_topic;
                $tbl->id_forums = $forum;
                $tbl->name = $topic;
                $tbl->priority = 0;
                $tbl->date_time = $date;
                $tbl->id_users = $uid;
                $tbl->email = $email;
                $tbl->date_time_new = $date;
                $tbl->id_user_new = $uid;
                $tbl->save(); 
                unset($tbl); 
            } else if (!$personal) {
                $tbl = new seTable("forum_topic");
                $tbl->where("id = '?'", $ext_topic);
                $tbl->andWhere("enable != 'N'");
                $tbl->fetchOne();
//                $qtd1 = intval($tbl->id);//count($tbl->getList());
//                unset($tbl);
//                if ($qtd1) {
                if (!$tbl->id) {
                    unset($tbl);
                    $error = $section->language->lang170;
                    $error_exists = 1;
                } else if ($createt) {
                    $tbl->name = $topic;
                    $tbl->save();
                    unset($tbl);                    
                } 
            }
            if (!$error_exists) {
                $text = getRequest('text', 3);
                if (utf8_strlen($text) >= $msgMaxLength) {
                    $text = utf8_substr($text, 0, $msgMaxLength);
                }
                $date = time();
                $ip = $_SERVER['REMOTE_ADDR'];
                $realid = $uid;
                $tbl = new seTable("forum_msg");
                $tbl->addField('msg_id', 'integer');
                $tbl->select("(max(id) + 1) AS mx");
                $tbl->fetchOne();                                
                $msgid = $tbl->mx;
                unset($tbl);
                $tbl = new seTable("forum_msg");
                $last = '';
                if ($mmid) {
                    $tbl->find($mmid);
                    $realid = $tbl->id_users;
                    $msgid = $mmid;
                    if ($mod) {
                        $tbl->moderator_edit = 'Y';
                        $tbl->date_time_moderator_edit = $date;
                    } else {
                        $tbl->date_time_edit = $date;
                    }
                } else {
                    if ($personal) {
                        $tbl->to_whom = $personal;
                    } else {
                        $tbl->id_topic = $ext_topic;
                    }
                    $last = '&last';
                    $tbl->date_time = $date;
                    $tbl->id_users = $uid;       
                    if ($quote_id) {
                        $tbl->msg_id = $quote_id;
                    } 
                }
                $tbl->text = $text;
                $tbl->ip = $ip;
                $tbl->save();
                unset($tbl);
                if (!$personal) {
                    $tbl = new seTable("forum_topic");
                    $tbl->find($ext_topic);
                    $tbl->date_time_new = $date;
                    $tbl->id_user_new = $uid;
                    $tbl->save();
                    unset($tbl);
                }
                if (!$mmid && !$mod) {
                    $tbl = new seTable("forum_msg");
                    $tbl->select("max(id) as maxid");            
                    $tbl->fetchOne();
                    $mm = $tbl->maxid;
                    unset($tbl);            
//Если есть прикрепленные файлы
                    if (isset($_SESSION['forum_attached'])) {
//                        $forum_attached = $_SESSION['forum_attached'];
                        se_db_query("UPDATE forum_attached
                                        SET
                                            id_msg = '$mm'
                                        WHERE
                                            file in ('" . join("','", array_keys($_SESSION['forum_attached'])) . "')");
/*
                        foreach($forum_attached as $k=>$af) {
                            $tbl = new seTable("forum_attached");
                            $tbl->where("file = '?'",$k);
                            $tbl->fetchOne();
                            $tbl->id_msg = $mm;
                            $tbl->save();
                            unset($tbl);
                        }
//*/
                    }
                }
                $_SESSION['forum_attached'] = NULL;
                $_SESSION['forum_msgtext'] = NULL;
 //Посылаем сообщение автору
                if (!$newt) {
                    $tbl = new seTable("forum_topic");
                    $topic = $tbl->find($ext_topic);
                    unset($tbl);
                    if (!empty($topic['email'])) {
                        $tbl = new seTable("forum_users");
                        $user = $tbl->find($uid);
                        unset($tbl);
                        $addr = $_SERVER['CHARSET_HTTP_METHOD'].$_SERVER['HTTP_HOST'];                
                        $mail_text = "<a href='$addr/$_page/$razdel/sub3/?id=$ext_topic'>".$topic['name'].'</a><br>';
                        $mail_text .= "<a href='$addr/$_page/$razdel/sub6/?id=".$user['id']."'>".$user['nick'].'</a>';
                        $mail_text .= '&nbsp;<b>'.date("d.m.Y, H:i", time()).'</b><br>';
                        $text = htmlspecialchars($text);
                        $text = textedit ($text, $realid, $msgid, $iconssmiles, $_page, $razdel, true); 
                        $mail_text .= $text;
                        $from = "From: =?utf-8?b?" . base64_encode($topic['name']) . "?= <noreply@" . $_SERVER['HTTP_HOST'].">\n";
                        $from .= "Content-type: text/html; charset=utf-8";   
                        $subject = "=?utf-8?b?" . base64_encode($section->language->lang171) . "?=";
                        mail($topic['email'], $subject, $mail_text, $from);
                    }
                }
                if ($personal) {
                    Header("Location: ".seMultiDir()."/$_page/$razdel/sub3/?puser=$uid&new$last&".time());
                } else {
                    Header("Location: ".seMultiDir()."/$_page/$razdel/sub3/?id=$ext_topic&new$last&".time());
                }
                exit();
            }
        }
    }
}

$colors = array (
    "#000000","#000033","#000066","#000099","#0000CC","#0000FF","#003300","#003333",
    "#003366","#003399","#0033CC","#0033FF","#006600","#006633","#006666","#006699",
    "#0066CC","#0066FF","#009900","#009933","#009966","#009999","#0099CC","#0099FF",
    "#00CC00","#00CC33","#00CC66","#00CC99","#00CCCC","#00CCFF","#00FF00","#00FF33",
    "#00FF66","#00FF99","#00FFCC","#00FFFF","#330000","#330033","#330066","#330099",
    "#3300CC","#3300FF","#333300","#333333","#333366","#333399","#3333CC","#3333FF",
    "#336600","#336633","#336666","#336699","#3366CC","#3366FF","#339900","#339933",
    "#339966","#339999","#3399CC","#3399FF","#33CC00","#33CC33","#33CC66","#33CC99",
    "#33CCCC","#33CCFF","#33FF00","#33FF33","#33FF66","#33FF99","#33FFCC","#33FFFF",
    "#660000","#660033","#660066","#660099","#6600CC","#6600FF","#663300","#663333",
    "#663366","#663399","#6633CC","#6633FF","#666600","#666633","#666666","#666699",
    "#6666CC","#6666FF","#669900","#669933","#669966","#669999","#6699CC","#6699FF",
    "#66CC00","#66CC33","#66CC66","#66CC99","#66CCCC","#66CCFF","#66FF00","#66FF33",
    "#66FF66","#66FF99","#66FFCC","#66FFFF","#990000","#990033","#990066","#990099",
    "#9900CC","#9900FF","#993300","#993333","#993366","#993399","#9933CC","#9933FF",
    "#996600","#996633","#996666","#996699","#9966CC","#9966FF","#999900","#999933",
    "#999966","#999999","#9999CC","#9999FF","#99CC00","#99CC33","#99CC66","#99CC99",
    "#99CCCC","#99CCFF","#99FF00","#99FF33","#99FF66","#99FF99","#99FFCC","#99FFFF",
    "#CC0000","#CC0033","#CC0066","#CC0099","#CC00CC","#CC00FF","#CC3300","#CC3333",
    "#CC3366","#CC3399","#CC33CC","#CC33FF","#CC6600","#CC6633","#CC6666","#CC6699",
    "#CC66CC","#CC66FF","#CC9900","#CC9933","#CC9966","#CC9999","#CC99CC","#CC99FF",
    "#CCCC00","#CCCC33","#CCCC66","#CCCC99","#CCCCCC","#CCCCFF","#CCFF00","#CCFF33",
    "#CCFF66","#CCFF99","#CCFFCC","#CCFFFF","#FF0000","#FF0033","#FF0066","#FF0099",
    "#FF00CC","#FF00FF","#FF3300","#FF3333","#FF3366","#FF3399","#FF33CC","#FF33FF",
    "#FF6600","#FF6633","#FF6666","#FF6699","#FF66CC","#FF66FF","#FF9900","#FF9933",
    "#FF9966","#FF9999","#FF99CC","#FF99FF","#FFCC00","#FFCC33","#FFCC66","#FFCC99",
    "#FFCCCC","#FFCCFF","#FFFF00","#FFFF33","#FFFF66","#FFFF99","#FFFFCC","#FFFFFF"
    );

for ($i = 0; $i < 12 ; $i++) {
    for ($j = 0; $j < 18 ; $j++) {
        $__data->setItemList($section, "color$i", 
            array ('color' => $colors [$i * 18 + $j]));
    }  
    $__data->setItemList($section, 'colors', array ('i' => $i));
}
// 04
                                          

?>