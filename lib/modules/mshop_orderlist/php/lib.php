<?php

if (!function_exists('getlicense_order')) {
    function getlicense_order($serial) {
        se_db_connect();
        $query = se_db_query("SELECT * FROM shop_license WHERE (`serial`='$serial');");
        if (isset($query)) {
            $result = se_db_fetch_array($query);
            return $result['regkey'];
        } else {
            return '';
        }
    }
}
?>