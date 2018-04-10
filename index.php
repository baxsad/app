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

$app->get('/', function (Request $req,  Response $res, $args = []) {
	$this->logger->addInfo("Ticket list");
    return $res->withStatus(200)->write('Hello World!');
});
$app->run();
