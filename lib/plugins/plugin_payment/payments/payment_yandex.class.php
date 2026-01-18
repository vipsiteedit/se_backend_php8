<?php
require_once dirname(__FILE__) . "/basePayment.class.php";

/**
 * Плагин платежной системы Yandex
 */

class payment_yandex extends basePayment
{

    public $logText;
    private $order_id = 0;
    public function setVars()
    {
        return array('ym' => 'Номер счета Yandex', 'ym_secret' => 'Секретный ключ');
    }

    public function startform()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        return $macros->execute($this->startform);
    }

    public function blank($pagename)
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $url = 'https://money.yandex.ru/quickpay/confirm.xml';
        $id_merchant = $macros->execute('[PAYMENT.YM]');
        $name = $macros->execute('[SHOPLIST.NAME]');
        $order_id = $macros->execute("[ORDER.ID]");
        $amount = $macros->execute("[ORDER.SUMMA]");

        $blank = "<h4 class=\"contentTitle\">Подтверждение оплаты через Яндекс.Деньги</h4>
                    <div class=''>Вы будете переведены на сайт платежной системы Яндекс.Деньги для оплаты заказа № $order_id</div>";
        $blank .= '[SETCURRENCY:RUR] <form method="POST" action="' . $url . '">
                        <input type="hidden" name="cms_name" value="siteedit">
                        <input type="hidden" name="receiver" value="' . $id_merchant . '">
                        <input type="hidden" name="formcomment" value="' . $name . '">
                        <input type="hidden" name="short-dest" value="' . $name . '">
                        <input type="hidden" name="label" value="' . $order_id . '">
                        <input type="hidden" name="quickpay-form" value="shop">
                        <input type="hidden" name="targets" value="Order ' . $order_id . '">
                        <input type="hidden" name="sum" value="[ORDER.SUMMA]" data-type="number">
                        <input type="hidden" name="need-fio" value="false">
                        <input type="hidden" name="need-email" value="false" >
                        <input type="hidden" name="need-phone" value="false">
                        <input type="hidden" name="need-address" value="false">
                        <input type="text" name="comment" value="" placeholder="Здесь вы можете оставить свой комментарий" style="width:90%"><br>
                        <div style="margin: 10px 0px 5px 0px;">
                            <input type="radio" name="paymentType" value="PC" style="height: auto;" checked> <span>Яндекс.Деньгами</span>
                        </div>
                        <div  style="margin: 0px 0px 10px 0px;">
                            <input type="radio" name="paymentType" value="AC" style="height: auto;"> <span>Банковской картой</span>
                        </div>
                        <input type="submit" name="submit-button" value="Оплатить">
                    </form>';
        $this->newPaymentLog();
        return $macros->execute($blank);
    }

    public function result()
    {
        define('SANDBOX', 1);
        $post = $_POST;
        if (SANDBOX == true) {
            $this->logs("I get POST request: <pre>" . print_r($post, 1) . "</pre>");
            $this->logs("I get GET request: <pre>" . print_r($_GET, 1) . "</pre>");
        }
        $this->order_id = intval($post['label']);
        $res = $this->getPaymentLog();
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $secret = $macros->execute('[PAYMENT.YM_SECRET]');

        $securityKey = $post['notification_type'] . '&'
            . $post['operation_id'] . '&'
            . $post['amount'] . '&'
            . $post['currency'] . '&'
            . $post['datetime'] . '&'
            . $post['sender'] . '&'
            . $post['codepro'] . '&'
            . $secret . '&'
            . $post['label'];
        $securityKey = sha1($securityKey);


        if (SANDBOX == true) {
            $this->logs("I get securityKey: <pre>" . $securityKey . "</pre>");
            $this->logs("I get sha1_hash: <pre>" . $post['sha1_hash'] . "</pre>");
        }

        if ($post['operation_id'] && ($post['sha1_hash'] == $securityKey) && ($post['withdraw_amount'] == $res['summ'])) {
            $this->activate($this->order_id);
            if (SANDBOX == true) {
                $this->logs("Order is payed");
                $this->logs("", true);
            }
        } else {
            if (SANDBOX == true) {
                $this->logs("I have a bloblem: Transaction - " . $post['operation_id'] . ' SIGN - ' . $post['sha1_hash'] . ' == ' . $securityKey . ' SUM - ' . $res['summ'] . ' == ' . $post['withdraw_amount']);
                $this->logs("", true);
            }
        }
        exit('OK');
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
            if (!is_dir(getcwd() . '/system/logs/yandex'))
                mkdir(getcwd() . '/system/logs/yandex');
            $date = date('c');
            file_put_contents(getcwd() . '/system/logs/yandex/' . $date . '.txt', $this->logText);
        }
    }
}
