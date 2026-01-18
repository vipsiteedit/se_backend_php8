<?php

if (!function_exists('myFormatMoneySp')) {
    function myFormatMoneySp($section, $money, $name) 
    {
        $tbl = new seTable('money_title', 'mt');
        $tbl->where("mt.name = '$name'");
        $tbl->fetchOne();

        if ($section->parametrs->param16 == 'Y') // Округлять цену до целых
            $money = round($money);
                    
        $submoney = $money - intval($money);
        if ($submoney > 0) {
            return $tbl->name_front . ' ' . number_format($money, 2, '.', ' ') . ' ' . $tbl->name_flang;
        } else {
            return $tbl->name_front . ' ' . number_format($money, 0, '', ' ') . ' ' . $tbl->name_flang;
        }
    }
}
?>