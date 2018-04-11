<?php

namespace buff\classes\response;

use buff\class\model\ModelInterface;

interface ResponseModelInterface extends ModelInterface
{
	function expose();
}