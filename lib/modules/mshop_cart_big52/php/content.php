<?php

if (empty($_SESSION['userregion']['country'])){
    if ($user_id){
        $person = new seTable('person', 'p');
        $person->select("p.country_id as country, p.state_id as region, p.town_id as city");      
        $person->where('p.id=?', $user_id); 
        $person->fetchOne();
        if ($person->isFind()){
            $country_id = $person->country;
            $region_id = $person->region;
            if (!empty($country_id) && !empty($region_id)){
                $_SESSION['userregion']['country'] = $person->country;
                $_SESSION['userregion']['region'] = $person->region;
                $city_id = $person->region;
                if (!empty($city_id))
                    $_SESSION['userregion']['city'] = $person->city;
                
            }                   
        }
    } 
    if (empty($_SESSION['userregion']['country'])){
        $_SESSION['userregion']['country'] = 10;
        $_SESSION['userregion']['region'] = 78;
        $_SESSION['userregion']['city'] = 1549;    
    }
}
 
$region_city = getRegionName();   
?>