<?php

use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

require_once "setup.php";

$app->get('/admin/categories/list', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'admin/categories_list.html.twig');
});

