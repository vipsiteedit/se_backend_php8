<?php

 //формирование таблицы заказов
if (!function_exists('getOrderList')){
    function getOrderList(){
        $incart = $_SESSION['mshopcart'];
        $text = '<table border="0">';
        //шапка
            $text .= '<tr>';
                $text .= '<td>Код<td>';
                $text .= '<td>Наименование<td>';
                $text .= '<td>Цена<td>';
                $text .= '<td>Кол-во<td>';
                $text .= '<td>Сумма<td>';
            $text .= '</tr>';
        //основная часть
        foreach($incart as $key=>$value){
            $text .= '<tr>';          
                $text .= '<td>'.$value['article'].'<td>';
                $text .= '<td>'.$value['name'].'<td>';
                $text .= '<td>'.$value['price'].'<td>';
                $text .= '<td>'.$value['count'].'<td>';
                $text .= '<td>'.$value['count']*$value['price'].'<td>';
            $text .= '</tr>';
        }
        $text .= '</table>';
        
        return $text;        
    }
}

?>
