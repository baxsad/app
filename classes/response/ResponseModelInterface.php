<?php

namespace Buff\classes\response;

use Buff\classes\model\ModelInterface;

interface ResponseModelInterface extends ModelInterface
{
	function expose();
}