<?php

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");


switch ($requestMethod) {
    case 'GET':
        // Temporary data, just for testing
        echo json_encode([
            ["id" => 1, "name" => "Samsung"],
            ["id" => 2, "name" => "Apple"],
            ["id" => 3, "name" => "Xiaomi"],
            ["id" => 4, "name" => "EPIM"],
            ["id" => 5, "name" => "myBrand"]
        ]);
        break;

    case 'POST':
        $json = json_decode(file_get_contents('php://input'));
        break;

    case 'DELETE':
        $json = json_decode(file_get_contents('php://input'));
        break;

    case 'PATCH':
        $json = json_decode(file_get_contents('php://input'));
        break;


    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
