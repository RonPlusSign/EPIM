<?php

/*
    author DRAKExD

*/
require_once __DIR__ . '/../classes/LoginHandler.php';
require_once __DIR__ . '/../classes/BrandsCategoriesHandler.php';
session_start();

$bch = new BrandsCategoriesHandler();
header("Content-Type: application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$bch->setPlatform("category");

switch ($requestMethod) {
    case 'GET':
        
        if (isset($_GET["id"])){ // Get the category with this id
            $risultato = $bch->getBrandsCategoriesID($_GET["id"]);
            if($risultato === false){
                http_response_code (404);
            }
            else {
                echo json_encode($risultato);
            }
        }
        else {

            // Return all the categories
             echo json_encode($bch->getBrandsCategories());
             
        }
        break;

    case 'POST':
        $json = json_decode(file_get_contents('php://input'), true);
        if(LoginHandler::isAdmin()){
           $risultato = $bch->addRecord($json["name"]);    // We have to take the name from the input JSON
           if($risultato === true){
            http_response_code (200);
           }
           else {
               http_response_code (400);
           }
        }
        else{
            http_response_code (403);
        }
        break;

    case 'DELETE':
        if(isset($_GET["id"])){
            if(LoginHandler::isAdmin()){
                $risultato = $bch->deleteRecord($_GET["id"]);    // We have to take id and name from the input JSON
                if($risultato === true){
                    http_response_code (200);
                }
                else {
                    http_response_code (404);
                }
            }
            else {
                http_response_code (403);
            }
        }     
        break;

    case 'PUT':
        $json = json_decode(file_get_contents('php://input'), true);
        if(LoginHandler::isAdmin()){
            $risultato = $bch->modifyRecord($json["id"], $json["name"]);  // We have to take id and name from the input JSON
            if($risultato === true){
                http_response_code (200);
            }
            else {
                http_response_code (400);
            }
        }
        else{
            http_response_code (403);
        }
        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
