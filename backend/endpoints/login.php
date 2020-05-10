<?php

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
require_once __DIR__ . '/../classes/LoginHandler.php';

session_start();

switch ($requestMethod) {
        /**
     * Handle user login and signup
     * 
     * Methods:
     * 
     *      GET:
     *          - default (no queries)  shows the current stats (logged/not logged, isAdmin)
     *          - ?logout   logout the user
     * 
     *      POST:
     *          Post json to login user
     * 
     */
    case 'GET':

        // Logout
        if (isset($_GET["logout"])) {
            LoginHandler::logout();
        } 
        
        // Show stats
        else {
            echo json_encode([
                "logged" => LoginHandler::isLogged(),
                "isAdmin" => LoginHandler::isAdmin()
            ]);
        }

        break;

    case 'POST':

        $json = json_decode(file_get_contents('php://input'), true);

        // Login user
        if (LoginHandler::loginUser($json["email"], $json["password"])) http_response_code(200);
        else http_response_code(403);

        break;

    default:
        http_response_code(405);
        break;
}
