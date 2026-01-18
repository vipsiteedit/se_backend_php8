<?php


function Article_CheckMail_new($name)
{
    if (preg_match("/[0-9a-z_\-]+@([0-9a-z_\-^\.]+\.[a-z]{2,4})$/i", $name, $matches))
    {
        return true;
    }
   return false;
}

if (!function_exists('Article_CheckAuth')){
function Article_CheckAuth($users)
{
    if (seUserGroup() == 3) return true;
    $is = seUserId();
    if (empty($is))  return false;
    
    $table = new seTable("se_user");
    $table->select("id, username");
    $table->andWhere("`id` = ?", seUserId());
    $table->fetchOne();
    
    if (empty($table->id)) return false;
    $name = $table->username;

    $logins = explode(",", $users);
    foreach($logins as $val){
        $val = trim($val);
        if (empty($val))   continue;
        if ($val == $name) return true;
    }

     return false;
}
}
?>
