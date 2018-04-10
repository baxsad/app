<?php


use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'actions/NotFoundAction.php';

$cof = require 'config/main.php';
$app = new \Slim\App($cof);

$container = $app->getContainer();
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};
$container['notFoundHandler'] = NotFoundAction::class;

$app->get('/', function (Request $req,  Response $res, $args = []) {
    return $res
        ->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write('Hello 胖虎！');
});

$app->get('/test', NotFoundAction::class);

$app->run();
