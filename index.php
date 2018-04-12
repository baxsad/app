<?php
date_default_timezone_set('PRC');
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/lib/USApplication.php';

// OW!~
// APP registry
\USApplication::registry();
// Instantiate the app
$cof = \USApplication::$base->config->loadConfig('main');
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
