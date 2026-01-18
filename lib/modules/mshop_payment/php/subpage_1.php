<?php

$_selMonth=se_db_input($_selMonth);
//$statusarr=explode(',',$section->parametrs->param32);

$montharr = explode(",", $section->parametrs->param31);
$_selMonth = getRequest('selMonth');
$datas = new seUserAccount();
$datas->select("DISTINCT DATE_FORMAT(date_payee,\"%m.%Y\") as `date`");
$datas->where("user_id IN (?)", seUserId());
$datas->orderby('date_payee');  
$datalist = $datas->getlist(-1);
unset($datas);
$selcount = count($datalist);
$i = 1;
foreach($datalist as $line) {
    $line['select'] = ($_selMonth==$line['date'] || (empty($_selMonth) && ($i==$selcount))) ? 'SELECTED' : '';
      //echo $line['date'].'<br>';
    list($month,$year) = explode('.', $line['date']);
    $line['name'] = trim($year . ' ' . $montharr[$month - 1]);
    $__data->setItemList($section, 'periods', $line);
    if (empty($_selMonth) && ($i==$selcount)) {
        $_selMonth = $line['date'];
    }
    $i++;
}
$summ = 0;
list($month,$year) = explode('.', $_selMonth);
$indate = $year.'-'.$month.'-01';
$useraccount = new seUserAccount();
$useraccount->select("in_payee,out_payee,entbalanse, curr, date_payee, operation");
$useraccount->where('user_id=?', seUserId());
$useraccount->andwhere("((date_payee<'?') OR ((date_payee LIKE '? %') AND (operation=0)))", $indate); //
$useraccount->orderby('date_payee', 1);
$acclisty = $useraccount->getList();

foreach ($acclisty as $line) {
    $k = se_Money_Convert(1, $line['curr'], $basecurr, $line['date_payee']); 
    if (intval($line['operation']) == 0) {
        $summ += ($line['entbalanse'] * $k);
        break;
    } else {
        $summ += ($line['in_payee']-$line['out_payee']) * $k;
    }
}      
unset($useraccount, $acclisty);
 $result = $summ_account = $summ;
$useraccount = new seUserAccount();
$useraccount->select("ua.*,ao.name");
$useraccount->leftjoin('se_account_operation ao', 'ua.operation = ao.operation_id');
$useraccount->where("ua.user_id IN (?)", seUserId());
if (!empty($_selMonth)) {
    $useraccount->andwhere("DATE_FORMAT(ua.`date_payee`,\"%m.%Y\")='?'", $_selMonth);
}
$useraccount->andwhere("1 ORDER BY date_payee,operation,order_id ASC");
$paylist = $useraccount->getList();

$summ_account = se_FormatMoney($summ_account, $basecurr);
$po = new seTable('payee_operation');
$operation = $po->getlist();
unset($po);
$fl = false;
foreach ($paylist as $line) {
    $item = array();
    $line['style'] = ($fl = !$fl) ? 'tableRowOdd' : 'tableRowEven';
    $result += round($line['in_payee'] - $line['out_payee'], 2);
    $line['in_payee'] = se_FormatMoney($line['in_payee'], $basecurr);//se_MoneyConvert($line['in_payee'],$line['curr'], $basecurr, $line['date_payee']);
    $line['out_payee'] = se_FormatMoney($line['out_payee'], $basecurr);// se_MoneyConvert($line['out_payee'],$line['curr'], $basecurr, $line['date_payee']);
           //$line['entbalanse'] = se_MoneyConvert($line['entbalanse'],$line['curr'], $basecurr, $line['date_payee']);
    $line['date_payee'] = date('d.m.Y', strtotime($line['date_payee']));
    $line['operation'] = $line['name'];
    $line['result'] = se_FormatMoney($result, $basecurr);
    $__data->setItemList($section, 'pays', $line);
}

?>