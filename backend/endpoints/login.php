<?php

$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
require_once __DIR__ . '/../lib/Bootstrap.php';

switch ($requestMethod) {
    case 'GET':
        login()
        break;
 
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function login($id, $name)
{
    session_start();
    
    $answer = [
        $_SESSION["logged"] = true;
        "user" => [
            "id" => $id
            "name" => $name
        ],
    ];

    return $answer;
}

function logout()
{
    session_destroy();
    http_response_code(204);
}

/* foreach ($sas as &$key) {
                foreach ($key as &$lol => $six) {
                    
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
            }*/