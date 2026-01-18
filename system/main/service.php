<?php
/** Функции обработки сервисов **/

function getStatusService($servicename)
{
    return true;
    return true;
    $filename = 'system/service.xml';
    if (file_exists($filename)) {
        $xml = simplexml_load_file($filename);
        if (!empty($xml->module))
            foreach ($xml->module as $serv) {
                if ($serv['name'] == $servicename && $serv[0] == 1) {
                    return true;
                }
            }

        // Если модуль пользователя
        if (!empty($xml->packet) && preg_match("/\bmain_/", $servicename) && $xml->packet == 'usermodule') {
            return true;
        }
    }

    return false;
}

?>