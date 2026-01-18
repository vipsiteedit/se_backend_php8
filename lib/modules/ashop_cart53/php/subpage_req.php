<?php

if (isRequest('company_name'))
    $company_name = trim(getRequest('company_name'));
elseif (isset($_SESSION['requisite']['company_name']))
    $company_name = $_SESSION['requisite']['company_name'];

if (isRequest('company_inn'))
    $company_inn = trim(getRequest('company_inn'));
elseif (isset($_SESSION['requisite']['company_inn']))
    $company_inn = $_SESSION['requisite']['company_inn'];
    
if (isRequest('company_phone'))
    $company_phone = trim(getRequest('company_phone')); 
elseif (isset($_SESSION['requisite']['company_phone']))
    $company_phone = $_SESSION['requisite']['company_phone'];
        
if (isRequest('company_email'))
    $company_email = trim(getRequest('company_email'));
elseif (isset($_SESSION['requisite']['company_email']))
    $company_email = $_SESSION['requisite']['company_email'];
    
if (isRequest('company_addr'))
    $company_addr = trim(getRequest('company_addr')); 
elseif (isset($_SESSION['requisite']['company_addr']))
    $company_addr = $_SESSION['requisite']['company_addr']; 

?>
