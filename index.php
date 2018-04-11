<?php
require 'vendor/autoload.php';
/**
 * Slim 
 */
$cof = require 'config/main.php';
$app = new \Slim\App($cof);
$app->run();
