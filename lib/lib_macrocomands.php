<?php
function setPaymentParams($order_id, $payment_id, $arr = array())
{
    if (empty($arr)) return;
    $fp = fopen(SE_ROOT . 'data/payment_' . $order_id . '_' . $payment_id . '.dat', "w+");
    fwrite($fp, serialize($arr));
    fclose($fp);
}

function getPaymentParams($order_id, $payment_id)
{
    if (file_exists(SE_ROOT . 'data/payment_' . $order_id . '_' . $payment_id . '.dat'))
        return unserialize(join('', file(SE_ROOT . 'data/payment_' . $order_id . '_' . $payment_id . '.dat')));
}


function se_summtostring($summa)
{
    $nf = array('zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine');
    $sel = "zero,one,two,three,four,five,six,seven,eight,nine";
    $reg = array('edin', 'dec', 'des', 'sot', 'mel', 'thou', 'mill', 'wh', 'fr');
    foreach ($reg as $r) {
        $d[$r] = se_db_fields_item('spr_numbers', "registr='$r'", $sel);
    }

    $summa = str_replace(array(' ', ','),  array('', '.'),  $summa);
    $des = explode('.', $summa);

    $c = utf8_strlen($des[0]);
    for ($i = 1; $i <= $c; $i++) {
        $nums[$i] = utf8_substr($des[0], $c - $i, 1);
    }
    $rez = '';
    if ($nums[7] != '') $rez .= $d['mill'][$nums[7]] . ' ';
    if ($nums[6] != '') $rez .= $d['sot'][$nums[6]] . ' ';
    if ($nums[5] != '' && $nums[5] != 1) $rez .= $d['dec'][$nums[5]] . ' ';
    if ($nums[5] != '' && $nums[5] == 1) $rez .= $d['des'][$nums[4]] . ' ' . $d['thou'][0] . " ";
    if ($nums[4] != '' && $nums[5] != 1) $rez .= $d['mel'][$nums[4]] . ' ' . $d['thou'][$nums[4]] . " ";


    if ($nums[3] != '') $rez .= $d['sot'][$nums[3]] . " ";
    if ($nums[2] != '' && $nums[2] != 1) $rez .= $d['dec'][$nums[2]] . " ";
    if ($nums[2] != '' &&  $nums[2] == 1) $rez .= $d['des'][$nums[1]] . " ";
    if ($nums[1] != '' &&  $nums[2] != 1) $rez .= $d['edin'][$nums[1]] . " ";
    if (!empty($rez)) $rez = $rez . $d['wh'][0] . " ";
    $kop = $des[1];

    while (utf8_strlen($kop) < 2) $kop .= "0";
    $rez .= $kop . " " . $d['fr'][0];

    $rez = '<span style="Text-transform:uppercase;">' . utf8_substr($rez, 0, 1) . '</span>' . utf8_substr($rez, 1, utf8_strlen($rez) - 1);
    return ($rez);
}


function se_macrocomands($SUB_PAY_EXECUTE, $FP = 0, $order_id = 0, $user_id = 0)
{

    $curr = se_baseCurrency();
    if (function_exists('getRequest')) {
        $_page = getRequest('page');
        $razdel = getRequest('razdel');
    }

    if (function_exists('se_getLang')) {
        $lang = se_getLang();
    } else $lang = 'rus';

    //echo $order_id;
    if ($lang == 'rus')
        $smonth = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
    else
        $smonth = array('January', ' February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

    // Заполняю переменными

    $SUB_PAY_EXECUTE = str_replace('[RAZDEL]', $razdel, $SUB_PAY_EXECUTE);
    $SUB_PAY_EXECUTE = str_replace('[PAGENAME]', $_page, $SUB_PAY_EXECUTE);

    while (preg_match("/\[POST\.(\w{1,}\:\w{1,})\]/i", $SUB_PAY_EXECUTE, $res_math)) {
        $res_ = $res_math[1];
        $def = explode(':', $res_);
        if (isset($_POST[strtolower($def[0])])) {
            $res_ = htmlspecialchars(stripslashes(@$_POST[strtolower($def[0])]));
        } else $res_ = @$def[1];
        $SUB_PAY_EXECUTE = str_replace($res_math[0], strtoupper($res_), $SUB_PAY_EXECUTE);
    }

    while (preg_match("/\[SELECTED\:(\w{1,})\]/i", $SUB_PAY_EXECUTE, $res_math)) {
        if (strtolower($res_) == strtolower($res_math[1])) {
            $SUB_PAY_EXECUTE = str_replace($res_math[0], "selected", $SUB_PAY_EXECUTE);
        } else {
            $SUB_PAY_EXECUTE = str_replace($res_math[0], "", $SUB_PAY_EXECUTE);
        }
    }

    while (preg_match("/\[IF\((.+?)\)\]/m", $SUB_PAY_EXECUTE, $res_math)) {
        $def = explode(':', $res_math[1]);
        $sel = explode(',', $def[0]);
        $res = @$def[1];
        foreach ($sel as $if) {
            $if = explode('=', $if);
            if (strtolower($res_) == strtolower($if[1])) $res = $if[0];
        }
        $SUB_PAY_EXECUTE = str_replace($res_math[0], $res, $SUB_PAY_EXECUTE);
    }



    while (preg_match("/\[SETCURRENCY\:(\w{1,})\]/m", $SUB_PAY_EXECUTE, $res_math)) {
        if (isset($res_math[1])) {
            $curr = $res_math[1];
            $_SESSION['THISCURR'] = $curr;
        }
        $SUB_PAY_EXECUTE = str_replace($res_math[0], '', $SUB_PAY_EXECUTE);
    }
    $SUB_PAY_EXECUTE = str_replace('[PAYMENT.CURR]', $_SESSION['THISCURR'], $SUB_PAY_EXECUTE);


    while (preg_match("/\[POST\.(\w{1,})\]/i", $SUB_PAY_EXECUTE, $res_math)) {
        $res_ = $res_math[1];
        if (isset($_POST[$res_])) {
            $res_ = htmlspecialchars(stripslashes(@$_POST[$res_]));
        } else {
            $res_ = '';
        }
        $SUB_PAY_EXECUTE = str_replace($res_math[0], $res_, $SUB_PAY_EXECUTE);
    }

    while (preg_match("/\[GET\.(\w{1,})\]/i", $SUB_PAY_EXECUTE, $res_math)) {
        $res_ = $res_math[1];
        if (isset($_POST[$res_])) {
            $res_ = htmlspecialchars(stripslashes(@$_GET[$res_]));
        } else $res_ = '';
        $SUB_PAY_EXECUTE = str_replace($res_math[0], $res_, $SUB_PAY_EXECUTE);
    }

    //$maxaccount=se_db_fields_item("shop_order","1","MAX(account)")+1;
    //se_db_query("UPDATE shop_order SET `account`='$maxaccount' WHERE (id='$order_id') AND (`account`=0)");

    $query = se_db_query("SELECT so.id_author, so.payment_type,so.date_order, so.date_payee, so.discount, so.commentary,
	    (SELECT sa.account FROM shop_account sa WHERE sa.id_order=so.id LIMIT 1) as account,
	    so.curr, so.status, so.delivery_payee, 
	    (SELECT dl.name FROM shop_deliverytype dl WHERE dl.id=so.delivery_type) as delivery_name, 
	    so.delivery_status, so.delivery_date,
	    (SELECT SUM((st.price) * st.count) FROM shop_tovarorder st WHERE st.id_order=so.id) AS `price_tovar`
	FROM `shop_order` so
	WHERE so.id = '$order_id'");

    //INNER JOIN `shop_tovarorder` st ON so.id = st.id_order


    if ($order_id > 0)
        $ORDER = se_db_fetch_array($query);

    if (!$FP) $FP = $ORDER['payment_type'];

    // Таблица MAIN
    $main = se_getAdmin();
    $NDS = $main['nds'];
    if (!empty($main))
        foreach ($main as $k => $v) {
            $SUB_PAY_EXECUTE = str_replace("[MAIN." . strtoupper(@$k) . "]", trim(@$v), $SUB_PAY_EXECUTE);
            $SUB_PAY_EXECUTE = str_replace("[ADMIN_" . strtoupper(@$k) . "]", trim(@$v), $SUB_PAY_EXECUTE);
            if ($k == 'esales') {
                $SUB_PAY_EXECUTE = str_replace("[ADMIN_MAIL_SALES]", trim($v), $SUB_PAY_EXECUTE);
            }
            if ($k == 'esupport') {
                $SUB_PAY_EXECUTE = str_replace("[ADMIN_MAIL_SUPPORT]", trim($v), $SUB_PAY_EXECUTE);
            }
        }

    if (!empty($ORDER))
        foreach ($ORDER as $k => $v) {
            $SUB_PAY_EXECUTE = str_replace("[ORDER." . strtoupper(@$k) . "]", trim(@$v), $SUB_PAY_EXECUTE);
        }
    // Добавляем адрес доставки
    if ($order_id) {
        $query = se_db_query("SELECT telnumber,email,calltime,address,postindex FROM shop_delivery WHERE id_order='$order_id'");
        $ORDERADDR = se_db_fetch_array($query);
        if (!empty($ORDERADDR))
            foreach ($ORDERADDR as $k => $v) {
                if (isset($k))
                    $SUB_PAY_EXECUTE = str_replace("[ORDER." . strtoupper($k) . "]", trim(@$v), $SUB_PAY_EXECUTE);
            }
    }


    if ($order_id && strpos($SUB_PAY_EXECUTE, "[CONTRACT]") !== false) {
        $query = se_db_query("SELECT * FROM `shop_contract` WHERE id_order = '$order_id'");
        $contract = se_db_fetch_array($query);
        $dcontr = explode('-', @$contract['date']);
        @$dcontr = $dcontr[1] . $dcontr[2] . utf8_substr($dcontr[0], 2, 2) . '/' . $contract['number'];
        $SUB_PAY_EXECUTE = str_replace("[CONTRACT]", $dcontr, $SUB_PAY_EXECUTE);
    }

    if (empty($curr)) {
        $curr = $ORDER['curr'];
    }
    if ($order_id) {
        $query = se_db_query("SELECT `count`,`price`,`discount` FROM  shop_tovarorder WHERE id_order='$order_id';");
        $discount = 0;
        $rozn = 0;
        while (@$res = se_db_fetch_array($query)) {
            $discount += round(se_MoneyConvert($res['discount'], $ORDER['curr'], $curr), 2) * $res['count'];
        }
        $summ = round(se_MoneyConvert($ORDER['price_tovar'] + $ORDER['delivery_payee'] - $ORDER['discount'], $ORDER['curr'], $curr), 2) - $discount; //-$ORDER['discount']
        $fullsumm = round(se_MoneyConvert($ORDER['price_tovar'], $ORDER['curr'], $curr), 2);
        $delivery = round(se_MoneyConvert($ORDER['delivery_payee'], $ORDER['curr'], $curr), 2);

        $discount += round(se_money_convert($ORDER['discount'], $ORDER['curr'], $curr), 2);
        $summmod = explode('.', str_replace(',', '.', $summ));
        $array_change = array(
            'ORDER_DISCOUNT' => se_formatMoney($discount, $curr),
            'SHOP_ORDER_DISCOUNT' => se_formatMoney($discount, $curr),
            'ORDER_SUMMA' => se_formatMoney($summ, $curr),
            'SHOP_ORDER_SUMM' => se_formatMoney($fullsumm, $curr),
            'ORDER_DELIVERY' => $delivery,
            'SHOP_ORDER_DEVILERY' => se_formatMoney($delivery, $curr),
            'SHOP_ORDER_DELIVERY' => se_formatMoney($delivery, $curr),
            'SHOP_ORDER_TOTAL' => se_formatMoney($summ + $delivery, $curr),
            'ORDER_SUMM_NOTAX' => se_formatMoney($summ - $NDS / (100 + $NDS) * $summ, $curr),
            'ORDER.SUMM_NOTAX' => str_replace(',', '.', ($summ - $NDS / (100 + $NDS) * $summ)),
            'ORDER_SUMM_WITH_TAX' => se_formatMoney($summ + $NDS / 100 * $summ, $curr),
            'ORDER.SUMM_WITH_TAX' => str_replace(',', '.', ($summ + $NDS / 100 * $summ)),
            'ORDER_SUMM_TAX_EXT' => se_formatMoney($NDS / 100 * $summ, $curr),
            'ORDER.SUMM_TAX_EXT' => str_replace(',', '.', ($NDS / 100 * $summ)),
            'ORDER.SUMMA' => str_replace(',', '.', $summ),
            'ORDER.SUMMA_WHOL' => $summmod[0],
            'ORDER.SUMMA_FRAC' => $summmod[1],
            'ORDER.AMOUNT' => round($summ, 2) * 100,
            'ORDER_SUMMNDS' => se_formatMoney($NDS / (100 + $NDS) * $summ, $curr),
            'ORDER_SUMM_TAX' => se_formatMoney($NDS / (100 + $NDS) * $summ, $curr),
            'ORDER_TAX' => round($NDS),
            'ORDER.SUMM_TAX' => str_replace(',', '.', $NDS / (100 + $NDS) * $summ),
            'ORDER.ID' => $order_id,
            'SHOP_ORDER_NUM' => $order_id
        );
    }

    $SUB_PAY_EXECUTE = str_replace('[CURDATE]', date('Y-m-d'), $SUB_PAY_EXECUTE);

    if ($order_id) {
        $user_id = $ORDER['id_author'];
    } else if (!$user_id && function_exists('seUserId')) {
        $user_id = seUserId();
    }

    if (!empty($user_id)) {
        $author = se_db_fields_item(
            "person",
            "id={$user_id}",
            "`reg_date` as `regdate`,`last_name` as `lastname`,`doc_ser`,`doc_num`,`doc_registr`, `addr` as `fizadres`,
	    `first_name` as `firstname`,`sec_name` as `secname`, `id`, `email` as `useremail`"
        );

        foreach ($author as $k => $v) {
            $array_change['USER.' . strtoupper($k)] = trim(stripslashes($v));
        }

        $SUB_PAY_EXECUTE = str_replace(
            array('[CLIENTNAME]', '[NAMECLIENT]'),
            trim($author['lastname'] . " " . $author['firstname'] . " " . $author['secname']),
            $SUB_PAY_EXECUTE
        );
        $query = se_db_query("SELECT `rekv_code`,`value` FROM user_rekv
			WHERE (id_author={$user_id}) AND (lang='$lang')");
        while ($line = se_db_fetch_array($query)) {
            $array_change['USER.' . strtoupper($line[0])] = $line[1];
        }
        // Таблица user_urid
        $user = se_db_fields_item("user_urid", "id={$user_id}", "company,director,posthead,bookkeeper,uradres,tel,fax");
        if (!empty($user)) foreach ($user as $k => $v) $array_change['USER.' . strtoupper($k)] = stripslashes($v);
    }
    if (!empty($array_change))
        foreach ($array_change as $k => $v) {
            while (preg_match("/\[" . $k . "]/", $SUB_PAY_EXECUTE)) {
                $SUB_PAY_EXECUTE = str_replace("[{$k}]", @$v, $SUB_PAY_EXECUTE);
            }
        }

    $nameuser = se_db_fields_item('person', 'id=' . $user_id, "concat_sw(' ',last_name,first_name,sec_name)");
    $SUB_PAY_EXECUTE = str_replace('[USERLOGIN]', se_db_fields_item('se_user', 'id=' . $user_id, 'username'), $SUB_PAY_EXECUTE);
    $SUB_PAY_EXECUTE = str_replace('[USERNAME]', $nameuser, $SUB_PAY_EXECUTE);

    // Таблица bank_accounts
    if ($FP) {
        $fpid = se_db_fields_item("shop_payment", "id=$FP", 'name_payment');
        $array_change['PAYMENT.NAME'] = $fpid;
        $array_change['PAYMENT.ID'] = $FP;
    } else $array_change['PAYMENT.NAME'] = 'Лицевой счет'; //Personal account';
    $query = se_db_query("select codename,value FROM bank_accounts WHERE id_payment IN (SELECT id FROM shop_payment WHERE shop_payment.lang='$lang');");
    while ($payment = se_db_fetch_array($query)) {
        $array_change['PAYMENT.' . strtoupper($payment[0])] = $payment[1];
    }

    if (!empty($array_change))
        foreach ($array_change as $k => $v) {
            $SUB_PAY_EXECUTE = str_replace("[" . $k . "]", $v, $SUB_PAY_EXECUTE);
        }

    if (preg_match("/\<DELIVERY\>([\w\W]{1,})\<\/DELIVERY\>/i", $SUB_PAY_EXECUTE, $res_math)) {
        if (!($ORDER['delivery_payee'] > 0)) $res_math[1] = '';
        $SUB_PAY_EXECUTE = str_replace($res_math[0], $res_math[1], $SUB_PAY_EXECUTE);
    }

    if (strpos($SUB_PAY_EXECUTE, '[SHOP_ORDER_VALUE_LIST]') !== false) {
        $value_list = '<table border=0 cellpadding=3 cellspacing=1>
        <tr>
          <td>Num</td><td>Name</td><td>Price</td><td>Discount</td><td>Count</td><td>Total</td>
        </tr>
        <SHOPLIST>
        <tr>
          <td>[SHOPLIST.ITEM]</td><td>[SHOPLIST.NAME]</td>
          <td>[SHOPLIST.PRICE]</td><td>[SHOPLIST.DISCOUNT]</td>
          <td>[SHOPLIST.COUNT]</td><td>[SHOPLIST.SUMMA]</td>
        </tr>
        </SHOPLIST></table>';
        $SUB_PAY_EXECUTE = str_replace('[SHOP_ORDER_VALUE_LIST]', $value_list, $SUB_PAY_EXECUTE);
    }


    if ($order_id && preg_match("/\<SHOPLIST\>([\w\W]{1,})\<\/SHOPLIST\>/i", $SUB_PAY_EXECUTE, $res_math)) {
        $SHOPLIST = "";
        $query = se_db_query("SELECT sp.name, st.`nameitem`, st.count,st.discount,st.price FROM shop_tovarorder st
			    LEFT OUTER JOIN shop_price sp ON (sp.id = st.id_price)
			    WHERE (`id_order`='$order_id')");
        $it = 0;
        while ($res = se_db_fetch_array($query)) {
            $LISTIT = $res_math[1];
            $it++;
            $LISTIT = str_replace("[SHOPLIST.ITEM]", $it, $LISTIT);
            //if (!empty($res['nameitem']) && !empty($res['name'])) $res['name'] = $res['name'].' ('.$res['nameitem'].')';
            if (!empty($res['nameitem'])) $res['name'] = $res['nameitem'];

            if (!empty($res['price'])) $res['price'] = se_MoneyConvert($res['price'], $ORDER['curr'], $curr);
            $discount = se_MoneyConvert($res['discount'], $ORDER['curr'], $curr);
            if (!empty($res['discount'])) $res['discount'] = se_formatMoney($discount, $curr);
            $res['summa'] = se_formatMoney(round($res['price'] - $discount, 2) * $res['count'], $curr);
            $res['price'] = se_formatMoney($res['price'], $curr);


            foreach ($res as $k => $v) {
                $LISTIT = str_replace("[SHOPLIST." . strtoupper($k) . "]", $v, $LISTIT);
            }
            $SHOPLIST .= $LISTIT;
        }
        if ($ORDER['delivery_payee'] > 0) $it++;


        $SUB_PAY_EXECUTE = str_replace("[ORDER.ITEMCOUNT]", $it, $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = str_replace($res_math[0], $SHOPLIST, $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = se_macrocomands($SUB_PAY_EXECUTE, $FP, $order_id, $user_id);
    }



    while (preg_match("/\[FORMATDATE\,(.+?)\,(.+?)\]/s", $SUB_PAY_EXECUTE, $res_math)) {
        $res_ = explode('-', $res_math[1]);
        $res = str_replace("'", '', $res_math[2]);
        if (strpos($res, 'ms') !== false) {
            @$month = $smonth[round($res_[1]) - 1];
            $res = str_replace('ms', $month, $res);
        }
        $res = str_replace('m', $res_[1], $res);
        $res = str_replace('d', $res_[2], $res);
        $res = str_replace('y', utf8_substr($res_[0], 2, 2), $res);
        $res = str_replace('Y', $res_[0], $res);
        $SUB_PAY_EXECUTE = str_replace($res_math[0], $res, $SUB_PAY_EXECUTE);
    }


    while (preg_match("/\[STR_SUMM\,(.+?)\]/i", $SUB_PAY_EXECUTE, $math)) {
        $math[1] = str_replace("'", '', $math[1]);
        $SUB_PAY_EXECUTE = preg_replace("/\[STR_SUMM\,(.+?)\]/i", se_summtostring($math[1]), $SUB_PAY_EXECUTE);
    }

    while (preg_match("/MD5\(\"(.+?)\"\)/iu", $SUB_PAY_EXECUTE, $res_math)) {
        $res_ = $res_math[1];
        $SUB_PAY_EXECUTE = str_replace($res_math[0], md5($res_), $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = se_macrocomands($SUB_PAY_EXECUTE, $FP, $order_id, $user_id);
    }

    while (preg_match("/DECODE\(\"(.+?)\"\)/iu", $SUB_PAY_EXECUTE, $res_math)) {
        $SUB_PAY_EXECUTE = str_replace($res_math[0], urldecode($res_math[1]), $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = se_macrocomands($SUB_PAY_EXECUTE, $FP, $order_id, $user_id);
    }

    while (preg_match("/DECODE_CP1251\(\"(.+?)\"\)/iu", $SUB_PAY_EXECUTE, $res_math)) {
        $SUB_PAY_EXECUTE = str_replace($res_math[0], iconv('CP1251', 'UTF-8', urldecode($res_math[1])), $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = se_macrocomands($SUB_PAY_EXECUTE, $FP, $order_id, $user_id);
    }


    while (preg_match("/BASE64ENCODE\((.+?)\)/iu", $SUB_PAY_EXECUTE, $res_math)) {
        $SUB_PAY_EXECUTE = str_replace($res_math[0],  base64_encode($res_math[1]), $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = se_macrocomands($SUB_PAY_EXECUTE, $FP, $order_id, $user_id);
    }

    while (preg_match("/BASE64DECODE\((.+?)\)/iu", $SUB_PAY_EXECUTE, $res_math)) {
        $SUB_PAY_EXECUTE = str_replace($res_math[0],  base64_decode($res_math[1]), $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = se_macrocomands($SUB_PAY_EXECUTE, $FP, $order_id, $user_id);
    }

    while (preg_match("/ENCODE\(\"(.+?)\"\)/iu", $SUB_PAY_EXECUTE, $res_math)) {
        $SUB_PAY_EXECUTE = str_replace($res_math[0], urlencode($res_math[1]), $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = se_macrocomands($SUB_PAY_EXECUTE, $FP, $order_id, $user_id);
    }

    while (preg_match("/ENCODE_CP1251\(\"(.+?)\"\)/iu", $SUB_PAY_EXECUTE, $res_math)) {
        $SUB_PAY_EXECUTE = str_replace($res_math[0], urlencode(iconv('UTF-8', 'CP1251', $res_math[1])), $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = se_macrocomands($SUB_PAY_EXECUTE, $FP, $order_id, $user_id);
    }

    while (preg_match("/SAMETEXT\(\"(.+?)\"\)/i", $SUB_PAY_EXECUTE, $res_math)) {
        $res_ = explode('","', $res_math[1]);
        if (mb_strtoupper($res_[0], 'UTF-8') == mb_strtoupper($res_[1], 'UTF-8')) {
            $res_ = 1;
        } else {
            $res_ = 0;
        }
        $SUB_PAY_EXECUTE = str_replace($res_math[0], $res_, $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = se_macrocomands($SUB_PAY_EXECUTE, $FP, $order_id, $user_id);
    }



    $SUB_PAY_EXECUTE = preg_replace("/\[USER\.(.+?)\]/i", "", $SUB_PAY_EXECUTE);

    if (function_exists('seMultiDir')) {
        $SUB_PAY_EXECUTE = str_replace('[MERCHANT_RESULT]', _HTTP_ . $_SERVER['HTTP_HOST'] . seMultiDir() . '/' . $_page . '/merchant/result/' . $order_id . '/' . $FP . '/?PHPSESSID=' . session_id(), $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = str_replace('[MERCHANT_SUCCESS]', _HTTP_ . $_SERVER['HTTP_HOST'] . seMultiDir() . '/' . $_page . '/merchant/success/' . $order_id . '/', $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = str_replace('[MERCHANT_FAIL]', _HTTP_ . $_SERVER['HTTP_HOST'] . seMultiDir() . '/' . $_page . '/merchant/fail/' . $order_id . '/', $SUB_PAY_EXECUTE);
    }

    $SUB_PAY_EXECUTE = str_replace('[THISNAMESITE]', $_SERVER['HTTP_HOST'], $SUB_PAY_EXECUTE);

    while (strpos($SUB_PAY_EXECUTE, '<php>') !== false) {
        list(, $res) = explode('<php>', $SUB_PAY_EXECUTE);
        if (strpos($res, '</php>') === false) break;
        list($res) = explode('</php>', $res);
        ob_start(); // пФЛТЩЧБЕН ВХЖЕТЙЪБГЙА 
        eval(str_replace('&gt;', '>', $res));
        $res_ = ob_get_contents(); // юЙФБЕН ЙЪ ВХЖЕТБ УПДЕТЦБОЙЕ ЧЛМАЮБЕНПЗП ЖБКМБ
        ob_end_clean(); // пЮЙЭБЕН ВХЖЕТ
        $SUB_PAY_EXECUTE = str_replace('<php>' . $res . '</php>', $res_, $SUB_PAY_EXECUTE);
    }
    while (preg_match("/\<\?php(.+?)\?\>/i", $SUB_PAY_EXECUTE, $res_math)) {
        ob_start(); // пФЛТЩЧБЕН ВХЖЕТЙЪБГЙА 
        eval($res_math[1]);
        $res_ = ob_get_contents(); // юЙФБЕН ЙЪ ВХЖЕТБ УПДЕТЦБОЙЕ ЧЛМАЮБЕНПЗП ЖБКМБ
        ob_end_clean(); // пЮЙЭБЕН ВХЖЕТ
        $SUB_PAY_EXECUTE = str_replace($res_math[0], $res_, $SUB_PAY_EXECUTE);
    }

    $SUB_PAY_EXECUTE = preg_replace("/\[(.+?)\]/i", "", $SUB_PAY_EXECUTE);

    while (preg_match("/@if\((.*?)\)\{(.+?)\}/s", $SUB_PAY_EXECUTE, $mach)) {
        if ((trim($mach[1]) == '') or ($mach[1] == '0') or ($mach[1] == 'false') or ($mach[1] == 'no')) $mach[2] = '';
        if (strpos($mach[1], '==')) {
            $rr = explode('==', $mach[1]);
            if ($rr[0] != $rr[1]) $mach[2] = '';
        }
        if (strpos($mach[1], '!=')) {
            $rr = explode('!=', $mach[1]);
            if ($rr[0] == $rr[1]) $mach[2] = '';
        }
        $SUB_PAY_EXECUTE = preg_replace("/@if\((.*?)\)\{(.+?)\}/s", $mach[2], $SUB_PAY_EXECUTE);
    }
    while (preg_match("/@notif\((.*?)\)\{(.+?)\}/s", $SUB_PAY_EXECUTE, $mach)) {
        if ((trim($mach[1]) != '') or ($mach[1] == '1') or ($mach[1] == 'true') or ($mach[1] == 'yes')) $mach[2] = '';
        if (strpos($mach[1], '!=')) {
            $rr = explode('!=', $mach[1]);
            if ($rr[0] == $rr[1]) $mach[2] = '';
        }
        if (strpos($mach[1], '==')) {
            $rr = explode('==', $mach[1]);
            if ($rr[0] != $rr[1]) $mach[2] = '';
        }
        $SUB_PAY_EXECUTE = preg_replace("/@notif\((.*?)\)\{(.+?)\}/s", $mach[2], $SUB_PAY_EXECUTE);
    }

    while (preg_match("/\bSUM\((.+?)\)/i", $SUB_PAY_EXECUTE, $res_math)) {
        $res_ = explode(',', $res_math[1]);
        $sumres = 0;
        if (!empty($res_))
            foreach ($res_ as $sumres_) {
                $sumres += str_replace('"', '', $sumres_);
            }
        if ($res_[0] == $res_[1]) $res_ = 1;
        else $res_ = 0;
        $SUB_PAY_EXECUTE = preg_replace("/\bSUM\((.+?)\)/i", $sumres, $SUB_PAY_EXECUTE);
        $SUB_PAY_EXECUTE = se_macrocomands($SUB_PAY_EXECUTE, $FP, $order_id);
    }

    while (preg_match("/\bCYBERSTRING\((.+?)\)/i", $SUB_PAY_EXECUTE, $res_math)) {
        $sumres = $res_math[1];
        $SUB_PAY_EXECUTE = preg_replace("/\bCYBERSTRING\((.+?)\)/i", $sumres, $SUB_PAY_EXECUTE);
    }

    unset($array_change);
    //$_SESSION['THISCURR'] = $curr;

    return $SUB_PAY_EXECUTE;
}
