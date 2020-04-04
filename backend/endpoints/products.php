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
        - Order ASC or DESC             ?asc or ?desc
        - order by pric                 ?s-price
        - order by titl                 ?s-title



    Pagination: ?p=    (Example ?p=1 page one, it will show an arbitrary amount of products per page)


 */

require_once __DIR__ . '/../classes/ProductsHandler.php';


$ph = new ProductsHandler();
$requestMethod = $_SERVER["REQUEST_METHOD"];
header("Content-Type: application/json");


switch ($requestMethod) {
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

        if (isset($_GET['c'])) {
            $ph->setExtraFilter('category',$_GET['c']);
        }

        if (isset($_GET['b'])) {
            $ph->setExtraFilter('brand',$_GET['b']);
        }

        if (isset($_GET['ps']) && isset($_GET['pe'])) {
        }

        if (isset($_GET['q'])) {
            echo json_encode($ph->getByTitle($_GET['q']));
        }

        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
