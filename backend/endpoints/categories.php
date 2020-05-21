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
        if (isset($_GET["id"])) // Get the category with this id
            echo "";
            // $var = $_GET["id"];
        else {
            // Return all the categories
            // Temporary data, just for testing
            echo json_encode([
                ["id" => 1, "name" => "Telefoni"],
                ["id" => 2, "name" => "Televisori"],
                ["id" => 3, "name" => "Lavatrici"],
                ["id" => 4, "name" => "Lampadine"],
                ["id" => 5, "name" => "Microonde"]
            ]);
        }
        break;

    case 'POST':
        $json = json_decode(file_get_contents('php://input' , true));

        $bch->setPlatform("category");
        $bch->addRecord($json["name"]);    // We have to take the name from the input JSON
        break;

    case 'DELETE':
        $json = json_decode(file_get_contents('php://input' , true));

        $bch->setPlatform("category");
        $bch->deleteRecord($json["id"]);    // We have to take the id from the input JSON
        break;

    case 'PUT':
        $bch->setPlatform("category");
        $bch->modifyRecord($json["id"], $json["name"]);  // We have to take id and name from the input JSON
        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
