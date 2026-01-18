<?php

/**
 * @author Vladimir Sukhopyatkin
 * @copyright 2011
 * 
 * Ieaaei neo?a ainoaaee
 */

define("EMS_API_URL", "emspost.ru/api/rest/");


class plugin_shopDeliveryService
{
    /**
     * @param string $service_list - neo?au ainoaaee ?a?ac caiyoo? ("API_EMS, API_..." e a?.) 
     **/

    private $service_arr;
    private $service_keys_arr = array();

    public function __construct($service_list)
    {
        $this->service_keys_arr = explode(',', $service_list);

        $this->service_arr = array();
        foreach ($this->service_keys_arr as $value) {
            switch ($value) {
                case 'EMS':
                    $this->service_arr[$value] = new EMS_service();
                    break;
            }
        }
    }

    public function getLocations($api, $type)
    {
        switch ($api) {
            case 'EMS':
                return $this->service_arr['EMS']->getLocations($type);
        }
    }

    public function getMaxWeight($api)
    {
        switch ($api) {
            case 'EMS':
                return $this->service_arr['EMS']->getMaxWeight();
        }
    }

    public function calculate($api, $from, $to, $weight, $type = 'att')
    {
        switch ($api) {
            case 'EMS':
                return $this->service_arr['EMS']->calculate($from, $to, $weight, $type);
        }
    }
}


class EMS_service
{
    private $method;
    private $callback;
    private $plain;

    public function __construct($callback = 'jsonEMSApi', $plain = 'true')
    {
        $this->callback = ''; //$callback;
        $this->plain = $plain;
    }

    /**
     * @param $type - {cities, countries, regions, russia}
     **/
    public function getLocations($type)
    {
        //  url(EMS_API_URL.'?method=ems.get.locations&type='.$type.'&plain='.$this->plain);

        $json = file_get_contents('http://' . EMS_API_URL . '?method=ems.get.locations&type=' . $type . '&plain=' . $this->plain);
        return json_decode($json, true);
    }

    public function getMaxWeight()
    {
        $json = file_get_contents('http://' . EMS_API_URL . '?method=ems.get.max.weight');
        return json_decode($json, true);
    }

    public function calculate($from, $to, $weight, $type = 'att')
    { //callback=jsonp1236078926969 ???
        $json = file_get_contents('http://' . EMS_API_URL . '?callback=' . $this->callback . '&method=ems.calculate&from=' . $from .
            '&to=' . $to . '&weight=' . str_replace(',', '.', $weight) . '&type=' . $type);
        return json_decode($json, true);
    }
}
