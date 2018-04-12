<?php
date_default_timezone_set('PRC');
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/lib/Buff.php';

// OW!~
// Buff registry
Buff::registry();
// Instantiate the app
$cof = require __DIR__ . '/config/main.php';
$app = new \Slim\App(['settings' => $cof]);
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
