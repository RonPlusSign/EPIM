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


class Product
{

    private $pdo;
    private static $productsPerPage = 16;
    private $sortResults;
    private $orderBy;
    private $orderAsc;

    function __construct()
    {
        self::$pdo = Database::getPDO();
        self::$sortResults = false;
    }



    public function setResultPerPageLimit($limit) {
        self::$productsPerPage = $limit;
    }



    private function queryByFilterString($filter, $filterString)
    {
        $query = 'SELECT * FROM products
        INNER JOIN category ON products.category=category.id
        INNER JOIN brand ON products.brand=brand.id
        WHERE MATCH (' . $filter . ') AGAINST (":filterString" IN NATURAL LANGUAGE MODE) 
        LIMIT :productsPerPage';

        // check if sort 'activated'
        if (self::$sortResults) { 
            $query .= ' ORDER BY :orderBy';
            if (self::$orderAsc) $query .= ' ASC';
            else $query .= ' DESC';
        }

        $stm = self::$pdo->prepare($query);
        $stm->bindParam(':filterString', $filterString);
        $stm->bindParam(':productsPerPage', self::$productsPerPage);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getByTitle($title)
    {
        $data = self::queryByFilterString('title', $title);
    }


}
