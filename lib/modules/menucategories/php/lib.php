<?php

if (!function_exists('mcat_parseGroup')){
function mcat_parseGroup($section, $upid = 0, $level = 0)
{     
    $lang= substr(SE_DIR, 0, (strlen(SE_DIR)-1)); // заносим в переменную подсайт многосайтового
      if ($lang=="") {
    $lang="rus";
  }        
    $tab_cat = new seTable($section->parametrs->param24.'categories');                    
    $tab_cat->select('id,upid,url,name,lang');
    
    if (!empty($upid)){                    
        $tab_cat->where('upid=?', $upid); 
        }
    else {
        $tab_cat->where('upid IS NULL');  
    }   
    $tab_cat->andwhere("lang='?'", $lang);  // теперь запрос выборка языка
       
    $tab_cat->orderby('id', 1);
    $categories = $tab_cat->getList();
    
    $__data = seData::getInstance();
    $page = $__data->getPageName();  
    
    $thismenu = null; 
   
  
    if (!empty($categories))
    {                  
        foreach ($categories as $value)
        {
            $id = $value['id'];
            $menu->name = strval($value['url']);                      
            $menu->title = $value['name'];
            if ($_SESSION[$section->parametrs->param15.'EditCtg'])
                $menu->url = seMultiDir().'/'.$page.'/'.$section->id.'/sub1/id/'.$value['id'].'/'; 
            else 
                $menu->url = '/'.$section->parametrs->param23.'/'.$section->parametrs->param15.'/'.$value['url'];

            $menu->level = $level + 1;
            $item = mcat_parseGroup($section, $id, $level + 1);
            
            if (!empty($item)){
                $menu->item = $item;                          
            }else{  
                unset($menu->item);
            }
            
            $thismenu[strval($value['url'])] = clone($menu);    
        }
    }             
    return $thismenu;   
}
}



function getTransName($str, $delimer = '_') {
$translate = array(
 'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ж'=>'g', 'з'=>'z',
 'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p',
 'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'ы'=>'i', 'э'=>'e', 'А'=>'A',
 'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Е'=>'E', 'Ж'=>'G', 'З'=>'Z', 'И'=>'I',
 'Й'=>'Y', 'К'=>'K', 'Л'=>'L', 'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R',
 'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Ы'=>'I', 'Э'=>'E', 'ё'=>"yo", 'х'=>"h",
 'ц'=>"ts", 'ч'=>"ch", 'ш'=>"sh", 'щ'=>"shch", 'ъ'=>"", 'ь'=>"", 'ю'=>"yu", 'я'=>"ya",
 'Ё'=>"YO", 'Х'=>"H", 'Ц'=>"TS", 'Ч'=>"CH", 'Ш'=>"SH", 'Щ'=>"SHCH", 'Ъ'=>"", 'Ь'=>"",
 'Ю'=>"YU", 'Я'=>"YA", '№'=>""
 );
 
 $string = strtr($str, $translate);
    return trim(preg_replace('/[^\w]+/i', $delimer, $string), $delimer);

    }
                
?>