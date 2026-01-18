<?php

if (!$access)
{
    Header('Location: '.seMultiDir().'/'.PAGE.'/');
    exit();
}
$table = new seTable('article_comm'); 
$art_name1 = $art_email1 = $art_note1 = $art_ans1 = '';
if (isset($_GET['object'], $_GET['id']))
{
    $id      = getRequest('id', 1);
    $objNumb = getRequest('object', 1);//se_db_input((int)$_GET['object']);
    if (isset($_POST['GoToEdit'])){
        if (!isset($_SESSION[PAGE.'_'.RAZDEL]))
        {
            $_SESSION[PAGE.'_'.RAZDEL] = time()+10;
        }
        else
        {
            if (time() < $_SESSION[PAGE.'_'.RAZDEL])
            {
                Header('Location: '.seMultiDir().'/'.PAGE.'/'.RAZDEL.'/sub1/object/'.$objNumb.'/id/'.$id.'/');
                exit();
            }
            else
                $_SESSION[PAGE.'_'.RAZDEL] = time()+10;
        }           

        if (isset($_POST['del']))
        {
            $table->addWhere("id=?",$id)->deletelist();
            Header('Location: '.seMultiDir().'/'.PAGE.'/'.RAZDEL.'/'.$objNumb.'/');
            exit();            
        }
        $_name  = utf8_substr(getRequest('name', 3), 0, 59);
        $_email = substr(trim(getRequest('email')), 0, 59);
        $_note  = utf8_substr(trim(getRequest('note', 3)), 0, 3499);
        $_note1 = utf8_substr(trim(getRequest('note1', 3)), 0, 3499);
        $flag = true;
            
        if (empty($_name))
        {
            $art_errtxt1 = '<li>'.$section->language->lang029.'</li>';
            $flag = false;
        }

        if ((empty($_note)) && $flag)
        {
            $art_errtxt1 = '<li>'.$section->language->lang031.'</li>';
            $flag = false;
        }
   
        if ($flag)
        {
            $table->from("article_comm")->find($id);
            $table->name        = $_name;
            $table->email       = $_email;
            $table->comment     = $_note;
            $table->adm_answer  = $_note1;
            if($table->save())
            {
                Header('Location: '.seMultiDir().'/'.PAGE.'/'.RAZDEL.'/'.$objNumb.'/?'.time());
                exit();
            }            
            else
                $art_errtxt1 = $section->language->lang028;
        }
                                        
    }
}
unset($table);

?>