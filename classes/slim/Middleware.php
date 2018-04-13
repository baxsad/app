<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Middleware\JwtAuthentication;
use Slim\Middleware\JwtAuthentication\RequestMethodRule;
use Slim\Middleware\JwtAuthentication\RequestPathRule;

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
	new JwtAuthentication([
		"attribute" => false,
		"header" => "X-Token",
		"cookie" => "nekot",
		"algorithm" => ["HS256", "HS384"],
		"logger" => $app->getContainer()['logger'],
		"secure" => false,
		"secret" => "supersecretkeyyoushouldnotcommittogithub",
		"rules" => [
            new RequestPathRule([
                "path"   => "/",
                "passthrough" => ['/api/token'],
            ]),
            new RequestMethodRule([
                "passthrough" => ["OPTIONS"]
            ]),
            new RequestMethodRule([
                "passthrough" => ["POST"],
                "path"   => ["/api/members/create","/api/members/auth"]
            ])
        ]
    ])
);