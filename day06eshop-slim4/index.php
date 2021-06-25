<?php

require_once "setup.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Views\Twig;

const PRODUCTS_PER_PAGE = 5;

require_once "account.php";
require_once "admin.php";

$app->get('/forbidden', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'error_forbidden.html.twig');
});

$app->get('/error_internal', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'error_internal.html.twig');
});

$app->get('/error_notfound', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'error_notfound.html.twig');
});

function isInteger($input){
    return(ctype_digit(strval($input)));
}

$app->get('/category/{id}', function (Request $request, Response $response, array $args) {
    $view = Twig::fromRequest($request);

    $categories = DB::query( "SELECT * FROM categories");

    $categoryId = $args['id'];

    if(!isInteger($categoryId) 
        || $categoryId < 1 
        || $categoryId > count($categories))
    {
        return $response->withHeader("Location","/forbidden",403);
    }


    $totalProducts = DB::queryFirstField("SELECT COUNT(*) AS 'count' FROM products");

    $totalPages = ceil($totalProducts / PRODUCTS_PER_PAGE);
    
    $get = $request->getQueryParams();

    $currentPage = 1;
    $productStart = 0;

    if(isset($get['page'])){
        $currentPage = $get['page'];
    }

    if($currentPage < 1){
        $currentPage = 1;
    } else if($currentPage > $totalPages){
        $currentPage = $totalPages;
    }

    $productStart = ($currentPage - 1) * PRODUCTS_PER_PAGE;

    $products = DB::query(
        "SELECT p.id AS 'productId', p.name AS 'productName'
            , p.description, p.unitPrice, p.pictureFilePath
            , c.name AS 'categoryName'
        FROM products AS p
        JOIN categories AS c 
        ON c.id = p.categoryId
        WHERE c.id = %i
        LIMIT %i,%i ",$categoryId, $productStart, PRODUCTS_PER_PAGE
    );

    return $view->render($response, 'index.html.twig',[
        'products' => $products,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
        'categories' => $categories
    ]);
});

$app->get('/', function (Request $request, Response $response, array $args) {

    $categories = DB::query( "SELECT * FROM categories");
    $view = Twig::fromRequest($request);

    $currentPage = 1;
    $productStart = 0;

    $totalProducts = DB::queryFirstField("SELECT COUNT(*) AS 'count' FROM products");
    $totalPages = ceil($totalProducts / PRODUCTS_PER_PAGE);
    
    $get = $request->getQueryParams();
    if(isset($get['page'])){
        $currentPage = $get['page'];
    }

    if($currentPage < 1) {
        // could show an error page here instead
        $currentPage = 1;
    } else if($currentPage > $totalPages) {
        // could show an error page here instead
        $currentPage = $totalPages;
    }

    $productSkip = ($currentPage - 1) * PRODUCTS_PER_PAGE;

    $products = DB::query(
        "SELECT p.id AS 'productId', p.name AS 'productName'
            , p.description, p.unitPrice, p.pictureFilePath
            , c.name AS 'categoryName'
        FROM products AS p
        JOIN categories AS c 
        ON c.id = p.categoryId
        ORDER BY p.id LIMIT %i,%i ", $productSkip, PRODUCTS_PER_PAGE
    );

    return $view->render($response, 'index.html.twig',[
        'products' => $products,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
        'categories' => $categories
    ]);
});

// +++ PAGINATION USING AJAX

$app->get('/ajax/productpage/{pageNo:[0-9]+}', function (Request $request, Response $response, array $args) {
    $view = Twig::fromRequest($request);
    $pageNo = $args['pageNo'];
    $productSkip = ($pageNo - 1) * PRODUCTS_PER_PAGE;
    $productsList = DB::query("SELECT * FROM products ORDER BY id LIMIT %i,%i ", $productSkip, PRODUCTS_PER_PAGE);    
    return $view->render($response, '/ajax_productpage.html.twig', [ 'productsList' => $productsList ]);
});

// ajax pagination index
$app->get('/ap', function (Request $request, Response $response, array $args) {

    $categories = DB::query( "SELECT * FROM categories");
    $view = Twig::fromRequest($request);

    $totalProducts = DB::queryFirstField("SELECT COUNT(*) AS 'count' FROM products");
    $totalPages = ceil($totalProducts / PRODUCTS_PER_PAGE);

    return $view->render($response, 'ap_index.html.twig',[
        'totalPages' => $totalPages,
        'categories' => $categories
    ]);
});

// --- PAGINATION USING AJAX


$app->get('/addToCart', function (Request $request, Response $response) {

    $sessionId = $_COOKIE['PHPSESSID'];
    $get = $request->getQueryParams();

    $message = 'failed';
    if(isset($get['quantity']) && isset($get['productId'])){

        $productId = $get['productId'];
        
        if($get['quantity'] > 0){
            $product = DB::queryFirstRow(
                "SELECT id
                FROM products 
                WHERE id = %i",$productId
            );


            if( isset($product['id'])){

                $quantity = $get['quantity'];

                $cartItem = DB::queryFirstRow(
                    "SELECT quantity 
                    FROM cartItems 
                    WHERE sessionId = %s
                    AND productId = %i", $sessionId, $productId
                );

                if(isset($cartItem['quantity'])){
                    $quantity += $cartItem['quantity'];
                }

                DB::insertUpdate("cartItems",[
                    'sessionId' => $sessionId,
                    'productId' => $productId,
                    'quantity' => $quantity
                ]);

                $message = "succeed";
            }
        }
    }
    
    $response->getBody()->write($message);
    return $response;
});

$app->get('/cart', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'cart.html.twig');
});


periodicCleanup();

// once every 100 pages are accessed attempt a cleanup
function periodicCleanup() {
    if (rand(1,1000) != 1) return;
    // remove all records from 'passwordresets' table that have expired
    // remove all unconfirmed accounts that were created more than X time ago
    // e-shop: remove cart items created 24 hours age or older
}


$app->run();

