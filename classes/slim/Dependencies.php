<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use buff\classes\handlers\NotFoundHandler;

/**
 * Slim Container
 */
$container = $app->getContainer();
/**
 * Slim logger
 */
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};
/**
 * Slim Database
 */
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['name'], $db['user'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
/**
 * Slim 404 Page
 */
$container['notFoundHandler'] = function ($c) {
    // return function ($request, $response) use ($c) {
    //     return $c['response']
    //         ->withStatus(404)
    //         ->withHeader('Content-Type', 'text/html')
    //         ->write('<center><h1 style="font-size: 10em">404</h1></center>');
    // };
    return new NotFoundHandler();
};