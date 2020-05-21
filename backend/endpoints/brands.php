<?php

/*
    author DRAKExD

*/

require_once __DIR__ . '/../classes/BrandsCategoriesHandler.php';
session_start();

$bch = new BrandsCategoriesHandler();
header("Content-Type: application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case 'GET':
        if (isset($_GET["id"])) // Get the brands with this id
            echo "";
        else {
            // Return all the brands
            // Temporary data, just for testing
            echo json_encode([
                ["id" => 1, "name" => "Samsung"],
                ["id" => 2, "name" => "Apple"],
                ["id" => 3, "name" => "Xiaomi"],
                ["id" => 4, "name" => "EPIM"],
                ["id" => 5, "name" => "myBrand"]
            ]);
        }
        break;

    case 'POST':
        $json = json_decode(file_get_contents('php://input' , true));

        $bch->setPlatform("brand");
        $bch->addRecord($json["name"]);    // We have to take the name from the input JSON
        break;

    case 'DELETE':
        $json = json_decode(file_get_contents('php://input', true));

        $bch->setPlatform("brand");
        $bch->deleteRecord($json["id"]);    // We have to take the id from the input JSON
        break;

    case 'PUT':
        $bch->setPlatform("brand");
        $bch->modifyRecord($json["id"], $json["name"]);  // We have to take id and name from the input JSON
        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
