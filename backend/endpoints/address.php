<?php

/*
    author RonPlusSign (Andrea Delli)

*/

require_once __DIR__ . '/../classes/AddressHandler.php';

header("Content-Type: application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case 'GET':
        // Get a specific region name
        if (isset($_GET["regions"]) && isset($_GET["id"])) {
            echo json_encode(AddressHandler::getRegion($_GET["id"]));
        }

        // Get all the regions
        else if (isset($_GET["regions"])) {
            echo json_encode(AddressHandler::getRegions());
        }

        // Get all the provinces of a region
        else if (isset($_GET["provinces"]) && isset($_GET["region"])) {
            echo json_encode(AddressHandler::getProvincesByRegion($_GET["region"]));
        }

        // Get a specific province
        else if (isset($_GET["provinces"]) && isset($_GET["id"])) {
            echo json_encode(AddressHandler::getProvince($_GET["id"]));
        }

        // Get all the provinces
        else if (isset($_GET["provinces"])) {
            echo json_encode(AddressHandler::getProvinces());
        }

        // Get all the cities of a province
        else if (isset($_GET["cities"]) && isset($_GET["province"])) {
            echo json_encode(AddressHandler::getCitiesByProvince($_GET["province"]));
        }

        // Get all the cities of a province
        else if (isset($_GET["cities"]) && isset($_GET["id"])) {
            echo json_encode(AddressHandler::getCity($_GET["id"]));
        }

        // Get all the cities
        else if (isset($_GET["cities"])) {
            echo json_encode(AddressHandler::getCities());
        }

        break;

        // case 'POST':
        //     $json = json_decode(file_get_contents('php://input'));
        //     break;

        // case 'DELETE':
        //     break;

        // case 'PUT':
        //     break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
