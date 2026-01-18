<?php

/**
 *  Модуль для создания/проверки подписи приватным/публичным ключом.
 *  KKBSign class
 */

class KKBsign
{
    /**
     * Загрузка приватного ключа в PEM формате
     * @param $filename
     * @param null $password
     * @return bool|string
     */

    public $ecode = 0;
    public $estatus;
    private $private_key;
    public $invert = 0;
    private $pubkey;

    public function load_private_key($c, $password = NULL)
    {
        $this->ecode = 0;
        if (strlen(trim($c)) == 0) {
            $this->ecode = 4;
            $this->estatus = "[KEY_FILE_NOT_FOUND]";
            return false;
        }
        //$c = file_get_contents($filename);
        if (strlen(trim($password)) > 0) {
            $prvkey = openssl_get_privatekey($c, $password);
            $this->parse_errors(openssl_error_string());
        } else {
            $prvkey = openssl_get_privatekey($c);
            $this->parse_errors(openssl_error_string());
        }
        if (is_resource($prvkey)) {
            $this->private_key = $prvkey;
            return $c;
        }
        return false;
    }

    /**
     * Установка флага инверсии
     */
    public function invert()
    {
        $this->invert = 1;
    }

    /**
     * Инверсия строки
     * @param $str
     * @return string
     */
    public function reverse($str)
    {
        return strrev($str);
    }

    /**
     * Подпись загруженным ключом строки $str
     * @param $str
     * @return string
     */
    public function sign($str)
    {
        if ($this->private_key) {
            openssl_sign($str, $out, $this->private_key);
            if ($this->invert == 1)
                $out = $this->reverse($out);
            //openssl_free_key($this->private_key);
            return $out;
        };
    }

    /**
     * кодирование в Base64
     * @param $str
     * @return string
     */
    public function sign64($str)
    {
        return base64_encode($this->sign($str));
    }

    /**
     * Проверка публичным ключом $file, является ли строка $str в Base64 подписанной приватным ключом строкой $data.
     * @param $data
     * @param $str
     * @param $filename
     * @return int
     */
    public function check_sign($data, $str, $filename)
    {
        if ($this->invert == 1)
            $str = $this->reverse($str);
        if (strlen(trim($filename)) == 0) {
            $this->ecode = 4;
            $this->estatus = "[KEY_FILE_NOT_FOUND]";
            return 2;
        }
        $this->pubkey = $filename;
        $pubkeyid = openssl_get_publickey($this->pubkey);
        $this->parse_errors(openssl_error_string());
        if (is_resource($pubkeyid)) {
            $result = openssl_verify($data, $str, $pubkeyid);
            $this->parse_errors(openssl_error_string());
            openssl_free_key($pubkeyid);
            return $result;
        }
        return 3;
    }

    /**
     * @param $data
     * @param $str
     * @param $filename
     * @return int
     */
    public function check_sign64($data, $str, $filename)
    {
        return $this->check_sign($data, base64_decode($str), $filename);
    }

    /**
     * @param $error
     * error:0906D06C - Error reading Certificate. Verify Cert type.
     * error:06065064 - Bad decrypt. Verify your Cert password or Cert type.
     * error:0906A068 - Bad password read. Maybe empty password.
     */
    function parse_errors($error)
    {
        if (strlen($error) > 0) {
            if (strpos($error, "error:0906D06C") > 0) {
                $this->ecode = 1;
                $this->estatus = "Error reading Certificate. Verify Cert type.";
            }
            if (strpos($error, "error:06065064") > 0) {
                $this->ecode = 2;
                $this->estatus = "Bad decrypt. Verify your Cert password or Cert type.";
            }
            if (strpos($error, "error:0906A068") > 0) {
                $this->ecode = 3;
                $this->estatus = "Bad password read. Maybe empty password.";
            }
            if ($this->ecode = 0) {
                $this->ecode = 255;
                $this->estatus = $error;
            }
        }
    }
};

/**
 * Class xml
 */
class xml
{
    var $parser;
    var $xarray = array();
    var $lasttag;

    public function xml()
    {
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, true);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");
    }

    public function parse($data)
    {
        xml_parse($this->parser, $data);
        ksort($this->xarray, SORT_STRING);
        return $this->xarray;
    }

    public function tag_open($parser, $tag, $attributes)
    {
        $this->lasttag = $tag;
        $this->xarray['TAG_' . $tag] = $tag;
        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $this->xarray[$tag . '_' . $key] = $value;
            };
        };
    }

    public function cdata($parser, $cdata)
    {
        $tag = $this->lasttag;
        $this->xarray[$tag . '_CHARDATA'] = $cdata;
    }

    public function tag_close($parser, $tag)
    {
    }
}

/**
 * Обработка XML шаблона - замена тэгов в файле на значения из массива
 * @param $filename - строка: имя XML шаблона
 * @param $reparray - массив: данные для замены
 * @return mixed|string
 */
function process_XML($filename, $reparray)
{
    if (is_file($filename)) {
        $content = file_get_contents($filename);
        foreach ($reparray as $key => $value) {
            $content = str_replace("[" . $key . "]", $value, $content);
        }
        return $content;
    } else {
        return "[ERROR]";
    }
}

/**
 * Обработка XML строки в массивзначений
 * @param $xml - строка: xml строка
 * @param $tag - строка: имя тэга разделителя
 * @return array
 * $array["LETTER"] = XML секция заключенная в <$tag></$tag>
 * $array["SIGN"] = XML секция подписи заключенная в <$tag+"_sign"></$tag+"_sign">
 * $array["RAWSIGN"] = XML секция подписи с отрезанными <$tag+"_sign"></$tag+"_sign"> тэгами
 */
function split_sign($xml, $tag)
{
    $array = array();
    $letterst = stristr($xml, "<" . $tag);
    $signst = stristr($xml, "<" . $tag . "_SIGN");
    $signed = stristr($xml, "</" . $tag . "_SIGN");
    $doced = stristr($signed, ">");
    $array['LETTER'] = substr($letterst, 0, -strlen($signst));
    $array['SIGN'] = substr($signst, 0, -strlen($doced) + 1);
    $rawsignst = stristr($array['SIGN'], ">");
    $rawsigned = stristr($rawsignst, "</");
    $array['RAWSIGN'] = substr($rawsignst, 1, -strlen($rawsigned));
    return $array;
}

/**
 * Обработка входных данных в полный банковский запрос
 * @param $order_id - целое: номер заказа - перекодируется в 6 значный формат с ведущими нулями
 * @param $currency_code - строка: заданные шифры валют 840-USD, 398-Tenge
 * @param $amount - целое: общая сумма платежа
 * @param $config - массив: конфигурация
 * @param bool $b64 - булевое: флаг для кодирования результата в base64 по умолчанию = true
 * @return string - "<document><merchant cert_id="123" name="test"><order order_id="000001" amount="10" currency="398">
 * <department merchant_id="12345" amount="10"/></order></merchant><merchant_sign type="RSA">LJlkjkLHUgkjhgmnYI</merchant_sign>
 * </document>"
 */
function process_request($order_id, $currency_code, $amount, $config, $b64 = true)
{

    if (empty($config)) {
        return "Config not exist";
    }

    if (strlen($order_id) > 0) {
        if (is_numeric($order_id)) {
            if ($order_id > 0) {
                $order_id = sprintf("%06d", $order_id);
            } else {
                return "Null Order ID";
            }
        } else {
            return "Order ID must be number";
        }
    } else {
        return "Empty Order ID";
    }

    if (strlen($currency_code) == 0) {
        return "Empty Currency code";
    }
    if ($amount == 0) {
        return "Nothing to charge";
    }
    if (strlen($config['PRIVATE_KEY_FN']) == 0) {
        return "Path for Private key not found";
    }
    if (strlen($config['XML_TEMPLATE_FN']) == 0) {
        return "Path for Private key not found";
    }

    $request = array();
    $request['MERCHANT_CERTIFICATE_ID'] = $config['MERCHANT_CERTIFICATE_ID'];
    $request['MERCHANT_NAME'] = $config['MERCHANT_NAME'];
    $request['ORDER_ID'] = $order_id;
    $request['CURRENCY'] = $currency_code;
    $request['MERCHANT_ID'] = $config['MERCHANT_ID'];
    $request['AMOUNT'] = $amount;

    $kkb = new KKBSign();
    $kkb->invert();
    if (!$kkb->load_private_key($config['PRIVATE_KEY_FN'], $config['PRIVATE_KEY_PASS'])) {
        if ($kkb->ecode > 0) {
            return $kkb->estatus;
        }
    }

    $result = process_XML($config['XML_TEMPLATE_FN'], $request);
    if (strpos($result, "[RERROR]") > 0) {
        return "Error reading XML template.";
    }
    $result_sign = '<merchant_sign type="RSA">' . $kkb->sign64($result) . '</merchant_sign>';
    $xml = "<document>" . $result . $result_sign . "</document>";
    if ($b64) {
        return base64_encode($xml);
    } else {
        return $xml;
    }
}

/**
 * Обработка входящего XML в массив значений с проверкой электронной подписи
 * @param $response - строка: XML ответ от банка
 * @param $config_file - строка: полный путь к файлу конфигурации
 * @return array|string - массив с нарезанным XML и результатом проверки подписи
 */
function process_response($response, $config)
{
    if (empty($config)) {
        $data["ERROR"] = "Config not exist";
        $data["ERROR_TYPE"] = "ERROR";
        return $data;
    }

    $xml_parser = new xml();
    $result = $xml_parser->parse($response);
    if (in_array("ERROR", $result)) {
        return $result;
    }
    if (in_array("DOCUMENT", $result)) {
        $kkb = new KKBSign();
        $kkb->invert();
        $data = split_sign($response, "BANK");
        $check = $kkb->check_sign64($data['LETTER'], $data['RAWSIGN'], $config['PUBLIC_KEY_FN']);
        if ($check == 1)
            $data['CHECKRESULT'] = "[SIGN_GOOD]";
        elseif ($check == 0)
            $data['CHECKRESULT'] = "[SIGN_BAD]";
        else
            $data['CHECKRESULT'] = "[SIGN_CHECK_ERROR]: " . $kkb->estatus;
        return array_merge($result, $data);
    }
    return "[XML_DOCUMENT_UNKNOWN_TYPE]";
}

/**
 * Возврат средств по уже проведённой транзакции
 * @param $reference - integer: ID транзакции
 * @param $approval_code - string: код подтверждения транзакции
 * @param $order_id - целое: номер заказа - перекодируется в 6 значный формат с ведущими нулями
 * @param $currency_code - строка: заданные шифры валют 840-USD, 398-Tenge
 * @param $amount - целое: общая сумма платежа
 * @param $reason - строка: причина возврата средств
 * @param $config_file - строка: полный путь к файлу конфигурации
 * @return string - "<document><merchant cert_id="123" name="test"><command type="reverse"/>
 * <payment reference="016604285111" orderid="000001" amount="10" currency="398" />
 * <reason>Order cancelled</reason>
 * </merchant><merchant_sign type="RSA">LJlkjkLHUgkjhgmnYI</merchant_sign>
 * </document>"
 */
function process_refund($reference, $approval_code, $order_id, $currency_code, $amount, $reason, $config_file)
{
    if (!$reference) return "Empty Transaction ID";

    if (is_file($config_file)) {
        $config = parse_ini_file($config_file, 0);
    } else {
        return "Config not exist";
    }

    if (strlen($order_id) > 0) {
        if (is_numeric($order_id)) {
            if ($order_id > 0) {
                $order_id = sprintf("%06d", $order_id);
            } else {
                return "Null Order ID";
            };
        } else {
            return "Order ID must be number";
        };
    } else {
        return "Empty Order ID";
    };

    if (!$reason)
        $reason = "Transaction revert";

    if (strlen($currency_code) == 0) {
        return "Empty Currency code";
    }
    if ($amount == 0) {
        return "Nothing to charge";
    }
    if (strlen($config['PRIVATE_KEY_FN']) == 0) {
        return "Path for Private key not found";
    }
    if (strlen($config['XML_COMMAND_TEMPLATE_FN']) == 0) {
        return "Path to xml command template not found";
    }

    $request = array();
    $request['MERCHANT_ID'] = $config['MERCHANT_ID'];
    $request['MERCHANT_NAME'] = $config['MERCHANT_NAME'];
    $request['COMMAND'] = 'reverse';
    $request['REFERENCE_ID'] = $reference;
    $request['APPROVAL_CODE'] = $approval_code;
    $request['ORDER_ID'] = $order_id;
    $request['CURRENCY'] = $currency_code;
    $request['MERCHANT_ID'] = $config['MERCHANT_ID'];
    $request['AMOUNT'] = $amount;
    $request['REASON'] = $reason;

    $kkb = new KKBSign();
    $kkb->invert();
    if (!$kkb->load_private_key($config['PRIVATE_KEY_FN'], $config['PRIVATE_KEY_PASS'])) {
        if ($kkb->ecode > 0) {
            return $kkb->estatus;
        }
    }

    $result = process_XML($config['XML_COMMAND_TEMPLATE_FN'], $request);
    if (strpos($result, "[RERROR]") > 0) {
        return "Error reading XML template.";
    }
    $result_sign = '<merchant_sign type="RSA" cert_id="' . $config['MERCHANT_CERTIFICATE_ID'] . '">' . $kkb->sign64($result) . '</merchant_sign>';
    $xml = "<document>" . $result . $result_sign . "</document>";
    return $xml;
}

/**
 * Возврат средств по уже проведённой транзакции
 * @param $reference - integer: ID транзакции
 * @param $approval_code - string: код подтверждения транзакции
 * @param $order_id - целое: номер заказа - перекодируется в 6 значный формат с ведущими нулями
 * @param $currency_code - строка: заданные шифры валют 840-USD, 398-Tenge
 * @param $amount - целое: общая сумма платежа
 * @param $config_file - строка: полный путь к файлу конфигурации
 * @return string - "<document><merchant cert_id="123" name="test"><command type="complete"/>
 * <payment reference="016604285111" orderid="000001" amount="10" currency="398" />
 * <reason>Order cancelled</reason>
 * </merchant><merchant_sign type="RSA">LJlkjkLHUgkjhgmnYI</merchant_sign>
 * </document>"
 */
function process_complete($reference, $approval_code, $order_id, $currency_code, $amount, $config_file)
{
    if (!$reference) return "Empty Transaction ID";

    if (is_file($config_file)) {
        $config = parse_ini_file($config_file, 0);
    } else {
        return "Config not exist";
    }

    if (strlen($order_id) > 0) {
        if (is_numeric($order_id)) {
            if ($order_id > 0) {
                $order_id = sprintf("%06d", $order_id);
            } else {
                return "Null Order ID";
            }
        } else {
            return "Order ID must be number";
        }
    } else {
        return "Empty Order ID";
    }

    if (strlen($currency_code) == 0) {
        return "Empty Currency code";
    }
    if ($amount == 0) {
        return "Nothing to charge";
    }
    if (strlen($config['PRIVATE_KEY_FN']) == 0) {
        return "Path for Private key not found";
    }
    if (strlen($config['XML_COMMAND_TEMPLATE_FN']) == 0) {
        return "Path for xml command template not found";
    }

    $request = array();
    $request['MERCHANT_ID'] = $config['MERCHANT_ID'];
    $request['MERCHANT_NAME'] = $config['MERCHANT_NAME'];
    $request['COMMAND'] = 'complete';
    $request['REFERENCE_ID'] = $reference;
    $request['APPROVAL_CODE'] = $approval_code;
    $request['ORDER_ID'] = $order_id;
    $request['CURRENCY'] = $currency_code;
    $request['MERCHANT_ID'] = $config['MERCHANT_ID'];
    $request['AMOUNT'] = $amount;
    $request['REASON'] = '';

    $kkb = new KKBSign();
    $kkb->invert();
    if (!$kkb->load_private_key($config['PRIVATE_KEY_FN'], $config['PRIVATE_KEY_PASS'])) {
        if ($kkb->ecode > 0) {
            return $kkb->estatus;
        }
    }

    $result = process_XML($config['XML_COMMAND_TEMPLATE_FN'], $request);
    if (strpos($result, "[RERROR]") > 0) {
        return "Error reading XML template.";
    }
    $result_sign = '<merchant_sign type="RSA" cert_id="' . $config['MERCHANT_CERTIFICATE_ID'] . '">' . $kkb->sign64($result) . '</merchant_sign>';
    $xml = "<document>" . $result . $result_sign . "</document>";
    return $xml;
}
