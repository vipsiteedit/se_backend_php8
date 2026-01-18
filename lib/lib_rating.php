<?php

if (!function_exists('se_rating_list')) {
    function se_rating_list($section, $param, $idRating = "")
    {
        $res = array();
        $recres = array();

        //$ip = $_SERVER['REMOTE_ADDR'];
        $ip = $_SERVER['HTTP_X_REAL_IP'] ?? ($_SERVER['REMOTE_ADDR'] ?? '');

        if (!file_exists("data")) {
            mkdir("data", 0755);
        }

        $_page = seData::getInstance()->getPageName(); // getRequest('page');
        if (!empty($idRating)) {
            $idRating = str_replace('[namepage]', $_page, $idRating);
            $idRating = substr(md5(str_replace("/", "", $idRating)), 0, 5);
            $fnamedat = "data/rating_" . $idRating . "_stat.dat";
            $fnameip = "data/rating_" . $idRating . "_ip.dat";
        } else {
            $fnamedat = "data/rating_" . $_page . "_" . $section->id . "_stat.dat";
            $fnameip = "data/rating_" . $_page . "_" . $section->id . "_ip.dat";
        }


        if (file_exists($fnamedat)) //���� ���� ���� ���������� 
        {
            $stat = file($fnamedat);
            foreach ($stat as $str) {
                $v = explode(chr(9), $str);
                $res[$v[0]] = trim($v[1]);
            }
        }

        $flag = false;
        if (isRequest('goRating') && isRequest('ratingraz') && getRequest('ratingraz') == $section->id)  //���� ������ '����������'
        {
            $rec = getRequest('ratingobj', 1);
            if (file_exists($fnameip))  //���� ���� ���� IP 
            {
                if (date("d", filemtime($fnameip)) != date("d"))  //���� ���� �� ��������� c �������, �������
                {
                    unlink($fnameip);
                } else {
                    $iplist = file($fnameip);

                    foreach ($iplist as $str) {
                        $v = explode(chr(9), $str);
                        if ($v[0] == $rec && trim($v[1]) == $ip) {
                            $flag = true;
                        }
                    }
                }
            }
            if (!$flag) {
                if (isset($res[$rec])) {
                    $res[$rec]++;
                } else {
                    $res[$rec] = 1;
                }

                //��������� ������ � ���� IP
                $f = fopen($fnameip, "a");
                flock($f, LOCK_EX);
                fputs($f, $rec . chr(9) . $ip . "\n");
                fflush($f);
                flock($f, LOCK_UN);
                fclose($f);
                //�������� ���� ����������
                $f = fopen($fnamedat, "w");
                flock($f, LOCK_EX);
                foreach ($res as $k => $v) {
                    fputs($f, $k . chr(9) . $v . "\n");
                }
                fflush($f);
                flock($f, LOCK_UN);
                fclose($f);
            }
        }


        if (!empty($section->objects) && !isRequest('object')) {
            $i = 0;
            foreach ($section->objects as $line) {
                $id = intval($line->id);
                if (isset($res[$id])) {
                    $rat = $res[$id];
                    $recres[$id] = $res[$id];
                    unset($res[$id]);
                } else {
                    $rat = "0";
                }
                $rat .= '<a name="' . $section->id . "_" . $id . '"></a>
            <input type="hidden" name="ratingraz" value="' . $section->id . '">
            <input type="hidden" name="ratingobj" value="' . $id . '">';

                $section->objects[$i]->rating = $rat;
                $section->objects[$i]->link = $section->id . "_" . $id;
                $i++;
            }
        }
        if (intval($param) == 1) //��������� ������ �� ��������� 
        {

            foreach ($section->objects as $line) {
                $id = intval($line->id);
                if (!isset($recres[$id])) {
                    $recres[$id] = 0;
                }
                $objcopy[$id] = clone ($line);
            }

            arsort($recres);
            $pos = array_keys($recres);


            unset($section->objects);
            $objects = $section->objects;
            foreach ($pos as $id) {
                append_simplexml($section->objects[], $objcopy[$id]);
            }
        }

        if (isRequest('clear')) {
            unlink($fnamedat);
            unlink($fnameip);
        }
    }
}
