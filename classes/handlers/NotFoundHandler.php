<?php

namespace Buff\classes\handlers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class NotFoundHandler
{
    protected $container;

    public function __construct(ContainerInterface $container) {
       $this->container = $container;
    }

    public function __invoke(Request $request, Response $response) {
        return $response
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Not Found!');
    }
}