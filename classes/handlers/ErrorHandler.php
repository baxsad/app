<?php

namespace Buff\classes\handlers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Crell\ApiProblem\ApiProblem;
use Throwable;

class ErrorHandler
{
    protected $container;

    public function __construct(ContainerInterface $container) {
       $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, Throwable $throwable) {
        $status  = $throwable->getCode() ?: 500;
        $problem = new ApiProblem($throwable->getMessage(), "about:blank");
        $problem->setStatus($status);
        $body = $problem->asJson(true);

        return $response
            ->withStatus($status)
            ->withHeader("Content-type", "application/problem+json")
            ->write($body);
    }
}