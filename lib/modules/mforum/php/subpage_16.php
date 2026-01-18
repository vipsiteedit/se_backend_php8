<?php

$thispage = 1;
$message_exists = 0;
$_message = '';
if (!$smod) {
    $tbl = new seTable("forum_forums", "ff");
    $tbl->where("moderator = '?'", $uid);
    $tbl->andWhere("id = '?'", $ext_id);
    if (!($all = count($tbl->getList()))) {
        $thispage = 0;
    }
    unset($tbl);
}
if ($thispage) {
    if ((isRequest('doOpen')) || (isRequest('doClose'))) {
        if (isRequest('checked')) {
            foreach ($_POST['checked'] as $v) {
                $tbl = new seTable("forum_topic", "ft");
                $tbl->find($v);
                $tbl->enable = (isRequest('doOpen')) ? 'Y' : 'N';
                $tbl->save();
                unset($tbl);
            }
        }
        Header("Location: ".seMultiDir().$_SERVER['HTTP_REFERER']);
        exit();
    } else if ((isRequest('doOn')) || (isRequest('doOff'))) {
        if (isRequest('checked')) {
            foreach ($_POST['checked'] as $v) {
                $tbl = new seTable("forum_topic", "ft");
                $tbl->find($v);
                $tbl->visible = (isRequest('doOn')) ? 'Y' : 'N';
                $tbl->save();
                unset($tbl);
            }
        }
        Header("Location: ".seMultiDir().$_SERVER['HTTP_REFERER']);
        exit();
    } else if ((isRequest('doUp')) || (isRequest('doDown'))) {
        if (isRequest ('checked')) {
            foreach ($_POST ['checked'] as $v) {
                $tbl = new seTable("forum_topic", "ft");
                $tbl->find($v);  
                if (isRequest('doDown')) {
                    if ($tbl->priority) {
                        --$tbl->priority;
                    }
                } else {
                    if (!$tbl->priority) {
                        ++$tbl->priority;
                    }
                }
                $tbl->save ();
                unset ($tbl);
            }
        }
        Header("Location: ".seMultiDir().$_SERVER['HTTP_REFERER']);
        exit();
    } else if (isRequest('doDel')) {
        if (isRequest('checked')) {
            $tbl = new seTable("forum_attached", "fa");
            $tbl->select("file, id_msg");
            $tbl->innerjoin("forum_msg fm", "fa.id_msg = fm.id");
            $tbl->where("fm.id_topic IN (".join(", ", $_POST['checked']).")");
            $rf = $tbl->getList();
            unset ($tbl);
            foreach ($rf as $file) {
                $msglist[] = $file['id_msg'];
                @unlink ("modules/forum/upload/".$file['file']);
                @unlink ("modules/forum/upload/".utf8_substr($file['file'], 0, utf8_strlen($file['file'])-4)."-1".utf8_substr($file['file'], -4));
            }
            $msglist = join(", ", $msglist);
            $tbl = new seTable("forum_attached", "fa");
            $tbl->where("id_msg IN ($msglist)");
            $tbl->deletelist();
            unset($tbl);
            $tbl = new seTable("forum_msg", "fm");
            $tbl->where("id_topic IN (". join(", ", $_POST['checked']) .")");
            $tbl->deletelist();
            unset($tbl);
            $tbl = new seTable("forum_topic", "ft");
            $tbl->where("id IN (". join(", ", $_POST['checked']) .")");
            $tbl->deletelist();
            unset($tbl);
        }
        Header("Location: ".seMultiDir().$_SERVER['HTTP_REFERER']);
        exit();
    } else {
        $tbl = new seTable("forum_forums", "ff");
        $tbl->select("ff.name AS name, fa.lang, id_area, fa.name AS area");
        $tbl->innerjoin("forum_area fa","fa.id = ff.id_area");
        $tbl->where("ff.id = '$ext_id'");
        $tbl->andwhere("fa.lang='?'" , $lang);   // выводим только нужны язык
        $forum = $tbl->fetchOne();
        unset ($tbl);
        $forumName = $forum['name'];
        $aid = $forum['id_area'];
        $tbl = new seTable("forum_topic", "ft");
        $tbl->where("id_forums = '?'", $ext_id);
        $tbl->orderby("`ft`.`priority` DESC, `ft`.`date_time_new`", 1);
        $rt = $tbl->getList();
        unset ($tbl);
        $allTopic = count($rt);
        if (!$allTopic) {
            $message_exists = 1;
            $_message = 'В данном форуме нет ни одной темы!';
        } else {
            $fArea = $forum['area'];
            if (isRequest('part')) {
                $ext_part = getRequest('part', 1);
            } else {
                $ext_part = 1;
            }
            $ipages = 0;
            if ($msgOfPart<count($rt)) {
                $ipages = 1;
                $n = ceil(count($rt)/$msgOfPart);
                for($i=1; $i<=$n; $i++) {
                    $__data->setItemList($section, 'ipages', 
                        array (
                            'ipage'   => $i,
                            'status' => (($i==$ext_part)?1:0)
                        )
                    );
                }
            }
            $bpage = ($ext_part - 1) * $msgOfPart;
            for ($i = $bpage; ($i < $bpage + $msgOfPart) && 
                                ($i < count($rt)); $i++) {
                $topic = $rt [$i];
                $__data->setItemList($section, 'themes',
                    array (
                        'id' => $topic['id'],
                        'name' => stripslashes(htmlspecialchars($topic['name'], ENT_QUOTES)),
                        'date' => date("d", $topic['date_time'])." ".$month_R[date("m", $topic['date_time'])].date(" Y года в H:i", $topic['date_time']),
                        'dateNew' => date("d", $topic['date_time_new'])." ".$month_R[date("m", $topic['date_time_new'])].date(" Y года в H:i", $topic['date_time_new']),
                        'enable' => (($topic['enable'] == 'Y') ? 1 : 0),
                        'visible' => (($topic['visible'] == 'Y') ? 1 : 0) 
                    )
                );
            }
        }
    }
}
// 16

?>