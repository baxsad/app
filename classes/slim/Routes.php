<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use buff\controllers\HomeController;
use buff\controllers\UserController;

/**
 * Slim Home
 */
$app->get('/', HomeController::class . ':home');
/**
 * Slim API
 */
$app->group('/api', function () use ($app) {

	// user
    $app->group('/users', function () use ($app) {
        $app->get('[/{id}]', UserController::class . ':get');
        $app->post('[/]', UserController::class . ':create');
        $app->put('/:id', UserController::class . ':update');
        $app->delete('/:id', UserController::class . ':delete');
    });
});