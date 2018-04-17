<?php

namespace Buff\classes\middlewares;

use DomainException;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Buff\classes\services\ResponseService;
use Buff\classes\utils\Environment;

final class PmsAuthentication
{
	public function __invoke($request, $response, $next)
    {

    	if (!Environment::$enablePms) {
    		$response = $next($request, $response);
            return $response;
    	}

        $params = $request->getQueryParams();
        $postParams = $request->getParsedBody();
        if ($postParams) {
            $params = array_merge($params, (array)$postParams);
        }

        if (empty($params)) {
        	$params = array();
        }

        ksort($params);
        $text = '';
        foreach ($params as $k => $v) {
            $text .= $k . $v;
        }

        $appSecret = Environment::$pmsSecretKey;
        $sign = md5($appSecret . $text . $appSecret);

        $header = "";
        $message = "Using sign from request header";

        $headers = $request->getHeader("X-Sign");
        $header = isset($headers[0]) ? $headers[0] : "";

        do {
            if (preg_match("/(.*)/", $header, $matches)) {
                $header_sign = $matches[1];
                if ($header_sign != $sign) {
                	break;
                }

                $response = $next($request, $response);
                return $response;
            }
            break;
        } while (false);

        $responseService = new ResponseService();
        $responseService
        	->withFailure()
        	->withCode(10001);
            
		return $response
            ->withStatus(200)
            ->write($responseService->write());
    }
}