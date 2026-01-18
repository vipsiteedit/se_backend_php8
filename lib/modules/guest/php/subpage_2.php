<?php

    $ipfile = se_file($filenameip);
    //eсли удаляем
    if(isRequest('del'))
    {
        $delid = getRequest('del');
        unset($ipfile[$delid]);
        $ipfile = array_values($ipfile);
        $file = se_fopen($filenameip,"w");
        flock($file,LOCK_SH);
        foreach($ipfile as $str)
        {
            $str = str_replace("\n\n","\n",$str);
            fwrite($file, $str);   
        }
        flock($file,LOCK_UN);
        fclose($file); 
    }
    //генерируем хтмл раздела
    $cont_subpage2 = "";
    foreach($ipfile as $id => $ipstr)
    {
        $cont_subpage2 .= "
                <tr>
                    <td class=\"tablerow\"><p class=\"blocktext\">".$ipstr."</p></td>
                    <td class=\"tablerow\"><a class=\"link\" href=\"[link.subpage=2]?del=".$id."\">{$section->parametrs->param30}</td>
                </tr>
                 ";  
    }
    if (empty($ipfile))
    {
        $cont_subpage2 = "
                        <tr>
                            <td class=\"tablerow\" colspan=\"2\">{$section->parametrs->param31}</td>
                        </tr>
                        ";
    }

?>