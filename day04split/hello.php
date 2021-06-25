<?php

require_once 'vendor/autoload.php';

require_once 'init.php';

use Slim\Http\Request;
use Slim\Http\Response;


// Define app routes
$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    return $response->write("Hello " . $args['name']);
});

$app->get('/hello/{namePPP}/{agePPP:[0-9]+}', function (Request $request, Response $response, $args) {
    $name = $args['namePPP'];
    $age = $args['agePPP'];
    DB::insert('people', ['name' => $name, 'age' => $age]);
    $insertId = DB::insertId();
    return $this->view->render($response, 'hello.html.twig', ['ageTTT' => $age, 'nameTTT' => $name, 'insertIdTTT' => $insertId]);
    // return $response->write("<p>Hello $name, you are <b>$age</b> y/o</p>");
});