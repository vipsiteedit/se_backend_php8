<?php

 $last_name    = htmlspecialchars($persons->last_name);
 $first_name   = htmlspecialchars($persons->first_name);
 $sec_name     = htmlspecialchars($persons->sec_name);
 @list($b_year, $b_month, $b_day) = explode('-', $persons->birth_date, 3);
 $doc_ser      = htmlspecialchars($persons->doc_ser);
 $doc_num      = htmlspecialchars($persons->doc_num);
 $doc_registr  = htmlspecialchars($persons->doc_registr);
 $post_index   = htmlspecialchars($persons->post_index);
 $country_id   = htmlspecialchars($persons->country_id);
 $state_id     = htmlspecialchars($persons->state_id);
 $city_id      = htmlspecialchars($persons->town_id);
 $addr         = htmlspecialchars($persons->addr);
 $phone        = htmlspecialchars($persons->phone);
 $email        = htmlspecialchars($persons->email);
 $icq          = htmlspecialchars($persons->icq);
//****** считование пола  
 $sex           = htmlspecialchars($persons->sex);
 if ($sex == "M") {
     $groupoption1 = "selected";
     $groupoption2 = "";
     $groupoption3 = "";
 } else if ($sex == "F") {
    $groupoption1 = "";
    $groupoption2 = "selected";
    $groupoption3 = "";
 } else {
     $groupoption1 = "";
     $groupoption2 = "";
     $groupoption3 = "selected"; 
 }
 //******
 
 $dealer_personal="";
 
  //************************************************
// генерируем html код списка стран
 $country = new seTable('country');
 $countrylist = $country ->getList();
 $select = "";   
    foreach($countrylist as $row) { 
        if ($country_id == $row['id']){
            $select .= '<option selected value="'.$row['id'].'">'. $row['name'] . '</option>';
        } else
        $select .= '<option value="'.$row['id'].'">'. $row['name'] . '</option>';
    } 
  
  if ($country_id == "" || $country_id == 0) {
      $countryhtml = '
      <option selected value="0">'.$section->language->lang027.'</option>'
      .$select;
  } else
    $countryhtml = '
    <option value="0">'.$section->language->lang027.'</option>'
    .$select;  
    
//************************************************
//Генерируем html код списка регионов 
 $region = new seTable('region');
 $region -> where('id_country=?',$country_id );
 $regionlist = $region ->getList();
 $select = ""; 

    foreach($regionlist as $row) {
        if ($state_id == $row['id']){
            $select .= '<option selected value="'.$row['id'].'">'. $row['name'] . '</option>';
        } else
        $select .= '<option value="'.$row['id'].'">'. $row['name'] . '</option>';
    } 
  
  if (!$state_id) {
      $regionhtml = '
      <option selected value="0">'. $section->language->lang028 .'</option>'
      .$select;
  } else
    $regionhtml = '
    <option value="0">'.$section->language->lang028.'</option>'
    .$select; 

//************************************************
//Генерируем html код списка городов 
 $town = new seTable('town');
 $town -> where('id_region=?',$state_id);
 $townlist = $town ->getList();
 $select = "";   
    foreach($townlist as $row) {
        if ($city_id == $row['id']){
            $select .= '<option selected value="'.$row['id'].'">'. $row['name'] . '</option>';
        } else
        $select .= '<option value="'.$row['id'].'">'. $row['name'] . '</option>';
    } 
  
  if (!$city_id) {
      $townhtml = '
      <option selected value="0">'.$section->language->lang029.'</option>'
      .$select;
  } else
    $townhtml = '
    <option value="0">'.$section->language->lang029.'</option>'
    .$select; 
//************************************************




 $last_name    = htmlspecialchars_decode($last_name);
 $first_name   = htmlspecialchars_decode($first_name);
 $sec_name     = htmlspecialchars_decode($sec_name);

 $doc_ser      = htmlspecialchars_decode($doc_ser);
 $doc_num      = htmlspecialchars_decode($doc_num);
 $doc_registr  = htmlspecialchars_decode($doc_registr);
 $post_index   = htmlspecialchars_decode($post_index);
 $country_id   = htmlspecialchars_decode($country_id);
 $state_id     = htmlspecialchars_decode($state_id);
 $city_id      = htmlspecialchars_decode($city_id);
 $addr         = htmlspecialchars_decode($addr);
 $phone        = htmlspecialchars_decode($phone);
 $email        = htmlspecialchars_decode($email);
 $icq          = htmlspecialchars_decode($icq);
 $sex          = htmlspecialchars_decode($sex);
?>