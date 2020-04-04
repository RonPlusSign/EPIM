<?php
/*
    Author: regi18

    Get the list of products bases on a filter:

        - by title          ?q=
        - by category       ?c=
        - by brand          ?b=
        - by price range    ?ps=x&pe=x  (price start and price end)

    Order by:
        - Order flag                    ?sort
        - order by price ascendent      ?price-asc
        - order by price descendent     ?price-desc
        - order by title ascentent      ?title-asc
        - order by title descentent     ?title-desc



    Pagination: ?p=    (Example ?p=1 page one, it will show an arbitrary amount of products per page)


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

    public function __construct()
    {
        $this->isSort = false;
        $this->extraFilter = NULL;
    }


    public function setResultPerPageLimit($limit)
    {
        self::$productsPerPage = $limit;
    }

    public function setExtraFilter($filter, $filterString)
    {
        $this->extraFilter = $filter;
        $this->extraFilterString = $filterString;
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




    private function queryByFilterString($filter, $filterString)
    {
        // $query = "SELECT * FROM product
        // INNER JOIN category ON product.category=category.id
        // INNER JOIN brand ON product.brand=brand.id
        // WHERE MATCH (" . $filter . ") AGAINST (:filterString IN NATURAL LANGUAGE MODE)";

        // $query = "SELECT * FROM (
        //     SELECT p.id, p.title, p.description, p.purchase_price, p.sell_price, p.recommended_price, p.quantity, c.name as category, b.name as brand FROM product as p
        //     INNER JOIN category as c ON p.category=c.id
        //     INNER JOIN brand as b ON p.brand=b.id) AS pf ";
            
        // if ($this->extraFilter == 'category') {
        //     $query .= "WHERE c.name = ':extraFilter'";
        // }
        // else if ($this->extraFilter == 'brand') {
        //     $query .= "WHERE b.name = ':extraFilter'";
        // }
        
        //$query .= "WHERE MATCH (" . $filter . ") AGAINST (:filterString IN NATURAL LANGUAGE MODE)";

        $query = "SELECT product.*, brand.name as brand, category.name as category FROM product
        INNER JOIN category ON product.category=category.id
        INNER JOIN brand ON product.brand=brand.id
        WHERE MATCH (" . $filter . ") AGAINST (:filterString IN NATURAL LANGUAGE MODE)";

        if ($this->extraFilter == 'category') {
            $query .= " AND category.name = :extraFilter";
        }
        else if ($this->extraFilter == 'brand') {
            $query .= " AND brand.name = :extraFilter";
        }
        


        // check if sort 'activated' 
        if ($this->isSort) {
            if ($this->orderBy == 'price') $query .= ' ORDER BY sell_price';
            else $query .= ' ORDER BY title';

            if ($this->isOrderAsc) $query .= ' ASC';
            else $query .= ' DESC';
        }

        $query .= ' LIMIT :productsPerPage';

        $stm = Database::$pdo->prepare($query);
        $stm->bindParam(':filterString', $filterString);
        $stm->bindParam(':productsPerPage', self::$productsPerPage);

        if ($this->extraFilter != NULL) $stm->bindParam(':extraFilter', $this->extraFilterString);


        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getByTitle($title)
    {
        return self::queryByFilterString('title', $title);
    }

    public function filterByCategory($category)
    {
        return self::queryByFilterString('category', $category);
    }

    public function filterByBrand($brand)
    {
        return self::queryByFilterString('category', $category);
    }
}
