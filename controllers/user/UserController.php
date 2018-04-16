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
use Buff\classes\utils\Token;
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

        $uid     = $req->getQueryParam('uid',$default = '');
        $account = $req->getQueryParam('account',$default = '');

        do {
            if (empty($uid) && empty($account)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5001);
                break;
            }
            if (!empty($uid) && !is_numeric($uid)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5002);
                break;
            }
            if (!empty($account) && !is_string($account)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5003);
                break;
            }
            $start = microtime(true);
            $user  = $this
                ->DB
                ->table('user')
                ->where('uid',$uid)
                ->orWhere('account',$account)
                ->get()
                ->first();
            $expend = (microtime(true)-$start)*1000;

            if (empty($user)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(6001);
                break;
            }
            $userModel = new UserModel($user);
            if ($userModel->getPrivate() == true) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(7001);
                break;
            }
            $this->responseService->withSuccess();
            $this->responseService->withData($userModel);
            $this->responseService->withExpend($expend);
        } while (false);

        return $res
            ->withStatus(200)
            ->write($this->responseService->write());
   }

   public function auth(Request $req,  Response $res, $args = []) {

        $scopes          = APP::$base->config->get('scopes','account');
        $identity_type   = $req->getParam('identity_type');
        $identifier      = $req->getParam('identifier');
        $credential      = $req->getParam('credential');

        do {
            if (empty($identity_type) || !is_string($identity_type)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5004);
                break;
            }
            if (!in_array($identity_type, $scopes)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5008);
                break;
            }
            if (empty($identifier) || !is_string($identifier)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5005);
                break;
            }
            if (empty($credential)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5006);
                break;
            }
            $start = microtime(true);
            $auth  = $this
                ->DB
                ->table('user_auths')
                ->where('identity_type',$identity_type)
                ->where('identifier',$identifier)
                ->get()
                ->first();
            $expend = (microtime(true)-$start)*1000;

            if (empty($auth)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5013);
                break;
            }
            $key     = $auth->credential;
            if (md5($credential) != $key) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5007);
                break;
            }
            $user = $this
                ->DB
                ->table('user')
                ->where('uid',$uid)
                ->get()
                ->first();
            if (empty($user)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5013);
                break;
            }
            $userModel = new UserModel($user);
            $data = $userModel->toArray();
            $token = Auth::create($user->uid,$user->account);
            $data["token"] = $token["token"];
            $data["expires"] = $token["expires"];
            $this->responseService->withSuccess();
            $this->responseService->withData($data);
            $this->responseService->withExpend($expend);
        } while (false);

        return $res
            ->withStatus(200)
            ->write($this->responseService->write());
   }

   public function create(Request $req, Response $res, $args = []) {

        $scopes          = APP::$base->config->get('scopes','account');
        $identity_type   = $req->getParam('identity_type');
        $identifier      = $req->getParam('identifier');
        $credential      = $req->getParam('credential');
        $account         = $req->getParam('account');

        do {
            if (empty($identity_type) || !is_string($identity_type)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5004);
                break;
            }
            if (!in_array($identity_type, $scopes)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5008);
                break;
            }
            if (empty($identifier) || !is_string($identifier)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5005);
                break;
            }
            if (StringEx::length($identifier) < 6) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5009);
                break;
            }
            if (empty($credential)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5006);
                break;
            }
            if (StringEx::length($credential) < 6) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5010);
                break;
            }
            if (empty($account)) {
                $account = "OYEid_".$identifier;
            }
            $start = microtime(true);
            $find = $this
                ->DB
                ->table('user')
                ->where('account',$account)
                ->get()
                ->first();
            if (!empty($find)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5012);
                break;
            }
            $creat_user = $this
                ->DB
                ->table('user')
                ->insert(['account' => $account,'username' => $identifier]);
            if (!$creat_user) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5011);
                break;
            }
            $user = $this
                ->DB
                ->table('user')
                ->where('account',$account)
                ->get()
                ->first();
            if (empty($user)) {
                $this
                    ->DB
                    ->table('user')
                    ->where('account',$account)
                    ->delete();
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5011);
                break;
            }
            $creat_user_auth = $this
                ->DB->table('user_auths')
                ->insert([
                    'uid' => $user->uid,
                    'identity_type' => $identity_type,
                    'identifier' => $identifier,
                    'credential' => md5($credential)
                        ]);
            if (!$creat_user_auth) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5011);
                break;
            }
            $expend = (microtime(true)-$start)*1000;

            $userModel = new UserModel($user);
            $data = $userModel->toArray();
            $token = Auth::create($userModel->getUID(),$userModel->getAccount());
            $data["token"] = $token["token"];
            $data["expires"] = $token["expires"];
            $this->responseService->withSuccess();
            $this->responseService->withData($data);
            $this->responseService->withExpend($expend);
        } while (false);

        return $res
            ->withStatus(200)
            ->write($this->responseService->write());
   }

   public function update(Request $req,  Response $res, $args = []) {

        $account    = $req->getQueryParam('account');
        $username   = $req->getQueryParam('username');
        $private    = $req->getQueryParam('private');
        $avatar     = $req->getQueryParam('avatar');
        $bio        = $req->getQueryParam('bio');
        $modify     = strtotime(date("Y-m-d H:i:s"));

        do {
            $updates = [];
            if (!empty($account) && is_string($account)) {
                $updates["account"] = $account;
            }
            if (!empty($username) && is_string($username)) {
                $updates["username"] = $username;
            }
            if (!empty($private) && is_bool($private)) {
                $updates["private"] = $private;
            }
            if (!empty($avatar) && is_string($avatar)) {
                $updates["avatar"] = $avatar;
            }
            if (!empty($bio) && is_string($bio)) {
                $updates["bio"] = $bio;
            }
            if (empty($updates)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5014);
                break;
            }

            $start = microtime(true);
            $jwt = $req->getAttribute("token");
            if (empty($jwt) || empty($jwt->uid)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5015);
                break;
            }
            $update_user_info = $this
                ->DB
                ->table("user")
                ->where("uid",$jwt->uid)
                ->update($updates);
            if (!$update_user_info) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5016);
                break;
            }
            if (!empty($account)) {
                $update_user_auths = $this
                    ->DB
                    ->table("user_auths")
                    ->where("uid",$jwt->uid)
                    ->where("identity_type","account")
                    ->update(["identifier" => $account]);
                if (!$update_user_auths) {
                    $this->responseService->withFailure();
                    $this->responseService->withErrorCode(5016);
                    break;
                }
            }
            $user = $this
                ->DB
                ->table('user')
                ->where('uid',$jwt->uid)
                ->get()
                ->first();
            if (empty($user)) {
                $this->responseService->withFailure();
                $this->responseService->withErrorCode(5016);
                break;
            }
            $expend = (microtime(true)-$start)*1000;
 
            $userModel = new UserModel($user);
            $this->responseService->withSuccess();
            $this->responseService->withData($userModel);
            $this->responseService->withExpend($expend);
        } while (false);

        return $res
            ->withStatus(200)
            ->write($this->responseService->write());
   }
}