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
        try {
            $stm = Database::$pdo->prepare("SELECT order_detail.order_id,order_detail.product_id, order_detail.quantity, order_detail.product_title, order_detail.product_price, order_detail.brand_name, order_history.*, user.email, user.name, user.surname FROM order_detail
                                            INNER JOIN order_history ON order_detail.order_id = order_history.id
                                            INNER JOIN user ON order_history.user = user.id
                                            ORDER BY order_history.timestamp DESC");
            $stm->execute();
            $allOrder = $stm->fetchAll(PDO::FETCH_ASSOC);
            $array = [];
            foreach ($allOrder as $orders) {
                $trovato = false;
                foreach ($array as &$object)  //si fa in questa maniera perchè altrimenti all'esterno del foreach non sarà cambiato nulla
                {
                    if (+$object['id'] === +$orders['order_id']) {
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
                if ($trovato === false) {
                    $datatime = new DateTime($orders['timestamp']);
                    $nuovoOggetto = [
                        "id" => $orders['id'],
                        "date" => $datatime->format('d-m-Y'),
                        "hour" => $datatime->format('H:i:s'),

                        "user_id" => $orders['user'],
                        "name" => $orders['name'],
                        "surname" => $orders['surname'],
                        "email" => $orders['email'],

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

    /**
     * Check if you are logged and Return all the order about the user logged
     */
    public function getUserOrders()
    {
        try {
            $stm = Database::$pdo->prepare("SELECT order_detail.order_id,order_detail.product_id, order_detail.quantity, order_detail.product_title, order_detail.product_price, order_detail.brand_name, order_history.* FROM order_detail
                                            INNER JOIN order_history ON order_detail.order_id = order_history.id
                                            WHERE order_history.user = :id
                                            ORDER BY order_history.timestamp DESC");
            $stm->bindParam(':id', $_SESSION["user_id"]);
            $stm->execute();
            $orderById = $stm->fetchAll(PDO::FETCH_ASSOC);
            $array = [];
            foreach ($orderById as $orders) {
                $trovato = false;
                foreach ($array as &$object)  //si fa in questa maniera perchè altrimenti all'esterno del foreach non sarà cambiato nulla
                {
                    if (+$object['id'] === +$orders['order_id']) {
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
                if ($trovato === false) {
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

    public function newOrder($addressId)
    {
        // Get the products inside the user cart
        $stm = Database::$pdo->prepare("SELECT cart.user, cart.product, cart.quantity as cartQuantity, product.* FROM cart
                                        INNER JOIN product ON cart.product = product.id
                                        WHERE cart.user = :id");
        $stm->bindParam(':id', $_SESSION["user_id"]);
        $stm->execute();

        $cart = $stm->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cart)) {
            // User cart is empty
            return false;
        } else {
            // Check if there are enough products available (product.quantity >= cart.quantity)
            foreach ($cart as $product) {
                $stm = Database::$pdo->prepare("SELECT product.quantity FROM product
                                        WHERE id = :id");

                $stm->bindParam(':id', $product['id']);
                $stm->execute();
                $quantity = $stm->fetch(PDO::FETCH_ASSOC);

                if ($quantity['quantity'] - $product['cartQuantity'] < 0) return false;
            }

            // Check if the user has the address received from the request 
            $stm = Database::$pdo->prepare("SELECT user_address.* FROM user_address
                                            WHERE user_address.user = :id AND user_address.address = :address");

            $stm->bindParam(':id', $_SESSION["user_id"]);
            $stm->bindParam(':address', $addressId);
            $stm->execute();

            $address = $stm->fetch(PDO::FETCH_ASSOC);

            if (empty($address)) {
                // User address not found
                return false;
            } else {

                // Insert the order attributes inside the database
                $stm = Database::$pdo->prepare("INSERT INTO order_history(user, address, phone_number, timestamp, shipping_cost, status)
                                                VALUES (:id, :address, :phone, NOW(), 5, 'spedito')");

                $stm->bindParam(':id', $_SESSION["user_id"]);
                $stm->bindParam(':address', $addressId);
                $stm->bindParam(':phone', $address['phone_number']);
                $stm->execute();

                $orderId = Database::$pdo->lastInsertId();

                foreach ($cart as $productCart) {
                    // Insert the purchased products inside the database
                    $stm = Database::$pdo->prepare("INSERT INTO order_detail(order_id, product_id, quantity, product_title, product_price, brand_name)
                                                        VALUES (:idorder, :idproduct, :quantity, :title, :price, :brand)");
                    $stm->bindParam(':idorder', $orderId);
                    $stm->bindParam(':idproduct', $productCart['id']);
                    $stm->bindParam(':quantity', $productCart['cartQuantity']);
                    $stm->bindParam(':title', $productCart['title']);
                    $stm->bindParam(':price', $productCart['sell_price']);
                    $stm->bindParam(':brand', $productCart['brand']);
                    $stm->execute();

                    // Update the product's quantity
                    $newQuantity = $quantity['quantity'] - $productCart['cartQuantity'];
                    $stm = Database::$pdo->prepare(
                        "UPDATE product
                                SET quantity = :quantity
                                WHERE id = :id"
                    );
                    $stm->bindParam(':quantity', $newQuantity);
                    $stm->bindParam(':id', $productCart["id"]);
                    $stm->execute();
                }

                try {
                    $stm = Database::$pdo->prepare("DELETE FROM cart WHERE cart.user=:id");
                    $stm->bindParam(':id', $_SESSION["user_id"]);
                    $stm->execute();

                    return true;
                } catch (\Exception $e) {
                    echo $e;
                }
            }
        }
    }
}
