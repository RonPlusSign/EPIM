<?php
/*
    Author: regi18

    See below for options
 */

require_once __DIR__ . '/../classes/ProductsHandler.php';


$ph = new ProductsHandler();
$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");
session_start();

switch ($requestMethod) {
        /**
     *  Get the list of products bases on a filter:
     *
     *      - by title          ?q=
     *      - by category       ?c=
     *      - by brand          ?b=
     *      - by price range    ?ps=x&pe=x  (price start and price end)
     *      - by sales          ?sales      (the only valid settings are ?asc or ?desc and ?p)
     *
     *  Order by:
     *      - order by price                ?sort=price
     *      - order by title                ?sort=title
     *      - Order ASC or DESC             ?asc or ?desc

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
            $orderBy = 'title';

            if (isset($_GET['desc'])) $isAsc = false;

            if ($_GET['sort'] == 'price') $orderBy = 'price';
            else if ($_GET['sort'] == 'title') $orderBy = 'title';

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
        if (isset($_GET['ps']) || isset($_GET['pe'])) {
            // if start is omited sets it to 0
            if (!isset($_GET['ps'])) $ph->setPriceRangeFilter(true, 0, $_GET['pe']);
            // if end is omited sets it to FLOAT_MAX
            else if (!isset($_GET['pe'])) $ph->setPriceRangeFilter(true, $_GET['ps'], PHP_FLOAT_MAX);
            // if both present, normal behavior
            else $ph->setPriceRangeFilter(true, $_GET['ps'], $_GET['pe']);
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
        } else if (isset($_GET['images']) && isset($_GET['id'])) {
            echo json_encode($ph->getProductImages($_GET["id"]));
        }

        // Get single product
        else if (isset($_GET['id'])) {
            $product = $ph->getProduct($_GET['id']);

            if (!$product) {
                echo json_encode(["success" => false, "info" => "Product not found"]);
                http_response_code(400);
            } else echo json_encode($product);
        }

        // Get best selling
        else if (isset($_GET['sales'])) {
            $products = $ph->getBestSelling();

            echo json_encode(array(
                "totalResults" => count($products),
                "productsPerPage" => $ph->getResultPerPageLimit(),
                "data" => $products
            ));
        }
        
        else {
            echo json_encode(array(
                "totalResults" => $ph->getNumberOfProducts(),
                "productsPerPage" => $ph->getResultPerPageLimit(),
                "data" => $ph->queryByFilterString("", "", false, true)
            ));
        }


        // Display help message
        // if (empty($_GET)) {
        //     // http_response_code(400);
        //     // echo "Missing queries. Options:\n";
        //     // echo "\n?q= | ?c= | ?b | ?ps=x&pe=x | ?sales";
        //     // echo "\n?sort= | ?asc or ?desc";
        //     // echo "\n?id= | ?p=";
        // }

        break;


        /**
         *  POST:
         * 
         *  Set quantity of product: ?set-quantity&id=
         *      - { "quantity": x }
         * 
         *  Add new product:
         *      - Same json as returned in GET, without 'id', brand and category (name).
         */
    case 'POST':
        $json = json_decode(file_get_contents('php://input'));

        // Set product's quantity
        if (isset($_GET['set-quantity']) && isset($_GET['id'])) {
            $ph->setQuantity($_GET['id'], $json->quantity) == 1 ? http_response_code(200) : http_response_code(403);
        }
        // Add new product
        else {
            $ph->addProduct($json) == 1 ? http_response_code(200) : http_response_code(403);
        }

        break;


        /**
         *  Delete product: ?id=
         */
    case 'DELETE':
        $json = json_decode(file_get_contents('php://input'));

        if (isset($_GET['id'])) {
            $ph->deleteProduct($_GET['id']) == 1 ? http_response_code(200) : http_response_code(403);
        }

        break;


        /**
         *  Edit product: ?id
         *  
         *  Patch data of selected product. You can post only the desired changed values.
         *  (same format as in POST)
         */
    case 'PATCH':
        $json = json_decode(file_get_contents('php://input'));

        if (isset($_GET['id'])) {
            $ph->editProduct($_GET['id'], $json) == 1 ? http_response_code(200) : http_response_code(403);
        }

        break;



    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
