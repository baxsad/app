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

   public function create(Request $req,  Response $res, $args = []) {

        $identifier_type = $request->getParam('identifier_type');
        $identifier      = $request->getParam('identifier');
        $credential      = $request->getParam('credential');
        if (empty($identifier_type) || !is_string($identifier_type))) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5004);
        } elseif (empty($identifier) || !is_string($identifier))) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5005);
        } elseif (empty($credential)) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5006);
        } else {
            $start = microtime(true);
            $auth  = $this->DB->table('user_auths')
                ->where('identifier_type',$identifier_type)
                ->where('identifier',$identifier)
                ->get()
                ->first();
            $expend = (microtime(true)-$start)*1000;
            if (empty($auth)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(6001);
            } else {
                $key = $auth->credential;
                if ($credential != $key) {
                    $this->responseService->withFailure();
                    $this->responseService->withErrorCode(5007);
                } else {
                    $now = new DateTime();
                    $future = new DateTime("now +99999999 hours");
                    $server = $req->getServerParams();
                    $jti = (new Base62)->encode(random_bytes(16));
                    $payload   = [
                        "iat" => strtotime(date("Y-m-d H:i:s")),
                        "exp" => strtotime((new \DateTime('+1 day'))->format('Y-m-d H:i:s')),
                        "jti" => $jti,
                        "sub" => $server["PHP_AUTH_USER"],
                    ];
                    $token = JWT::encode($payload, "ILLBEWAITINGTILLIHEARYOUSAYIDO", "HS256");

                    $data["token"] = $token;
                    $data["expires"] = $future->getTimeStamp();
                    $this->responseService->withSuccess();
                    $this->responseService->withData($userModel);
                    $this->responseService->withExpend($expend);
                }
            }
        }

        return $res
            ->withStatus(200)
            ->write($this->responseService->write());
   }

   public function auth(Request $req,  Response $res, $args = []) {

        
        return $res
            ->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write('auth');
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