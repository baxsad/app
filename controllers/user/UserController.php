<?php

namespace buff\controllers\user;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Query\Builder;
use buff\classes\services\ResponseService;

class UserController
{
   protected $container;
   protected $table;
   private $responseService;

   public function __construct(ContainerInterface $container) {
       $this->container = $container;
       $this->table = $container->get('db')->table('user');
       $this->responseService = new ResponseService();
   }

   public function get(Request $req,  Response $res, $args = []) {
        $uid = $req->getAttribute('id');
        $user = $this->table->where('uid','=',$uid)->get();
        $userModel = new UserModel($user);
        $this->responseService->withData($userModel)->write();
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