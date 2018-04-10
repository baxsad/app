<?php


use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$cof = require 'config/main.php';
$app = new \Slim\App(['settings' => $cof]);

$container = $app->getContainer();
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('<center><h1 style="font-size: 10em">404</h1></center>');
    };
};

$app->get('/', function (Request $req,  Response $res, $args = []) {
    return $res->withStatus(
    	200
    )->withHeader(
        'Content-Type',
        'application/json'
    )->write(
    	'{"status": 1,"msg": "success","data": {}}'
    );
});

$app->run();
