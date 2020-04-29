<?php

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
require_once __DIR__ . '/../classes/LoginHandler.php';

session_start();
$lh = new LoginHandler();

switch ($requestMethod) {
    case 'GET':
        if (isset($_GET["logout"])) { // Do logout
            $lh->logout();
        } else if (isset($_GET["admin"])) {    // Check if the user is an admin
            try {
                $answer = [
                    "logged" => $lh->checkLogin(),
                    "isAdmin" => false
                ];

                if ($lh->isAdmin()) {
                    $answer["isAdmin"] = true;
                }
            } catch (\Exception $e) {
            }
        } else {   // Normal user login
            $answer = [
                "logged" => $lh->checkLogin()
            ];
        }
        echo json_encode($answer);
        break;

    case 'POST': // user is logging on the website
        /* POST Format:
        {
            "email": "pippo@baudo.it",
            "password": "myPassword"
        }
        */

        $json = json_decode(file_get_contents('php://input'), true);

        if ($lh->doLogin($json["email"], $json["password"]))
            http_response_code(204);
        else http_response_code(403);
        break;
    default:
        http_response_code(405);
        break;
}

/*
foreach ($sas as &$key) {
    foreach ($key as &$lol => $six) {
    }
}

try {
    $stm = Database::$pdo->prepare("SELECT product.*, brand.name as brand, category.name as category FROM product
                                    INNER JOIN category ON product.category=category.id
                                    INNER JOIN brand ON product.brand=brand.id
                                    WHERE product.id=:id");
    $stm->bindParam(':id', $id);
    $stm->execute();
    return $stm->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    echo $e;
}
*/
