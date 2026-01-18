<?php
require_once dirname(__FILE__) . "/basePayment.class.php";

/**
 * Плагин платежной системы HandyPay
 */

class payment_handypay extends basePayment
{
    public $logText;

    public function setVars()
    {
        return array(
            'hd_user' => 'Идентификатор пользователя HandyPay',
            'hd_shop_id' => 'Идентификатор партнёра',
            'hd_scid' => 'Идентификатор витрины',
            'hd_shop_article_id' => 'Идентификатор партнёра РПС,'
        );
    }

    public function startform()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        return $macros->execute($this->startform);
    }

    public function blank($pagename)
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $url = 'https://mcom.handypay.kz';
        $id_merchant = $macros->execute('[PAYMENT.HD_USER]');
        $order_id = $macros->execute("[ORDER.ID]");
        $amount = $macros->execute("[ORDER.SUMMA]");
        $amount = round(se_MoneyConvert($amount, se_BaseCurrency(), 'KZT'), 2, PHP_ROUND_HALF_UP);
        $amountKZT = $amount * 100;
        $result_url = $macros->execute($this->getPathPayment('[MERCHANT_SUCCESS]', $pagename));
        $fail_url = $macros->execute($this->getPathPayment('[MERCHANT_FAIL]', $pagename));
        $name = new seTable('shop_tovarorder', 'st');
        $name->select('sp.name');
        $name->innerjoin('shop_price sp', 'sp.id = st.id_price');
        $name->where("`id_order`='?'", $macros->execute("[ORDER.ID]"));
        $name->fetchOne();
        $nameOrder = htmlspecialchars($name->name);

        $blank = "<h4 class=\"contentTitle\">Подтверждение оплаты через HandyPay</h4>
                    <div class=''>Вы будете переведены на сайт платежной системы HandyPay для оплаты заказа № $order_id</div>";
        $blank .= '[SETCURRENCY:KZT]<form class="handypay" method="POST" action="' . $url . '">
                  <input type="hidden" name="ServiceId" value="' . $id_merchant . '"/>
                  <input type="hidden" name="Parameters" value="' . $order_id . '"/>
                  <input type="hidden" name="Amount" value="' . $amountKZT . '"/>
                  <input type="hidden" name="BackUrl" value="' . $result_url . '"/>
                  <input type="hidden" name="Description" value="' . $nameOrder . '"/>
                  <input type="submit" class="hpay" value="Оплатить через HandyPay"/>
                </form>';
        $id_yandex = $macros->execute('[PAYMENT.HD_SHOP_ID]');
        if ($id_yandex) {
            $scid = ($macros->execute('[PAYMENT.HD_SCID]')) ? $macros->execute('[PAYMENT.HD_SCID]') : '5045';
            $ShopArticleId = ($macros->execute('[PAYMENT.HD_SHOP_ARTICLE_ID]')) ? $macros->execute('[PAYMENT.HD_SHOP_ARTICLE_ID]') : '34642';
            $blank .= ' или ';
            $blank .= '[SETCURRENCY:KZT]<form class="yandex" method="POST" action="http://money.yandex.ru/eshop.xml">
                     <input type="hidden" name="scid" value="' . $scid . '">
                     <input type="hidden" name="ShopID" value="' . $id_yandex . '">
                     <input type="hidden" name="ShopArticleId" value="' . $ShopArticleId . '">
                     <input type="hidden" name="shopSuccessURL" value="' . $result_url . '">
                     <input type="hidden" name="shopFailURL" value="' . $fail_url . '">
                     <input type="hidden" name="customerNumber" value="' . $order_id . '">
                     <input type="hidden" name="Sum" value="' . $amount . '">
                     <input type="submit" class="yapay" name="BuyButton" value="Оплатить через Яндекс">
                    </form>';
        }
        $this->newPaymentLog();
        return $macros->execute($blank);
    }

    public function result()
    {
        define('SANDBOX', 1);
        if (SANDBOX == true) {
            $this->logs("I get POST request: <pre>" . print_r($_POST, 1) . "</pre>");
            $this->logs("I get GET request: <pre>" . print_r($_GET, 1) . "</pre>");
        }
        $post = $_POST;
        if (empty($post)) {
            if (SANDBOX == true) {
                $this->logs('No POST request', true);
            }
            header("Content-type: text/xml");
            echo "<response><code>error</code><msg>WRONG REQUEST</msg></response>";
            exit;
        }

        if (($post['Type'] == 'check') && ($post['Parameters'])) {
            $check = new seTable('shop_order');
            $check->find((int)$post['Parameters']);
            header("Content-type: text/xml");
            if ($check->id) {
                if ($check->status == 'Y') {
                    echo  "<response><code>error</code><msg>Order already payed</msg></response>";
                } else {
                    $this->order_id = $post['Parameters'];
                    $this->getPaymentLog();
                    if (isset($post['Currency'])) {
                        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
                        $amount = $macros->execute("[ORDER.SUMMA]");
                    }
                    if ($amount) {
                        echo  "<response><code>OK</code><msg>Order is found</msg><amount>{$amount}</amount></response>";
                    } else {
                        echo  "<response><code>error</code><msg>Order not exist</msg></response>";
                    }
                }
            } else {
                echo  "<response><code>error</code><msg>Order not exist</msg></response>";
            }
            exit;
        }
        $this->order_id = $post['Parameters'];
        $amount = $post['Amount'];
        $currency = (isset($post['Currency'])) ? $post['Currency'] : 'KZT';
        $res = $this->getPaymentLog();
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);

        if (SANDBOX == true) {
            $this->logs("I get order ID: <pre>" . $this->order_id . "</pre>");
            $this->logs("I get Amount: <pre>" . $amount . "</pre>");
            $this->logs("Amount from DB: <pre>" . $res['summ'] . "</pre>");
            $this->logs("I get currency: <pre>" . $currency . "</pre>");
        }
        $res['summ'] = round(se_MoneyConvert($macros->execute("[ORDER.SUMMA]"), se_BaseCurrency(), $currency), 2, PHP_ROUND_HALF_UP);
        $max = floatval($res['summ']) * 1.01;
        $min = floatval($res['summ']) * 0.99;
        $amount = $amount / 100;
        header("Content-type: text/xml");
        if (($min <= $amount) && ($max >= $amount)) {
            if (($post['state'] == 'SUCCESS') && ($post['Type'] == 'pay')) {
                $so = new seTable("shop_order");
                $so->find($res['order_id']);
                if ($so->id && ($so->status == 'Y')) {
                    if (SANDBOX == true) {
                        $this->logs("Order already payed");
                    }
                    echo "<response><code>error</code><msg>Order already payed</msg></response>";
                } else {
                    $this->activate($this->order_id);
                    if (SANDBOX == true) {
                        $this->logs("Order is payed");
                    }
                    echo "<response><code>OK</code><msg>Order payed</msg></response>";
                }
            } else {
                if (SANDBOX == true) {
                    $this->logs("I have a bloblem:  state - " . $post['state'] . " or Type = " . $post['Type']);
                }
                echo "<response><code>error</code><msg>WRONG STATE</msg></response>";
            }
        } else {
            if (SANDBOX == true) {
                $this->logs("I have a bloblem:  min DB summ - " . $min . " max Db summ - " . $max);
            }
            echo "<response><code>error</code><msg>WRONG AMOUNT</msg></response>";
        }
        $this->logs('', true);
        exit;
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
            if (!is_dir(getcwd() . '/system/logs/handypay'))
                mkdir(getcwd() . '/system/logs/handypay');
            $date = date('c');
            file_put_contents(getcwd() . '/system/logs/handypay/' . $date . '.txt', $this->logText);
        }
    }
}
