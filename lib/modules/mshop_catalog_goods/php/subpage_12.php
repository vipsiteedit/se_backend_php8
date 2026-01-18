<?php

if (!$flag) return; 

// группы товаров
$shop_group->select('id,name,code_gr,picture');
$shop_group_list = $shop_group->getList();
foreach($shop_group_list as $spr){
    $id_gr = $spr['id'];
    $name_gr = $spr['name'];
    $code_gr = $spr['code_gr'];
    $picture = $spr['picture'];
    
    $__data->setItemList($section,'shop_group',$spr);    
}


// редактирование товара
if (isRequest('id')){
        $shop_price->select();
        $id = getRequest('id');
        $shop_price-> find($id);          
                         
        //получить содержание товара
        $_name      = $shop_price->name;
        $_text      = $shop_price->text;
        $_article   = $shop_price->article;
        $_code      = $shop_price->code;
        $_note      = $shop_price->note;
        $_price     = $shop_price->price;
        $_manufacturer  = $shop_price->manufacturer;
        $_presence_count   = $shop_price->presence_count;
       // $_date_manufactured = $shop_price->date_manufactured;
        $filename   = $shop_price->img;
        $group      = $_POST['group'];
        $group      = $shop_price->id_group;
}   

//сохранение 
if (isset($_POST['Save'])){
            $flag     = true;   //нет ошибки
            $file     = false;

    if (empty($_POST['name']) && $flag){
            $flag = false;
            $errortext =  $section->parametrs->param299;
    }
    
    $group_sel = $_POST['group']; 
                 
    if ($group_sel == "not" && $flag){
            $flag = false;
            $errortext = $section->parametrs->param324;
    }

    
    // если загружается картинка
    if (is_uploaded_file($_FILES['userfile']['tmp_name'])){                     
            $userfile = $_FILES['userfile']['tmp_name'];
            $userfile_size = $_FILES['userfile']['size'];
            $user = strtolower(htmlspecialchars($_FILES['userfile']['name'], ENT_QUOTES));

            //Проверяем, что загруженный файл - картинка
            $sz = GetImageSize($userfile);
            if (preg_match("/([^.]+)\.(gif|jpеg|jpg|png)$/", $user, $m) && ($sz[2]==1 || $sz[2]==2 || $sz[2]==3)) {
                $extendfile = $m[2];
            } else {
                    $errortext =  $section->parametrs->param295; 
                    $flag = false;
            }                  
            //Если размер файла больше заданного
            if ($userfile_size > 1024000){
                    $errortext =  $section->parametrs->param296;
                    $flag = false;
            }
            $file = true;  
    } //конец обработки картинки
    
    //  если была ошибка - нет одного из полей
    if (!$flag){
            $_name        = htmlspecialchars($_POST['name']);
            $_code        = htmlspecialchars($_POST['code']);
            $_manufacturer  = htmlspecialchars($_POST['manufacturer']);
          //  $_date_manufactured  = htmlspecialchars($_POST['date_manufactured']);
            $_article      = htmlspecialchars($_POST['article']);
            $_price      = htmlspecialchars($_POST['price']);
            $_note       = htmlspecialchars($_POST['note']); 
            $_text       = htmlspecialchars($_POST['text']);
            $_presence_count = htmlspecialchars($_POST['presence_count']); 
            $_curr       = htmlspecialchars($_POST['curr']);
            $_unit       = htmlspecialchars($_POST['unit']); 
          /*  $_enabled      = htmlspecialchars($_POST['enabled']);
            $_group       = htmlspecialchars($_POST['group']);  */
             
            
    }else{
            $time = date("siGdmy");
            $name  = $_POST['name'];
            $code  = $_POST['code'];
            $manufacturer  = $_POST['manufacturer'];
          //  $date_manufactured  = $_POST['date_manufactured'];
            $article  = $_POST['article'];
            $price  = $_POST['price'];
            $note  = $_POST['note'];
            $text  = $_POST['text'];
            $curr  = $_POST['curr'];
            $presence_count = $_POST['presence_count'];
            $group = $_POST['group'];
           /* $enabled  = $_POST['enabled'];
            $group  = $_POST['group'];  */
            $imgname = 'shop_price'.$time;
             
            if($curr == "rub"){
                $valute = $section->parametrs->param308;
            }elseif($curr == "dol"){
                $valute = $section->parametrs->param309;
            }elseif($curr == "eur"){
                $valute = $section->parametrs->param310;
            }
                                                            
            // если картинка есть
            if ($file){  
                    $uploadfile     = getcwd().$IMAGE_DIR . $imgname . ".".$extendfile;
                    $uploadfileprev = getcwd().$IMAGE_DIR . $imgname . "_prev.".$extendfile;
                    $filename       = $imgname . '.' . $extendfile;
                 
                    if ($sz[0]>$width){
                            $uploadfiletmp  = getcwd().$IMAGE_DIR . $imgname . ".temp";
                            move_uploaded_file($userfile, $uploadfiletmp);
                            ImgCreate($uploadfileprev,$uploadfile,$uploadfiletmp, $extendfile, $width, $thumbwdth);
                            @unlink($uploadfiletmp);
                    } else{
               
                            move_uploaded_file($userfile, $uploadfile);
                            ThumbCreate($uploadfileprev,$uploadfile, $extendfile,$thumbwdth);
                    } 
            
            } 
            
           // unset($shop_price);
            $shop_price = new seTable('shop_price');
            
            if(empty($id)){       
                $shop_price->insert();
            }else{
                $shop_price->find($id); 
            }     
           
            if(empty($code)){
                $shop_price->code = $id.$time;
            }else{
                $shop_price->code = translitIt($code);
            }    
            
            if(empty($article)){
                $shop_price->article = $time;
            }else{
                $shop_price->article = translitIt($article);
            }        
             
            $shop_price->id             = $id;             
            $shop_price->name           = $name;
            $shop_price->id_group       = $group;
            $shop_price->img            = $filename;
            $shop_price->manufacturer   = $manufacturer;
          //  $shop_price->date_manufactured  = $date_manufactured;
            $shop_price->text           = $text;
            $shop_price->note           = $note;
            $shop_price->price          = $price;
            $shop_price->presence_count = $presence_count;
            $shop_price->curr           = $curr;     
           /* $shop_price->enabled      = $enabled;
            $shop_price->id_group       = $group; */  
            $shop_price->save();
            recalculatGroup($group);                   
            //echo seMultiDir().'/'.$_page.'/';
            Header("Location: ".seMultiDir().'/'.$_page.'/'); 
           
    }
                                       
} //конец сохранения

?>