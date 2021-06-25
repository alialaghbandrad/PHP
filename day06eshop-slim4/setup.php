<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require __DIR__ . '/vendor/autoload.php';

session_start();

$app = AppFactory::create();

// Add Error Middleware for 404 - not found handling
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setErrorHandler(
    \Slim\Exception\HttpNotFoundException::class, 
        function () use ($app) {
            $response = $app->getResponseFactory()->createResponse();
            return $response->withHeader('Location','/error_notfound', 404);
        }
);

// create a log channel

$log = new Logger('main');
$log->pushHandler(new StreamHandler('logs/everything.log', Logger::DEBUG));
$log->pushHandler(new StreamHandler('logs/errors.log', Logger::ERROR));

if (strpos($_SERVER['HTTP_HOST'], "ipd20.com") !== false) {
    // hosting on ipd20.com
    DB::$user = 'cp4966_teacher';
    DB::$password = 'UzXoLgOfibQ1Nk7n';
    DB::$dbName = 'cp4966_teacher';
} else { // local computer
    DB::$user = 'day06eshop';
    DB::$password = 'UzXoLgOfibQ1Nk7n';
    DB::$dbName = 'day06eshop';
    DB::$port = 3333;
}

DB::$error_handler = 'db_error_handler'; // runs on mysql query errors
DB::$nonsql_error_handler = 'db_error_handler'; // runs on library errors (bad syntax, etc)

function db_error_handler($params)
{
    header("Location: /error_internal", 500);

    global $log;
    $log->error("Database erorr[Connection]: " . $params['error']);

    if ($params['query']) {
        $log->error("Database error[Query]: " . $params['query']);
    }
    die();
}

// Create Twig
$twig = Twig::create(__DIR__ . '/templates', ['cache' => __DIR__ . '/tmplcache', 'debug' =>true]);

// Set Global variable($_SESSION)
$twig->getEnvironment()->addGlobal('session', $_SESSION);

// //set global date formatter. this is valid
// $twig->getEnvironment()
//     ->getExtension(\Twig\Extension\CoreExtension::class)
//     ->setDateFormat("F jS \\a\\t g:ia");


// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));
