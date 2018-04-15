<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Middleware\JwtAuthentication;
use Slim\Middleware\JwtAuthentication\RequestMethodRule;
use Slim\Middleware\JwtAuthentication\RequestPathRule;
use Buff\classes\services\ResponseService;
use Buff\classes\utils\Environment;
use Buff\classes\utils\Token;

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
		"secure" => false,
		"secret" => Environment::$jwtSecretKey,
        "relaxed" => ["localhost", "127.0.0.1"],
        "environment" => ["HTTP_AUTHORIZATION", "REDIRECT_HTTP_AUTHORIZATION"],
        "algorithm" => Environment::$jwtAlgorithm,
        "header" => "X-Token",
        "regexp" => "/OwO\s+(.*)$/i",
        "cookie" => "X-Cookie",
        "attribute" => "jwt",
		"logger" => $app->getContainer()['logger'],
		"rules" => [
            new RequestPathRule([
                "path"   => ["/api/members"]
            ]),
            new RequestMethodRule([
                "passthrough" => ["OPTIONS"]
            ]),
            new RequestMethodRule([
                "passthrough" => ["POST"],
                "path"   => ["/api/members/create","/api/members/auth"]
            ]),
            new RequestMethodRule([
                "passthrough" => ["GET"],
                "path"   => ["/api/members/show"]
            ])
        ],
        "before" => function ($request, $arguments) use ($container) {
            $container["token"]->populate($arguments["decoded"]);
        },
        "error" => function (Request $request, Response $response, $arguments) {
        	$responseService = new ResponseService();
        	$responseService
        	    ->withFailure()
        	    ->withErrorCode(9001);
            
			return $response
                ->withStatus(200)
                ->write($responseService->write());
		}
    ])
);