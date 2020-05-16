<?php
/*
    Authors: regi18, RonPlusSign

    See below for options
 */

require_once __DIR__ . '/../classes/UserHandler.php';
require_once __DIR__ . '/../classes/CartHandler.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
session_start();

switch ($requestMethod) {
    case 'GET':

        // Get the products inside the cart
        if (isset($_GET["cart"])) {
            if (isset($_SESSION["user_id"])) {
                $products = CartHandler::getCart($_SESSION["user_id"]);

                if ($products) {
                    echo json_encode($products);
                    http_response_code(200);
                } else http_response_code(403);
            } else http_response_code(403);
        }
        // Get all users
        else if (isset($_GET["all"])) {
            $users = UserHandler::getUsers();

            if ($users) {
                echo json_encode($users);
                http_response_code(200);
            } else http_response_code(403);
        }

        // Get current user
        else {
            $user = UserHandler::getUser($_SESSION["user_id"]);

            if ($user) echo json_encode($user);
            else http_response_code(403);
        }

        break;

    case 'POST':
        $json = json_decode(file_get_contents('php://input'), true);

        // Set the isAdmin attribute of an user to true/false
        if (isset($_GET["admin"])) {
            if (!UserHandler::setAdminFlag($json["id"], $json["isAdmin"])) http_response_code(403);
            else http_response_code(200);
        }

        // Sign-up user
        else if (isset($_GET["signup"])) {
            if (UserHandler::signUp($json["email"], $json["name"], $json["surname"], $json["phoneNumber"], $json["password"])) http_response_code(200);
            else {
                http_response_code(400);
                echo '{ "error": "E-mail already in use." }';
            }
        }
        // Add a product to the cart
        else if (isset($_GET["cart"])) {
            if (isset($_SESSION["user_id"])) {
                if (CartHandler::addToCart(
                    $_SESSION["user_id"],
                    $json["id"],
                    $json["quantity"]
                ))
                    http_response_code(200);
                else http_response_code(400);
            } else http_response_code(403);
        }

        break;


    case 'PATCH':
        $json = json_decode(file_get_contents('php://input'));

        // Edit the product quantity inside the cart
        if (isset($_GET["cart"])) {
            if (isset($_SESSION["user_id"])) {
                if (CartHandler::addToCart(
                    $_SESSION["user_id"],
                    $json->id,
                    $json->quantity
                )) http_response_code(200);
                else http_response_code(400);
            } else http_response_code(403);
        }
        // Edit user data
        else {
            try {
                if (UserHandler::editUser($json)) http_response_code(200);
                else http_response_code(400);
            } catch (\Throwable $th) {
                http_response_code(403);
            }
        }
        break;

    case 'DELETE':
        // Delete a product from the user cart
        if (isset($_GET["cart"]) && isset($_GET["id"])) {
            if (isset($_SESSION["user_id"])) {
                if (CartHandler::removeProduct($_SESSION["user_id"], $_GET["id"])) http_response_code(200);
                else http_response_code(403);
            } else http_response_code(403);
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
