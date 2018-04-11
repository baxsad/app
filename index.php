<?php

// OW!~
require __DIR__ . '/vendor/autoload.php';
$cof = require __DIR__ . '/config/main.php';
$app = new \Slim\App($cof);
require __DIR__ . '/classes/app/Dependencies.php';
require __DIR__ . '/classes/app/Middleware.php';
require __DIR__ . '/classes/app/Routes.php';
$app->run();
