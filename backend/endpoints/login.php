<?php

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
require_once __DIR__ . '/../classes/loginHandler.php';

session_start();
$lh = new loginHandler();

switch ($requestMethod) {
    case 'GET':
        $lh->checkLogin();
        break;

    case 'POST': // user is logging on the website
        /* POST Format:
        {
            "email": "pippo@baudo.it",
            "password": "myPassword"
        }
        */
        $lh->doLogin();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
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
