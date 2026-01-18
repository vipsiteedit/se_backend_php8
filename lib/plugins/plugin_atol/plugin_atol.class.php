<?php

if (!class_exists('CurlAbstract')) {
    require_once SE_LIBS . 'plugins/curlAbstract.class.php';
}

class plugin_atol extends CurlAbstract
{
    private $apiVersion = "v3";
    private $urlApi = "https://online.atol.ru/possystem/";
    private $apiLogin = '';
    private $apiPassword = '';
    private $apiGroup = '';
    private $token = '';

    const OP_SELL = 1; //Приход
    const OP_SELL_REFUND = 2; //Возврат прихода
    const OP_SELL_CORRECTION = 3; //Коррекция прихода
    const OP_BUY = 4; // Расход
    const OP_BUY_REFUND = 5; // Возврат расхода
    const OP_BUY_CORRECTION = 6; // Коррекция расхода

    public function __construct()
    {
        $this->getSettings();

        $this->getToken();
    }

    private function getSettings()
    {
        $settings = plugin_shopsettings::getInstance();

        $this->apiLogin = $settings->getValue('atol_login'); //'edgestiletest';
        $this->apiPassword = $settings->getValue('atol_password'); //'0wgbyWWSp';
        $this->apiGroup = $settings->getValue('atol_group'); //'Edgestile-Test';
    }

    private function log($text)
    {
        $text = date('[Y-m-d H:i:s]') . ' ' . $text . "\r\n";

        $dir = SE_ROOT . 'system/logs/atol/';

        if (!is_dir($dir))
            mkdir($dir);

        $filename = $dir . date('Y-m-d_') . '.log';

        $file = fopen($filename, 'ab');
        fwrite($file, $text);
        fclose($file);
    }

    private function getToken()
    {
        $data = array(
            'login' => $this->apiLogin,
            'pass' => $this->apiPassword
        );

        $url = $this->urlApi . $this->apiVersion . '/getToken';

        $result = $this->get($url, $data, 'JSON_POST');

        $this->log('result - ' . print_r($result, 1));

        if (!empty($result)) {
            $result = json_decode($result, true);
            if ($result['code'] <= 1 && $result['token'])
                $this->token = $result['token'];
        }
    }

    public function operation($type = self::OP_SELL, $data = null)
    {
        if (!$this->token) return;
        switch ($type) {
            case 1: // Приход
                $operation = 'sell';
                break;
            case 2: // Возврат прихода
                $operation = 'sell_refund';
                break;
            case 3: // Коррекция прихода»
                $operation = 'sell_correction';
                break;
            case 4: // Расход
                $operation = 'buy';
                break;
            case 5: // Возврат расхода
                $operation = 'buy_refund';
                break;
            case 6: // Коррекция расхода
                $operation = 'buy_correction';
                break;
        }

        $url = $this->urlApi . $this->apiVersion . '/' . $this->apiGroup . '/' . $operation . '?tokenid=' . $this->token;

        $this->log($url);

        $this->log(print_r($data, 1));

        $result = $this->get($url, $data, 'JSON_POST');

        $this->log($result);

        return $result;
    }

    public function report($uuid)
    {
        $url = $this->urlApi . $this->apiVersion . '/' . $this->apiGroup . '/report/' . $uuid . '?tokenid=' . $this->token;

        $this->log($url);

        $result = $this->get($url, '', 'JSON_GET');

        $this->log($result);

        return $result;
    }
}
