<?php

require_once dirname(__FILE__) . "/basePayment.class.php";
require_once dirname(__FILE__) . "/yandex_kassa/lib/autoload.php";

/**
 * Плагин платежной системы Yandex
 */

use YooKassa\Client;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;

class payment_yandex_kassa extends basePayment
{

    private $client;
    public $logText;
    private $order_id;

    public function __construct($order_id = 0, $payment_id = 0)
    {
        parent::__construct($order_id, $payment_id);
        $this->client = new \YooKassa\Client();
    }

    public function setVars()
    {
        return array('shopId' => 'ID магазина', 'wmtoken' => 'Ключ API');
    }

    public function startform()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        return $macros->execute($this->startform);
    }

    public function blank($pagename)
    {
        try {
            $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
            $this->client->setAuth($macros->execute('[PAYMENT.SHOPID]'), $macros->execute('[PAYMENT.WMTOKEN]'));
            $summ = $macros->execute('[ORDER.SUMMA]');

            $shopSuccessURL = $this->getPathPayment('[MERCHANT_SUCCESS]', $pagename);
            $order_id = $macros->execute("[ORDER.ID]");
            $userEmail = $macros->execute('[USER.USEREMAIL]');
            $userPhone = $macros->execute("[USER.PHONE]");
            $userName = $macros->execute("[CLIENTNAME]");
            if (strpos($userPhone, '8') === 0) $userPhone = '7' . substr($userPhone, 1);

            // Формируем список оплат
            $items = array();
            $discount = 0;
            $nsumm = 0;
            $goods = $this->getGoodsOrder($order_id);
            foreach ($goods as $item) {
                $nsumm += ($item['price'] - $item['discount']) * $item['count'];
            }
            $kd = ($nsumm) ? 1 - (($nsumm - $summ) / $nsumm) : 1;
            foreach ($goods as $item) {
                $items[] = array(
                    "description" => $item['nameitem'],
                    "quantity" => $item['count'],
                    "amount" => array(
                        "value" => round(($item['price'] - $item['discount']) * $kd, 2),
                        "currency" => "RUB"
                    ),
                    "vat_code" => "2",
                    "payment_mode" => "full_prepayment",
                    "payment_subject" => "service"
                );
            }


            $idempotenceKey = uniqid('', true);
            $payparam = array(
                'amount' => array(
                    'value' => $summ,
                    'currency' => 'RUB',
                ),
                'payment_method_data' => array(
                    'type' => 'bank_card',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => $shopSuccessURL,
                ),
                'receipt' => array(
                    'customer' => array(
                        'email' => $userEmail,
                        'phone' => $userPhone,
                        //'full_name' => $userName
                    ),
                    'items' => $items
                ),
                'description' => 'Заказ №' . $order_id,
            );

            $response = $this->client->createPayment(
                $payparam,
                $idempotenceKey
            );

            //get confirmation url
            $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();
            $this->newPaymentLog($response->getId());
            //print_r($confirmationUrl);
            header("Location: " . $confirmationUrl);
        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        }
    }

    private function getGoodsOrder($order_id)
    {
        $goods = new seShopOrderGoods();
        $goods->where('id_order=?', $order_id);
        return $goods->getList();
    }


    public function result()
    {
        error_reporting(E_ALL);
        $this->logs("I get API requestBody: <pre>test</pre>", true);
        $source = file_get_contents('php://input');
        $requestBody = json_decode($source, true);

        if ($requestBody['event'] == 'payment.waiting_for_capture') {
            $res = $this->getPaymentLog($requestBody['object']['id']);
            $this->order_id = $res['order_id'];
            $this->logs("Order: <pre>" . $this->order_id . "</pre>", true);
            if ($requestBody['object']['paid']) {
                $this->activate($this->order_id);
                $this->logs("Order: <pre>" . $this->order_id . " Оплачен!.</pre>", true);
                return true;
            }
        }
    }

    public function success()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $this->success = '<h4 class="contentTitle">Оплата проведена успешно</h4><p>Ваш заказ № ' . $this->order_id . ' оплачен</p>';
        return $macros->execute($this->success);
    }

    public function fail()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $this->fail = '<h4 class="contentTitle">Ошибка в проведении платежа</h4>';
        return $macros->execute($this->fail);
    }

    private function logs($text, $toFile = false)
    {
        $this->logText = $this->logText . $text . "\r\n <br>";
        if ($toFile == true) {
            if (!is_dir(getcwd() . '/system/logs/yandexshop'))
                mkdir(getcwd() . '/system/logs/yandexshop');
            $date = date('c');
            file_put_contents(getcwd() . '/system/logs/yandexshop/' . $date . '.txt', $this->logText);
        }
    }
}
