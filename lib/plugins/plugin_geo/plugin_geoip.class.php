<?php
class plugin_geoip
{

    public function __construct()
    {
        if (empty($_SESSION['user_region'])) {
            $this->getCity(0, true);
        }
    }

    public function confirmCity()
    {
        $_SESSION['user_region']['confirm'] = true;
    }

    public function getSelected()
    {
        return $_SESSION['user_region'];
    }

    public function getCity($id_city = 0, $save = false)
    {
        $city = '';

        if ($id_city) {
            $query_string = '?id=' . $id_city;
        } else {
            $query_string = '?ip=' . $this->getIp();
        }

        $data = $this->getData($query_string);

        if (!empty($data)) {
            $city = current($data);
            if ($save) {
                $_SESSION['user_region'] = array(
                    'id_city' => $city['id'],
                    'id_region' => $city['region_id'],
                    'id_country' => $city['country_id'],
                    'city' => $city['city_name'],
                    'region' => $city['region_name'],
                    'country' => $city['country_name'],
                    'code_country' => mb_strtolower($city['country'])
                );
            }
        }
        return $city;
    }

    public function getData($query_string = '')
    {
        $url = 'http://ewebcms.ru/geo/getCity.php' . $query_string;

        $content = '';
        if (extension_loaded('curl')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            $content = curl_exec($ch);
            curl_close($ch);
        } else {
            $content = file_get_contents($url);
        }

        if (!empty($content)) {
            $content = json_decode($content, true);
        }
        return $content;
    }

    public function getIp()
    {
        $ip = false;
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) and preg_match("#^[0-9.]+$#", $_SERVER["HTTP_X_FORWARDED_FOR"]))
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        elseif (isset($_SERVER["HTTP_X_REAL_IP"]) and preg_match("#^[0-9.]+$#", $_SERVER["HTTP_X_REAL_IP"]))
            $ip = $_SERVER["HTTP_X_REAL_IP"];
        elseif (preg_match("#^[0-9.]+$#", $_SERVER["REMOTE_ADDR"]))
            $ip = $_SERVER["REMOTE_ADDR"];
        return $ip;
    }
}
