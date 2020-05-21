<?php

require_once __DIR__ . '/../classes/OrdersHandler.php';

header("Content-Type: application/json");
session_start();

$requestMethod = $_SERVER["REQUEST_METHOD"];
$omh = new OrdersHandler();

switch ($requestMethod) {
        /**
     * Handle order management
     * 
     * Methods:
     * 
     *      GET:
     *          - default return all the order about the user with the logged id
     *          
     *          - by id return all the order if you are an admin
     *      POST:
     *          - by purchase add all the order of the cart
     * 
     */
    case 'GET':

        if (isset($_GET["admin"])) // Get the order with this id
        {
            if(LoginHandler::isAdmin())
            {
                echo json_encode( $omh->getAllOrders());
                http_response_code(200);
            }
            else
            {
                http_response_code(403);
            }
        }
        else // Return all the order if you are an Admin
        {
            if(LoginHandler::isLogged()){
                echo json_encode($omh->getUserOrders());
                http_response_code(200);
            }
            else
            {
                http_response_code(403);
            }
        }
        break;
    case 'POST':
        $json = json_decode(file_get_contents('php://input'), true);


        if(isset($_GET(["purchase"])))
        {
            if(LoginHandler::isLogged())
            {
                $response = $omh->newOrder($json["address"]);
                if($response === true)
                {
                    http_response_code(200);
                }
                else
                {
                    http_response_code(400);
                }
            }
            else
            {
                http_response_code(403);
            }
        }
    break;

    default:
        http_response_code(405);
        break;
}