<?php

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
require_once __DIR__ . '/../lib/Bootstrap.php';

session_start();

switch ($requestMethod) {
    case 'GET':
        checkLogin();
        break;

    case 'POST': // user is logging on the website
        /* POST Format:
        {
            "email": "pippo@baudo.it",
            "password": "myPassword"
        }
        */
        doLogin();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function doLogin()
{
    $json = json_decode(file_get_contents('php://input'), true);

    $passwordHash = password_hash($json["password"], PASSWORD_BCRYPT);

    try {
        $stm = Database::$pdo->prepare("SELECT * FROM user
                                        WHERE user.email = :email
                                        AND user.password = :userPassword");
        $stm->bindParam(':email', $json["email"]);
        $stm->bindParam(':userPassword', $passwordHash);
        $stm->execute();
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // User not found
            http_response_code(403);
        } else {
            // User found
            $_SESSION["logged"] = true;
            $_SESSION["user_id"] = $user["id"];
            http_response_code(204);
        }
    } catch (\Exception $e) {
        echo $e;
    }
}

function checkLogin()
{
    $answer = [
        "logged" => false
    ];

    if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true) {
        // user is logged
        $answer["logged"] = true;
    }

    echo json_encode($answer);
}

function logout()
{
    session_destroy();
    http_response_code(200);
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
