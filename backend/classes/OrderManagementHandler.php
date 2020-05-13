<?php
/*
    Author: daniel
 */

require_once __DIR__ . '/../lib/Bootstrap.php';

class OrderManagementHandler
{
    /**
     * Checks if you are an admin and return all the order
     */
    public function getAllOrders()
    {
        if(LoginHandler::isAdmin())
        {
            try {
                $stm = Database::$pdo->prepare("SELECT * FROM order_detail
                                                INNER JOIN order_history ON order_detail.order_id = order_history.id");
                $stm->execute();
                $allOrder = $stm->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
            } catch (\Exception $e) {
                echo $e;
            }
        }
        else
        {
            http_response_code(403);
        }
    }

    /**
     * Check if you are logged and Return all the order about the user logged
     */
    public function getOrderById()
    {
        if(LoginHandler::isLogged())
        {
            try {
                $stm = Database::$pdo->prepare("SELECT * FROM order_history
                                                INNER JOIN order_history ON order_detail.order_id = order_history.id
                                                WHERE order_history.user = :id");
                $stm->bindParam(':id', $_SESSION["user_id"]);
                $stm->execute();
                $orderById = $stm->fetch(PDO::FETCH_ASSOC);
                http_response_code(200);
            } catch (\Exception $e) {
                echo $e;
            }
        }
        else
        {
            http_response_code(403);
        }
    }

    public function newOrder()
    {
        if(LoginHandler::isLogged())
        {
            
        }
        else
        {
            http_response_code(403);
        }
    }
}