<?php

namespace Buff\classes\middlewares;

use DomainException;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

final class PmsAuthentication
{
	public function __invoke($request, $response, $next)
    {

        $params = $request->getParams();
        var_dump($params);die;

        $response = $next($request, $response);

        return $response;
    }
}