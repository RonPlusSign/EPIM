<?php

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
require_once __DIR__ . '/../classes/orderManagementHandler.php';
$omh = new orderManagementHandler();
switch ($requestMethod) {
        /**
     * Handle order management
     * 
     * Methods:
     * 
     *      GET:
     *          - default return all the order if you are an admin
     *          - by id return all the order about the user with the logged id
     * 
     *      POST:
     *          
     * 
     */
    case 'GET':

        if (isset($_GET["id"])) // Get the order with this id
        {


            //da far trasformare la risposta in json ancora
            $omh->getOrderById();
        }
        else // Return all the order if you are an Admin
        {


            //da far trasformare la risposta in json ancora
            $omh->getAllOrders();
        }
        break;

    case 'POST':


        break;

    default:
        http_response_code(405);
        break;
}