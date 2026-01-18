<?php

if (!function_exists('to_utf8')){
    function to_utf8 ($file, $savefile) {
        $f = file ($file);
        $ff = fopen ($savefile,'w');
        if ($f [0] [0] == chr (8)) {
            foreach ($f as $v) {
                $data = explode (chr (8), $v);
                $date = explode ('.', iconv ('cp1251', 'utf-8', trim ($data [1])));
                //print_r ($data);
/*
                echo '<br>[USRNAME] = ' . iconv ('cp1251', 'utf-8', trim ($data [2])) . 
                     '<br>[USRMAIL] = ' . iconv ('cp1251', 'utf-8', trim ($data [6])) .
                     '<br>[USRNOTE] = ' . iconv ('cp1251', 'utf-8', trim ($data [4])) . 
                     '<br>[ADMTEXT] = ' . iconv ('cp1251', 'utf-8', trim ($data [5])) . 
                     '<br>[DATE] = ' . mktime (0, 0, 0, intval ($date [1]), intval ($date [0]), intval ($date [2])) . 
                     '<br>[IP] = ' . iconv ('cp1251', 'utf-8', trim ($data [7])) . '
                     <br>&lt;-------------------------------------------------------&gt;<br>';
//*/
                $data = serialize (
                    array (
                        'usrname' => iconv ('cp1251', 'utf-8', trim ($data [2])),
                        'usrmail' => iconv ('cp1251', 'utf-8', trim ($data [6])),
                        'usrnote' => base64_encode (iconv ('cp1251', 'utf-8', trim ($data [4]))),
                        'admtext' => base64_encode (iconv ('cp1251', 'utf-8', trim ($data [5]))),
                        'date'    => mktime (0, 0, 0, intval ($date [1]), intval ($date [0]), intval ($date [2])),
                        'ip'      => iconv ('cp1251', 'utf-8', trim ($data [7]))
                    )
                );
                fprintf ($ff, "%s\n", $data);
            }
        } else {
            foreach ($f as $v) {
                $data = unserialize ($v);
                if (function_exists ('mb_detect_encoding')) {
                    $break = 0;
                    foreach ($data as $k => $vv) {
                        $dt = $vv;
                        if ($b64 = in_array ($k, array ('usrnote', 'admtext'))) {
                            $dt = base64_decode ($dt);
                        }
                        $code = mb_detect_encoding ($dt, 'cp1251,utf-8');
                        if (empty ($code)) {
                            $break = 1;
                            break;
                        }
                        if (in_array ($code, array ('CP1251', 'cp1251', 'Windows-1251'))) {
                            $dt = iconv ('cp1251', 'utf-8', $dt);
                        }
                        if ($b64) {
                            $dt = base64_encode ($dt);
                        }
                        $data [$k] = $dt;
                    }
                    if ($break) {
                        continue;
                    }
                }
                fprintf ($ff, "%s\n", serialize ($data));
            }
        }    
        fclose ($ff);
    }
}

function cmpar($a, $b) {
    $c = unserialize ($a);
    $d = unserialize ($b);
    return $c['date'] - $d['date'];
}
?>
