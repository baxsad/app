<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use buff\controllers\home\HomeController;
use buff\controllers\user\UserController;

/**
 * Slim Home
 */
$app->get('/', HomeController::class . ':home');
/**
 * Slim API
 */
$app->group('/api', function () use ($app) {

	// user
    $app->group('/members', function () use ($app) {
        $app->get('/show', UserController::class . ':get');
        $app->post('[/]', UserController::class . ':create');
        $app->put('/:id', UserController::class . ':update');
        $app->delete('/:id', UserController::class . ':delete');
    });
});