<?php

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
require_once __DIR__ . '/../classes/OrdersHandler.php';
$omh = new OrdersHandler();
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
            echo json_encode($omh->getUserOrders());
        }
        else // Return all the order if you are an Admin
        {
            echo json_encode( $omh->getAllOrders());
        }
        break;

    case 'POST':


        break;

    default:
        http_response_code(405);
        break;
}