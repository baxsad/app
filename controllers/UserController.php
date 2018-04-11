<?php

namespace buff\controllers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Query\Builder;

class UserController
{
   protected $container;
   protected $table;

   public function __construct(ContainerInterface $container) {
       $this->table = $container->get('db')->table('user');
       $this->container = $container;
   }

   public function get(Request $req,  Response $res, $args = []) {
        $users = $this->table->get();
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write(json_encode($users));
   }

   public function create(Request $req,  Response $res, $args = []) {
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write('create');
   }

   public function update(Request $req,  Response $res, $args = []) {
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write('update');
   }

   public function delete(Request $req,  Response $res, $args = []) {
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write('delete');
   }
}