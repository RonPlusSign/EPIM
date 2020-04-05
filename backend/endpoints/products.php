<?php
/*
    Author: regi18

    See below for options
 */

require_once __DIR__ . '/../classes/ProductsHandler.php';


$ph = new ProductsHandler();
$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");


switch ($requestMethod) {
        /**
     *  Get the list of products bases on a filter:
     *
     *      - by title          ?q=
     *      - by category       ?c=
     *      - by brand          ?b=
     *      - by price range    ?ps=x&pe=x  (price start and price end)
     *
     *  Order by:
     *      - Order flag                    ?sort
     *      - Order ASC or DESC             ?asc or ?desc
     *      - order by pric                 ?s-price
     *      - order by titl                 ?s-title
     *
     *  Pagination: ?p=    (Example ?p=0 page one, it will show an arbitrary amount of products per page)
     * 
     *  Single product: ?id=
     *
     */
    case 'GET':

        // Check for sorting settings
        if (isset($_GET['sort'])) {
            $isAsc = true;
            $orderBy = '';

            if (isset($_GET['desc'])) $isAsc = false;

            if (isset($_GET['s-price'])) $orderBy = 'price';
            else if (isset($_GET['s-title'])) $orderBy = 'title';

            $ph->setSortSettings(true, $isAsc, $orderBy);
        }

        // Category filter
        if (isset($_GET['c'])) {
            $ph->setExtraFilter('category', $_GET['c']);
        }

        // Brand filter
        if (isset($_GET['b'])) {
            $ph->setExtraFilter('brand', $_GET['b']);
        }

        // Price range filter
        if (isset($_GET['ps']) && isset($_GET['pe'])) {
            $ph->setPriceRangeFilter(true, $_GET['ps'], $_GET['pe']);
        }

        if (isset($_GET['p'])) {
            $ph->setPageNumber($_GET['p']);
        }

        // Get all products by title filter
        if (isset($_GET['q'])) {
            echo json_encode(array(
                "totalResults" => $ph->getNumberOfProducts($_GET['q']),
                "productsPerPage" => $ph->getResultPerPageLimit(),
                "data" => $ph->getByTitle($_GET['q'])
            ));
        }
        // Get single product
        else if (isset($_GET['id'])) {
            echo json_encode(
                $ph->getProduct($_GET['id'])
            );
        }

        break;

    case 'POST':

        // Set product's quantity
        if (isset($_GET['set-quantity']) && isset($_GET['id'])) {
            $json = json_decode(file_get_contents('php://input'));
            //$ph->setQuantity($_GET['id'], $json->quantity);
        }


    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
