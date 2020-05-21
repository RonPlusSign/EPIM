<?php
/*
    Author: regi18
 */

require_once __DIR__ . '/../lib/Bootstrap.php';
require_once __DIR__ . '/../classes/LoginHandler.php';


class ProductsHandler
{

    private static $productsPerPage = 16;
    private $isSort;
    private $orderBy;
    private $isOrderAsc;
    private $extraFilter;
    private $extraFilterString;
    private $isPriceRangeFilter;
    private $priceRangeFilterMin;
    private $priceRangeFilterMax;
    private $pageNumber;


    public function __construct()
    {
        $this->isSort = false;
        $this->isAdminView = false;
        $this->extraFilter = NULL;
        $this->pageNumber = 0;
    }

    /**
     * Sets how many products will be returned for every page
     * 
     * @param int $limit The number of priducts
     */
    public function setResultPerPageLimit($limit)
    {
        self::$productsPerPage = $limit;
    }

    public function getResultPerPageLimit()
    {
        return +self::$productsPerPage;
    }

    /**
     * Sets the page number
     * 
     * @param int $page
     */
    public function setPageNumber($page)
    {
        $this->pageNumber = $page;
    }

    /**
     * Sets an additional filter. Available options:
     * 'brand', 'category'
     * 
     * @param string $filter Filter (choose between brand and category)
     * @param string $filterString The parameter of the filter
     */
    public function setExtraFilter($filter, $filterString)
    {
        $this->extraFilter = $filter;
        $this->extraFilterString = $filterString;
    }

    /**
     * Activates and deactivates price range filter
     * To deactivate call this function with one parameter:
     * setPriceRangeFilter(false);
     * 
     * @param bool $isActive status of the filter
     * @param float $minimum Minimum price
     * @param float $maximum Maximum price
     */
    public function setPriceRangeFilter($isActive, $minimum = 0, $maximum = PHP_FLOAT_MAX)
    {
        $this->isPriceRangeFilter = $isActive;
        $this->priceRangeFilterMin = $minimum;
        $this->priceRangeFilterMax = $maximum;
    }


    /**
     * Set ORDER BY in query
     * 
     * @param bool $isSort      Activates/Deactivates sorting
     * @param bool $isOrderAsc  Sets if order should be ASC or DESC
     * @param bool $orderBy     Order by column, available: price, title
     */
    public function setSortSettings($isSort, $isOrderAsc = true, $orderBy = '')
    {
        $this->isSort = $isSort;

        if (!$isSort) return;

        else $this->orderBy = $orderBy;
        $this->isOrderAsc = $isOrderAsc;
    }


    /**
     * Match products by string
     * 
     * @param string $filter The column name on which to search (available: title)
     * @param string $filterString The string to search for
     * @param boolean $countResults If true the query return the number of total rows
     * @param boolean $disableStringFilter If true the query ignores the filters and returns all products (still limited by the page count)
     * 
     * @return Array Filtered data
     */
    public function queryByFilterString($filter, $filterString, $countResults = false, $disableStringFilter = true)
    {
        // Query
        if ($countResults) {
            $query = "SELECT count(*) as number_of_products FROM product as p";
        } else {
            $query = "SELECT p.id, p.title, p.description, p.sell_price, p.quantity";

            // If admin add purchase and recommended price
            if (LoginHandler::isAdmin()) {
                $query .= ", p.purchase_price, p.recommended_price";
            }

            $query .= ", b.name as brand, b.id as brand_id, c.name as category, c.id as category_id FROM product as p";
        }

        $query .= " INNER JOIN category as c ON p.category=c.id
                    INNER JOIN brand as b ON p.brand=b.id";

        if (!$disableStringFilter) {
            $query .= " WHERE MATCH (" . $filter . ") AGAINST (:filterString IN NATURAL LANGUAGE MODE)";
        }
        else if ($this->isSort || $this->extraFilter !== NULL) $query .= " WHERE 1 ";



        // Check for extra filter
        if ($this->extraFilter == 'category') {
            $query .= " AND c.name = :extraFilter";
        } else if ($this->extraFilter == 'brand') {
            $query .= " AND b.name = :extraFilter";
        }

        // Check for price filter
        if ($this->isPriceRangeFilter) {
            $query .= " AND p.sell_price >= :minPrice AND p.sell_price <= :maxPrice";
        }

        // check if sort 'activated' 
        if ($this->isSort) {
            if ($this->orderBy == 'price') $query .= ' ORDER BY sell_price';
            else $query .= ' ORDER BY title';

            if ($this->isOrderAsc) $query .= ' ASC';
            else $query .= ' DESC';
        }

        if (!$countResults) $query .= ' LIMIT :limitOffset, :productsPerPage';

        $stm = Database::$pdo->prepare($query);
        if (!$disableStringFilter) $stm->bindParam(':filterString', $filterString);

        // check if countResult is disabled
        if (!$countResults) {
            $stm->bindParam(':productsPerPage', self::$productsPerPage);
            $limitOffset = $this->pageNumber * self::$productsPerPage;
            $stm->bindParam(':limitOffset', $limitOffset);
        }

        // Bind extraFilter params
        if ($this->extraFilter != NULL) $stm->bindParam(':extraFilter', $this->extraFilterString);
        // Bind price filter param
        if ($this->isPriceRangeFilter != NULL) {
            $stm->bindParam(':minPrice', $this->priceRangeFilterMin);
            $stm->bindParam(':maxPrice', $this->priceRangeFilterMax);
        }

        $stm->execute();
        $products = $stm->fetchAll(PDO::FETCH_ASSOC);

        // Add products images
        if (!$countResults) {
            foreach ($products as &$product) {
                $product["images"] = $this->getProductImages($product["id"]);
            }
        }

        return $products;
    }

    /**
     * This function does a similar query to "getByTitle" 
     * but it returns only the total number of rows (without any limit)
     */
    public function getNumberOfProducts($title = NULL)
    {
        if ($title) return self::queryByFilterString('title', $title, true, false)[0]["number_of_products"];
        else return self::queryByFilterString('', '', true, true)[0]["number_of_products"]; 
    }

    public function getByTitle($title)
    {
        return self::queryByFilterString('title', $title, false, false);
    }


    /**
     * Get a single product
     * 
     * @param int $id The product's id
     * 
     * @return Array the product
     */
    public function getProduct($id)
    {

        try {
            $query = "SELECT b.name as brand, b.id as brand_id, 
            c.name as category, c.id as category_id, p.id, p.title, p.description, p.sell_price, p.quantity";

            if (LoginHandler::isAdmin()) {
                $query .= ", p.purchase_price, p.recommended_price ";
            }

            $query .= " FROM product as p
            INNER JOIN category as c ON p.category=c.id
            INNER JOIN brand as b ON p.brand=b.id
            WHERE p.id=:id";

            $stm = Database::$pdo->prepare($query);

            $stm->bindParam(':id', $id);
            $stm->execute();
            $product = $stm->fetch(PDO::FETCH_ASSOC);

            $images = $this->getProductImages($id);

            if ($images) $product["images"] = $images;

            return $product;
        } catch (\Exception $e) {
            echo $e;
        }
    }


    public function getProductImages($id)
    {
        try {
            $stm = Database::$pdo->prepare("SELECT product_image.url FROM product
                                            INNER JOIN product_image ON product_image.product=p.id
                                            WHERE p.id=:id");
            $stm->bindParam(':id', $id);
            $stm->execute();
            $resultArray = $stm->fetchAll(PDO::FETCH_ASSOC);

            $images = [];
            foreach ($resultArray as $el) {
                array_push($images, $el["url"]);
            }

            return $images;
        } catch (\Exception $e) {
            return [];
        }
    }



    /**
     * @return Boolean false if not logged as admin
     */
    public function setQuantity($id, $quantity)
    {
        if (LoginHandler::isAdmin()) {
            $stm = Database::$pdo->prepare("UPDATE product SET quantity=:quantity WHERE id=:id");
            $stm->bindParam(':id', $id);
            $stm->bindParam(':quantity', $quantity);
            $stm->execute();
            return $stm->rowCount();
        } else {
            return false;
        }
    }




    /******************************
     * ADD DELETE MODIFY Products *
     ******************************/

    /**
     * Add a new product
     * 
     * @param stdClass $productJson An array containing the JSON with the product
     */
    public function addProduct($productJson)
    {
        if (LoginHandler::isAdmin()) {
            try {
                $stm = Database::$pdo->prepare("INSERT INTO product (title, description, purchase_price, sell_price, recommended_price, quantity, category, brand)
                                            VALUES (:title, :description, :purchase_price, :sell_price, :recommended_price, :quantity, :category, :brand)");
                $data = [
                    ':title' => $productJson->title,
                    ':description' => $productJson->description,
                    ':purchase_price' => $productJson->purchase_price,
                    ':sell_price' => $productJson->sell_price,
                    ':recommended_price' => $productJson->recommended_price,
                    ':quantity' => $productJson->quantity,
                    ':category' => $productJson->category,
                    ':brand' => $productJson->brand,
                ];
                $stm->execute($data);
                return $stm->rowCount();
            } catch (\Exception $e) {
                echo $e;
            }
        } else {
            return false;
        }
    }

    /**
     * Delete a product
     * 
     * @param int $id The product's id
     * @return int The row count of the query
     */
    public function deleteProduct($id)
    {
        if (LoginHandler::isAdmin()) {
            try {
                $stm = Database::$pdo->prepare("DELETE FROM product WHERE p.id=:id");
                $stm->bindParam(':id', $id);
                $stm->execute();
                return $stm->rowCount();
            } catch (\Exception $e) {
                echo $e;
            }
        } else {
            return false;
        }
    }

    /**
     * Edit a p.
     * It will patch every parameter it will receive.
     * Every other parameter will remain unchanged.
     * 
     * @param stdClass $newProductJson An array containing the JSON with the product
     */
    public function editProduct($id, $newProductJson)
    {
        if (LoginHandler::isAdmin()) {
            try {
                $stm = Database::$pdo->prepare("UPDATE product
                                            SET title               = COALESCE(:title,title)
                                              , description         = COALESCE(:description,description)
                                              , purchase_price      = COALESCE(:purchase_price,purchase_price)
                                              , sell_price          = COALESCE(:sell_price,sell_price)
                                              , recommended_price   = COALESCE(:recommended_price,recommended_price)
                                              , quantity            = COALESCE(:quantity,quantity)
                                              , category            = COALESCE(:category,category)
                                              , brand               = COALESCE(:brand,brand)
                                            WHERE id = :id");
                $data = [
                    ':title' => $newProductJson->title,
                    ':description' => $newProductJson->description,
                    ':purchase_price' => $newProductJson->purchase_price,
                    ':sell_price' => $newProductJson->sell_price,
                    ':recommended_price' => $newProductJson->recommended_price,
                    ':quantity' => $newProductJson->quantity,
                    ':category' => $newProductJson->category,
                    ':brand' => $newProductJson->brand,
                    ':id' => +$id
                ];
                $stm->execute($data);
                return $stm->rowCount();
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }


    /**
     *  Get best-selling products
     * 
     *  (If sortSetting is set to ASC it will return the worst selling)
     */
    public function getBestSelling()
    {

        try {
            $query = "SELECT COUNT(*) as product_sales FROM product p 
                      INNER JOIN order_detail od ON p.id = od.product_id 
                      GROUP BY p.id
                      ORDER BY product_sales ";

            // If no sort settings -> DESC
            $this->isSort && $this->isOrderAsc ? $query .= "ASC" : $query .= "DESC";

            $query .= " LIMIT :limitOffset, :productsPerPage";

            $stm = Database::$pdo->prepare($query);


            $stm->bindParam(':productsPerPage', self::$productsPerPage);

            $limitOffset = $this->pageNumber * self::$productsPerPage;
            $stm->bindParam(':limitOffset', $limitOffset);

            $stm->execute();
            $bySales = $stm->fetch(PDO::FETCH_ASSOC);

            // If the "best sales" array is empty return the first 10 products form the db
            if (!$bySales) {
                return self::queryByFilterString("", "", false, true);
            } else return $bySales;
        } catch (\Exception $e) {
            echo $e;
        }
    }
}
