<?php

namespace Buff\classes\utils;

class Token
{
	public $decoded;

	public function populate($decoded)
    {
        $this->decoded = $decoded;
    }
}