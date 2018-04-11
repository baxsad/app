<?php

namespace buff\classes\response;

use buff\classes\model\ModelInterface;

interface ResponseModelInterface extends ModelInterface
{
	function expose();
}