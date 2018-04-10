<?php


use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use buff\controllers\HomeController;
use buff\controllers\UserController;

require 'vendor/autoload.php';

$cof = require 'config/main.php';
$app = new \Slim\App($cof);
$con = $app->getContainer();
$con['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};
$con['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('<center><h1 style="font-size: 10em">404</h1></center>');
    };
};

$app->get('/', HomeController::class . ':home');
$app->get('/user', UserController::class . ':users');

$app->run();
