<?php

class plugin_geoI18{

    private $lang = 0;
    private $format_json = false;

    public function __construct($lang = 0, $format_json = false)
    {
        $this->lang = $lang;
        $this->format_json = $format_json;
        /* 
             0 - Русский
             1 - Украинский
             3 - Английский
             4 - Испанский
             6 - Немецкий
             7 - Итальянский
             12- Португальский
             16- Французский
          */
    } 


    public function getCountryList()
    {
        $headerOptions = array(
            'http' => array(
            'method' => "GET",
            'header' => "Accept-language: en\r\nCookie: remixlang={$this->lang}\r\n"
            )
        );
        $methodUrl = 'http://api.vk.com/method/database.getCountries?v=5.5&need_all=1&count=1000';
        $streamContext = stream_context_create($headerOptions);
        $json = file_get_contents($methodUrl, false, $streamContext);
        if (!$this->format_json)
          return json_decode($json, true);
        else 
          return $json;
    }

    public function getRegionList($countryId = 1)
    {
        $headerOptions = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: remixlang={$this->lang}\r\n"
            )
        );
        $methodUrl = 'http://api.vk.com/method/database.getRegions?v=5.5&need_all=1&offset=0&count=1000&country_id=' . $countryId;
        $streamContext = stream_context_create($headerOptions);
        $json = file_get_contents($methodUrl, false, $streamContext);
        if (!$this->format_json)
          return json_decode($json, true);
        else 
          return $json;
    }

    public function getTownList($countryId, $regionId)
    {
        $headerOptions = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: remixlang={$this->lang}\r\n"
            )
        );
        $methodUrl = 'http://api.vk.com/method/database.getCities?v=5.5&country_id=' . $countryId . '&ion_id=' . $regionId . '&offset=0&need_all=1&count=1000';
        $streamContext = stream_context_create($headerOptions);
        $json = file_get_contents($methodUrl, false, $streamContext);
        if (!$this->format_json)
          return json_decode($json, true);
        else 
          return $json;
    }
}
