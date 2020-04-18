<?php

/*
    author DRAKExD

*/

require_once __DIR__ . '/../classes/BrandsCategoriesHandler.php';

$ph = new BransCategoriesHandler();
$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) 
{   case 'POST' :
        add_record();
    break;       
    
    case 'DELETE' : 
        delete_record();
    break;

    case 'PUT' :
        modify_record();
    break;

    default:
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}

