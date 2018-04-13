<?php
date_default_timezone_set('Asia/Shanghai');
defined('SYS_ENV') or define('SYS_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/lib/APP.php';

// OW!~
// APP registry
APP::registry();
// Instantiate the app
$cof = APP::$base->config->get('settings');
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
