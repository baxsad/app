<?php


use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$cof = require 'config/main.php'
$app = new \Slim\App(['settings' => $cof]);
$app->get('/', function (Request $req,  Response $res, $args = []) {
    return $res->withStatus(200)->write('Hello World!');
});
$app->run();
