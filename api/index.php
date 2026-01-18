<?php

$allowedMethods = array('POST', 'DELETE', 'GET', 'PUT');
$allowedMethods = implode(",", $allowedMethods);

$headers = getallheaders();
if (!empty($headers['Secookie']))
    session_id($headers['Secookie']);
session_start();

chdir($_SERVER['DOCUMENT_ROOT']);
date_default_timezone_set("Europe/Moscow");
ini_set('display_errors', 0);
error_reporting(E_ALL);

define('API_VERSION', "2");
define('IS_EXT', file_exists($_SERVER['DOCUMENT_ROOT'] . '/system/main/init.php'));
define('API_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/api/');
define('SE_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
define('API_ROOT_URL', "http://" . $_SERVER['SERVER_NAME'] . "/api");
define('AUTH_SERVER', "https://api.siteedit.ru");

function writeLog($data)
{
    if (!is_string($data))
        $data = print_r($data, 1);
    $file = fopen($_SERVER['DOCUMENT_ROOT'] . "/api/debug.log", "a+");
    $query = "$data" . "\n";
    fputs($file, $query);
    fclose($file);
}
require_once 'lib/lib_utf8.php';
require_once 'lib/lib_function.php';
require_once 'lib/lib_se_function.php';
require_once API_ROOT . "vendor/autoload.php";

$method = $_SERVER['REQUEST_METHOD'];
$apiClass = parse_url($_SERVER["REQUEST_URI"]);
$api =  str_replace("api/", "", trim($apiClass['path'], "/"));
if (strpos($api, 'Auth') === 0) {
    list($apiClass, $apiMethod) = explode("/", $api);
} else {
    list($apiPath, $apiClass, $apiMethod) = explode("/", $api);
    $apiClass = $apiPath . "/" . $apiClass;
}
if (empty($apiMethod)) {
    if ($method == "GET")
        $apiMethod = "get";
    else $apiMethod = "post";
}

//$apiClass = str_replace("api/", "", trim($apiClass['path'], "/"));
$origin = !empty($headers['Origin']) ? $headers['Origin'] : $headers['origin'];

if (!empty($origin)) {
    $url = parse_url($origin);
    if ($url) {
        if ($url['host'] == $_SERVER['HTTP_HOST'])
            header("Access-Control-Allow-Origin: http://" . $_SERVER['HTTP_HOST']);
        if ($url['host'] == 'localhost' && $url['port'] == 1337)
            header("Access-Control-Allow-Origin: http://localhost:1337");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Project, Secookie");
        header("Access-Control-Allow-Methods: $allowedMethods");
    }
    if ($method == "OPTIONS")
        exit;
}

if (strpos($apiClass, "Auth"))
    $apiClass = "Auth";

if ($apiClass == "Auth" && strtolower($apiMethod) == "logout") {
    $_SESSION = array();
    session_destroy();
    echo "Session destroy!";
    exit;
}

if ($apiClass == "Auth" && strtolower($apiMethod) == "get") {
    if (empty($_SESSION['isAuth'])) {
        header("HTTP/1.1 401 Unauthorized");
        echo 'Сессия истекла! Необходима авторизация!';
        exit;
    }
}

$phpInput = file_get_contents('php://input');
$hostname = $_SERVER['HTTP_HOST'];

if ($apiClass == "Auth" && strtolower($apiMethod) == "info")
    $hostname = $headers["Project"];

define("HOSTNAME", $hostname);
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
$dbConfig = DOCUMENT_ROOT . '/system/config_db.php';


$dirSettings = DOCUMENT_ROOT . '/manager';
if (!file_exists($dirSettings))
    mkdir($dirSettings);

define("DIR_SETTINGS", $dirSettings);

if (file_exists($dbConfig))
    require_once $dbConfig;
else {
    header("HTTP/1.1 401 Unauthorized");
    echo 'Сессия истекла! Необходима авторизация!';
    exit;
}

$coreVersion = "530";
define('CORE_VERSION', $coreVersion);

if ($apiClass != "Auth" && empty($_SESSION['isAuth']) && !in_array($_SERVER["REMOTE_ADDR"], $allowableServers)) {
    //header("HTTP/1.1 401 Unauthorized");
    echo 'Необходима авторизация!';
    exit;
}

$class = "\\SE\\" . str_replace("/", "\\", $apiClass);

if (!class_exists($class)) {
    header("HTTP/1.1 501 Not Implemented");
    echo "Объект '{$apiClass}' не найден!";
    exit;
}
if (!method_exists($class, $apiMethod)) {
    header("HTTP/1.1 501 Not Implemented");
    echo "Метод'{$apiMethod}' не поддерживается!";
    exit;
}

$apiObject = new $class($phpInput);
if ($apiObject->initConnection($CONFIG))
    $apiObject->$apiMethod();
$apiObject->output();
