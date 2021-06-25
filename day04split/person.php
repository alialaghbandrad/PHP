<?php

require_once 'vendor/autoload.php';

require_once 'init.php';

use Slim\Http\Request;
use Slim\Http\Response;

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
