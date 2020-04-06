<?php
/*
    Author: regi18
 */

require_once __DIR__ . '/../lib/Bootstrap.php';


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
        return self::$productsPerPage;
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
    public function setPriceRangeFilter($isActive, $minimum = 0, $maximum = 0)
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
     * 
     * @return Array Filtered data
     */
    private function queryByFilterString($filter, $filterString, $countResults = false)
    {
        // Query
        if ($countResults) {
            $query = "SELECT count(*) as number_of_products FROM product";
        } else {
            $query = "SELECT product.*, brand.name as brand, brand.id as brand_id, category.name as category, category.id as category_id FROM product";
        }

        $query .= " INNER JOIN category ON product.category=category.id
                    INNER JOIN brand ON product.brand=brand.id
                    WHERE MATCH (" . $filter . ") AGAINST (:filterString IN NATURAL LANGUAGE MODE)";


        // Check for extra filter
        if ($this->extraFilter == 'category') {
            $query .= " AND category.name = :extraFilter";
        } else if ($this->extraFilter == 'brand') {
            $query .= " AND brand.name = :extraFilter";
        }

        // Check for price filter
        if ($this->isPriceRangeFilter) {
            $query .= " AND product.sell_price >= :minPrice AND product.sell_price <= :maxPrice";
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
        $stm->bindParam(':filterString', $filterString);

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
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * This function does a similar query to "getByTitle" 
     * but it returns only the total number of rows (without any limit)
     */
    public function getNumberOfProducts($title)
    {
        return self::queryByFilterString('title', $title, true)[0]["number_of_products"];
    }

    public function getByTitle($title)
    {
        return self::queryByFilterString('title', $title);
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
    }



    public function setQuantity($id, $quantity)
    {
        // TODO check login
        $stm = Database::$pdo->prepare("UPDATE product SET quantity=:quantity WHERE id=:id");
        $stm->bindParam(':id', $id);
        $stm->bindParam(':quantity', $quantity);
        $stm->execute();
        return $stm->rowCount();
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
        // TODO check login
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
    }

    /**
     * Delete a product
     * 
     * @param int $id The product's id
     * @return int The row count of the query
     */
    public function deleteProduct($id)
    {
        // TODO check login
        try {
            $stm = Database::$pdo->prepare("DELETE FROM product WHERE product.id=:id");
            $stm->bindParam(':id', $id);
            $stm->execute();
            return $stm->rowCount();
        } catch (\Exception $e) {
            echo $e;
        }
    }

    /**
     * Edit a product.
     * It will patch every parameter it will receive.
     * Every other parameter will remain unchanged.
     * 
     * @param stdClass $newProductJson An array containing the JSON with the product
     */
    public function editProduct($id, $newProductJson)
    {
        // TODO check login
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
            echo $e;
        }
    }
}