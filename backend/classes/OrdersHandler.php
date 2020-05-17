<?php
/*
    Author: daniel
 */

require_once __DIR__ . '/../lib/Bootstrap.php';

class OrdersHandler
{
    /**
     * Checks if you are an admin and return all the order
     */
    public function getAllOrders()
    {
        if(LoginHandler::isAdmin())
        {
            try {
                $stm = Database::$pdo->prepare("SELECT order_detail.id, order_detail.order_id,order_detail.product_id, order_detail.quantity, order_detail.product_title, order_detail.product_price, order_detail.brand_name, order_history.*, user.email, user.name, user.surname FROM order_detail
                                                INNER JOIN order_history ON order_detail.order_id = order_history.id
                                                INNER JOIN user ON order_history.user = user.id");
                $stm->execute();
                $allOrder = $stm->fetchAll(PDO::FETCH_ASSOC);                
                http_response_code(200);
                return $allOrder;
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
    public function getUserOrders()
    {
        
        if(LoginHandler::isLogged())
        {
            try {
                $stm = Database::$pdo->prepare("SELECT order_detail.order_id,order_detail.product_id, order_detail.quantity, order_detail.product_title, order_detail.product_price, order_detail.brand_name, order_history.* FROM order_detail
                                                INNER JOIN order_history ON order_detail.order_id = order_history.id
                                                WHERE order_history.user = :id");
                $stm->bindParam(':id', $_SESSION["user_id"]);
                $stm->execute();
                $orderById = $stm->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                $array=[];
                foreach ($orderById as $orders) {
                    $trovato = false;
                    foreach ($array as &$object)  //si fa in questa maniera perchè altrimenti all'esterno del foreach non sarà cambiato nulla
                    {
                        if(+$object['id'] === +$orders['order_id'])
                        {
                            $trovato = true;
                            $prodotto = [
                                "productId" => $orders['product_id'],
                                "productTitle" => $orders['product_title'],
                                "quantity" => $orders['quantity'],
                                "price" => $orders['product_price'],
                                "brandName" => $orders['brand_name']
                            ];
                            $object['products'][] = $prodotto;
                            
                        }
                        
                    }
                    if($trovato = false)
                    {
                        $datatime = new DateTime($orders['timestamp']);
                        $nuovoOggetto = [
                            "id" => $orders['id'],
                            "date" => $datatime->format('d-m-Y'),
                            "hour" => $datatime->format('H:i:s'),
                            "address" => $orders['address'],
                            "phoneNumber" => $orders['phone_number'],
                            "shippingCost" => $orders['shipping_cost'],
                            "status" => $orders['status']
                        ];
                        $array[] = $nuovoOggetto;
                    }
                }
                
                return $array;
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