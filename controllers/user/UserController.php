<?php

namespace Buff\controllers\user;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Capsule\Manager;
use Buff\classes\services\ResponseService;
use Buff\lib\data\StringEx;
use Buff\classes\utils\Auth;
use APP;

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
                $act = $auth->account;
                if (md5($credential) != $key) {
                    $this->responseService->withFailure();
                    $this->responseService->withErrorCode(5007);
                } else {
                    $user = $this->DB->table('user')
                        ->where('uid',$uid)
                        ->get()
                        ->first();
                    $userModel = new UserModel($user);
                    $data = $userModel->toArray();
                    $token = Auth::create($uid,$act);
                    $data["token"] = $token["token"];
                    $data["expires"] = $token["expires"];
                    $this->responseService->withSuccess();
                    $this->responseService->withData($data);
                    $this->responseService->withExpend($expend);
                }
            }
        }

        return $res
            ->withStatus(200)
            ->write($this->responseService->write());
   }

   public function create(Request $req,  Response $res, $args = []) {

        $scopes          = APP::$base->config->get('scopes','reg');
        $identity_type   = $req->getParam('identity_type');
        $identifier      = $req->getParam('identifier');
        $credential      = $req->getParam('credential');
        if (empty($identity_type) || !is_string($identity_type)) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5004);
        } elseif (!!count(array_intersect($identity_type, $scopes))) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5008);
        } elseif (empty($identifier) || !is_string($identifier)) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5005);
        } elseif (StringEx::length($identifier) < 6) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5009);
        } elseif (empty($credential)) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5006);
        } elseif (StringEx::length($credential) < 6) {
            $this->responseService->withFailure();
            $this->responseService->withErrorCode(5010);
        } else {
            $start = microtime(true);
            $uid   = $this->DB->table('user')
                ->insertGetId(['account' => $identifier,'username' => $identifier,]);
            $auth  = $this->DB->table('user_auths')
                ->insert(['uid' => $uid,
                          'account' => $identifier,
                          'identity_type' => $identity_type,
                          'identifier' => $identifier,
                          'credential' => md5($credential)
                         ]);
            $expend = (microtime(true)-$start)*1000;
            $userModel = new UserModel($user);
            $data = $userModel->toArray();
            $token = Auth::create($user->uid,$user->account);
            $data["token"] = $token["token"];
            $data["expires"] = $token["expires"];
            $this->responseService->withSuccess();
            $this->responseService->withData($data);
            $this->responseService->withExpend($expend);
        }

        return $res
            ->withStatus(200)
            ->write($this->responseService->write());
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