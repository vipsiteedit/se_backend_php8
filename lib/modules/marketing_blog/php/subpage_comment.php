<?php

//  смайлики
    $smile = new seTable("emotions");
    $smile->select("id, img, abbr");
    $smileList = $smile->getList();

    $__data->setList($section, 'smiles', $smileList);

?>