<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use buff\classes\handlers\NotFoundHandler;

/**
 * Slim Container
 */
$container = $app->getContainer();

/**
 * Slim NotFoundHandler
 */
$container['notFoundHandler'] = function ($c) {
    return new NotFoundHandler($c);
};