<?php

declare(strict_types=1);
error_reporting(E_ALL);
// Защита от прямого доступа
if (defined('SE_INDEX_INCLUDED')) {
    exit('Прямой доступ запрещён.');
}
define('SE_INDEX_INCLUDED', true);

// Настройка логирования ошибок

ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('error_log', __DIR__ . '/logs/error.log');

// Установка часового пояса
try {
    date_default_timezone_set('Europe/Moscow');
} catch (Exception $e) {
    error_log('Ошибка установки часового пояса: ' . $e->getMessage());
    date_default_timezone_set('UTC');
}

// Запуск сессии с безопасными настройками
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'use_strict_mode' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // Только для HTTPS
    ]);
}

// Определение базового пути (на два уровня вверх от текущего файла)
$baseDir = dirname(__DIR__, 2);
chdir($baseDir);
$initFile = $baseDir . '/system/main/init.php';

// Подключение основного файла инициализации
if (!file_exists($initFile)) {
    error_log('Файл инициализации не найден: ' . $initFile);
    exit('Критическая ошибка: файл инициализации отсутствует.');
}
require_once $initFile;