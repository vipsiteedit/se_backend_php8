<?php

if (seUserGroup() < 3) {
    header("Location: ".$thispagelink);
    exit;
}    

    $ipfile = file($filenameip);
    //eсли удаляем
    if(isRequest('del'))
    {
        $delid = getRequest('del');
        unset($ipfile[$delid]);
        $ipfile = array_values($ipfile);
        $file = fopen($filenameip,"w");
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
                    <td class=\"tablerow\"><a class=\"link\" href=\"[link.subpage=block]?del=".$id."\">{$section->language->lang019}</td>
                </tr>
                 ";  
    }
    if (empty($ipfile))
    {
        $cont_subpage2 = "
                        <tr>
                            <td class=\"tablerow\" colspan=\"2\">{$section->language->lang018}</td>
                        </tr>
                        ";
    }

?>
