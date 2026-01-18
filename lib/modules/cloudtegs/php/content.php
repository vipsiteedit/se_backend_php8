<?php

$lang= substr(SE_DIR, 0, (strlen(SE_DIR)-1)); // заносим в переменную подсайт многосайтового
if($lang=='') 
    $lang = se_getLang();

$posts = new seTable('se_blog_posts');
$posts->where("comment_presence='?'" , "no");
$posts->andwhere("lang='?'", $lang);   // теперь запрос выборка языка
$postlist = $posts->getList();
 $itogmass= array();
foreach($postlist as $onepost){
// echo $onepost[tags]."<br>";
 $tags = explode(',', $onepost['tags']);
 
 foreach($tags as $tag){

 $tag= trim($tag);
 $fl=2;
 $j=0;
  foreach($itogmass as $elemitogmass) {
   
   if (($elemitogmass['name']==$tag)and (!empty($tag))) {
     $fl=1;
    //echo " у ".$tag." было значение ".$itogmass[$j][freq]." оно было под  ".$j."  ";
     $itogmass[$j]['freq']=$itogmass[$j]['freq']+1;
   // echo "Теперь у ".$tag." значение ".$itogmass[$j][freq]."<br>";
   }
   $j++;
  }
  
  if (($fl==2) and (!empty($tag))) {
  //echo "Я только что добавил ".$tag."<br>";
  $elem="";
  $elem['name']=$tag;
  $elem['freq']="1";
  $itogmass[]=$elem;
 // print_r($itogmass);
 // echo "<br>";
  //array_push($itogmass, $elem);
 // print_r($elem);
 // echo "<br>";
 // print_r($itogmass);
  //echo "<br>";
  }

 }

//print_r($itogmass);

}
 /*
 function order($array, $by) {
    $result = array();
    foreach ($array as $val) {
        if (!is_array($val) || !key_exists($by, $val)) {
            continue;
        }
        end($result);
        $current = current($result);
        while ($current[$by] < $val[$by]) {
            $result[key($result)+1] = $current;
            prev($result);
            $current = current($result);
        }
        $result[key($result)+1] = $val;
    }
    return $result;
}
 */
 
//print_r(order($itogmass, 'freq'));

// array_multisort($itogmass[freq],SORT_DESC);
// print_r($itogmass);

/*
$kol=$section->parametrs->param1;

for ( $i=0; $i<=$kol; $i++) { 

//echo $itogmass[$i][name] . "<br>";

$tegper="";
$tegper[name] = $itogmass[$i][name] ;
$tegper[id] = $itogmass[$i][freq] ;

$__data->setItemList($section, 'tegsblogs', $tegper);

 }
 */

$arrCount = count($itogmass);

if ($arrCount > intval($section->parametrs->param1))
    $arrCount = intval($section->parametrs->param1);
$itogmass = array_slice($itogmass, 0, $arrCount);
 
$i = $min = $max = 0;
    
    if (!empty($itogmass))
    {
        $min = $itogmass[0]['freq'];
        $max = $itogmass[0]['freq'];
        for ($i = 1; $i < count($itogmass); $i++) {
            if ($itogmass[$i]['freq'] > $max) {
                $max = $itogmass[$i]['freq'];
            }
            if ($itogmass[$i]['freq'] < $min) {
                $min = $itogmass[$i]['freq'];
            }
        }
        $minSize = $section->parametrs->param2;
        $maxSize = $section->parametrs->param3;
        foreach ($itogmass as $item) {
            if ($min == $max) {
                $fontSize = round(($maxSize - $minSize) / 2 + $minSize);
            }
            else {
                $fontSize = round((($item['freq'] - $min)/($max - $min)) * ($maxSize - $minSize) + $minSize);
            }
            
            if ($section->parametrs->param4 != 'N')
                $showVisits = "(".$item['freq'].")";
            else
                $showVisits = "";
            $CLOUD = $CLOUD."<a class=\"cloudItem\" href=\"".seMultiDir()."/".$section->parametrs->param5."/tag/".urlencode(trim($item['name']))."/"."\" style=\"font-size:".$fontSize."%\">".$item['name'].$showVisits."</a> ";
        } 
    } 
?>