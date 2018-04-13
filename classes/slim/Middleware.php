<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// alexberce/Slim-API
// tuupola/slim-api-skeleton
/**
 * Slim To enable CORS
 */
// $app->options('/{routes:.+}', function ($request, $response, $args) {
//     return $response;
// });

/**
 * Slim Middleware
 */
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
		->withHeader('Allow', 'GET, POST, PUT, DELETE, OPTIONS')
		->withHeader('Content-Type', 'application/json; charset=UTF-8');
});

$app->add(
	new \Tuupola\Middleware\JwtAuthentication([
		"attribute" => "jwt",
		"header" => "X-Token",
		"cookie" => "nekot",
		"algorithm" => ["HS256", "HS384"],
		"logger" => $app->getContainer()['logger'],
		"secure" => false,
		"secret" => "supersecretkeyyoushouldnotcommittogithub",
		"rules" => [
            new \Tuupola\Middleware\JwtAuthentication\RequestPathRule([
                "path"   => "/",
                "ignore" => ['/api/token'],
            ]),
            new \Tuupola\Middleware\JwtAuthentication\RequestMethodRule([
                "ignore" => ["OPTIONS"]
            ]),new Tuupola\Middleware\JwtAuthentication\RequestMethodRule([
                "ignore" => ["POST"],
                "path"   => ["/api/members/create","/api/members/auth"]
            ])
        ]
    ])
);