<?php
require_once dirname(__FILE__) . "/basePayment.class.php";

/**
 * Плагин платежной системы PayOnline
 */

class payment_payonline extends basePayment
{
    private $order_id = 0;

    public function setVars()
    {
        return array('po_user' => 'Merchant ID', 'po_passw' => 'Private security key');
    }

    public function startform()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        return $macros->execute($this->startform);
    }

    public function blank($pagename)
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $url = 'https://secure.payonlinesystem.com/ru/payment/select/';
        $id_merchant = $macros->execute('[PAYMENT.PO_USER]');
        $privateSecurityKey = $macros->execute('[PAYMENT.PO_PASSW]');
        $order_id = $macros->execute("[ORDER.ID]");
        $amount = se_FormatNumber($macros->execute("[ORDER.SUMMA]"), '');
        $order_user = $macros->execute("[USER.ID]");
        $description = 'Order:' . $order_id . '/User:' . $order_user;
        $param = 'MerchantId=' . $id_merchant
            . '&OrderId=' . $order_id
            . '&Amount=' . $amount
            . '&Currency=RUB'
            . '&OrderDescription=' . $description
            . '&PrivateSecurityKey=' . $privateSecurityKey;
        $securityKey = md5($param);
        $result_url = $macros->execute($this->getPathPayment('[MERCHANT_SUCCESS]', $pagename));
        $fail_url = $macros->execute($this->getPathPayment('[MERCHANT_FAIL]', $pagename));


        $blank = "<h4 class=\"contentTitle\">Подтверждение оплаты через Интеркассa</h4>
                    <div class=''>Вы будете переведены на сайт платежной системы payonline.ru для оплаты заказа № $order_id. Сумма вашего заказа $amount руб. без учета комиссии системы.</div>";
        $blank .= '[SETCURRENCY:RUR]<form method="post" action="' . $url . '">
                    <input name="MerchantId" value="' . $id_merchant . '" type="hidden">
                    <input name="OrderId" value="' . $order_id . '" type="hidden">
                    <input name="Amount" value="' . $amount . '" type="hidden">
                    <input name="Currency" value="RUB" type="hidden">
                    <input name="OrderDescription" value="' . $description . '" type="hidden">
                    <input name="SecurityKey" value="' . $securityKey . '" type="hidden">
                    <input name="ReturnUrl" value="' . $result_url . '" type="hidden">
                    <input name="FailUrl" value="' . $fail_url . '" type="hidden">
                    <input name="submit" value="Перейти к оплате" type="submit">
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
        $this->order_id = $post['OrderId'];
        $res = $this->getPaymentLog();
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $id_merchant = $macros->execute('[PAYMENT.PO_USER]');
        $id_key = $macros->execute('[PAYMENT.PO_PASSW]');


        $param = 'DateTime=' . $post['DateTime']
            . '&TransactionID=' . $post['TransactionID']
            . '&OrderId=' . $post['OrderId']
            . '&Amount=' . $post['Amount']
            . '&Currency=RUB'
            . '&PrivateSecurityKey=' . $id_key;
        $securityKey = md5($param);


        if (SANDBOX == true) {
            $this->logs("I get order ID: <pre>" . $this->order_id . "</pre>");
            $this->logs("I get DateTime: <pre>" . $post['DateTime'] . "</pre>");
            $this->logs("I get TransactionID: <pre>" . $post['TransactionID'] . "</pre> of system");
            $this->logs("I get Amount: <pre>" . $post['Amount'] . "</pre> of system");
            //$this->logs("I get PrivateSecurityKey: <pre>".$id_key."</pre> of system");
            $this->logs("I get id merchant: <pre>" . $id_merchant . "</pre> from system");
        }

        if ($post['TransactionID'] && ($post['SecurityKey'] == $securityKey) && ($post['Amount'] == $res['summ'])) {
            $this->activate($this->order_id);
            if (SANDBOX == true) {
                $this->logs("Order is payed");
            }
        } else {
            if (SANDBOX == true) {
                $this->logs("I have a bloblem: Transaction - " . $post['TransactionID'] . ' SIGN - ' . $post['SecurityKey'] . ' == ' . $securityKey . ' SUM - ' . $res['summ'] . ' == ' . $post['Amount']);
            }
        }
        exit;
    }

    public function success()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        /*      закомментировал проверку статуса
        $id_merchant = $macros->execute('[PAYMENT.PO_USER]');
        $id_key = $macros->execute('[PAYMENT.PO_PASSW]');

        $url = 'https://secure.payonlinesystem.com/payment/search/';
        $securityKey = 'MerchantId='.$id_merchant.'&OrderId='.$this->order_id.'&PrivateSecurityKey='.$id_key;
        $securityKey = md5($securityKey);
        $req = 'MerchantId='.$id_merchant.'&OrderId='.$this->order_id.'&SecurityKey='.$securityKey;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        $res = curl_exec($ch);  //  TransactionId=25387367&Amount=100.50&Currency=RUB&OrderId=51&DateTime=2014-06-05 04:27:12&Status=Pending
*/
        $this->success = '<h4 class="contentTitle">Оплата проведена успешно</h4><p>Ваш заказ № ' . $this->order_id . ' оплачен</p>';
        return $macros->execute($this->success);
    }

    public function fail()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $this->fail = '<h4 class="contentTitle">Ошибка в проведении платежа</h4>';
        return $macros->execute($this->fail);
    }

    private function logs($text)
    {
        file_put_contents(getcwd() . '/payonline_log.txt', $text . "\r\n <br>", FILE_APPEND);
    }
}
