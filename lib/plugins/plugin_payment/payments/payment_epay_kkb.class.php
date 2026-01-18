<?php
require_once dirname(__FILE__) . "/basePayment.class.php";
require_once dirname(__FILE__) . "/kkb/kkb.utils.php";

/**
 * Плагин платежной системы Казкомерц банк
 */

class payment_epay_kkb extends basePayment
{
    private $order_id;
    public $logText;

    public function setVars()
    {
        return array(
            'kkb_company' => 'Название компании',
            'kkb_id' => 'ID продавца',
            'kkb_serial' => 'Серийный номер сертификата',
            'kkb_pub_cert' => 'Открытый ключ',
            'kkb_prv_cert' => 'Закрытый ключ',
            'kkb_pass' => 'Пароль к закрытому ключу',
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

        $id_serial = $macros->execute('[PAYMENT.KKB_SERIAL]');
        $company_name = $macros->execute('[PAYMENT.KKB_COMPANY]');
        $prv_key = $macros->execute('[PAYMENT.KKB_PRV_CERT]');
        $pass = $macros->execute("[PAYMENT.KKB_PASS]");
        $pub_key = $macros->execute('[PAYMENT.KKB_PUB_CERT]');
        $id_merchant = $macros->execute("[PAYMENT.KKB_ID]");
        $config = array(
            "MERCHANT_CERTIFICATE_ID" => $id_serial,                //  Серийный номер сертификата Cert Serial Number
            "MERCHANT_NAME" => $company_name,                       //  Название магазина (продавца) Shop/merchant Name
            "PRIVATE_KEY_FN" => $prv_key,                           //  Путь к закрытому ключу Private cert path
            "PRIVATE_KEY_PASS" => $pass,                            //  Пароль к закрытому ключу Private cert password
            "XML_TEMPLATE_FN" => dirname(__FILE__) . "/kkb/template.xml",                   //  Путь к XML шаблону XML template path
            "XML_COMMAND_TEMPLATE_FN" => dirname(__FILE__) . "/kkb/command_template.xml",   //  Путь к XML шаблону для команд (возврат/подтверждение)
            "PUBLIC_KEY_FN" => $pub_key,                            //  Путь к открытому ключу Public cert path
            "MERCHANT_ID" => $id_merchant                           //  Терминал ИД в банковской Системе
        );

        $url = (!$this->test) ? 'https://epay.kkb.kz/jsp/process/logon.jsp' : 'https://3dsecure.kkb.kz/jsp/process/logon.jsp';
        $currency_id = '398';
        $order_id_config = $macros->execute("[ORDER.ID]");
        $order_id = $order_id_config * 100000;
        $order_id = ($this->test) ? '000001' : $order_id;
        $amount = $macros->execute("[ORDER.SUMMA]");
        $amount = round(se_MoneyConvert($amount, se_BaseCurrency(), 'KZT'), 2, PHP_ROUND_HALF_UP);
        //$amount *= 100;
        $amount = ($this->test) ? '10' : $amount;
        $content = process_request($order_id, $currency_id, $amount, $config);
        //echo $content;
        $email = $macros->execute("[USER.USEREMAIL]");
        $success_url = $macros->execute($this->getPathPayment('[MERCHANT_SUCCESS]', $pagename));
        $result_url = $macros->execute($this->getPathPayment('[MERCHANT_RESULT]', $pagename));

        $blank = "<h4 class=\"contentTitle\">Подтверждение оплаты через Казкомерц банк</h4>
                    <div class=''>Вы будете переведены на сайт платежной системы Казкомерц банк для оплаты заказа № $order_id_config</div>";
        $blank .= '[SETCURRENCY:KZT]<form method="POST" action="' . $url . '">
                        <input type="hidden" name="Signed_Order_B64" value="' . $content . '">
                        <input type="hidden" name="email" value="' . $email . '">
                        <input type="hidden" name="BackLink" value="' . $success_url . '">
                        <input type="hidden" name="PostLink" value="' . $result_url . '">
                        <input type="submit" value="Оплатить" >
                </form>';
        $this->newPaymentLog();
        return $macros->execute($blank);
    }

    public function result()
    {
        define('SANDBOX', 1);
        $post = $_POST;
        if (SANDBOX == true) {
            $this->logs("I get POST request: <pre>" . print_r($_POST, 1) . "</pre>");
            $this->logs("I get GET request: <pre>" . print_r($_GET, 1) . "</pre>");
        }

        $macros = new plugin_macros();
        $id_serial = $macros->execute('[PAYMENT.KKB_SERIAL]');
        $company_name = $macros->execute('[PAYMENT.KKB_COMPANY]');
        $prv_key = $macros->execute('[PAYMENT.KKB_PRV_CERT]');
        $pass = $macros->execute("[PAYMENT.KKB_PASS]");
        $pub_key = $macros->execute('[PAYMENT.KKB_PUB_CERT]');
        $id_merchant = $macros->execute("[PAYMENT.KKB_ID]");
        $config = array(
            "MERCHANT_CERTIFICATE_ID" => $id_serial,                //  Серийный номер сертификата Cert Serial Number
            "MERCHANT_NAME" => $company_name,                       //  Название магазина (продавца) Shop/merchant Name
            "PRIVATE_KEY_FN" => $prv_key,                           //  Путь к закрытому ключу Private cert path
            "PRIVATE_KEY_PASS" => $pass,                            //  Пароль к закрытому ключу Private cert password
            "XML_TEMPLATE_FN" => dirname(__FILE__) . "/kkb/template.xml",                   //  Путь к XML шаблону XML template path
            "XML_COMMAND_TEMPLATE_FN" => dirname(__FILE__) . "/kkb/command_template.xml",   //  Путь к XML шаблону для команд (возврат/подтверждение)
            "PUBLIC_KEY_FN" => $pub_key,                            //  Путь к открытому ключу Public cert path
            "MERCHANT_ID" => $id_merchant                           //  Терминал ИД в банковской Системе
        );
        if (isset($post["response"])) {
            $response = $post["response"];
        }
        $result = process_response(stripslashes($response), $config);
        //foreach ($result as $key => $value) {echo $key." = ".$value."<br>";};
        if (is_array($result)) {
            if (in_array("ERROR", $result)) {
                if (SANDBOX == true) {
                    if ($result["ERROR_TYPE"] == "ERROR") {
                        $this->logs("System error: " . $result["ERROR"]);
                    } elseif ($result["ERROR_TYPE"] == "system") {
                        $this->logs("Bank system error > Code: '" . $result["ERROR_CODE"] . "' Text: '" . $result["ERROR_CHARDATA"] . "' Time: '" . $result["ERROR_TIME"] . "' Order_ID: '" . $result["RESPONSE_ORDER_ID"] . "'");
                    } elseif ($result["ERROR_TYPE"] == "auth") {
                        $this->logs("Bank system user autentication error > Code: '" . $result["ERROR_CODE"] . "' Text: '" . $result["ERROR_CHARDATA"] . "' Time: '" . $result["ERROR_TIME"] . "' Order_ID: '" . $result["RESPONSE_ORDER_ID"] . "'");
                    }
                    $this->logs('', true);
                }
                echo "0";
                exit;
            }
            if (in_array("DOCUMENT", $result)) {
                if (SANDBOX == true) {
                    $getResult = '';
                    foreach ($result as $key => $value) {
                        $getResult .= "Postlink Result: " . $key . " = " . $value . "<br>";
                    }
                    $this->logs("Result DATA: <br>" . $getResult);
                }

                unset($macros);
                $this->order_id = $result['ORDER_ORDER_ID'] / 100000;
                $res = $this->getPaymentLog();
                $macros = new plugin_macros(0, intval($this->order_id), $this->payment_id);

                $res['summ'] = round(se_MoneyConvert($res['summ'], se_BaseCurrency(), 'KZT'), 2, PHP_ROUND_HALF_UP);
                $max = floatval($res['summ']) * 1.01;
                $min = floatval($res['summ']) * 0.99;
                if (($result['CHECKRESULT'] == '[SIGN_GOOD]')
                    &&  ($min <= $result['ORDER_AMOUNT'])
                    && ($max >= $result['ORDER_AMOUNT'])
                ) {
                    $this->activate($this->order_id);
                    if (SANDBOX == true) {
                        $this->logs("Order is payed", true);
                    }
                    echo "1";
                } else {
                    if (SANDBOX == true) {
                        $this->logs("I have a bloblem: CHECKRESULT - " . $result['CHECKRESULT'] . ' MIN SUM - ' . $min . ' MAX SUM - ' . $max, true);
                        echo "0";
                    }
                }
                exit;
            }
        } else {
            if (SANDBOX == true) {
                $this->logs("System error " . $result, true);
            }
        }
        echo "0";
        exit;

        exit;
    }

    public function success()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $this->success = '<h4 class="contentTitle">Оплата заказа</h4><p>Ваш заказ № ' . $this->order_id . ' принят</p>';
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
            if (!is_dir(getcwd() . '/system/logs/epay_kkb'))
                mkdir(getcwd() . '/system/logs/epay_kkb');
            $date = date('c');
            file_put_contents(getcwd() . '/system/logs/epay_kkb/' . $date . '.txt', $this->logText);
            //file_put_contents(getcwd().'/epay_kkb_log.txt', $text."\r\n <br>", FILE_APPEND);
        }
    }
}
