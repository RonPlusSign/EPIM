
<?php
/*
    Author: RonPlusSign (Andrea Delli)
 */

require_once __DIR__ . '/../lib/Bootstrap.php';
require_once __DIR__ . '/../classes/LoginHandler.php';
require_once __DIR__ . '/../classes/ProductsHandler.php';

class CartHandler
{
    /**
     * Add a product to user's cart (modify the quantity if the product is already present)
     * @param integer $id The product id
     * @param integer $quantity the quantity of a product
     * @return boolean FALSE if the quantity is not available, TRUE on success
     */
    static public function addToCart($userId, $productId, $quantity)
    {
        if ($quantity <= 0) self::removeProduct($userId, $productId);

        // Check if there is enough product quantity in the database
        $stm = Database::$pdo->prepare("SELECT quantity FROM product WHERE id = :id");
        $stm->bindParam(":id", $productId);

        $stm->execute();

        $availableQuantity = $stm->fetch(PDO::FETCH_ASSOC)["quantity"];

        if ($availableQuantity < $quantity) {
            return false;
        } else {
            // Check if it is an update or an insert
            $stm = Database::$pdo->prepare("SELECT * FROM cart WHERE user = :userId AND product = :productId");
            $stm->bindParam(":productId", $productId);
            $stm->bindParam(":userId", $userId);

            $stm->execute();

            if ($stm->fetch()) {
                // Product found, update its quantity in the cart
                $stm = Database::$pdo->prepare("UPDATE cart SET quantity = :quantity WHERE user = :userId AND product = :productId");
                $stm->bindParam(":productId", $productId);
                $stm->bindParam(":userId", $userId);
                $stm->bindParam(":quantity", $quantity);

                $stm->execute();
                return $stm->rowCount();
            } else {
                // Product not found, Add it to the cart
                $stm = Database::$pdo->prepare("INSERT INTO cart VALUES (:userId, :productId, :quantity)");
                $stm->bindParam(":productId", $productId);
                $stm->bindParam(":userId", $userId);
                $stm->bindParam(":quantity", $quantity);

                $stm->execute();
                return $stm->rowCount();
            }
        }
    }

    /**
     * Get all products inside the user cart
     */
    public static function getCart($userId)
    {
        // TODO: Check if the quantity is available!
        // If the cart quantity is < than the product quantity, return the product quantity as the cart quantity
        // And it will also update the value inside the database
        if (LoginHandler::isLogged()) {
            $stm = Database::$pdo->prepare("SELECT cart.quantity AS selectedQuantity,
                product.id,
                product.title,
                product.description,
                product.sell_price,
                product.quantity,
                product.category AS category_id,
                product.brand AS brand_id,
                brand.name AS brand,
                category.name as category
                
                FROM cart
                INNER JOIN product ON cart.product = product.id
                INNER JOIN brand ON product.brand = brand.id
                INNER JOIN category ON product.category = category.id
                WHERE user = :userId 
            ");
            $stm->bindParam(":userId", $userId);
            $stm->execute();

            $products = $stm->fetchAll(PDO::FETCH_ASSOC);

            $productHandler = new ProductsHandler();
            foreach ($products as &$product) {
                $product["images"] = $productHandler->getProductImages($product["id"]);
            }

            return $products;
        } else return false;
    }

    /**
     * Remove a product from the user cart
     */
    public static function removeProduct($userId, $productId)
    {
        if (LoginHandler::isLogged()) {
            $stm = Database::$pdo->prepare("DELETE FROM cart WHERE user = :userId AND product = :productId");
            $stm->bindParam(":productId", $productId);
            $stm->bindParam(":userId", $userId);

            $stm->execute();

            return $stm->rowCount();
        } else return false;
    }
}
