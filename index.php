<?php
/**
 * 设置默认时区
 */
date_default_timezone_set('Asia/Shanghai');

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use buff\controllers\HomeController;
use buff\controllers\UserController;
/**
 * 自动加载类
 */
require 'vendor/autoload.php';
/**
 * Slim 初始化
 */
$cof = require 'config/main.php';
$app = new \Slim\App($cof);
/**
 * Slim Container
 */
$con = $app->getContainer();
/**
 * Slim logger
 */
$con['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};
/**
 * Slim 404 Page
 */
$con['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('<center><h1 style="font-size: 10em">404</h1></center>');
    };
};
/**
 * Slim Middleware
 */
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});
/**
 * Slim 路由 -> Home
 */
$app->get('/', HomeController::class . ':home');
/**
 * Slim 路由 -> API
 */
$app->group('/api', function () use ($app) {
    $app->get('/user', UserController::class . ':users');
});
/**
 * Slim 运行
 */
$app->run();
