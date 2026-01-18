<?php

if (!$flag) return; 

$shop_group->select('id,upid,name,code_gr,picture');
$shop_group_list = $shop_group->getList();

foreach($shop_group_list as $spr){
    $id_gr = $spr['id'];
    $name_gr = $spr['name'];
    $code_gr = $spr['code_gr'];
    $picture = $spr['picture']; 
    $upid = $spr['upid'];
    
    $__data->setItemList($section,'shop_group',$spr);    
}  


// редактирование группы
if(isRequest('id')){
    $shop_group->select();
    $id = getRequest('id');
    $shop_group-> find($id);
    $_name = $shop_group->name; 
    $_code_gr = $shop_group->code_gr; 
    $group      = $_POST['group'];
    $group      = $shop_group->id;
    $delbtn = true;
}


// сохранение группы
if(isset($_POST['Save'])){

            $flag     = true;   //нет ошибки
            $file     = false;

    if (empty($_POST['name']) && $flag){
            $flag = false;
            $errortext =  $section->parametrs->param314;
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
    
    if (!$flag){
            $_name  = htmlspecialchars($_POST['name']);
            $_code_gr  = htmlspecialchars($_POST['code_gr']);
    }else{
    
            $time = date("siGdmy");
            $name  = $_POST['name'];
            $code_gr  = $_POST['code_gr'];
            $group = $_POST['group'];
            $upid = $_POST['group'];
            
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
                    }else{
               
                            move_uploaded_file($userfile, $uploadfile);
                            ThumbCreate($uploadfileprev,$uploadfile, $extendfile,$thumbwdth);
                    } 
            
            } 
            
            
            if(empty($id)){       
                $shop_group->insert();
            }else{
                $shop_group->find($id); 
            }  
               
            $shop_group->upid = $group;
            $shop_group->id     = $id;
            $shop_group->name   = $name;
            
            if(empty($code_gr)){
                $shop_group->code_gr = $time;
            }else{
                $shop_group->code_gr = translitIt($code_gr);
            }
            
            $shop_group->picture   = $filename;
            $shop_group->save();                   
            
        Header("Location: /".$_page); 
            
    }
     
} // конец сохранения 

if (isset($_POST['Del'])){
        $shop_group->delete($id);
        Header ("Location: /$_page/{$section->id}/sub15/");
        exit();
    } 

?>