<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Buff\controllers\home\HomeController;
use Buff\controllers\user\UserController;

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
        $app->post('/create', UserController::class . ':create');
        $app->post('/auth', UserController::class . ':auth');
        $app->put('/update', UserController::class . ':update');
    });

    // tokens
    $app->group('/token', function () use ($app) {

    });
});