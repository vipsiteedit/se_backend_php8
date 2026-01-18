<?php

if (!function_exists('getCompareParams')){
function getCompareParams($compare){
    $param = array();
    $i = 0;                                 
    $alltitle = array();                    //хранитель всех уникальных параметров
    foreach($compare as $line){             //цикл по товару
        $list = array();
        $tlb = new seTable('shop_price_param','spp');   
        $tlb->select("spp.id, spp.price_id, spp.param_id, spp.value, (SELECT nameparam FROM shop_param WHERE id=spp.param_id) as title"); 
        $tlb->where("`price_id`='$line'");
        $tlb->andwhere("`parent_id` IS NULL");
        $tlb->orderby('title');
        $list = $tlb->getList();
        unset($tlb);
        if(empty($list)){                   //если у товара нет доп параметров
            $param[$i]['NOPARAM'] = 'NOPARAM';
        } 
        else {                            //иначе парсим
            $title = '';                    //дубликат названия параметра
            $flag = 0;                      //говорит, что надо добавить элемент в хранитель
            foreach($list as $item){        //цикл по найденым строкам из БД товара
                if($item['title']==$title){ //если название параметра совпало с дубликатом
                    $param[$i][$item['title']] = $param[$i][$item['title']].','.$item['value'];
                } 
                else {                    //иначе создаем новый параметр
                    $title = $item['title'];                            
                    $param[$i][$item['title']] = $item['value'];
                    if(empty($alltitle)){   //если хранитель пустой, то добавляем элемент
                        $alltitle[] = $title;                                   
                    } else {                            //иначе                             
                        foreach($alltitle as $name){    //цикл по хранителю                    
                            if($title==$name)           //если в хранителе есть такой параметр
                                $flag = 1;              //говорим, что ненадо добавлять элемент в хранитель
                        }
                        if($flag==0)                    //надо добавить элемент в хранитель
                            $alltitle[] = $title;
                    }
                }
            }
        }
        $i++;
//        $list[] = $tlb->getSql();
    }    
    return array($param, $alltitle);
}
}

function getCompareList() {
$test_compare = new seTable('shop_modifications_feature', 'smf');
$test_compare->select("DISTINCT 
    smf.id_price,
    sf.id AS fid,
    sfg.id AS gid,
    sfg.name AS gname,
    sf.name AS fname,
    sf.type,
    GROUP_CONCAT(DISTINCT CASE    
                    WHEN (sf.type = 'list' OR sf.type = 'colorlist') THEN (SELECT sfvl.value FROM shop_feature_value_list sfvl WHERE sfvl.id = smf.id_value)
                    WHEN (sf.type = 'number') THEN smf.value_number
                    WHEN (sf.type = 'bool') THEN smf.value_bool
                    WHEN (sf.type = 'string') THEN smf.value_string 
                    ELSE NULL
                END SEPARATOR ', ') AS value");
$test_compare->innerJoin('shop_feature sf', 'sf.id=smf.id_feature');
$test_compare->leftJoin('shop_feature_group sfg', 'sf.id_feature_group=sfg.id');
$test_compare->where('smf.id_price IN (?)', join(', ', $_SESSION['compare']));
$test_compare->andWhere('smf.id_modification IS NULL');
$test_compare->groupBy('smf.id_price, sf.id');
$test_compare->orderBy('sfg.sort', 0);
$test_compare->addOrderBy('sf.sort', 0);
$test_list = $test_compare->getList();

$empty_list = array();
foreach ($_SESSION['compare'] as $line){
    $empty_list[$line] = null;
}

$copmpares = array();
$allcount = count($empty_list);

foreach($test_list as $line){

    if (!isset($copmpares['g_' . $line['gid']])){
        $copmpares['g_' . $line['gid']] = array(
            'name' => $line['gname'],
            'group' => true,
            'count' => $allcount + 1
        );   
    }
    if (!isset($copmpares[$line['fid']])) {
        $copmpares[$line['fid']] = array(
            'name' => $line['fname'], 
            'type' => $line['type'],
            'diff' => 'r', 
            'values' => $empty_list, 
            'count' => 0
        );
    } 
    $copmpares[$line['fid']]['count']++;
    $copmpares[$line['fid']]['values'][$line['id_price']] = $line['value']; 
    if ($copmpares[$line['fid']]['count'] == $allcount && count(array_unique($copmpares[$line['fid']]['values'])) == 1)
         $copmpares[$line['fid']]['diff'] = 'c';        
}
return $copmpares;    
}

?>
