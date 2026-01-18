<?php

$FP = getRequest('FP', 1);
$img_src = '/' . trim($__MDL_URL, '/') . '/';
if ($ORDER_ID && $FP) {
    $payment->find($FP);
    // Заполняю переменными
    $payment->blank = se_macrocomands($payment->blank, $FP, $ORDER_ID, se_baseCurrency(), $lang);
}
$blank = '';
$end = $payment->blank;
while (preg_match("/([\w\W]*?)(<img\s[^>]*?>)([\w\W]*)/i", $end, $dt)) {
    list(, $begin, $img, $end) = $dt;
    $blank .= $begin;
    preg_match("/<img\s[^>]*?src\s*=\s*['\"]?(.*\.(?:jpg|jpeg|bmp|gif|png))['\"]?[^>]*?>/i", $img, $src);    
    if (!empty($src[1])) {
        $src = trim($src[1]);
        $yes = 1;
        if (!preg_match("/^[\w]*:\/\//i", $src)) {
            if (!file_exists(getcwd() . '/' . ltrim($src, '/'))) {
                $yes = 0;
            }            
        } else {
            $src = se_file_get_contents($src);
            if (empty($src)) {
                $yes = 0;
            }
        }
        if ($yes) {
            $blank .= $img;
        }
    }
}
$payment->blank = $blank . $end;

?>