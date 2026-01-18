<?php
require_once dirname(__FILE__) . "/basePayment.class.php";

/**
 * Плагин платежной системы intellectmoney
 * Secret Key                                    - придумай сам
 * Result URL                                    - пример, http://имя сайта.e-stile.ru/lib/merchant/result.php?payment=intellectmoney
 * Email                                         - ваш email для сбора почты
 * Принимать только уникальные ID покупки        - поставить галочку
 * Режим отладки                                 - убрать галочку
 * Высылать Secret Key на Result URL (при HTTPS) - убрать галочку
 * Кодировка уведомлений UTF                     - поставить галочку
 */

class payment_intellectmoney extends basePayment
{
    public $logText;
    private $order_id;

    public function setVars()
    {
        return array('pim_id' => 'Merchant ID', 'pim_passw' => 'Secret key');
    }

    public function startform()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        return $macros->execute($this->startform);
    }

    public function blank($pagename)
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $url = 'https://merchant.intellectmoney.ru/ru/';
        $order_id = $macros->execute("[ORDER.ID]");
        $id_merchant = $macros->execute('[PAYMENT.PIM_ID]');
        $amount = se_FormatNumber($macros->execute("[ORDER.SUMMA]"), '');
        $currency = ($this->test) ? 'TST' : 'RUR';
        $success_url = $macros->execute($this->getPathPayment('[MERCHANT_SUCCESS]', $pagename));
        $failure_url = $macros->execute($this->getPathPayment('[MERCHANT_FAIL]', $pagename));


        $blank = "<h4 class=\"contentTitle\">Подтверждение оплаты через IntellectMoney</h4>
                    <div class=''>Вы будете переведены на сайт платежной системы IntellectMoney для оплаты заказа № $order_id</div>";
        $blank .= '[SETCURRENCY:RUR]' . "<form method='POST' action='$url' >" .
            "<input id='orderId' type='hidden' value='$order_id' name='orderId'/>" .
            "<input id='eshopId' type='hidden' value='$id_merchant' name='eshopId'/>" .
            "<input id='serviceName' type='hidden' value='order#$order_id' name='serviceName'/>" .
            "<input id='recipientAmount' type='hidden' value='$amount' name='recipientAmount'/>" .
            "<input type='hidden' value='$currency' name='recipientCurrency'/>" .
            "<input type='hidden' value='$success_url' name='successUrl'/>" .
            "<input type='hidden' value='$failure_url' name='failUrl'/>" .
            "<input class='buttonSend' type=submit value='Оплатить' />" .
            "</form>";

        $this->newPaymentLog();
        return $macros->execute($blank);
    }

    public function result()
    {
        define('SANDBOX', 1);
        $post = $_POST;
        if (SANDBOX == true) {
            $this->logs("I get POST request: <pre>" . print_r($post, 1) . "</pre>");
        }
        $this->order_id = $post['orderId'];
        $res = $this->getPaymentLog();
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $secret_key = $macros->execute('[PAYMENT.PIM_PASSW]');
        $hash = strtoupper($post["hash"]);
        $for_hash = $post['eshopId'] . "::" . $post['orderId'] . "::" . $post['serviceName'] . "::" .
            $post['eshopAccount'] . "::" . $post['recipientAmount'] . "::" . $post['recipientCurrency'] . "::" .
            $post['paymentStatus'] . "::" . $post['userName'] . "::" . $post['userEmail'] . "::" .
            $post['paymentData'] . "::" . $secret_key;
        $my_hash = strtoupper(md5($for_hash));
        $checksum = ($my_hash == $hash) ? true : false;

        if (SANDBOX == true) {
            $this->logs("Hash line " . $for_hash);
            $this->logs("Check hash. My as system " . $my_hash . ' == ' . $hash);
        }

        if ($checksum && ($post['recipientAmount'] == $res['summ']) && ($post['paymentStatus'] == 5)) {
            $this->activate($this->order_id);
            if (SANDBOX == true) {
                $this->logs("Order is payed", true);
            }
            //  ответ должен присутствовать
            echo "OK";
            exit;
        } else if ($checksum && ($post['recipientAmount'] == $res['summ']) && ($post['paymentStatus'] == 3)) {
            if (SANDBOX == true) {
                $this->logs("Order is pending", true);
            }
            //  ответ должен присутствовать
            echo "OK";
            exit;
        } else if (!$checksum) {
            if (SANDBOX == true) {
                $this->logs("Some problem", true);
            }
            exit;
        } else {
            if (SANDBOX == true) {
                $this->logs("Just get status  - " . $post['paymentStatus'], true);
            }
            echo "OK";
            exit;
        }
    }

    public function success()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $this->success = '<h4 class="contentTitle">Оплата заказа</h4><p>Ваш заказ № ' . $this->order_id . ' оплачен</p>';
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
            if (!is_dir(getcwd() . '/system/logs/intellectmoney'))
                mkdir(getcwd() . '/system/logs/intellectmoney');
            $date = date('c');
            file_put_contents(getcwd() . '/system/logs/intellectmoney/' . $date . '.txt', $this->logText);
        }
    }
}
