<?php

declare(strict_types=1);

/**
 * Получает курсы валют из XML-ленты ЦБ РФ.
 *
 * @param string $code Код валюты (например, 'USD'). По умолчанию 'USD'.
 * @param bool $single Если true, возвращает данные одной валюты, иначе все валюты. По умолчанию true.
 * @return array|null Возвращает данные о валюте(ах) в виде массива или null при неудаче.
 * @throws RuntimeException Если не удалось получить данные или выполнить файловые операции.
 */
function getCurrencyValues(string $code = 'USD', bool $single = true): ?array
{
    // Проверка константы SE_ROOT
    if (!defined('SE_ROOT')) {
        throw new RuntimeException('Константа SE_ROOT не определена.');
    }

    $dir = SE_ROOT . '/data';
    $cacheFile = $dir . '/currency.json';

    // Проверка и создание директории
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException("Не удалось создать директорию: $dir");
    }

    // Проверка актуальности кэша
    if (!file_exists($cacheFile) || date('Y-m-d', filemtime($cacheFile)) < date('Y-m-d')) {
        $url = 'https://www.cbr-xml-daily.ru/daily.xml';
        $xmlData = @file_get_contents($url);
        if ($xmlData === false) {
            throw new RuntimeException("Не удалось получить данные с $url");
        }

        $xml = simplexml_load_string($xmlData);
        if ($xml === false) {
            throw new RuntimeException('Не удалось разобрать XML-данные.');
        }

        $json = json_encode($xml, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        if (file_put_contents($cacheFile, $json, LOCK_EX) === false) {
            throw new RuntimeException("Не удалось записать в файл кэша: $cacheFile");
        }
    }

    // Чтение и декодирование кэша
    $json = file_get_contents($cacheFile);
    if ($json === false) {
        throw new RuntimeException("Не удалось прочитать файл кэша: $cacheFile");
    }

    $curr = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    if (!isset($curr['Valute'])) {
        throw new RuntimeException('Некорректная структура данных о валютах в кэше.');
    }

    if ($single) {
        foreach ($curr['Valute'] as $valute) {
            if (isset($valute['CharCode']) && $valute['CharCode'] === $code) {
                return $valute;
            }
        }
        return null; // Явно возвращаем null, если валюта не найдена
    }

    return $curr['Valute'];
}