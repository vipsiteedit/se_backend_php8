<?php

if ($product_files = $psg->getGoodsFiles($viewgoods)) {
    foreach ($product_files as $val) {
        $__data->setItemList($section, 'files', $val);
    }
}

?>
