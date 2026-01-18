<?php

if (seUserGroup() == 3) {
    header("Location: /$_page");
    exit();
}

?>