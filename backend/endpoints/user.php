<?php
/*
    Authors: regi18, RonPlusSign

    See below for options
 */

require_once __DIR__ . '/../classes/UserHandler.php';
require_once __DIR__ . '/../classes/CartHandler.php';
require_once __DIR__ . '/../classes/LoginHandler.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
session_start();

switch ($requestMethod) {
    case 'GET':

        // Get the products inside the cart
        if (isset($_GET["cart"])) {
            if (LoginHandler::isLogged()) {
                $products = CartHandler::getCart($_SESSION["user_id"]);

                if ($products) {
                    echo json_encode($products);
                    http_response_code(200);
                } else http_response_code(403);
            } else http_response_code(403);
        }

        // Get the user addresses
        else if (isset($_GET["address"])) {
            echo json_encode(UserHandler::getAddresses());
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

        // Add an address to the user addresses
        else if (isset($_GET["address"])) {
            if (UserHandler::addAddress($json["city"], $json["street"], $json["houseNumber"], $json["postalCode"], $json["phoneNumber"])) http_response_code(200);
            else http_response_code(403);
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
            if (LoginHandler::isLogged()) {
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
            if (LoginHandler::isLogged()) {
                if (CartHandler::addToCart(
                    $_SESSION["user_id"],
                    $json->id,
                    $json->quantity
                )) http_response_code(200);
                else http_response_code(400);
            } else http_response_code(403);
        }

        // Edit one address from the user addresses
        else if (isset($_GET["address"])) {
            if (LoginHandler::isLogged()) {
                // NEVER MODIFY AN ADDRESS! MORE ACCOUNTS MAY REFER TO IT
                // GET THE PREVIOUS ADDRESS AND ADD A NEW ONE WITH THE PATCHED ATTRIBUTES
                if (UserHandler::modifyUserAddress($_SESSION["user_id"], $json)) http_response_code(200);
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
            if (LoginHandler::isLogged()) {
                if (CartHandler::removeProduct($_SESSION["user_id"], $_GET["id"])) http_response_code(200);
                else http_response_code(403);
            } else http_response_code(403);

            // Delete an address from the user's addresses
        } else if (isset($_GET["address"]) && isset($_GET["id"])) {
            if (UserHandler::removeAddress($_GET["id"])) http_response_code(200);
            else http_response_code(403);
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
