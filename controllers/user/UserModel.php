<?php

namespace Buff\controllers\user;

use Buff\classes\model\ModelInterface;
use Buff\classes\response\ResponseModel;
use Buff\classes\response\ResponseModelInterface;

class UserModel extends ResponseModel implements ResponseModelInterface
{
	private $uid;
	private $account;
	private $username;
	private $avatar;
	private $bio;
	private $private;
	private $created;
	private $modify;

	public function __construct(Object $dao)
	{
		$this->uid        = (int)    $dao->uid;
		$this->account    = (string) $dao->account;
		$this->username   = (string) $dao->username;
		$this->avatar     = (string) $dao->avatar;
		$this->bio        = (string) $dao->bio;
		$this->private    = (bool)   $dao->private;
		$this->created    = (int)    strtotime($dao->created);
		$this->modify     = (int)    strtotime($dao->modify);
	}

	public function getUID(): int
	{
		return $this->uid;
	}

	public function getAccount(): string
	{
		return $this->account;
	}

	public function getUserName(): string
	{
		return $this->username;
	}

	public function getAvatar(): string
	{
		return $this->avatar;
	}

	public function getBio(): string
	{
		return $this->bio;
	}

	public function getPrivate(): bool
	{
		return $this->private;
	}

	public function getCreated(): int
	{
		return $this->created;
	}

	public function getModify(): int
	{
		return $this->modify;
	}

	public function toArray(): array
	{
		return [
			'uid'        => $this->uid,
			'account'    => $this->account,
			'username'   => $this->username,
			'avatar'     => $this->avatar,
			'bio'        => $this->bio,
			'private'    => $this->private,
			'created'    => $this->created,
			'modify'     => $this->modify
		];
	}
}