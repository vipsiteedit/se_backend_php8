<?php

if (!$flag) return; 

// Вывод данных из БД   
            
$categories = new seTable($section->parametrs->param24.'categories');
  
    $categories->select('id,lang, upid,name');
                                                      
    $categories->orderby('id',0);
    $categories->where("lang='?'", $lang);  // теперь запрос выборка языка
    $categorieslist = $categories->getList(); 
   
  
    
   /* foreach ($categorieslist as $ctg) 
    {
    $__data->setItemList($section, 'categories', $ctg);
    }*/      
   
//----------------------------------------------------    

// Построение уровней категорий

 $itogmass= array();

    foreach ($categorieslist as $elem){
        
        if (empty($elem[upid]))  { 
            // $probel=" ";
            $elemitog[id]=$elem[id];
            $elemitog[level]="0";
            $elemitog[name]=$elem[name];
           
            array_push($itogmass, $elemitog);
        }
    }
    
    for ($i = 0; $i <= 100; $i++){
        foreach ($itogmass as $elemitog){
            if($elemitog[level]==$i){
                foreach ($categorieslist as $elem){
                    if ($elemitog[id]==$elem[upid]){
                    
                        $upid=$elem[upid];
                        $j=0;
                        foreach ($itogmass as $mom){
                            $j++;
                            if ($mom[id]==$upid){
                                $numrod=$j;       
                            }
                        } 
                        $level=$itogmass[$numrod][level];
                               
                        $p[id]=$elem[id];
                        $p[level]=$i+1;
                        $p[name]=$elem[name];
                        $elemitogmas[1]=$p;
                        array_splice($itogmass, $numrod, 0, $elemitogmas);
                    }                                                             
                }
            }   
        }   
    }
                    
    foreach($itogmass as $elemitog){
        $probel="";
        for ($i = 0; $i <= $elemitog[level]; $i++) {
            $probel.="&nbsp;&nbsp;&nbsp;";
        }
        $spisok.=$probel.$elemitog[name]."<br>";
    }
   
  //  echo $spisok;
    
    foreach($itogmass as $sel){
        $probel="";
        for ($i = 0; $i <= $sel[level]; $i++) {
            $probel.="&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        $sel[name]=$probel.$sel[name];
    
        $__data->setItemList($section, 'categories', $sel);                    
    }
                              
//-----------------------------------------------
    
// Редактирование категории 
   
    if (isRequest('id')){
    
        $categories->select();
        $id = getRequest('id');
        $categories->find($id);
        $namectg = $categories->name;
        $url = $categories->url;
        $keywords = $categories->keywords;
        $description = $categories->description;
        $upid = $categories->upid;
        $btn = true;
    }                                                        
                                                        
    if (isRequest('AddCtg')){ 
    
    $categories = new seTable($section->parametrs->param24.'categories'); 
    
    $flg = true;
                      
        if (!empty($id)) {
        
            $categories->find($id);
        }
        else
        {
        
            $categories->insert;
        }                                            
                                                // Добавление категории
        $namectg = getRequest('namectg',3);
       
        $keywords = getRequest('keywordctg',3);
        $description = getRequest('descriptctg',3);
        $upid = getRequest('upidctg',3);
        
        if(empty($_POST['namectg']) && $flg){
            $flg = false;
            $errortext = "<div class='errorText'>".$section->language->lang020."</div>";
        }
                  
    if (!$flg) {
        $namectg = htmlspecialchars($_POST['namectg']);
        $keywords = htmlspecialchars($_POST['keywords']);
        $description = htmlspecialchars($_POST['description']);
        $upid = htmlspecialchars($_POST['upidctg']);
        $url = htmlspecialchars($_POST['urlctg']);
    }else{
    
                                         
        $categories->name =$namectg;
        
        if (empty($keywords)){
            $categories->keywords =$namectg;
        } else {
            $categories->keywords =$keywords;
        }
        
        $categories->description =$description; 
        
        if (!empty($upid)){ 
            
            $categories->upid =$upid;  
            $categoriesrod= new seTable($section->parametrs->param24.'categories');
            $categoriesrod->find($upid);
            $rodcategorurl = $categoriesrod->url;
            
        } else {
            
            $categories->upid ='null';
        }
         
         $url = getRequest('urlctg',3);
         
         
        
        if (empty($url)) { 
            $categories->url = $rodcategorurl.getTransName($namectg);  //Транслит 
        } else {
            $categories->url = getTransName($url);
        }   
        
        
        $categories->lang = $lang;
                      
        $categories->save();
                 
    
    $btn = false;
    
    
                                      
        header ("Location: ".seMultiDir()."/$_page/?".time());    
    }
    }
    
    if (isRequest('Close')){
    
        header ("Location:  ".seMultiDir()."/$_page/?".time());
    }
       
// Удаление категории
                     
    if (isRequest('Del')){
        $categories->delete($id);
        header ("Location:  ".seMultiDir()."/$_page/?".time());
        exit();
    }
                  

?>