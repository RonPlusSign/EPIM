<?php
/*
    Author: regi18

    See below for options
 */

require_once __DIR__ . '/../classes/UserHandler.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
session_start();

switch ($requestMethod) {
    case 'GET':


        // Get all users
        if (isset($_GET["all"])) {
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

        break;


    case 'PATCH':
        $json = json_decode(file_get_contents('php://input'));

        if (isset($_GET['id'])) {
            if (UserHandler::editUser($_GET['id'], $json)) http_response_code(200);
            else http_response_code(403);
        }

        break;



    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
