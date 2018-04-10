<?php

namespace buff\controllers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class UserController
{
   protected $container;

   public function __construct(ContainerInterface $container) {
       $this->container = $container;
   }

   public function users(Request $req,  Response $res, $args = []) {
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write('Hello 你好！');
   }
}