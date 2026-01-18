<?php

// функция удаления в массиве пустых элементов
if (!function_exists('Clear_array_empty')){
function Clear_array_empty($array)
{
$ret_arr = array();
foreach($array as $val)
{
    if (!empty($val))
    {
        $ret_arr[] = trim($val);
    }
}
return $ret_arr;
}
}
?>