<?php

// for development we want to see all the errors, some php.ini versions disable those (e.g. MAMP)
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once 'vendor/autoload.php';

use Slim\Http\Request;
use Slim\Http\Response;

DB::$dbName = 'day03people';
DB::$user = 'day03people';
DB::$password = 'G455ehMuGRmdCq70';
DB::$host = 'localhost';
DB::$port = 3333;

// Create and configure Slim app
$config = ['settings' => [
    'addContentLengthHeader' => false,
    'displayErrorDetails' => true
]];
$app = new \Slim\App($config);

// Fetch DI Container
$container = $app->getContainer();

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(dirname(__FILE__) . '/templates', [
        'cache' => dirname(__FILE__) . '/tmplcache',
        'debug' => true, // This line should enable debug mode
    ]);
    //
    $view->getEnvironment()->addGlobal('test1','VALUE');
    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    return $view;
};


// Define app routes
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello " . $args['name']);
});

$app->get('/hello/{namePPP}/{agePPP:[0-9]+}', function ($request, $response, $args) {
    $name = $args['namePPP'];
    $age = $args['agePPP'];
    DB::insert('people', ['name' => $name, 'age' => $age]);
    $insertId = DB::insertId();
    return $this->view->render($response, 'hello.html.twig', ['ageTTT' => $age, 'nameTTT' => $name, 'insertIdTTT' => $insertId]);
    // return $response->write("<p>Hello $name, you are <b>$age</b> y/o</p>");
});

// STATE 1: first display of the form
$app->get('/addperson', function ($request, $response, $args) {    
    return $this->view->render($response, 'addperson.html.twig');
});

// STATE 2&3: receiving form submission
$app->post('/addperson', function (Request $request, Response $response, $args) {    
    $name = $request->getParam('name');
    $age = $request->getParam('age');
    // validation
    $errorList = [];
    if (strlen($name) < 2 || strlen($name) > 100) {
        $name = "";
        $errorList[] = "Name must be 2-100 characters long"; // append to array
    }
    if (filter_var($age, FILTER_VALIDATE_INT) === false || $age < 0 || $age > 150) {
        $age = "";
        $errorList[] = "Age must be a number between 0 and 150";
    }
    //
    if ($errorList) { // STATE 2: errors - show and redisplay the form
        $valuesList = ['name' => $name, 'age' => $age];
        return $this->view->render($response, "addperson.html.twig", ['errorList' => $errorList, 'v' => $valuesList]);
    } else { // STATE 3: success
        DB::insert('peopleXAZX', ['name' => $name, 'age' => $age]);
        return $this->view->render($response, "addperson_success.html.twig");
    }
});

// Run app
$app->run();

