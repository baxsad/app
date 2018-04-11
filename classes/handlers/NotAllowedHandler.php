<?php

namespace buff\classes\handlers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class NotAllowedHandler
{
    protected $container;

    public function __construct(ContainerInterface $container) {
       $this->container = $container;
    }

    public function __invoke(Request $request, Response $response) {
        return $response
            ->withStatus(405)
            ->withHeader('Content-Type', 'text/html')
            ->write('Method Not Allowed!');
    }
}