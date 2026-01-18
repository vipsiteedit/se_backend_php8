<?php
function string_summ($summa)
{
se_db_connect();
$nf=array('zero','one','two','three','four','five','six','seven','eight','nine');
$sel="zero,one,two,three,four,five,six,seven,eight,nine";
$reg=array('edin','dec','des','sot','mel','thou','mill','wh','fr');
foreach($reg as $r) $d[$r]=se_db_fields_item('spr_numbers',"registr='$r'",$sel);

 $summa=str_replace(' ','',str_replace(',','.',$summa));
 $des=explode(".",$summa);

 $c=strlen($des[0]);
 for ($i=1; $i<=$c; $i++) $nums[$i]=substr($des[0],$c-$i,1);
 $rez="";
 if (!empty($nums[7])) @$rez.=$d['mill'][$nums[7]]." ";
 if (!empty($nums[6])) @$rez.=$d['sot'][$nums[6]]." ";
 if ((!empty($nums[5]))&&(@$nums[5]!=1)) @$rez.=$d['dec'][$nums[5]]." ";
 if ((!empty($nums[5]))&&(@$nums[5]==1)) @$rez.=$d['des'][$nums[4]]." ".$d['thou'][0]." ";
 if ((!empty($nums[4]))&&(@$nums[5]!=1))  @$rez.=$d['mel'][$nums[4]]." ".$d['thou'][$nums[4]]." ";
 if (!empty($nums[3])) @$rez.=$d['sot'][$nums[3]]." ";
 if ((!empty($nums[2]))&&($nums[2]<>1)) @$rez.=$d['dec'][$nums[2]]." ";
 if ((!empty($nums[2]))&&($nums[2]==1)) @$rez.=$d['des'][$nums[1]]." ";
 if ((!empty($nums[1]))&&($nums[2]!=1)) @$rez.=$d['edin'][$nums[1]]." ";
 if (!empty($rez)) $rez=strtoupper($rez).$d['wh'][0]." ";
 $kop=@$des[1];
 while (strlen($kop)<2) $kop.="0";
   $rez.=$kop." ".$d['fr'][0];
   
   $rez='<SPAN style="Text-transform:uppercase;">'.substr($rez,0,1).'</SPAN>'.substr($rez,1,strlen($rez)-1);
   return($rez);
};


function macrocomands($SUB_PAY_EXECUTE, $FP, $order, $curr='RUR', $lang="rus"){
global $_page,$razdel,$THISCURR;
se_db_connect();
if ($lang=='rus')
   $smonth=array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
else
   $smonth=array('January',' February','March','April','May','June','July','August','September','October','November','December');

// Заполняю переменными
  $curr="";
  $SUB_PAY_EXECUTE=str_replace('[RAZDEL]',$razdel,$SUB_PAY_EXECUTE);
  $SUB_PAY_EXECUTE=str_replace('[PAGENAME]',$_page,$SUB_PAY_EXECUTE);

  While (preg_match("/\[POST\.(\w{1,}\:\w{1,})\]/i",$SUB_PAY_EXECUTE,$res_math)){
    $res_=$res_math[1];
    $def=explode(':',$res_);
    if (isset($_POST[strtolower($def[0])])) $res_=htmlspecialchars(stripslashes(@$_POST[strtolower($def[0])]));
    else $res_=@$def[1];
    $SUB_PAY_EXECUTE=str_replace($res_math[0],strtoupper($res_),$SUB_PAY_EXECUTE);
  }

  while (preg_match("/\[SELECTED\:(\w{1,})\]/i",$SUB_PAY_EXECUTE,$res_math)){
    if (strtolower($res_)==strtolower($res_math[1]))
       $SUB_PAY_EXECUTE=str_replace($res_math[0],"selected",$SUB_PAY_EXECUTE);
    else
    $SUB_PAY_EXECUTE=str_replace($res_math[0],"",$SUB_PAY_EXECUTE);
  }

  while (preg_match("/\[IF\((.+?)\)\]/m",$SUB_PAY_EXECUTE,$res_math)){
     $def=explode(':',$res_math[1]);
     $sel=explode(',',$def[0]);
     $res=@$def[1];
     foreach ($sel as $if) {
       $if=explode('=',$if);
       if (strtolower($res_)==strtolower($if[1])) $res=$if[0];
     }
    $SUB_PAY_EXECUTE=str_replace($res_math[0],$res,$SUB_PAY_EXECUTE);
  }

  while (preg_match("/\[SETCURRENCY\:(\w{1,})\]/m",$SUB_PAY_EXECUTE,$res_math)){
     if (isset($res_math[1])) $curr=$res_math[1];
    $SUB_PAY_EXECUTE=str_replace($res_math[0],"",$SUB_PAY_EXECUTE);
  }

  While (preg_match("/\[POST\.(\w{1,})\]/i",$SUB_PAY_EXECUTE,$res_math)){
    $res_=$res_math[1];
    if (isset($_POST[$res_])) $res_=htmlspecialchars(stripslashes(@$_POST[$res_])); else $res_="";
    $SUB_PAY_EXECUTE=str_replace($res_math[0],$res_,$SUB_PAY_EXECUTE);
  }

  While (preg_match("/\[GET\.(\w{1,})\]/i",$SUB_PAY_EXECUTE,$res_math)){
    $res_=$res_math[1];
    if (isset($_POST[$res_])) $res_=htmlspecialchars(stripslashes(@$_GET[$res_])); else $res_="";
    $SUB_PAY_EXECUTE=str_replace($res_math[0],$res_,$SUB_PAY_EXECUTE);
  }

 $maxaccount=se_db_fields_item("shop_order","1","MAX(account)")+1;
se_db_query("UPDATE shop_order SET `account`='$maxaccount' WHERE (id='$order') AND (`account`=0)");

$query = se_db_query("SELECT shop_order.id_author, shop_order.date_order, shop_order.date_payee, shop_order.discount,(select sa.account from shop_account sa where sa.id_order=shop_order.id) as account,
shop_order.curr, shop_order.status, shop_order.delivery_payee, (SELECT dl.name FROM shop_deliverytype dl WHERE dl.id=shop_order.delivery_type) as delivery_name, shop_order.delivery_status,
shop_order.delivery_date,(SELECT SUM((shop_tovarorder.price)*shop_tovarorder.count)
FROM shop_tovarorder
WHERE shop_tovarorder.id_order=shop_order.id) AS `price_tovar`
FROM `shop_order`
INNER JOIN `shop_tovarorder` ON shop_order.id = shop_tovarorder.id_order
WHERE shop_order.id = '$order'");

$ORDER=se_db_fetch_array($query);

// Таблица MAIN
$main=se_db_fields_item("main","lang='$lang'","*");
$NDS=$main['nds'];
if (!empty($main))
foreach ($main as $k => $v)
        $SUB_PAY_EXECUTE = str_replace("[MAIN.".strtoupper(@$k)."]", trim(@$v), $SUB_PAY_EXECUTE);

if (!empty($ORDER))
foreach ($ORDER as $k => $v) {
        $SUB_PAY_EXECUTE = str_replace("[ORDER.".strtoupper(@$k)."]", trim(@$v), $SUB_PAY_EXECUTE);
}
// Добавляем адрес доставки
$query = se_db_query("SELECT telnumber,email,calltime,address,postindex FROM shop_delivery WHERE id_order='$order'");
$ORDERADDR=se_db_fetch_array($query);
if (!empty($ORDERADDR))
foreach ($ORDERADDR as $k => $v) {
        if (isset($k))
        $SUB_PAY_EXECUTE = str_replace("[ORDER.".strtoupper($k)."]", trim(@$v), $SUB_PAY_EXECUTE);
}



if (strpos($SUB_PAY_EXECUTE,"[CONTRACT]")!==false) {
  $query = se_db_query("SELECT * FROM `shop_contract` WHERE id_order = '$order'");
  $contract=se_db_fetch_array($query);
  $dcontr=explode('-',@$contract['date']);
  @$dcontr=$dcontr[1].$dcontr[2].substr($dcontr[0],2,2).'/'.$contract['number'];
  $SUB_PAY_EXECUTE = str_replace("[CONTRACT]", $dcontr, $SUB_PAY_EXECUTE);
}

if (empty($curr)) $curr=@$ORDER['curr'];
    $query = se_db_query("SELECT `count`,`price`,`discount` FROM  shop_tovarorder WHERE id_order='$order';");
    $discount=0;
    $rozn=0;
    while (@$res=se_db_fetch_array($query)) {
       $discount+=round(se_MoneyConvert($res['discount'],@$ORDER['curr'],$curr),2) *$res['count'];
     }
     //echo $discount."!";
    // $discount=$discount-$delivery;
$summ=round(se_MoneyConvert(@$ORDER['price_tovar']+@$ORDER['delivery_payee']-@$ORDER['discount'],@$ORDER['curr'],$curr),2)-$discount; //-$ORDER['discount']
$delivery=round(se_MoneyConvert(@$ORDER['delivery_payee'],$ORDER['curr'],$curr),2);

$discount+=round(se_money_convert(@$ORDER['discount'],@$ORDER['curr'],$curr),2);

$array_change=array('ORDER_DISCOUNT'=>se_formatMoney($discount, $curr),
                    'ORDER_SUMMA'=>se_formatMoney($summ, $curr),
                    'ORDER_DELIVERY'=>$delivery,
                    'ORDER_SUMM_NOTAX'=>se_formatMoney($summ-$NDS/100*$summ, $curr),
                    'ORDER.SUMM_NOTAX'=>str_replace(',','.',($summ-$NDS/100*$summ)),
                    'ORDER.SUMMA'=>str_replace(',','.',$summ),
                    'ORDER.AMOUNT'=>round($summ,2)*100,
                    'ORDER_SUMMNDS'=>se_formatMoney($NDS/100*$summ,$curr),
                    'ORDER_SUMM_TAX'=>se_formatMoney($NDS/100*$summ,$curr),
                    'ORDER.SUMM_TAX'=>str_replace(',','.',$NDS/100*$summ),
                    'ORDER.ID'=>$order,
                    'CURDATE'=>date('Y-m-d'));


foreach ($array_change as $k => $v)
    while (preg_match("/\[".$k."]/",$SUB_PAY_EXECUTE))
        $SUB_PAY_EXECUTE = str_replace("[".$k."]", @$v, $SUB_PAY_EXECUTE);


// Таблица user_urid
$user=se_db_fields_item("user_urid","id_author=".@$ORDER['id_author'],"*");
if (!empty($user)) foreach ($user as $k => $v) $array_change['USER.'.strtoupper($k)]=$v;

$query=se_db_query("SELECT `rekv_code`,`value` FROM user_rekv
WHERE (id_author=".@$ORDER['id_author'].") AND (lang='$lang')");
while ($user=se_db_fetch_array($query))
  $array_change['USER.'.strtoupper($user[0])]=$user[1];

$author=se_db_fields_item("author","id=".@$ORDER['id_author'],
"a_reg_date as regdate,a_last_name as lastname,doc_ser,doc_num,doc_registr,a_first_name as firstname,a_sec_name as secname, id, a_email as useremail, addr");
if (!empty($author)) foreach ($author as $k => $v) $array_change['USER.'.strtoupper($k)]=trim($v);
 @$SUB_PAY_EXECUTE=str_replace('[CLIENTNAME]',trim($author['lastname']." ".$author['firstname']." ".$author['secname']),$SUB_PAY_EXECUTE);

// Таблица bank_accounts
$fpid=se_db_fields_item("shop_payment","id='$FP'",'name_payment');
  $array_change['PAYMENT.NAME']=@$fpid;

$query=se_db_query("select codename,value FROM bank_accounts WHERE id_payment IN (SELECT id FROM shop_payment WHERE shop_payment.lang='$lang');");
while ($payment=se_db_fetch_array($query))
  $array_change['PAYMENT.'.strtoupper($payment[0])]=$payment[1];


foreach ($array_change as $k => $v)
        $SUB_PAY_EXECUTE = str_replace("[".$k."]", $v, $SUB_PAY_EXECUTE);


  if (preg_match("/\<DELIVERY\>([\w\W]{1,})\<\/DELIVERY\>/i",$SUB_PAY_EXECUTE,$res_math)){
    if (!(@$ORDER['delivery_payee']>0)) $res_math[1]='';
    $SUB_PAY_EXECUTE=str_replace($res_math[0],$res_math[1],$SUB_PAY_EXECUTE);
  }

  if (preg_match("/\<SHOPLIST\>([\w\W]{1,})\<\/SHOPLIST\>/i",$SUB_PAY_EXECUTE,$res_math)){
    $SHOPLIST="";
    $query=se_db_query("SELECT sp.name, st.`nameitem`, st.count,st.discount,st.price FROM shop_tovarorder st
    LEFT OUTER JOIN shop_price sp ON (sp.id = st.id_price)
    WHERE (`id_order`='$order')");
    $it=0;
    while ($res=se_db_fetch_array($query)) {
       $LISTIT=$res_math[1];
       $it++;
       $LISTIT = str_replace("[SHOPLIST.ITEM]", $it, $LISTIT);
       if (!empty($res['nameitem']) && !empty($res['name'])) $res['name']=$res['name'].' ('.$res['nameitem'].')';
       if (!empty($res['nameitem']) && empty($res['name'])) $res['name']=$res['nameitem'];

       if (!empty($res['price'])) $res['price']=se_MoneyConvert($res['price'],$ORDER['curr'],$curr);
       if (!empty($res['discount'])) $res['discount']=se_formatMoney(se_MoneyConvert($res['discount'],$ORDER['curr'],$curr),$curr);
       $res['summa']=se_formatMoney(round($res['price'],2)*$res['count'],$curr);
       $res['price']=se_formatMoney($res['price'],$curr);


       foreach ($res as $k => $v) {
         $LISTIT = str_replace("[SHOPLIST.".strtoupper($k)."]", $v, $LISTIT);
       }
       $SHOPLIST.=$LISTIT;
    }
    if ($ORDER['delivery_payee']>0) $it++;
    $SUB_PAY_EXECUTE=str_replace("[ORDER.ITEMCOUNT]",$it,$SUB_PAY_EXECUTE);
    $SUB_PAY_EXECUTE=str_replace($res_math[0],$SHOPLIST,$SUB_PAY_EXECUTE);
    $SUB_PAY_EXECUTE=macrocomands($SUB_PAY_EXECUTE, $FP, $order, $curr, $lang);
  }

  While (preg_match("/MD5\(\"(.+?)\"\)/i",$SUB_PAY_EXECUTE,$res_math)){
    $res_=$res_math[1];
    $SUB_PAY_EXECUTE=str_replace($res_math[0],strtoupper(md5($res_)),$SUB_PAY_EXECUTE);
    $SUB_PAY_EXECUTE=macrocomands($SUB_PAY_EXECUTE, $FP, $order, $curr, $lang);
 }

  While (preg_match("/SAMETEXT\(\"(.+?)\"\)/i",$SUB_PAY_EXECUTE,$res_math)){
   /* $fp=se_fopen('/modules/fp.dat',"w+");
    fwrite($fp,$res_math[1]);
    fclose($fp);
  */
    $res_=explode('","',$res_math[1]);
    if ($res_[0]==$res_[1]) $res_=1; else $res_=0;
    $SUB_PAY_EXECUTE=str_replace($res_math[0],$res_,$SUB_PAY_EXECUTE);
    $SUB_PAY_EXECUTE=macrocomands($SUB_PAY_EXECUTE, $FP, $order, $curr, $lang);
    
  }

   While (preg_match("/\[FORMATDATE\,(.+?)\,(.+?)\]/s",$SUB_PAY_EXECUTE,$res_math)){
    $res_=explode('-',$res_math[1]);
    $res=str_replace("'",'',$res_math[2]);
     if (strpos($res,'ms')!==false) {
       @$month=$smonth[round($res_[1])-1];
       $res=str_replace('ms',$month,$res);
     };
     $res=str_replace('m',$res_[1],$res);
     $res=str_replace('d',$res_[2],$res);
     $res=str_replace('y',substr($res_[0],2,2),$res);
     $res=str_replace('Y',$res_[0],$res);
    $SUB_PAY_EXECUTE=str_replace($res_math[0],$res,$SUB_PAY_EXECUTE);
  }

  while (preg_match("/\[STR_SUMM\,(.+?)\]/i",$SUB_PAY_EXECUTE,$math)){
    $math[1]=str_replace("'",'',$math[1]);
    $SUB_PAY_EXECUTE=preg_replace("/\[STR_SUMM\,(.+?)\]/i",string_summ($math[1]),$SUB_PAY_EXECUTE);
  }
  $SUB_PAY_EXECUTE=preg_replace("/\[USER\.(.+?)\]/i","",$SUB_PAY_EXECUTE);
  $SUB_PAY_EXECUTE=str_replace('[THISNAMESITE]',$_SERVER['HTTP_HOST'],$SUB_PAY_EXECUTE);
  $SUB_PAY_EXECUTE=preg_replace("/\[(.+?)\]/i","",$SUB_PAY_EXECUTE);

  while (preg_match("/@if\((.*?)\)\{(.+?)\}/s",$SUB_PAY_EXECUTE,$mach)) {
    if ((trim($mach[1])=='') or ($mach[1]=='0') or ($mach[1]=='false') or ($mach[1]=='no')) $mach[2]='';
    if (strpos($mach[1],'==')) { $rr=explode('==',$mach[1]); if ($rr[0]!=$rr[1]) $mach[2]=''; }
    if (strpos($mach[1],'!=')) { $rr=explode('!=',$mach[1]); if ($rr[0]==$rr[1]) $mach[2]=''; }
    $SUB_PAY_EXECUTE=preg_replace("/@if\((.*?)\)\{(.+?)\}/s",$mach[2],$SUB_PAY_EXECUTE);
  }
  while (preg_match("/@notif\((.*?)\)\{(.+?)\}/s",$SUB_PAY_EXECUTE,$mach)) {
    if ((trim($mach[1])!='') or ($mach[1]=='1') or ($mach[1]=='true') or ($mach[1]=='yes')) $mach[2]='';
    if (strpos($mach[1],'!=')) { $rr=explode('!=',$mach[1]); if ($rr[0]==$rr[1]) $mach[2]=''; }
    if (strpos($mach[1],'==')) { $rr=explode('==',$mach[1]); if ($rr[0]!=$rr[1]) $mach[2]=''; }
    $SUB_PAY_EXECUTE=preg_replace("/@notif\((.*?)\)\{(.+?)\}/s",$mach[2],$SUB_PAY_EXECUTE);
  }

  While (preg_match("/\bSUM\((.+?)\)/i",$SUB_PAY_EXECUTE,$res_math)){
    $res_=explode(',',$res_math[1]);
    $sumres=0;
    if (!empty($res_))
    foreach($res_ as $sumres_) $sumres+=str_replace('"','',$sumres_);
    if ($res_[0]==$res_[1]) $res_=1; else $res_=0;
    $SUB_PAY_EXECUTE=preg_replace("/\bSUM\((.+?)\)/i",$sumres,$SUB_PAY_EXECUTE);
    $SUB_PAY_EXECUTE=macrocomands($SUB_PAY_EXECUTE, $FP, $order, $curr, $lang);
  }
  
  While (preg_match("/\bCYBERSTRING\((.+?)\)/i",$SUB_PAY_EXECUTE,$res_math)){
    $sumres=signature($res_math[1]);
    $SUB_PAY_EXECUTE=preg_replace("/\bCYBERSTRING\((.+?)\)/i",$sumres,$SUB_PAY_EXECUTE);
  }

unset($array_change);
$THISCURR=$curr;
return $SUB_PAY_EXECUTE;
}

if (!function_exists('se_shop_mailsend')) {
function se_shop_mailsend($mailtype, $email, $from, $email_from, $fp, $order, $curr='RUR', $language="rus") {
    $headers = "";
    $headers .= "From: $from <$email_from>\r\n";
    $headers .= "Return-path: $email_from\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";

    $mail_text = se_db_fetch_array(se_db_query("SELECT * FROM `shop_mail` WHERE (`lang`='".$language."')AND(`mailtype`='".$mailtype."') LIMIT 1;"), MYSQL_ASSOC);
    $mail_text['letter'] = str_replace("\r\n", "<br>", $mail_text['letter']);
    $mail_text['letter'] = macrocomands($mail_text['letter'], $fp, $order, $curr,$language);
    $mail_text['subject'] = macrocomands($mail_text['subject'], $fp, $order, $curr,$language);

    mail($email, $mail_text['subject'], $mail_text['letter'], $headers, '-f'.$email_from);
}
}
?>