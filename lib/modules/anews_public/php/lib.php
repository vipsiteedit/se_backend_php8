<?php

if (!function_exists('isModerator')){
function isModerator($name)
{
   return (seUserName() == $name && seUserGroup() > 1);
}
}
?>