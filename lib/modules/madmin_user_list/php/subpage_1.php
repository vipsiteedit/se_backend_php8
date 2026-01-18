<?php

// Удалить 
if ((seUserGroup() == 3) && isRequest('GoToRekvDelete') && $user) {
    $user->delete($_user);
    $messagetext = "";
    header("Location: " . _HOST_ . "/$_page/");
    exit();
}

?>