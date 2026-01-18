<?php
require_once dirname(__FILE__) . "/basePayment.class.php";

/**
 * Плагин платежной системы Robokassa
 */

class payment_robokassa extends basePayment
{
    private $order_id;
    public function setVars()
    {
        return array('rk_user' => 'Идентификатор магазина', 'rk_passw1' => 'Пароль 1', 'rk_passw2' => 'Пароль 2');
    }

    public function startform()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        return $macros->execute($this->startform);
    }

    public function blank($pagename)
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $url = ($this->test) ? 'http://test.robokassa.ru/Index.aspx' : 'https://merchant.roboxchange.com/Index.aspx';
        $id_merchant = $macros->execute('[PAYMENT.RK_USER]');
        $amount = $macros->execute("[ORDER.SUMMA]");
        $order_id = $macros->execute("[ORDER.ID]");
        $order_user = $macros->execute("[USER.ID]");
        $description = 'Order:' . $order_id . '/User:' . $order_user;
        $passw1 = $macros->execute('[PAYMENT.RK_PASSW1]');
        $temp_str = "$id_merchant:$amount:$order_id:$passw1:Shp_item=$order_user";
        $crc  = md5($temp_str);

        $blank = "<h4 class=\"contentTitle\">Подтверждение оплаты</h4>
                    <div class=''>Вы будете переведены на сайт платежной системы ROBOKASSA для оплаты заказа № $order_id</div>";
        $blank .= "[SETCURRENCY:RUR]<form action='" . $url . "' method='POST'>" .
            "<input type='hidden' name='MrchLogin' value='" . $id_merchant . "'>" .
            "<input type='hidden' name='OutSum' value='" . $amount . "'>" .
            "<input type='hidden' name='InvId' value='" . $order_id . "'>" .
            "<input type='hidden' name='Desc' value='" . $description . "'>" .
            "<input type='hidden' name='SignatureValue' value='" . $crc . "'>" .
            "<input type='hidden' name='Shp_item' value='" . $order_user . "'>" .
            "<input type='hidden' name='IncCurrLabel' value=''>" .
            "<input type='hidden' name='Culture' value='ru'>" .
            "<input type='submit' value='Оплатить'>" .
            "</form>";
        $this->newPaymentLog();
        return $macros->execute($blank);
    }

    public function result()
    {
        define('SANDBOX', 0);
        $post = $_POST;
        if (SANDBOX == true) {
            $this->logs("I get POST request: <pre>" . print_r($post, 1) . "</pre>");
            $this->logs("I get GET request: <pre>" . print_r($_GET, 1) . "</pre>");
        }
        $this->order_id = $post['InvId'];
        $res = $this->getPaymentLog();
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $passw2 = $macros->execute('[PAYMENT.RK_PASSW2]');
        $crc = strtoupper($post["SignatureValue"]);
        $temp_str = $post['OutSum'] . ":" . $post['InvId'] . ":" . $passw2 . ":Shp_item=" . $post['Shp_item'];
        $temp_str = md5($temp_str);
        $signature = strtoupper($temp_str);

        if (SANDBOX == true) {
            $this->logs("I get order ID: <pre>" . $this->order_id . "</pre>");
            $this->logs("I get signature: <pre>" . $signature . "</pre>");
        }

        if (($crc == $signature) && ($post['OutSum'] == $res['summ'])) {
            $this->activate($this->order_id);
            if (SANDBOX == true) {
                $this->logs("Order is payed");
            }
            echo "OK{$this->order_id}\n";
        } else {
            if (SANDBOX == true) {
                $this->logs("I have a bloblem: SIGN - " . $crc . ' == ' . $signature . ' SUM - ' . $res['summ'] . ' == ' . $post['OutSum']);
            }
            echo "bad sign\n";
        }
        exit;
    }

    public function success()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $passw1 = $macros->execute('[PAYMENT.RK_PASSW1]');
        $out_summ = $_POST["OutSum"];
        $inv_id = $_POST["InvId"];
        $shp_item = $_POST["Shp_item"];
        $crc = strtoupper($_POST["SignatureValue"]);

        $signature = strtoupper(md5("$out_summ:$inv_id:$passw1:Shp_item=$shp_item"));

        if ($crc != $signature)
            $this->success = '<h4 class="contentTitle">Ошибка в проведении платежа</h4><p>Что-то случилось. Обратитесь к администратору сайта. Ваш заказ № ' . $this->order_id . '</p>';
        else
            $this->success = '<h4 class="contentTitle">Оплата проведена успешно</h4><p>Ваш заказ № ' . $this->order_id . ' оплачен</p>';
        return $macros->execute($this->success);
    }

    public function fail()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $this->fail = '<h4 class="contentTitle">Ошибка в проведении платежа</h4><p>Что-то случилось. Обратитесь к администратору сайта. Ваш заказ № ' . $this->order_id . '</p>';
        return $macros->execute($this->fail);
    }

    private function logs($text)
    {
        file_put_contents(getcwd() . '/robokassa_log.txt', $text . "\r\n <br>", FILE_APPEND);
    }
}
