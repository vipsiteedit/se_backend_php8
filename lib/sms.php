<?php

chdir($_SERVER["DOCUMENT_ROOT"]);

ini_set('display_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');
define('SE_INDEX_INCLUDED', true);
require_once 'system/main/init.php';

$serial = $_POST["serial"];
$db_password = $_POST["db_password"];
if ($serial != $CONFIG["DBSerial"] || $db_password != $CONFIG["DBPassword"]) {
    header("HTTP/1.1 401 Unauthorized");
    echo 'Для запрошенной операции необходима авторизация!';
    exit;
}

$providerName = $_REQUEST["provider"];
$action = $_REQUEST["action"];

$provider = new seTable('sms_providers', 'sp');
$provider->where("name = '?'", $providerName);
$provider->fetchOne();
if (!$provider->isFind()) {
    echo "Не найден указанный провайдер!";
    exit;
}

$settings = json_decode($provider->settings, true);

if ($providerName == "sms.ru") {
    $apiId = $settings["api_id"]["value"];
    $sms = new plugin_sms($apiId);
    if ($action == "balance") {
        $info = $sms->my_balance();
        if ($info["code"] == 100)
            echo $info["balance"];
    }
    if ($action == "status") {
        $log = new seTable("sms_log");
        $log->select("id, id_sms");
        $log->where("id_provider = ? AND code < 103", $provider->id);
        $items = $log->getList();
        foreach ($items as $item) {
            $result = $sms->sms_status($item["id_sms"]);
            $result = empty($result) ? 103 : $result;
            $log = new seTable("sms_log");
            $log->addUpdate("code", $result);
            $log->addUpdate("status", "'" . $sms->response_code["status"][$result] . "'");
            $log->where("id = ?", $item["id"]);
            $log->save();
        }
    }
}

if ($providerName == "qtelecom.ru") {
    $login = $settings["login"]["value"];
    $password = $settings["password"]["value"];
    $sms = new plugin_qtsms($login, $password);
    if ($action == "balance") {
        $info = $sms->get_balance();
        echo $info["balance"]["AGT_BALANCE"];
    }
    if ($action == "status") {
        $log = new seTable("sms_log");
        $log->select("id, id_sms");
        $log->where("code IS NULL AND id_provider = ?", $provider->id);
        $items = $log->getList();
        foreach ($items as $item) {
            $result = $sms->status_sms_id($item["id_sms"]);
            $log = new seTable("sms_log");
            if (!empty($result["MESSAGES"]["MESSAGE"])) {
                $result = $result["MESSAGES"]["MESSAGE"];
                if ($result["SMS_CLOSED"])
                    $log->addUpdate("code", 1);
                $log->addUpdate("status", "'" . $result["SMS_STATUS"] . "'");
                $log->where("id = ?", $item["id"]);
            } else {
                $log->addUpdate("code", 2);
                $log->addUpdate("status", "'" . "" . "'");
            }
            $log->where("id = ?", $item["id"]);
            $log->save();
        }
    }
}

if ($providerName == "inCore Dev") {
    $sms = new plugin_sms_incore($settings['host']['value'], $settings['login']['value'], $settings['password']['value']);
    if ($action == "balance") {
        $info = $sms->getBalance();
        echo $info;
    }
    if ($action == "status") {
        $log = new seTable("sms_log");
        $log->select("id, id_sms");
        $log->where("code IS NULL AND id_provider = ?", $provider->id);
        $items = $log->getList();
        foreach ($items as $item) {
            $result = $sms->getState($item["id_sms"]);
            $log = new seTable("sms_log");
            if ($result['value'] = 'deliver') {
                $log->addUpdate("code", $result['value']);
            }
            $log->addUpdate("status", "'" . $result['value'] . "'");
            $log->addUpdate("cost", "'" . $result['price'] . "'");
            $log->addUpdate("count", "'" . $result['num_parts'] . "'");
            $log->where("id = ?", $item["id"]);
            $log->save();
        }
    }
}
