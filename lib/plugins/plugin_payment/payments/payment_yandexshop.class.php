<?php
require_once dirname(__FILE__) . "/basePayment.class.php";

/**
 * Плагин платежной системы Yandex
 */

class payment_yandexshop extends basePayment
{
    public $logText;
    public function setVars()
    {
        return array('shopId' => 'ID магазина', 'scid' => 'Номер витрины', 'wmpassw' => 'секретный код');
    }

    public function startform()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        return $macros->execute($this->startform);
    }

    public function blank($pagename)
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $url = ($this->test) ? 'https://demomoney.yandex.ru/eshop.xml' : 'https://money.yandex.ru/eshop.xml';
        //$url = "https://demomoney.yandex.ru/eshop.xml";

        $shopId = $macros->execute('[PAYMENT.SHOPID]');
        $scid = $macros->execute('[PAYMENT.SCID]');
        $customerNumber = $macros->execute('[USER.USEREMAIL]');
        $customerEmail = $macros->execute('[USER.USEREMAIL]');
        $shopSuccessURL = $this->getPathPayment('[MERCHANT_SUCCESS]', $pagename);
        $shopFailURL = $this->getPathPayment('[MERCHANT_FAIL]', $pagename);

        $name = $macros->execute('[SHOPLIST.NAME]');
        $order_id = $macros->execute("[ORDER.ID]");
        $userPhone = $macros->execute("[USER.PHONE]");

        $blank = "<h4 class=\"contentTitle\">Подтверждение оплаты через Яндекс.Касса</h4>
                    <div class=''>Вы будете переведены на сайт платежной системы Яндекс.Касса для оплаты заказа № $order_id</div>";
        $blank .= '[SETCURRENCY:RUR]<form method="POST" action="' . $url . '">
                        <input type="hidden" name="cms_name" value="siteedit">
                        <input name="shopId" value="' . $shopId . '" type="hidden">
                        <input name="scid" value="' . $scid . '" type="hidden"> 
                        <input type="hidden" name="sum" value="[ORDER.SUMMA]" data-type="number">
                        <input name="customerNumber" value="' . $customerNumber . '" type="hidden"> 
                        <label>Способ оплаты:</label>
                        <select name="paymentType">
                        <option value="">Выбор платежной системы на Яндекс.Касса</option>
                        <option value="PC">Со счета в Яндекс.Деньгах</option>
                        <option value="AC">С банковской карты</option>
                        <option value="WM">Со счета WebMoney</option>
                        <option value="GP">По коду через терминал</option>
                        <option value="AB">Оплата через Альфа-Клик</option>
                        <option value="MC">Платеж со счета мобильного телефона</option>
                        <option value="MP">Оплата через мобильный терминал (mPOS)</option>
                        <option value="MA">Оплата через MasterPass</option>
                        <option value="PB">Оплата через Промсвязьбанк</option>
                        <option value="SB">Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн</option>
                        </select>
                        <input name="shopSuccessURL" value="' . $shopSuccessURL . '" type="hidden"> 
                        <input name="shopFailURL" value="' . $shopFailURL . '" type="hidden"> 
                        <input name="cps_phone" value="' . $userPhone . '" type="hidden"/>
                        <input name="orderNumber" value="' . $order_id . '" type="hidden">
                        <input name="cps_email" value="' . $customerEmail . '" type="hidden"/>
                        <input type="submit" name="submit-button" value="Оплатить">
                    </form>';
        //    <input name="orderNumber" value="'.$order_id.'" type="hidden">

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
        if ($_POST['action'] == 'checkOrder') {
            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <checkOrderResponse performedDatetime=\"" . date("c") . "\"
            code=\"0\" invoiceId=\"" . $_POST['invoiceId'] . "\" 
            shopId=\"" . $_POST['shopId'] . "\"/>\n";
            $this->logs("", true);
            exit;
        } elseif ($_POST['action'] == 'paymentAviso') {
            $this->order_id = intval($post['orderNumber']);
            $res = $this->getPaymentLog();
            $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
            $shopPassword = $macros->execute('[PAYMENT.WMPASSW]');
            $hash = strtoupper(md5($_POST['action'] . ';' . $_POST['orderSumAmount'] . ';' . $_POST['orderSumCurrencyPaycash'] . ';' . $_POST['orderSumBankPaycash'] . ';' . $_POST['shopId'] . ';' . $_POST['invoiceId'] . ';' . $_POST['customerNumber'] . ';' . $shopPassword));

            if (SANDBOX == true) {
                $this->logs($_POST['md5'] . '=' . $hash);
                $this->logs("I get sha1_hash: <pre>" . $post['md5'] . "</pre>");
            }

            if ($post['invoiceId'] && ($post['md5'] == $hash)) {
                $this->activate($this->order_id);
                echo '<?xml version="1.0" encoding="UTF-8"?>
            <paymentAvisoResponse
                performedDatetime ="' . date('c') . '"
                code="0" invoiceId="' . $_POST['invoiceId'] . '" 
                shopId="' . $_POST['shopId'] . '"/>
            ';
                if (SANDBOX == true) {
                    $this->logs("Order is payed");
                    $this->logs("", true);
                }
            } else {
                echo '<?xml version="1.0" encoding="UTF-8"?>
            <paymentAvisoResponse
                performedDatetime ="' . date('c') . '"
                code="1" invoiceId="' . $_POST['invoiceId'] . '" 
                shopId="' . $_POST['shopId'] . '"/>
            ';
                if (SANDBOX == true) {
                    $this->logs("I have a bloblem: Transaction - " . $post['action'] . ' SIGN - ' . $post['md5'] . ' == ' . $hash . ' SUM - ' . $res['summ'] . ' == ' . $post['orderSumAmount']);
                    $this->logs("", true);
                }
            }
        } else {
            if (SANDBOX == true) {
                $this->logs("", true);
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
