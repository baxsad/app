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
	new \Slim\Middleware\JwtAuthentication(
		[
			"secret"    => Environment::$jwtSecretKey,
			"relaxed" => Environment::$developmentDomains,
			"algorithm" => "HS256",
			"header" => "X-Token",
			"rules"     => [
				new \Slim\Middleware\JwtAuthentication\RequestPathRule(
					[
						"path"        => "/",
						"passthrough" => ['/v1/token'],
					]
				),
				new \Slim\Middleware\JwtAuthentication\RequestMethodRule(
					[
						"passthrough" => ["OPTIONS"],
					]
				),
				new \Slim\Middleware\JwtAuthentication\RequestMethodRule(
					[
						"passthrough" => ["POST"],
					    "path" => "/v1/users"
					]
				),
				new \Slim\Middleware\JwtAuthentication\RequestMethodRule(
					[
						"passthrough" => ["POST"],
						"path" => "/v1/account"
					]
				)
			],
			"callback"  => function ($request, $response, $arguments) use ($container) {
				$container["jwt"] = $arguments["decoded"];
			},
			"error"     => function ($request, $response, $arguments) {
				(new Invobox\Api\Response\ResponseService())
					->withErrorMessage($arguments["message"])
					->withStatusCode(401)
					->withErrorCode(ResponseErrorCodes::RESPONSE_CODE_UNAUTHORIZED)
					->write();
			},
		]
	)
);