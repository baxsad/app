<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Buff\classes\handlers\ErrorHandler;
use Buff\classes\handlers\NotFoundHandler;
use Buff\classes\handlers\NotAllowedHandler;

/**
 * Slim Container
 */
$container = $app->getContainer();

/**
 * Slim ErrorHandler
 */
$container['errorHandler'] = function ($c) {
    return new ErrorHandler($c);
};
/**
 * Slim phpErrorHandler
 */
$container["phpErrorHandler"] = function ($c) {
    return $c["errorHandler"];
};
/**
 * Slim NotFoundHandler
 */
$container['notFoundHandler'] = function ($c) {
    return new NotFoundHandler($c);
};
/**
 * Slim NotAllowedHandler
 */
$container['notAllowedHandler'] = function ($c) {
    return new NotAllowedHandler($c);
};