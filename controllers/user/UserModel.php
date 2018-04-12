<?php

namespace Buff\controllers\user;

use Buff\classes\model\ModelInterface;
use Buff\classes\response\ResponseModel;
use Buff\classes\response\ResponseModelInterface;

class UserModel extends ResponseModel implements ResponseModelInterface
{
	private $uid;
	private $username;
	private $avatar;

	public function __construct(Object $dao)
	{
		$this->uid        = (int)    $dao->uid;
		$this->username   = (string) $dao->username;
		$this->avatar     = (string) $dao->avatar;
	}

	public function getUID(): int
	{
		return $this->uid;
	}

	public function getUserName(): string
	{
		return $this->username;
	}

	public function getAvatar(): string
	{
		return $this->avatar;
	}

	public function toArray(): array
	{
		return [
			'uid'        => $this->uid,
			'username'   => $this->username,
			'avatar'     => $this->avatar
		];
	}
}