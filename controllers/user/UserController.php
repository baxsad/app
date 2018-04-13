<?php

namespace Buff\controllers\user;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Capsule\Manager;
use Buff\classes\services\ResponseService;
use Buff\lib\data\StringEx;
use Firebase\JWT\JWT;
use Tuupola\Base62;

class UserController
{
   protected $container;
   private $responseService;
   private $DB;

   public function __construct(ContainerInterface $container) {
       $this->container = $container;
       $this->DB = $this->container->get('db');
       $this->responseService = new ResponseService();
   }

   public function get(Request $req,  Response $res, $args = []) {

        $uid = $req->getQueryParam('uid',$default = '');
        $account = $req->getQueryParam('account',$default = '');
        if (empty($uid) && empty($account)) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5001);
        } else {
            if (!empty($uid) && !is_string($uid)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5002);
            } elseif (!empty($account) && !is_string($account)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5003);
            } else {
                $start = microtime(true);
                $user  = $this->DB->table('user')
                    ->where('uid',$uid)
                    ->orWhere('account',$account)
                    ->get()
                    ->first();
                $expend = (microtime(true)-$start)*1000;
                if (empty($user)) {
                    $this->responseService->withFailure();
                    $this->responseService->withErrorCode(6001);
                } else {
                    $userModel = new UserModel($user);
                    if ($userModel->getPrivate() == true) {
                        $this->responseService->withFailure();
                        $this->responseService->withErrorCode(7001);
                    } else {
                        $this->responseService->withSuccess();
                        $this->responseService->withData($userModel);
                        $this->responseService->withExpend($expend);
                    }
                }
            }
        }

        return $res
            ->withStatus(200)
            ->write($this->responseService->write());
   }

   public function auth(Request $req,  Response $res, $args = []) {

        $identity_type   = $req->getParam('identity_type');
        $identifier      = $req->getParam('identifier');
        $credential      = $req->getParam('credential');
        if (empty($identity_type) || !is_string($identity_type)) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5004);
        } elseif (empty($identifier) || !is_string($identifier)) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5005);
        } elseif (empty($credential)) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5006);
        } else {
            $start = microtime(true);
            $auth  = $this->DB->table('user_auths')
                ->where('identity_type',$identity_type)
                ->where('identifier',$identifier)
                ->get()
                ->first();
            $expend = (microtime(true)-$start)*1000;
            if (empty($auth)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(6001);
            } else {
                $key = $auth->credential;
                $uid = $auth->uid;
                if (md5($credential) != $key) {
                    $this->responseService->withFailure();
                    $this->responseService->withErrorCode(5007);
                } else {
                    $user = $this->DB->table('user')
                        ->where('uid',$uid)
                        ->get()
                        ->first()
                        ->toArray();
                    $now = strtotime(date("Y-m-d H:i:s"));
                    $future = strtotime((new \DateTime('+99 day'))->format('Y-m-d H:i:s'));
                    $server = $req->getServerParams();
                    $payload   = [
                        "uid" => $uid,
                        "iat" => $now,
                        "exp" => $future,
                        "sub" => $server["PHP_AUTH_USER"],
                    ];
                    $token = JWT::encode($payload, "ILLBEWAITINGTILLIHEARYOUSAYIDO", "HS256");

                    $user["token"] = $token;
                    $user["expires"] = $future;
                    $this->responseService->withSuccess();
                    $this->responseService->withData($user);
                    $this->responseService->withExpend($expend);
                }
            }
        }

        return $res
            ->withStatus(200)
            ->write($this->responseService->write());
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