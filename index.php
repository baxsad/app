<?php
require __DIR__ . '/vendor/autoload.php';

// OW!~
// Instantiate the app
$cof = require __DIR__ . '/config/main.php';
$app = new \Slim\App($cof);

/**
 * Register dependencies
 * Register handlers
 * Register middleware
 * Register routes
 */
require __DIR__ . '/classes/slim/Dependencies.php';
require __DIR__ . '/classes/slim/Handlers.php';
require __DIR__ . '/classes/slim/Middleware.php';
require __DIR__ . '/classes/slim/Routes.php';

// run app
$app->run();
