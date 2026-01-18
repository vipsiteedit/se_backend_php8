<?php

$country_list = $region_list = $city_list = '';

if ($country_list = getRegionList()){
    $country_id = $_SESSION['userregion']['country'];
    if ($region_list = getRegionList('region', $country_id)){
        $region_id = $_SESSION['userregion']['region'];
        $city_list = getRegionList('city', $region_id);
    }     
} 


?>